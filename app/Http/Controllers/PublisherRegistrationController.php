<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Publisher;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionPlan;
use App\Notifications\AdminNewPublisherNotification;
use App\Notifications\PublisherWelcomeNotification;
use App\Notifications\SubscriptionInvoiceNotification;
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
        $plans = SubscriptionPlan::active()->orderBy('sort_order')->get();
        return view('auth.publisher-register', compact('plans'));
    }

    /**
     * Handle publisher registration
     */
    public function register(Request $request)
    {
        // Honeypot: jika field ini diisi berarti bot
        if ($request->filled('website_url')) {
            return redirect()->back()->withInput();
        }

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

            // Paket langganan (opsional)
            'selected_plan_id' => 'nullable|exists:subscription_plans,id',

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
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);
            $user->role = 'publisher';
            $user->email_verified_at = null;
            $user->save();

            // Create publisher company
            $publisher = new Publisher([
                'user_id'          => $user->id,
                'name'             => $request->company_name,
                'address'          => $request->company_address,
                'phone'            => $request->company_phone,
                'email'            => $request->company_email,
                'website'          => $request->company_website,
                'selected_plan_id' => $request->selected_plan_id ?: null,
            ]);
            $publisher->status = 'pending';
            $publisher->save();

            // Generate validation token
            $validationToken = $publisher->generateValidationToken();

            // Buat invoice jika publisher memilih paket
            if ($request->selected_plan_id) {
                $plan = SubscriptionPlan::find($request->selected_plan_id);
                if ($plan) {
                    $payment = SubscriptionPayment::create([
                        'publisher_id'         => $publisher->id,
                        'subscription_plan_id' => $plan->id,
                        'invoice_number'       => SubscriptionPayment::generateInvoiceNumber(),
                        'amount'               => $plan->price,
                        'status'               => 'pending_payment',
                    ]);
                    // Kirim invoice via email
                    try {
                        $user->notify(new SubscriptionInvoiceNotification($payment));
                    } catch (\Exception $e) {
                        \Log::warning('Gagal kirim invoice email: ' . $e->getMessage());
                    }
                }
            }

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
            $user->notify(new PublisherWelcomeNotification($user, $publisher));
        } catch (\Exception $e) {
            \Log::error('Failed to send publisher welcome email: ' . $e->getMessage());
        }
    }

    /**
     * Notify admin about new publisher registration
     */
    private function notifyAdminNewPublisher($user, $publisher)
    {
        try {
            $adminUsers = User::where(function ($q) {
                $q->where('is_admin', true)->orWhere('role', 'admin');
            })->get();

            foreach ($adminUsers as $admin) {
                $admin->notify(new AdminNewPublisherNotification($user, $publisher));
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
