<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PublisherRegistrationController extends Controller
{
    /**
     * Show publisher registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.publisher-register');
    }

    /**
     * Handle publisher registration
     */
    public function register(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            // Personal Information
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            
            // Publisher Company Information
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:500',
            'company_phone' => 'required|string|max:20',
            'company_email' => 'required|email|max:255',
            'company_website' => 'nullable|url|max:255',
            
            // Agreement
            'terms_agreement' => 'required|accepted',
        ], [
            // Custom error messages
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'company_name.required' => 'Nama perusahaan/institusi wajib diisi.',
            'company_address.required' => 'Alamat perusahaan wajib diisi.',
            'company_phone.required' => 'Telepon perusahaan wajib diisi.',
            'company_email.required' => 'Email perusahaan wajib diisi.',
            'terms_agreement.required' => 'Anda harus menyetujui syarat dan ketentuan.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'publisher',
                'email_verified_at' => null, // Will be verified via email
            ]);

            // Create publisher company
            $publisher = Publisher::create([
                'user_id' => $user->id,
                'name' => $request->company_name,
                'address' => $request->company_address,
                'phone' => $request->company_phone,
                'email' => $request->company_email,
                'website' => $request->company_website,
                'status' => 'pending', // Set as pending by default
            ]);

            // Generate validation token
            $validationToken = $publisher->generateValidationToken();

            // Send verification email (if email verification is enabled)
            if (config('auth.verification.enabled', false)) {
                $user->sendEmailVerificationNotification();
            }

            // Send welcome email to new publisher
            $this->sendWelcomeEmail($user, $publisher);

            // Send notification to admin
            $this->notifyAdminNewPublisher($user, $publisher);

            // Auto-login the user
            Auth::login($user);

            return redirect()->route('publisher.validation.pending')
                ->with('success', 'Registrasi berhasil! Akun Anda menunggu validasi admin.')
                ->with('validation_token', $validationToken)
                ->with('publisher_name', $publisher->name);

        } catch (\Exception $e) {
            \Log::error('Publisher registration failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.');
        }
    }

    /**
     * Send welcome email to new publisher
     */
    private function sendWelcomeEmail($user, $publisher)
    {
        try {
            // TODO: Create welcome email template
            // Mail::to($user->email)->send(new PublisherWelcomeMail($user, $publisher));
            \Log::info("Welcome email should be sent to: {$user->email}");
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
        }
    }

    /**
     * Notify admin about new publisher registration
     */
    private function notifyAdminNewPublisher($user, $publisher)
    {
        try {
            // Get admin users
            $adminUsers = User::where('is_admin', true)
                             ->orWhere('role', 'administrator')
                             ->get();

            foreach ($adminUsers as $admin) {
                // TODO: Create admin notification email
                // Mail::to($admin->email)->send(new NewPublisherNotificationMail($user, $publisher));
                \Log::info("Admin notification should be sent to: {$admin->email}");
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send admin notification: ' . $e->getMessage());
        }
    }

    /**
     * Show registration success page
     */
    public function success()
    {
        return view('auth.publisher-register-success');
    }
}
