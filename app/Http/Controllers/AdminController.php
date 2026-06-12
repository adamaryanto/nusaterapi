<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Therapist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalPatients = User::where('role', 'customer')->count();
        $totalBookings = Booking::count();
        $totalTransactions = Booking::where('pay_status', 'Lunas')->sum('total_payment');
        $totalTherapists = Therapist::count();

        $recentBookings = Booking::with('user', 'therapist')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Calculate weekly booking counts (Monday to Sunday of current week)
        $weeklyBookingsData = [];
        $startOfWeek = \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY);
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i)->toDateString();
            $weeklyBookingsData[] = Booking::where('schedule_date', $date)->count();
        }

        return view('admin.dashboard', compact(
            'totalPatients', 'totalBookings', 'totalTransactions', 'totalTherapists', 'recentBookings', 'weeklyBookingsData'
        ));
    }

    public function patients()
    {
        $patients = User::with('membershipTier')
            ->where('role', 'customer')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($patients as $patient) {
            $latestBooking = Booking::where('user_id', $patient->id)
                ->where('status', 'Selesai')
                ->orderBy('schedule_date', 'desc')
                ->first();
            $patient->latest_therapy = $latestBooking 
                ? \Carbon\Carbon::parse($latestBooking->schedule_date)->translatedFormat('d M Y') 
                : 'Belum Pernah';
        }

        return view('admin.patients', compact('patients'));
    }

    public function bookings()
    {
        $bookings = Booking::with('user', 'therapist')->orderBy('created_at', 'desc')->get();
        return view('admin.bookings', compact('bookings'));
    }

    public function bookingsDetail($id)
    {
        $booking = Booking::with('user', 'therapist')->findOrFail($id);
        return view('admin.bookings_detail', compact('booking'));
    }

    public function bookingsCancel($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update([
            'status' => 'Dibatalkan',
            'pay_status' => 'Batal'
        ]);
        return redirect()->route('admin.bookings.detail', $id)->with('success', 'Booking berhasil dibatalkan.');
    }

    public function transactions()
    {
        $bookings = Booking::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.transactions', compact('bookings'));
    }

    public function transactionsDetail($id)
    {
        $booking = Booking::with('user', 'therapist')->findOrFail($id);
        return view('admin.transactions_detail', compact('booking'));
    }

    public function therapists()
    {
        $therapists = Therapist::all();
        foreach ($therapists as $t) {
            $t->total_patients = Booking::where('therapist_id', $t->id)
                ->distinct('user_id')
                ->count('user_id');

            $t->total_income = Booking::where('therapist_id', $t->id)
                ->where('status', 'Selesai')
                ->sum('total_payment');
        }
        return view('admin.therapists', compact('therapists'));
    }

    public function therapistsCreate()
    {
        return view('admin.therapists_create');
    }

    public function therapistsStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|string|in:Laki-laki,Perempuan',
            'address' => 'nullable|string',
            'specialty' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/therapists'), $filename);
            $avatarPath = 'uploads/therapists/' . $filename;
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $avatarPath) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'gender' => $request->gender,
                'address' => $request->address,
                'avatar_path' => $avatarPath,
                'role' => 'therapist',
            ]);

            Therapist::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'gender' => $request->gender,
                'specialty' => $request->specialty,
                'avatar_path' => $avatarPath,
                'status' => 'Active',
                'rating' => 5.0
            ]);
        });

        return redirect()->route('admin.therapists')->with('success', 'Terapis berhasil ditambahkan beserta akun loginnya.');
    }

    public function therapistsEdit($id)
    {
        $therapist = Therapist::with('user')->findOrFail($id);
        return view('admin.therapists_edit', compact('therapist'));
    }

    public function therapistsUpdate(Request $request, $id)
    {
        $therapist = Therapist::findOrFail($id);
        $userId = $therapist->user_id;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email' . ($userId ? ',' . $userId : ''),
            'password' => ($userId ? 'nullable' : 'required') . '|string|min:8',
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|string|in:Laki-laki,Perempuan',
            'address' => 'nullable|string',
            'specialty' => 'required|string|max:255',
            'status' => 'required|string|in:Active,Inactive',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $avatarPath = $therapist->avatar_path;
        if ($request->hasFile('avatar')) {
            // Delete old avatar if it exists
            if ($avatarPath && file_exists(public_path($avatarPath))) {
                @unlink(public_path($avatarPath));
            }
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/therapists'), $filename);
            $avatarPath = 'uploads/therapists/' . $filename;
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $therapist, $avatarPath) {
            $user = $therapist->user;
            
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'address' => $request->address,
                'avatar_path' => $avatarPath,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            if (!$user) {
                $userData['role'] = 'therapist';
                if (!$request->filled('password')) {
                    $userData['password'] = Hash::make('12345678');
                }
                $user = User::create($userData);
                $therapist->user_id = $user->id;
            } else {
                $user->update($userData);
            }

            $therapist->update([
                'user_id' => $therapist->user_id,
                'name' => $request->name,
                'gender' => $request->gender,
                'specialty' => $request->specialty,
                'status' => $request->status,
                'avatar_path' => $avatarPath,
            ]);
        });

        return redirect()->route('admin.therapists')->with('success', 'Terapis berhasil diperbarui.');
    }

    public function therapistsDestroy($id)
    {
        $therapist = Therapist::findOrFail($id);
        
        \Illuminate\Support\Facades\DB::transaction(function () use ($therapist) {
            if ($therapist->user) {
                $therapist->user->delete();
            }
            
            if ($therapist->avatar_path && file_exists(public_path($therapist->avatar_path))) {
                @unlink(public_path($therapist->avatar_path));
            }
            
            $therapist->delete();
        });

        return redirect()->route('admin.therapists')->with('success', 'Terapis berhasil dihapus.');
    }

    public function profile()
    {
        $admin = Auth::user();
        return view('admin.profile', compact('admin'));
    }

    public function profileUpdate(Request $request)
    {
        $admin = User::find(Auth::id());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $avatarPath = $admin->avatar_path;
        if ($request->hasFile('avatar')) {
            if ($avatarPath && file_exists(public_path($avatarPath))) {
                @unlink(public_path($avatarPath));
            }
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/avatars'), $filename);
            $avatarPath = 'uploads/avatars/' . $filename;
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'avatar_path' => $avatarPath,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function therapistsDetail(Request $request, $id)
    {
        $therapist = Therapist::with('user')->findOrFail($id);
        $filter = $request->query('filter', 'week'); // default to 'week'

        $query = Booking::with('user')
            ->where('therapist_id', $id)
            ->where('status', 'Selesai');

        if ($filter === 'today') {
            $query->whereDate('schedule_date', \Carbon\Carbon::today()->toDateString());
        } elseif ($filter === 'month') {
            $query->whereYear('schedule_date', \Carbon\Carbon::now()->year)
                  ->whereMonth('schedule_date', \Carbon\Carbon::now()->month);
        } else { // week
            $startOfWeek = \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY)->toDateString();
            $endOfWeek = \Carbon\Carbon::now()->endOfWeek(\Carbon\Carbon::SUNDAY)->toDateString();
            $query->whereBetween('schedule_date', [$startOfWeek, $endOfWeek]);
        }

        $completedBookings = $query->orderBy('schedule_date', 'desc')
            ->orderBy('schedule_time', 'desc')
            ->get();

        // Calculate stats
        $totalSessions = $completedBookings->count();
        $totalIncome = $completedBookings->sum(function($b) {
            return round(($b->service_price * 0.7) / 10000) * 10000;
        });

        return view('admin.therapists_detail', compact(
            'therapist', 'completedBookings', 'filter', 'totalSessions', 'totalIncome'
        ));
    }

    public function toggleMembership($id)
    {
        $user = User::where('role', 'customer')->findOrFail($id);
        $user->is_member = !$user->is_member;
        $user->save();

        $statusMessage = $user->is_member ? 'Membership pasien berhasil diaktifkan.' : 'Membership pasien berhasil dinonaktifkan.';
        return redirect()->back()->with('success', $statusMessage);
    }

    public function membershipIndex()
    {
        $tiers = \App\Models\MembershipTier::orderBy('price', 'asc')->get();
        
        $customers = User::where('role', 'customer')
            ->orderBy('name', 'asc')
            ->get();
            
        return view('admin.membership', compact('tiers', 'customers'));
    }

    public function membershipCreate()
    {
        return view('admin.membership_create');
    }

    public function membershipEdit($id)
    {
        $tier = \App\Models\MembershipTier::findOrFail($id);
        return view('admin.membership_edit', compact('tier'));
    }

    public function membershipStore(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'discount_wd' => 'required|integer|min:0|max:100',
            'discount_we' => 'required|integer|min:0|max:100',
            'limit_wd' => 'nullable|integer|min:0',
            'limit_we' => 'nullable|integer|min:0',
            'window' => 'required|integer|min:1',
            'duration' => 'required|integer|min:1',
            'free_reschedule_limit' => 'required|integer|min:0',
            'reschedule_fee' => 'required|integer|min:0',
            'status' => 'required|string|in:active,inactive',
        ]);

        \App\Models\MembershipTier::create($request->all());

        return redirect()->route('admin.membership')->with('success', 'Paket membership baru berhasil ditambahkan.');
    }

    public function membershipUpdate(\Illuminate\Http\Request $request, $id)
    {
        $tier = \App\Models\MembershipTier::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'discount_wd' => 'required|integer|min:0|max:100',
            'discount_we' => 'required|integer|min:0|max:100',
            'limit_wd' => 'nullable|integer|min:0',
            'limit_we' => 'nullable|integer|min:0',
            'window' => 'required|integer|min:1',
            'duration' => 'required|integer|min:1',
            'free_reschedule_limit' => 'required|integer|min:0',
            'reschedule_fee' => 'required|integer|min:0',
            'status' => 'required|string|in:active,inactive',
        ]);

        $tier->update($request->all());

        return redirect()->route('admin.membership')->with('success', 'Paket membership berhasil diperbarui.');
    }

    public function membershipDestroy($id)
    {
        $tier = \App\Models\MembershipTier::findOrFail($id);
        $tier->delete();

        return redirect()->route('admin.membership')->with('success', 'Paket membership berhasil dihapus.');
    }

    public function changePatientTier(\Illuminate\Http\Request $request, $id)
    {
        $user = User::where('role', 'customer')->findOrFail($id);
        
        $request->validate([
            'membership_tier_id' => 'nullable|exists:membership_tiers,id',
        ]);
        
        $user->membership_tier_id = $request->membership_tier_id;
        $user->is_member = !empty($request->membership_tier_id);
        $user->save();

        return redirect()->route('admin.membership')->with('success', 'Tingkat membership pasien berhasil diperbarui.');
    }

    public function systemSettings()
    {
        $adminFee = \App\Models\WebSetting::get('admin_fee', '5000');
        $ppnPercentage = \App\Models\WebSetting::get('ppn_percentage', '11');

        return view('admin.system_settings', compact('adminFee', 'ppnPercentage'));
    }

    public function systemSettingsUpdate(Request $request)
    {
        $request->validate([
            'admin_fee' => 'required|integer|min:0',
            'ppn_percentage' => 'required|numeric|min:0|max:100',
        ]);

        \App\Models\WebSetting::set('admin_fee', $request->admin_fee);
        \App\Models\WebSetting::set('ppn_percentage', $request->ppn_percentage);

        return redirect()->route('admin.settings.system')->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
