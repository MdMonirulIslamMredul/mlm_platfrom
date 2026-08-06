<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show the registration form
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (in_array($user->role, ['admin', 'super_admin'], true)) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'phone' => ['nullable', 'string', 'unique:users,phone', 'regex:/^01[3-9]\d{8}$/'],
            'password' => 'required|string|min:8|confirmed',
            'invite_code' => 'nullable|string|exists:users,referral_code',
        ], [
            'invite_code.exists' => 'The provided invite code is invalid.',
            'phone.unique' => 'This phone number has already been registered.',
            'phone.regex' => 'Phone number must be an 11-digit Bangladeshi number starting with 01 (e.g. 017XXXXXXXX).',
            'email.unique' => 'This email address has already been registered.',
            'email.email' => 'Please enter a valid email address (e.g. name@domain.com).',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Find referrer user by invite code if provided
            $referrer = $request->filled('invite_code')
                ? User::where('referral_code', $request->invite_code)->first()
                : null;

            // Generate unique 8-character uppercase referral code
            do {
                $referralCode = strtoupper(Str::random(8));
            } while (User::where('referral_code', $referralCode)->exists());

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'user',
                'referral_code' => $referralCode,
                'referred_by' => $referrer ? $referrer->id : null,
                'balance' => 0.00,
                'total_refer_bonus' => 0.00,
            ]);

            Auth::login($user);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registration successful!',
                    'redirect' => url('/dashboard')
                ]);
            }

            return redirect()->route('dashboard')->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registration failed. Please try again.'
                ], 500);
            }
            return redirect()->back()->with('error', 'Registration failed. Please try again.')->withInput();
        }
    }

    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (in_array($user->role, ['admin', 'super_admin'], true)) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $redirectUrl = route('dashboard');
            if (in_array($user->role, ['admin', 'super_admin'], true)) {
                $redirectUrl = route('admin.dashboard');
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful!',
                    'redirect' => $redirectUrl,
                ]);
            }

            return redirect()->intended($redirectUrl)->with('success', 'Login successful!');
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password.'
        ], 401);
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Update the authenticated admin profile and password.
     */
    public function updateAdminProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'super_admin'], true)) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        if (!empty($validated['current_password']) && !Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.'])->withInput();
        }

        $user->name = $validated['name'] ?? $user->name;
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? $user->phone;

        if (!empty($validated['new_password'])) {
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully!');
    }
}
