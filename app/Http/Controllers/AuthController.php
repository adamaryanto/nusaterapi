<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return match(Auth::user()->role) {
                'admin'     => redirect()->route('admin.dashboard'),
                'therapist' => redirect()->route('therapist.dashboard'),
                default     => redirect()->route('landing'),
            };
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            return match(Auth::user()->role) {
                'admin'     => redirect()->route('admin.dashboard'),
                'therapist' => redirect()->route('therapist.dashboard'),
                default     => redirect()->route('landing'),
            };
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return match(Auth::user()->role) {
                'admin'     => redirect()->route('admin.dashboard'),
                'therapist' => redirect()->route('therapist.dashboard'),
                default     => redirect()->route('landing'),
            };
        }
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'gender' => 'nullable|string|in:Laki-laki,Perempuan',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        return redirect()->route('login')->with('success', 'Akun Anda berhasil dibuat! Silakan login menggunakan email dan password Anda.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20', // It has red asterisk * in mockup so it is required
            'gender' => 'nullable|string|in:Laki-laki,Perempuan',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $avatarPath = $user->avatar_path;
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
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'avatar_path' => $avatarPath,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('customer.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function membership()
    {
        $user = Auth::user();
        $isMember = (bool)$user->is_member;
        
        $weeklyLimit = 0;
        $discountAmount = 0;
        $usedCount = 0;
        
        if ($isMember) {
            $weeklyLimit = (int)\App\Models\WebSetting::get('membership_weekly_limit', '3');
            $discountAmount = (int)\App\Models\WebSetting::get('membership_discount_amount', '15000');
            
            $startOfWeek = \Carbon\Carbon::now()->startOfWeek();
            $endOfWeek = \Carbon\Carbon::now()->endOfWeek();
            
            $usedCount = \App\Models\Booking::where('user_id', $user->id)
                ->where('is_membership_discount_applied', true)
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->count();
        }
        
        return view('customer.membership', compact('user', 'isMember', 'weeklyLimit', 'discountAmount', 'usedCount'));
    }
}
