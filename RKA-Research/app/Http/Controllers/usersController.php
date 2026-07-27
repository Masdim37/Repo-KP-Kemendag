<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;

class usersController extends Controller
{
    public function ShowLogin()
    {
        return view('user.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        $usernameInput = $request->input('username');
        $passwordInput = $request->input('password');

        $user = User::where('username', $usernameInput)->first();

        if (!$user) {
            return back()
                ->withInput($request->only('username'))
                ->with('error', 'Username atau Password salah!');
        }

        // if ($user->status !== 'active') {
        //     return back()
        //         ->withInput($request->only('username'))
        //         ->with('error', 'Akun belum aktif atau belum selesai diverifikasi.');
        // }

        if (!Hash::check($passwordInput, $user->password)) {
            return back()
                ->withInput($request->only('username'))
                ->with('error', 'Username atau Password salah!');
        }

        Session::put('user_id', $user->userID);
        Session::put('user_name', $user->name);
        Session::put('username', $user->username);
        Session::put('role_id', $user->roleID);
        Session::put('unit_id', $user->unitID);
        Session::put('satker_id', $user->satkerID);

        $user->update([
            'last_login_at' => now(),
        ]);

        return redirect('/Homepage');
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER STEP 1
    |--------------------------------------------------------------------------
    | Input:
    | - name
    | - nip
    | - email
    | - username
    | - password
    | - password_confirmation
    */
    public function register_step1(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('user.register-step1');
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
            ],
            'nip' => [
                'required',
                'digits:18',
                'unique:users,nip',
            ],
            'email' => [
                'required',
                'email',
                'max:150',
                'unique:users,email',
            ],
            'username' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                'unique:users,username',
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->numbers(),
            ],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap maksimal 150 karakter.',

            'nip.required' => 'NIP wajib diisi.',
            'nip.digits' => 'NIP harus terdiri dari 18 digit angka.',
            'nip.unique' => 'NIP sudah terdaftar.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 150 karakter.',
            'email.unique' => 'Email sudah terdaftar.',

            'username.required' => 'Username wajib diisi.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip, dan underscore.',
            'username.unique' => 'Username sudah digunakan.',
            'username.max' => 'Username maksimal 100 karakter.',

            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        try {
            $user = DB::transaction(function () use ($validated) {
                $userID = $this->generateUserID();

                return User::create([
                    'userID' => $userID,
                    'name' => $validated['name'],
                    'nip' => $validated['nip'],
                    'email' => $validated['email'],
                    'username' => $validated['username'],
                    'password' => Hash::make($validated['password']),

                    // Status tetap pending_otp sampai step 3 selesai.
                    'status' => 'pending_otp',
                    'email_verified_at' => null,

                    // Diisi pada step 2.
                    'jabatanID' => null,
                    'is_data_confirmed' => 0,
                    'data_confirmed_at' => null,

                    // Ditunda untuk tahap berikutnya.
                    'roleID' => null,
                    'unitID' => null,
                    'satkerID' => null,
                ]);
            });

            Session::put('register_user_id', $user->userID);
            Session::put('register_email', $user->email);

            return redirect()
                ->route('register.step2')
                ->with('success', 'Identitas diri berhasil disimpan. Silakan lengkapi informasi jabatan.');
        } catch (\Throwable $e) {
            $message = 'Registrasi tahap 1 gagal. Silakan coba kembali.';

            if (config('app.debug')) {
                $message .= ' Error: ' . $e->getMessage();
            }

            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', $message);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER STEP 2
    |--------------------------------------------------------------------------
    | Input:
    | - jabatanID
    | - checkbox pernyataan data benar
    |
    | Tampilan:
    | - jabatan_name
    | - jabatan_type
    | - jabatan_level
    | - eselon
    */
    public function register_step2(Request $request)
    {
        $registerUserID = Session::get('register_user_id');

        if (!$registerUserID) {
            return redirect()
                ->route('register.step1')
                ->with('error', 'Sesi registrasi tidak ditemukan. Silakan mulai kembali.');
        }

        $user = User::where('userID', $registerUserID)->first();

        if (!$user) {
            Session::forget(['register_user_id', 'register_email']);

            return redirect()
                ->route('register.step1')
                ->with('error', 'Data user tidak ditemukan. Silakan mulai kembali.');
        }

        if ($user->status === 'active') {
            return redirect()
                ->route('login')
                ->with('success', 'Akun sudah aktif. Silakan login.');
        }

        $jabatans = DB::table('jabatan')
            ->select(
                'jabatanID',
                'jabatan_code',
                'jabatan_name',
                'jabatan_type',
                'jabatan_level',
                'eselon'
            )
            ->orderBy('jabatan_type')
            ->orderBy('jabatan_level')
            ->orderBy('jabatan_name')
            ->get();

        if ($request->isMethod('get')) {
            return view('user.register-step2', [
                'user' => $user,
                'jabatans' => $jabatans,
            ]);
        }

        $validated = $request->validate([
            'jabatanID' => [
                'required',
                'exists:jabatan,jabatanID',
            ],
            'data_confirmation' => [
                'accepted',
            ],
        ], [
            'jabatanID.required' => 'Jabatan wajib dipilih.',
            'jabatanID.exists' => 'Jabatan yang dipilih tidak valid.',
            'data_confirmation.accepted' => 'Anda wajib menyatakan bahwa data yang diisi sudah benar.',
        ]);

        try {
            $result = DB::transaction(function () use ($user, $validated) {
                $jabatan = DB::table('jabatan')
                    ->where('jabatanID', $validated['jabatanID'])
                    ->lockForUpdate()
                    ->first();

                if (!$jabatan) {
                    return [
                        'success' => false,
                        'message' => 'Jabatan tidak ditemukan atau tidak aktif.',
                    ];
                }

                $lockedUser = User::where('userID', $user->userID)
                    ->lockForUpdate()
                    ->first();

                if (!$lockedUser) {
                    return [
                        'success' => false,
                        'message' => 'Data user tidak ditemukan.',
                    ];
                }

                if ($jabatan->jabatan_type === 'JPT') {
                    $roleID = 'role0003'; // LEADER
                } else {
                    $roleID = 'role0002'; // RESEARCHER
                } //role0001 : SUPERADMIN

                $lockedUser->update([
                    'jabatanID' => $jabatan->jabatanID,
                    'roleID' => $roleID,
                    'is_data_confirmed' => 1,
                    'data_confirmed_at' => now(),
                ]);

                // Nonaktifkan OTP lama yang belum dipakai.
                DB::table('user_otps')
                    ->where('userID', $lockedUser->userID)
                    ->where('purpose', 'register_verification')
                    ->where('is_used', 0)
                    ->update([
                        'is_used' => 1,
                        'updated_at' => now(),
                    ]);

                $otpCode = $this->generateOtpCode();

                $this->storeOtp($lockedUser->userID, $otpCode, 'register_verification');

                return [
                    'success' => true,
                    'user' => $lockedUser,
                    'otp_code' => $otpCode,
                    'message' => 'Informasi jabatan berhasil disimpan. Kode OTP telah dikirim ke email Anda.',
                ];
            });

            if (!$result['success']) {
                return back()
                    ->withInput()
                    ->with('error', $result['message']);
            }

            $this->sendOtpEmail($result['user'], $result['otp_code']);

            return redirect()
                ->route('register.step3')
                ->with('success', $result['message']);
        } catch (\Throwable $e) {
            $message = 'Registrasi tahap 2 gagal. Silakan coba kembali.';

            if (config('app.debug')) {
                $message .= ' Error: ' . $e->getMessage();
            }

            return back()
                ->withInput()
                ->with('error', $message);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER STEP 3
    |--------------------------------------------------------------------------
    | Input:
    | - otp_code
    |
    | Fitur:
    | - verifikasi OTP 6 digit
    */
    public function register_step3(Request $request)
    {
        $registerUserID = Session::get('register_user_id');

        if (!$registerUserID) {
            return redirect()
                ->route('register.step1')
                ->with('error', 'Sesi registrasi tidak ditemukan. Silakan mulai kembali.');
        }

        $user = User::where('userID', $registerUserID)->first();

        if (!$user) {
            Session::forget(['register_user_id', 'register_email']);

            return redirect()
                ->route('register.step1')
                ->with('error', 'Data user tidak ditemukan. Silakan mulai kembali.');
        }

        if ($user->status === 'active') {
            Session::forget(['register_user_id', 'register_email', 'register_verified']);

            return redirect()
                ->route('login')
                ->with('success', 'Akun sudah aktif. Silakan login.');
        }

        if ($request->isMethod('get')) {
            return view('user.register-step3', [
                'user' => $user,
                'email' => $user->email,
            ]);
        }

        $request->validate([
            'otp_code' => [
                'required',
                'digits:6',
            ],
        ], [
            'otp_code.required' => 'Kode OTP wajib diisi.',
            'otp_code.digits' => 'Kode OTP harus terdiri dari 6 digit angka.',
        ]);

        try {
            $result = DB::transaction(function () use ($request, $user) {
                $lockedUser = User::where('userID', $user->userID)
                    ->lockForUpdate()
                    ->first();

                if (!$lockedUser) {
                    return [
                        'success' => false,
                        'message' => 'Data user tidak ditemukan.',
                    ];
                }

                if (!$lockedUser->jabatanID || (int) $lockedUser->is_data_confirmed !== 1) {
                    return [
                        'success' => false,
                        'redirect_step2' => true,
                        'message' => 'Informasi jabatan belum lengkap. Silakan lengkapi terlebih dahulu.',
                    ];
                }

                if ($lockedUser->status === 'active') {
                    return [
                        'success' => true,
                        'already_active' => true,
                        'message' => 'Akun sudah aktif.',
                    ];
                }

                $otp = DB::table('user_otps')
                    ->where('userID', $lockedUser->userID)
                    ->where('purpose', 'register_verification')
                    ->where('is_used', 0)
                    ->orderByDesc('created_at')
                    ->lockForUpdate()
                    ->first();

                if (!$otp) {
                    return [
                        'success' => false,
                        'message' => 'Kode OTP tidak ditemukan. Silakan kirim ulang OTP.',
                    ];
                }

                if (Carbon::parse($otp->expired_at)->isPast()) {
                    DB::table('user_otps')
                        ->where('otpID', $otp->otpID)
                        ->update([
                            'is_used' => 1,
                            'updated_at' => now(),
                        ]);

                    return [
                        'success' => false,
                        'message' => 'Kode OTP sudah kedaluwarsa. Silakan kirim ulang OTP.',
                    ];
                }

                if ((int) $otp->attempt_count >= (int) $otp->max_attempt) {
                    DB::table('user_otps')
                        ->where('otpID', $otp->otpID)
                        ->update([
                            'is_used' => 1,
                            'updated_at' => now(),
                        ]);

                    return [
                        'success' => false,
                        'message' => 'Percobaan OTP sudah melebihi batas. Silakan kirim ulang OTP.',
                    ];
                }

                $inputOtp = $request->input('otp_code');

                if (!Hash::check($inputOtp, $otp->otp_hash)) {
                    $newAttemptCount = ((int) $otp->attempt_count) + 1;
                    $isExhausted = $newAttemptCount >= (int) $otp->max_attempt;

                    DB::table('user_otps')
                        ->where('otpID', $otp->otpID)
                        ->update([
                            'attempt_count' => $newAttemptCount,
                            'is_used' => $isExhausted ? 1 : 0,
                            'updated_at' => now(),
                        ]);

                    $remainingAttempt = max(((int) $otp->max_attempt) - $newAttemptCount, 0);

                    if ($isExhausted) {
                        return [
                            'success' => false,
                            'message' => 'Kode OTP salah dan batas percobaan sudah habis. Silakan kirim ulang OTP.',
                        ];
                    }

                    return [
                        'success' => false,
                        'message' => 'Kode OTP salah. Sisa percobaan: ' . $remainingAttempt . '.',
                    ];
                }

                DB::table('user_otps')
                    ->where('otpID', $otp->otpID)
                    ->update([
                        'verified_at' => now(),
                        'is_used' => 1,
                        'updated_at' => now(),
                    ]);

                $lockedUser->update([
                    'email_verified_at' => now(),
                    'status' => 'active',
                ]);

                Session::put('register_verified', true);

                return [
                    'success' => true,
                    'message' => 'Verifikasi OTP berhasil. Akun Anda sudah aktif.',
                ];
            });

            if (!empty($result['redirect_step2'])) {
                return redirect()
                    ->route('register.step2')
                    ->with('error', $result['message']);
            }

            if (!$result['success']) {
                return back()
                    ->withInput()
                    ->with('error', $result['message']);
            }

            Session::forget(['register_user_id', 'register_email', 'register_verified']);

            return redirect()
                ->route('login')
                ->with('success', $result['message'] . ' Silakan login.');
        } catch (\Throwable $e) {
            $message = 'Verifikasi OTP gagal. Silakan coba kembali.';

            if (config('app.debug')) {
                $message .= ' Error: ' . $e->getMessage();
            }

            return back()
                ->withInput()
                ->with('error', $message);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RESEND OTP REGISTER
    |--------------------------------------------------------------------------
    */
    public function resend_register_otp()
    {
        $registerUserID = Session::get('register_user_id');

        if (!$registerUserID) {
            return redirect()
                ->route('register.step1')
                ->with('error', 'Sesi registrasi tidak ditemukan. Silakan mulai kembali.');
        }

        try {
            $result = DB::transaction(function () use ($registerUserID) {
                $user = User::where('userID', $registerUserID)
                    ->lockForUpdate()
                    ->first();

                if (!$user) {
                    return [
                        'success' => false,
                        'message' => 'Data user tidak ditemukan.',
                    ];
                }

                if ($user->status === 'active') {
                    return [
                        'success' => false,
                        'message' => 'Akun sudah aktif. Silakan login.',
                    ];
                }

                if (!$user->jabatanID || (int) $user->is_data_confirmed !== 1) {
                    return [
                        'success' => false,
                        'message' => 'Informasi jabatan belum lengkap. Silakan lengkapi tahap 2 terlebih dahulu.',
                    ];
                }

                $recentOtpExists = DB::table('user_otps')
                    ->where('userID', $user->userID)
                    ->where('purpose', 'register_verification')
                    ->where('created_at', '>=', now()->subMinute())
                    ->exists();

                if ($recentOtpExists) {
                    return [
                        'success' => false,
                        'message' => 'OTP baru saja dikirim. Silakan tunggu minimal 1 menit sebelum mengirim ulang.',
                    ];
                }

                DB::table('user_otps')
                    ->where('userID', $user->userID)
                    ->where('purpose', 'register_verification')
                    ->where('is_used', 0)
                    ->update([
                        'is_used' => 1,
                        'updated_at' => now(),
                    ]);

                $otpCode = $this->generateOtpCode();

                $this->storeOtp($user->userID, $otpCode, 'register_verification');

                return [
                    'success' => true,
                    'user' => $user,
                    'otp_code' => $otpCode,
                    'message' => 'Kode OTP baru telah dikirim ke email Anda.',
                ];
            });

            if (!$result['success']) {
                return back()->with('error', $result['message']);
            }

            $this->sendOtpEmail($result['user'], $result['otp_code']);

            return back()->with('success', $result['message']);
        } catch (\Throwable $e) {
            $message = 'Gagal mengirim ulang OTP. Silakan coba kembali.';

            if (config('app.debug')) {
                $message .= ' Error: ' . $e->getMessage();
            }

            return back()->with('error', $message);
        }
    }

    private function generateUserID(): string
    {
        $lastUser = User::where('userID', 'LIKE', 'usr%')
            ->orderByDesc('userID')
            ->lockForUpdate()
            ->first();

        if (!$lastUser) {
            return 'usr00001';
        }

        $lastNumber = (int) substr($lastUser->userID, 3);
        $nextNumber = $lastNumber + 1;

        return 'usr' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    private function generateOtpID(): string
    {
        $lastOtpID = DB::table('user_otps')
            ->where('otpID', 'LIKE', 'otp%')
            ->orderByDesc('otpID')
            ->lockForUpdate()
            ->value('otpID');

        if (!$lastOtpID) {
            return 'otp00001';
        }

        $lastNumber = (int) substr($lastOtpID, 3);
        $nextNumber = $lastNumber + 1;

        return 'otp' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    private function generateOtpCode(): string
    {
        return (string) random_int(100000, 999999);
    }

    private function storeOtp(string $userID, string $otpCode, string $purpose = 'register_verification'): void
    {
        $otpID = $this->generateOtpID();

        DB::table('user_otps')->insert([
            'otpID' => $otpID,
            'userID' => $userID,
            'otp_hash' => Hash::make($otpCode),
            'purpose' => $purpose,
            'expired_at' => now()->addMinutes(10),
            'verified_at' => null,
            'attempt_count' => 0,
            'max_attempt' => 5,
            'is_used' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function sendOtpEmail(User $user, string $otpCode): void
    {
        $messageBody =
            "Yth. {$user->name},\n\n" .
            "Terima kasih telah melakukan registrasi pada Sistem Informasi Penelitian RKA-K/L.\n\n" .
            "Kode OTP Anda adalah: {$otpCode}\n\n" .
            "Kode OTP berlaku selama 10 menit.\n\n" .
            "Jika Anda tidak melakukan registrasi, abaikan email ini.\n\n" .
            "Hormat kami,\n" .
            "Biro Perencanaan";

        Mail::raw($messageBody, function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Kode OTP Registrasi Sistem Informasi Penelitian RKA-K/L');
        });
    }

    public function showForgotPasswordForm()
    {
        return view('user.forgot-password');
    }

    public function sendForgotPasswordOtp(Request $request)
    {
        $request->validate([
            'identity' => ['required', 'string', 'max:150'],
        ], [
            'identity.required' => 'Email atau username wajib diisi.',
        ]);

        $identity = $request->input('identity');

        $user = User::where('email', $identity)
            ->orWhere('username', $identity)
            ->first();

        if (!$user) {
            return back()
                ->withInput()
                ->with('error', 'Email atau username tidak ditemukan.');
        }

        if ($user->status === 'nonactive') {
            return back()
                ->withInput()
                ->with('error', 'Akun tidak aktif. Silakan hubungi admin.');
        }

        try {
            $result = DB::transaction(function () use ($user) {
                $lockedUser = User::where('userID', $user->userID)
                    ->lockForUpdate()
                    ->first();

                if (!$lockedUser) {
                    return [
                        'success' => false,
                        'message' => 'Data user tidak ditemukan.',
                    ];
                }

                /*
             * Nonaktifkan OTP forgot_password lama yang belum dipakai.
             */
                DB::table('user_otps')
                    ->where('userID', $lockedUser->userID)
                    ->where('purpose', 'forgot_password')
                    ->where('is_used', 0)
                    ->update([
                        'is_used' => 1,
                        'updated_at' => now(),
                    ]);

                $otpCode = $this->generateOtpCode();

                $this->storeOtp($lockedUser->userID, $otpCode, 'forgot_password');

                return [
                    'success' => true,
                    'user' => $lockedUser,
                    'otp_code' => $otpCode,
                    'message' => 'Kode OTP telah dikirim ke email Anda.',
                ];
            });

            if (!$result['success']) {
                return back()
                    ->withInput()
                    ->with('error', $result['message']);
            }

            Session::put('forgot_password_user_id', $result['user']->userID);
            Session::put('forgot_password_email', $result['user']->email);
            Session::forget('forgot_password_verified');

            $this->sendForgotPasswordOtpEmail($result['user'], $result['otp_code']);

            return redirect()
                ->route('forgot.password.otp')
                ->with('success', $result['message']);
        } catch (\Throwable $e) {
            $message = 'Gagal mengirim OTP. Silakan coba kembali.';

            if (config('app.debug')) {
                $message .= ' Error: ' . $e->getMessage();
            }

            return back()
                ->withInput()
                ->with('error', $message);
        }
    }

    public function showForgotPasswordOtpForm()
    {
        $forgotPasswordUserID = Session::get('forgot_password_user_id');

        if (!$forgotPasswordUserID) {
            return redirect()
                ->route('forgot.password')
                ->with('error', 'Sesi lupa password tidak ditemukan. Silakan mulai kembali.');
        }

        $user = User::where('userID', $forgotPasswordUserID)->first();

        if (!$user) {
            Session::forget([
                'forgot_password_user_id',
                'forgot_password_email',
                'forgot_password_verified',
            ]);

            return redirect()
                ->route('forgot.password')
                ->with('error', 'Data user tidak ditemukan. Silakan mulai kembali.');
        }

        return view('user.forgot-password-otp', [
            'email' => $user->email,
        ]);
    }

    public function verifyForgotPasswordOtp(Request $request)
    {
        $request->validate([
            'otp_code' => ['required', 'digits:6'],
        ], [
            'otp_code.required' => 'Kode OTP wajib diisi.',
            'otp_code.digits' => 'Kode OTP harus terdiri dari 6 digit angka.',
        ]);

        $forgotPasswordUserID = Session::get('forgot_password_user_id');

        if (!$forgotPasswordUserID) {
            return redirect()
                ->route('forgot.password')
                ->with('error', 'Sesi lupa password tidak ditemukan. Silakan mulai kembali.');
        }

        try {
            $result = DB::transaction(function () use ($request, $forgotPasswordUserID) {
                $user = User::where('userID', $forgotPasswordUserID)
                    ->lockForUpdate()
                    ->first();

                if (!$user) {
                    return [
                        'success' => false,
                        'message' => 'Data user tidak ditemukan.',
                    ];
                }

                $otp = DB::table('user_otps')
                    ->where('userID', $user->userID)
                    ->where('purpose', 'forgot_password')
                    ->where('is_used', 0)
                    ->orderByDesc('created_at')
                    ->lockForUpdate()
                    ->first();

                if (!$otp) {
                    return [
                        'success' => false,
                        'message' => 'Kode OTP tidak ditemukan. Silakan kirim ulang OTP.',
                    ];
                }

                if (Carbon::parse($otp->expired_at)->isPast()) {
                    DB::table('user_otps')
                        ->where('otpID', $otp->otpID)
                        ->update([
                            'is_used' => 1,
                            'updated_at' => now(),
                        ]);

                    return [
                        'success' => false,
                        'message' => 'Kode OTP sudah kedaluwarsa. Silakan kirim ulang OTP.',
                    ];
                }

                if ((int) $otp->attempt_count >= (int) $otp->max_attempt) {
                    DB::table('user_otps')
                        ->where('otpID', $otp->otpID)
                        ->update([
                            'is_used' => 1,
                            'updated_at' => now(),
                        ]);

                    return [
                        'success' => false,
                        'message' => 'Percobaan OTP sudah melebihi batas. Silakan kirim ulang OTP.',
                    ];
                }

                $inputOtp = $request->input('otp_code');

                if (!Hash::check($inputOtp, $otp->otp_hash)) {
                    $newAttemptCount = ((int) $otp->attempt_count) + 1;
                    $isExhausted = $newAttemptCount >= (int) $otp->max_attempt;

                    DB::table('user_otps')
                        ->where('otpID', $otp->otpID)
                        ->update([
                            'attempt_count' => $newAttemptCount,
                            'is_used' => $isExhausted ? 1 : 0,
                            'updated_at' => now(),
                        ]);

                    $remainingAttempt = max(((int) $otp->max_attempt) - $newAttemptCount, 0);

                    if ($isExhausted) {
                        return [
                            'success' => false,
                            'message' => 'Kode OTP salah dan batas percobaan sudah habis. Silakan kirim ulang OTP.',
                        ];
                    }

                    return [
                        'success' => false,
                        'message' => 'Kode OTP salah. Sisa percobaan: ' . $remainingAttempt . '.',
                    ];
                }

                DB::table('user_otps')
                    ->where('otpID', $otp->otpID)
                    ->update([
                        'verified_at' => now(),
                        'is_used' => 1,
                        'updated_at' => now(),
                    ]);

                return [
                    'success' => true,
                    'message' => 'Verifikasi OTP berhasil. Silakan buat password baru.',
                ];
            });

            if (!$result['success']) {
                return back()
                    ->withInput()
                    ->with('error', $result['message']);
            }

            Session::put('forgot_password_verified', true);

            return redirect()
                ->route('forgot.password.reset')
                ->with('success', $result['message']);
        } catch (\Throwable $e) {
            $message = 'Verifikasi OTP gagal. Silakan coba kembali.';

            if (config('app.debug')) {
                $message .= ' Error: ' . $e->getMessage();
            }

            return back()
                ->withInput()
                ->with('error', $message);
        }
    }

    public function showResetPasswordForm()
    {
        $forgotPasswordUserID = Session::get('forgot_password_user_id');
        $isVerified = Session::get('forgot_password_verified');

        if (!$forgotPasswordUserID || !$isVerified) {
            return redirect()
                ->route('forgot.password')
                ->with('error', 'Verifikasi OTP diperlukan sebelum mengganti password.');
        }

        $user = User::where('userID', $forgotPasswordUserID)->first();

        if (!$user) {
            Session::forget([
                'forgot_password_user_id',
                'forgot_password_email',
                'forgot_password_verified',
            ]);

            return redirect()
                ->route('forgot.password')
                ->with('error', 'Data user tidak ditemukan. Silakan mulai kembali.');
        }

        return view('user.reset-password', [
            'email' => $user->email,
        ]);
    }

    public function resetForgotPassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->numbers(),
            ],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password baru tidak sesuai.',
        ]);

        $forgotPasswordUserID = Session::get('forgot_password_user_id');
        $isVerified = Session::get('forgot_password_verified');

        if (!$forgotPasswordUserID || !$isVerified) {
            return redirect()
                ->route('forgot.password')
                ->with('error', 'Verifikasi OTP diperlukan sebelum mengganti password.');
        }

        try {
            DB::transaction(function () use ($request, $forgotPasswordUserID) {
                $user = User::where('userID', $forgotPasswordUserID)
                    ->lockForUpdate()
                    ->firstOrFail();

                $user->update([
                    'password' => Hash::make($request->input('password')),
                    'updated_at' => now(),
                ]);

                /*
             * Matikan semua OTP forgot_password user ini setelah password berhasil diganti.
             */
                DB::table('user_otps')
                    ->where('userID', $user->userID)
                    ->where('purpose', 'forgot_password')
                    ->where('is_used', 0)
                    ->update([
                        'is_used' => 1,
                        'updated_at' => now(),
                    ]);
            });

            Session::forget([
                'forgot_password_user_id',
                'forgot_password_email',
                'forgot_password_verified',
            ]);

            return redirect()
                ->route('login')
                ->with('success', 'Password berhasil diubah, silakan login.');
        } catch (\Throwable $e) {
            $message = 'Password gagal diubah. Silakan coba kembali.';

            if (config('app.debug')) {
                $message .= ' Error: ' . $e->getMessage();
            }

            return back()->with('error', $message);
        }
    }

    public function resendForgotPasswordOtp()
    {
        $forgotPasswordUserID = Session::get('forgot_password_user_id');

        if (!$forgotPasswordUserID) {
            return redirect()
                ->route('forgot.password')
                ->with('error', 'Sesi lupa password tidak ditemukan. Silakan mulai kembali.');
        }

        try {
            $result = DB::transaction(function () use ($forgotPasswordUserID) {
                $user = User::where('userID', $forgotPasswordUserID)
                    ->lockForUpdate()
                    ->first();

                if (!$user) {
                    return [
                        'success' => false,
                        'message' => 'Data user tidak ditemukan.',
                    ];
                }

                $recentOtpExists = DB::table('user_otps')
                    ->where('userID', $user->userID)
                    ->where('purpose', 'forgot_password')
                    ->where('created_at', '>=', now()->subMinute())
                    ->exists();

                if ($recentOtpExists) {
                    return [
                        'success' => false,
                        'message' => 'OTP baru saja dikirim. Silakan tunggu minimal 1 menit sebelum mengirim ulang.',
                    ];
                }

                DB::table('user_otps')
                    ->where('userID', $user->userID)
                    ->where('purpose', 'forgot_password')
                    ->where('is_used', 0)
                    ->update([
                        'is_used' => 1,
                        'updated_at' => now(),
                    ]);

                $otpCode = $this->generateOtpCode();

                $this->storeOtp($user->userID, $otpCode, 'forgot_password');

                return [
                    'success' => true,
                    'user' => $user,
                    'otp_code' => $otpCode,
                    'message' => 'Kode OTP baru telah dikirim ke email Anda.',
                ];
            });

            if (!$result['success']) {
                return back()->with('error', $result['message']);
            }

            $this->sendForgotPasswordOtpEmail($result['user'], $result['otp_code']);

            return back()->with('success', $result['message']);
        } catch (\Throwable $e) {
            $message = 'Gagal mengirim ulang OTP. Silakan coba kembali.';

            if (config('app.debug')) {
                $message .= ' Error: ' . $e->getMessage();
            }

            return back()->with('error', $message);
        }
    }

    private function sendForgotPasswordOtpEmail(User $user, string $otpCode): void
    {
        $messageBody =
            "Yth. {$user->name},\n\n" .
            "Kami menerima permintaan perubahan password akun Anda pada Sistem Informasi Penelitian RKA-K/L.\n\n" .
            "Kode OTP Anda adalah: {$otpCode}\n\n" .
            "Kode OTP berlaku selama 10 menit.\n\n" .
            "Jika Anda tidak meminta perubahan password, abaikan email ini atau segera hubungi admin.\n\n" .
            "Hormat kami,\n" .
            "Biro Perencanaan";

        Mail::raw($messageBody, function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Kode OTP Lupa Password Sistem Informasi Penelitian RKA-K/L');
        });
    }

    public function logout()
    {
        Session::flush();

        return redirect('/login')->with('success', 'Berhasil logout.');
    }
}
