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

        return view('admin.dashboard', compact(
            'totalPatients', 'totalBookings', 'totalTransactions', 'totalTherapists', 'recentBookings'
        ));
    }

    public function patients()
    {
        $patients = User::where('role', 'customer')
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
                'address' => $request->address,
                'avatar_path' => $avatarPath,
                'role' => 'therapist',
            ]);

            Therapist::create([
                'user_id' => $user->id,
                'name' => $request->name,
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
}
