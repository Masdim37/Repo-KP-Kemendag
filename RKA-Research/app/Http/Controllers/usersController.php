<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class usersController extends Controller
{
    public function ShowLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => [
                'required',
                'string',
            ],
            'password' => [
                'required',
                'string',
            ],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('username', $validated['username'])
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->first();

        if (
            !$user ||
            !Hash::check($validated['password'], $user->password)
        ) {
            return back()
                ->withInput($request->only('username'))
                ->with('error', 'Username atau password salah!');
        }

        /*
     * Regenerasi session untuk mencegah session fixation.
     */
        $request->session()->regenerate();

        Session::put([
            'user_id' => $user->userID,
            'user_name' => $user->name,
            'username' => $user->username,
            'jabatan_id' => $user->jabatanID,
            'role_id' => $user->roleID,
            'unit_id' => $user->unitID,
            'satker_id' => $user->satkerID,
        ]);

        $user->last_login_at = now();
        $user->save();

        return redirect('/Dashboard');
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

    public function ShowRegister()
    {
        return view('register.step1');
    }

    function register_step1(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
            ],
            'nip' => [
                'required',
                'digits:18',
                // Ubah rule unique agar mengabaikan data yang memiliki deleted_at (sudah di-soft delete)
                // Rule::unique('users', 'nip')->whereNull('deleted_at'),
            ],
            'email' => [
                'required',
                'email',
                'max:150',
                // Ubah rule unique
                Rule::unique('users', 'email')->whereNull('deleted_at'),
            ],
            'username' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                // Ubah rule unique
                Rule::unique('users', 'username')->whereNull('deleted_at'),
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
                // 1. Cek apakah ada akun yang sudah dihapus dengan NIP / Email / Username yang sama
                $trashedUser = User::onlyTrashed()
                    ->where(function ($query) use ($validated) {
                        $query->where('email', $validated['email'])
                            ->orWhere('username', $validated['username']);
                            // ->orWhere('nip', $validated['nip']);
                    })->first();

                // 2. Jika akun terhapus ditemukan, lakukan restore dan update datanya
                if ($trashedUser) {
                    $trashedUser->restore(); // Ini akan mengosongkan kolom deleted_at

                    $trashedUser->update([
                        'name' => $validated['name'],
                        'nip' => $validated['nip'],
                        'email' => $validated['email'],
                        'username' => $validated['username'],
                        'password' => Hash::make($validated['password']),

                        // 'created_at' => now(),

                        // Kembalikan statusnya seperti akun baru (pending_otp)
                        'status' => 'pending_otp',
                        'email_verified_at' => null,
                        'jabatanID' => null,
                        'is_data_confirmed' => 0,
                        'data_confirmed_at' => null,
                        'roleID' => null,
                        'unitID' => null,
                        'satkerID' => null,
                    ]);

                    return $trashedUser; // Return trashed user yang sudah diperbarui
                }

                // 3. Jika tidak ada data yang terhapus, buat akun baru seperti biasa
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
                ->route('register')
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
            return view('register.step2', [
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

                $leaderLevels = [
                    'JPT_UTAMA',
                    'JPT_MADYA',
                    'JPT_PRATAMA',
                    'ADMINISTRATOR',
                ];

                $roleID = in_array(
                    $jabatan->jabatan_level,
                    $leaderLevels,
                    true
                )
                    ? 'role0003'
                    : 'role0002';

                $lockedUser->update([
                    'jabatanID' => $jabatan->jabatanID,
                    'roleID' => $roleID,
                    'is_data_confirmed' => 1,
                    'data_confirmed_at' => now(),
                ]);

                // Nonaktifkan OTP registrasi sebelumnya.
                DB::table('user_otps')
                    ->where('userID', $lockedUser->userID)
                    ->where('purpose', 'register_verification')
                    ->where('is_used', 0)
                    ->update([
                        'is_used' => 1,
                        'updated_at' => now(),
                    ]);

                $otpCode = $this->generateOtpCode();

                $this->storeOtp(
                    $lockedUser->userID,
                    $otpCode,
                    'register_verification'
                );

                return [
                    'success' => true,
                    'user' => $lockedUser,
                    'otp_code' => $otpCode,
                    'message' => 'Informasi jabatan berhasil disimpan. Kode OTP telah dikirim ke email Anda.',
                ];
            });
        } catch (\Throwable $e) {
            report($e);

            $message = 'Registrasi tahap 2 gagal. Silakan coba kembali.';

            if (config('app.debug')) {
                $message .= ' Error: ' . $e->getMessage();
            }

            return back()
                ->withInput()
                ->with('error', $message);
        }

        if (!$result['success']) {
            return back()
                ->withInput()
                ->with('error', $result['message']);
        }

        try {
            $this->sendOtpEmail(
                $result['user'],
                $result['otp_code']
            );
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('register.step3')
                ->with(
                    'error',
                    'Informasi jabatan berhasil disimpan, tetapi email OTP gagal dikirim. Silakan gunakan tombol Kirim Ulang OTP.'
                );
        }

        return redirect()
            ->route('register.step3')
            ->with('success', $result['message']);
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
            return view('register.step3', [
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
                        'redirect' => 'login',
                    ];
                }

                if (!$user->jabatanID || (int) $user->is_data_confirmed !== 1) {
                    return [
                        'success' => false,
                        'message' => 'Informasi jabatan belum lengkap. Silakan lengkapi tahap 2 terlebih dahulu.',
                        'redirect' => 'register.step2',
                    ];
                }

                // $recentOtpExists = DB::table('user_otps')
                //     ->where('userID', $user->userID)
                //     ->where('purpose', 'register_verification')
                //     ->where('created_at', '>=', now()->subMinute())
                //     ->exists();

                $recentOtpExists = DB::table('user_otps')
                    ->where('userID', $user->userID)
                    ->where('purpose', 'register_verification')
                    ->where('is_used', 0)
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

    private function storeOtp(
        string $userID,
        string $otpCode,
        string $purpose = 'register_verification',
        int $validMinutes = 10
    ): string {
        $otpID = $this->generateOtpID();

        DB::table('user_otps')->insert([
            'otpID' => $otpID,
            'userID' => $userID,
            'otp_hash' => Hash::make($otpCode),
            'purpose' => $purpose,
            'expired_at' => now()->addMinutes($validMinutes),
            'verified_at' => null,
            'attempt_count' => 0,
            'max_attempt' => 5,
            'is_used' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $otpID;
    }

    private function sendOtpEmail(User $user, string $otpCode): void
    {
        $messageBody =
            "Yth. {$user->name},\n\n" .
            "Terima kasih telah melakukan registrasi pada Sistem Informasi Penelitian RKA-K/L.\n\n" .
            "Kode OTP Anda adalah: {$otpCode}\n\n" .
            "Kode OTP berlaku selama 10 menit.\n\n" .
            "Jika Anda tidak melakukan registrasi, abaikan email ini.";

        Mail::raw($messageBody, function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Kode OTP Registrasi Sistem Informasi Penelitian RKA-K/L');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | LUPA PASSWORD
    |--------------------------------------------------------------------------
    | Alur:
    | 1. Pengguna memasukkan email.
    | 2. Sistem mengirim OTP enam digit yang berlaku lima menit.
    | 3. OTP diverifikasi maksimal lima kali percobaan.
    | 4. Session verifikasi diberi token reset selama 15 menit.
    | 5. Pengguna membuat password baru.
    | 6. Sistem menampilkan halaman sukses.
    */

    public function ShowForgotPassword()
    {
        $this->clearForgotPasswordSession();

        return view('forgot-pw.email');
    }

    public function sendForgotPasswordOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'max:150',
            ],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.max' => 'Alamat email maksimal 150 karakter.',
        ]);

        $email = strtolower(trim($validated['email']));

        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        /*
         * Jangan membocorkan apakah email tertentu terdaftar.
         * Hanya akun aktif yang dapat melanjutkan proses reset password.
         */
        if (!$user || $user->status !== 'active') {
            return back()
                ->withInput($request->only('email'))
                ->with(
                    'status',
                    'Jika email terdaftar dan akun aktif, kode OTP akan dikirim ke alamat tersebut.'
                );
        }

        try {
            $result = DB::transaction(function () use ($user) {
                $lockedUser = User::where('userID', $user->userID)
                    ->lockForUpdate()
                    ->first();

                if (!$lockedUser || $lockedUser->status !== 'active') {
                    return [
                        'success' => false,
                        'message' => 'Akun tidak dapat memproses permintaan reset password.',
                    ];
                }

                DB::table('user_otps')
                    ->where('userID', $lockedUser->userID)
                    ->where('purpose', 'forgot_password')
                    ->where('is_used', 0)
                    ->update([
                        'is_used' => 1,
                        'updated_at' => now(),
                    ]);

                $otpCode = $this->generateOtpCode();
                $otpID = $this->storeOtp(
                    $lockedUser->userID,
                    $otpCode,
                    'forgot_password',
                    5
                );

                return [
                    'success' => true,
                    'user' => $lockedUser,
                    'otp_id' => $otpID,
                    'otp_code' => $otpCode,
                ];
            });

            if (!$result['success']) {
                return back()
                    ->withInput($request->only('email'))
                    ->with('error', $result['message']);
            }

            Session::put([
                'forgot_password_user_id' => $result['user']->userID,
                'forgot_password_email' => $result['user']->email,
            ]);

            Session::forget([
                'forgot_password_verified',
                'forgot_password_verified_at',
                'forgot_password_reset_token',
            ]);

            try {
                $this->sendForgotPasswordOtpEmail(
                    $result['user'],
                    $result['otp_code']
                );
            } catch (\Throwable $mailException) {
                DB::table('user_otps')
                    ->where('otpID', $result['otp_id'])
                    ->update([
                        'is_used' => 1,
                        'updated_at' => now(),
                    ]);

                $this->clearForgotPasswordSession();
                report($mailException);

                return back()
                    ->withInput($request->only('email'))
                    ->with(
                        'error',
                        'Kode OTP gagal dikirim. Periksa konfigurasi email lalu coba kembali.'
                    );
            }

            return redirect()
                ->route('forgot.password.otp')
                ->with('success', 'Kode OTP telah dikirim ke email Anda.');
        } catch (\Throwable $e) {
            report($e);

            $message = 'Permintaan reset password gagal diproses. Silakan coba kembali.';

            if (config('app.debug')) {
                $message .= ' Error: ' . $e->getMessage();
            }

            return back()
                ->withInput($request->only('email'))
                ->with('error', $message);
        }
    }

    public function showForgotPasswordOtpForm()
    {
        $forgotPasswordUserID = Session::get('forgot_password_user_id');

        if (!$forgotPasswordUserID) {
            return redirect()
                ->route('forgot.password')
                ->with(
                    'error',
                    'Sesi lupa password tidak ditemukan. Silakan mulai kembali.'
                );
        }

        $user = User::where('userID', $forgotPasswordUserID)->first();

        if (!$user || $user->status !== 'active') {
            $this->clearForgotPasswordSession();

            return redirect()
                ->route('forgot.password')
                ->with(
                    'error',
                    'Data akun tidak ditemukan atau akun tidak aktif.'
                );
        }

        return view('forgot-pw.otp', [
            'maskedEmail' => $this->maskEmail($user->email),
            'otpExpiresIn' => 5,
        ]);
    }

    public function verifyForgotPasswordOtp(Request $request)
    {
        $validated = $request->validate([
            'otp' => [
                'required',
                'digits:6',
            ],
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.digits' => 'Kode OTP harus terdiri dari 6 digit angka.',
        ]);

        $forgotPasswordUserID = Session::get('forgot_password_user_id');

        if (!$forgotPasswordUserID) {
            return redirect()
                ->route('forgot.password')
                ->with(
                    'error',
                    'Sesi lupa password tidak ditemukan. Silakan mulai kembali.'
                );
        }

        try {
            $result = DB::transaction(
                function () use ($validated, $forgotPasswordUserID) {
                    $user = User::where('userID', $forgotPasswordUserID)
                        ->lockForUpdate()
                        ->first();

                    if (!$user || $user->status !== 'active') {
                        return [
                            'success' => false,
                            'message' => 'Data akun tidak ditemukan atau akun tidak aktif.',
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
                            'message' => 'Batas percobaan OTP telah habis. Silakan kirim ulang OTP.',
                        ];
                    }

                    if (!Hash::check($validated['otp'], $otp->otp_hash)) {
                        $newAttemptCount = ((int) $otp->attempt_count) + 1;
                        $isExhausted =
                            $newAttemptCount >= (int) $otp->max_attempt;

                        DB::table('user_otps')
                            ->where('otpID', $otp->otpID)
                            ->update([
                                'attempt_count' => $newAttemptCount,
                                'is_used' => $isExhausted ? 1 : 0,
                                'updated_at' => now(),
                            ]);

                        $remainingAttempts = max(
                            ((int) $otp->max_attempt) - $newAttemptCount,
                            0
                        );

                        return [
                            'success' => false,
                            'message' => $isExhausted
                                ? 'Kode OTP salah dan batas percobaan telah habis. Silakan kirim ulang OTP.'
                                : 'Kode OTP salah. Sisa percobaan: ' . $remainingAttempts . '.',
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
                    ];
                }
            );

            if (!$result['success']) {
                return back()
                    ->withInput()
                    ->with('error', $result['message']);
            }

            $request->session()->regenerate();

            Session::put([
                'forgot_password_verified' => true,
                'forgot_password_verified_at' => now()->toDateTimeString(),
                'forgot_password_reset_token' => Str::random(64),
            ]);

            return redirect()
                ->route('forgot.password.reset')
                ->with(
                    'success',
                    'Verifikasi OTP berhasil. Silakan buat kata sandi baru.'
                );
        } catch (\Throwable $e) {
            report($e);

            $message = 'Verifikasi OTP gagal. Silakan coba kembali.';

            if (config('app.debug')) {
                $message .= ' Error: ' . $e->getMessage();
            }

            return back()
                ->withInput()
                ->with('error', $message);
        }
    }

    public function resendForgotPasswordOtp()
    {
        $forgotPasswordUserID = Session::get('forgot_password_user_id');

        if (!$forgotPasswordUserID) {
            return redirect()
                ->route('forgot.password')
                ->with(
                    'error',
                    'Sesi lupa password tidak ditemukan. Silakan mulai kembali.'
                );
        }

        try {
            $result = DB::transaction(function () use ($forgotPasswordUserID) {
                $user = User::where('userID', $forgotPasswordUserID)
                    ->lockForUpdate()
                    ->first();

                if (!$user || $user->status !== 'active') {
                    return [
                        'success' => false,
                        'message' => 'Data akun tidak ditemukan atau akun tidak aktif.',
                    ];
                }

                $latestOtpCreatedAt = DB::table('user_otps')
                    ->where('userID', $user->userID)
                    ->where('purpose', 'forgot_password')
                    ->max('created_at');

                if (
                    $latestOtpCreatedAt &&
                    Carbon::parse($latestOtpCreatedAt)
                    ->addMinute()
                    ->isFuture()
                ) {
                    return [
                        'success' => false,
                        'message' => 'Silakan tunggu satu menit sebelum mengirim ulang OTP.',
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
                $otpID = $this->storeOtp(
                    $user->userID,
                    $otpCode,
                    'forgot_password',
                    5
                );

                return [
                    'success' => true,
                    'user' => $user,
                    'otp_id' => $otpID,
                    'otp_code' => $otpCode,
                ];
            });

            if (!$result['success']) {
                return back()->with('error', $result['message']);
            }

            try {
                $this->sendForgotPasswordOtpEmail(
                    $result['user'],
                    $result['otp_code']
                );
            } catch (\Throwable $mailException) {
                DB::table('user_otps')
                    ->where('otpID', $result['otp_id'])
                    ->update([
                        'is_used' => 1,
                        'updated_at' => now(),
                    ]);

                report($mailException);

                return back()->with(
                    'error',
                    'Kode OTP gagal dikirim. Silakan coba kembali.'
                );
            }

            Session::forget([
                'forgot_password_verified',
                'forgot_password_verified_at',
                'forgot_password_reset_token',
            ]);

            return back()->with(
                'success',
                'Kode OTP baru telah dikirim ke email Anda.'
            );
        } catch (\Throwable $e) {
            report($e);

            $message = 'Gagal mengirim ulang OTP. Silakan coba kembali.';

            if (config('app.debug')) {
                $message .= ' Error: ' . $e->getMessage();
            }

            return back()->with('error', $message);
        }
    }

    public function showResetPasswordForm()
    {
        if (!$this->hasValidForgotPasswordVerification()) {
            $this->clearForgotPasswordSession();

            return redirect()
                ->route('forgot.password')
                ->with(
                    'error',
                    'Sesi verifikasi telah berakhir. Silakan ulangi proses lupa password.'
                );
        }

        $user = User::where(
            'userID',
            Session::get('forgot_password_user_id')
        )->first();

        if (!$user || $user->status !== 'active') {
            $this->clearForgotPasswordSession();

            return redirect()
                ->route('forgot.password')
                ->with(
                    'error',
                    'Data akun tidak ditemukan atau akun tidak aktif.'
                );
        }

        return view('forgot-pw.password', [
            'resetToken' => Session::get('forgot_password_reset_token'),
        ]);
    }

    public function resetForgotPassword(Request $request)
    {
        $validated = $request->validate([
            'reset_token' => [
                'required',
                'string',
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'reset_token.required' => 'Token reset password tidak ditemukan.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sesuai.',
            'password.min' => 'Kata sandi minimal terdiri dari 8 karakter.',
        ]);

        if (!$this->hasValidForgotPasswordVerification()) {
            $this->clearForgotPasswordSession();

            return redirect()
                ->route('forgot.password')
                ->with(
                    'error',
                    'Sesi verifikasi telah berakhir. Silakan ulangi proses lupa password.'
                );
        }

        $sessionToken = (string) Session::get('forgot_password_reset_token');

        if (
            $sessionToken === '' ||
            !hash_equals($sessionToken, $validated['reset_token'])
        ) {
            $this->clearForgotPasswordSession();

            return redirect()
                ->route('forgot.password')
                ->with(
                    'error',
                    'Token reset password tidak valid. Silakan ulangi proses.'
                );
        }

        $forgotPasswordUserID = Session::get('forgot_password_user_id');

        try {
            $result = DB::transaction(
                function () use ($validated, $forgotPasswordUserID) {
                    $user = User::where('userID', $forgotPasswordUserID)
                        ->lockForUpdate()
                        ->first();

                    if (!$user || $user->status !== 'active') {
                        return [
                            'success' => false,
                            'message' => 'Data akun tidak ditemukan atau akun tidak aktif.',
                        ];
                    }

                    if (Hash::check($validated['password'], $user->password)) {
                        return [
                            'success' => false,
                            'message' => 'Kata sandi baru tidak boleh sama dengan kata sandi saat ini.',
                        ];
                    }

                    $user->update([
                        'password' => Hash::make($validated['password']),
                        'updated_at' => now(),
                    ]);

                    DB::table('user_otps')
                        ->where('userID', $user->userID)
                        ->where('purpose', 'forgot_password')
                        ->update([
                            'is_used' => 1,
                            'updated_at' => now(),
                        ]);

                    return [
                        'success' => true,
                    ];
                }
            );

            if (!$result['success']) {
                return back()->with('error', $result['message']);
            }

            $this->clearForgotPasswordSession();
            Session::flash('forgot_password_completed', true);

            return redirect()->route('forgot.password.success');
        } catch (\Throwable $e) {
            report($e);

            $message = 'Kata sandi gagal diperbarui. Silakan coba kembali.';

            if (config('app.debug')) {
                $message .= ' Error: ' . $e->getMessage();
            }

            return back()->with('error', $message);
        }
    }

    public function showForgotPasswordSuccess()
    {
        if (!Session::get('forgot_password_completed')) {
            return redirect()->route('login');
        }

        return view('forgot-pw.success');
    }

    private function hasValidForgotPasswordVerification(): bool
    {
        $userID = Session::get('forgot_password_user_id');
        $isVerified = Session::get('forgot_password_verified');
        $verifiedAt = Session::get('forgot_password_verified_at');
        $resetToken = Session::get('forgot_password_reset_token');

        if (
            !$userID ||
            $isVerified !== true ||
            !$verifiedAt ||
            !$resetToken
        ) {
            return false;
        }

        return Carbon::parse($verifiedAt)
            ->addMinutes(15)
            ->isFuture();
    }

    private function clearForgotPasswordSession(): void
    {
        Session::forget([
            'forgot_password_user_id',
            'forgot_password_email',
            'forgot_password_verified',
            'forgot_password_verified_at',
            'forgot_password_reset_token',
        ]);
    }

    private function maskEmail(string $email): string
    {
        [$localPart, $domain] = array_pad(
            explode('@', $email, 2),
            2,
            ''
        );

        if ($domain === '') {
            return '***';
        }

        $visibleCharacters = substr(
            $localPart,
            0,
            min(2, strlen($localPart))
        );

        return $visibleCharacters . '***@' . $domain;
    }

    private function sendForgotPasswordOtpEmail(
        User $user,
        string $otpCode
    ): void {
        $messageBody =
            "Yth. {$user->name},\n\n" .
            "Kami menerima permintaan pengaturan ulang kata sandi akun Anda " .
            "pada Sistem Informasi Penelitian RKA-K/L.\n\n" .
            "Kode OTP Anda adalah: {$otpCode}\n\n" .
            "Kode OTP berlaku selama 5 menit dan hanya dapat digunakan satu kali.\n\n" .
            "Jika Anda tidak meminta pengaturan ulang kata sandi, abaikan email ini " .
            "atau segera hubungi administrator sistem";

        Mail::raw($messageBody, function ($message) use ($user) {
            $message->to($user->email)
                ->subject(
                    'Kode OTP Lupa Password Sistem Informasi Penelitian RKA-K/L'
                );
        });
    }

    public function showAccount(Request $request)
    {
        $userID = Session::get('user_id');

        if (!$userID) {
            return redirect()
                ->route('login')
                ->with('error', 'Silakan login untuk mengakses halaman akun.');
        }

        $user = User::where('userID', $userID)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->first();

        if (!$user) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('error', 'Akun tidak ditemukan atau sudah tidak aktif.');
        }

        /*
         * Blade yang sudah dibuat membaca data jabatan melalui:
         * $user->jabatan->jabatan_name, jabatan_type, jabatan_level, eselon.
         * setRelation dipakai agar tidak bergantung pada ada/tidaknya method
         * relationship jabatan() pada model User.
         */
        $jabatan = null;

        if ($user->jabatanID) {
            $jabatan = DB::table('jabatan')
                ->where('jabatanID', $user->jabatanID)
                ->first();
        }

        $user->setRelation('jabatan', $jabatan);

        $user->setAttribute(
            'registered_date',
            $user->created_at
                ? Carbon::parse($user->created_at)
                ->locale('id')
                ->translatedFormat('d M Y')
                : '-'
        );

        $user->setAttribute(
            'last_login_display',
            $user->last_login_at
                ? Carbon::parse($user->last_login_at)
                ->timezone('Asia/Jakarta')
                ->locale('id')
                ->translatedFormat('d M Y, H:i') . ' WIB'
                : 'Belum pernah login'
        );

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

        return view('menu.user.account', [
            'user' => $user,
            'jabatans' => $jabatans,
        ]);
    }

    public function updateAccount(Request $request)
    {
        $userID = Session::get('user_id');

        if (!$userID) {
            return redirect()
                ->route('login')
                ->with('error', 'Sesi login telah berakhir. Silakan login kembali.');
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'email')
                    ->ignore($userID, 'userID'),
            ],
            'username' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('users', 'username')
                    ->ignore($userID, 'userID'),
            ],
            'jabatanID' => [
                'required',
                'string',
                'exists:jabatan,jabatanID',
            ],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap maksimal 100 karakter.',

            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.max' => 'Alamat email maksimal 100 karakter.',
            'email.unique' => 'Alamat email sudah digunakan akun lain.',

            'username.required' => 'Username wajib diisi.',
            'username.alpha_dash' =>
            'Username hanya boleh berisi huruf, angka, strip, dan underscore.',
            'username.max' => 'Username maksimal 100 karakter.',
            'username.unique' => 'Username sudah digunakan akun lain.',

            'jabatanID.required' => 'Jabatan wajib dipilih.',
            'jabatanID.exists' => 'Jabatan yang dipilih tidak ditemukan.',
        ]);

        try {
            $result = DB::transaction(
                function () use ($validated, $userID) {
                    $user = User::where('userID', $userID)
                        ->whereNull('deleted_at')
                        ->lockForUpdate()
                        ->first();

                    if (!$user || $user->status !== 'active') {
                        return [
                            'success' => false,
                            'message' => 'Akun tidak ditemukan atau sudah tidak aktif.',
                        ];
                    }

                    $jabatan = DB::table('jabatan')
                        ->where('jabatanID', $validated['jabatanID'])
                        ->first();

                    if (!$jabatan) {
                        return [
                            'success' => false,
                            'message' => 'Data jabatan tidak ditemukan.',
                        ];
                    }

                    /*
                     * Superadmin tidak diturunkan role-nya ketika mengedit jabatan.
                     * Selain superadmin, role mengikuti level jabatan sebagaimana
                     * alur registrasi yang sudah digunakan sebelumnya.
                     */
                    $roleID = $user->roleID === 'role0001'
                        ? 'role0001'
                        : $this->resolveRoleIDFromJabatanLevel(
                            $jabatan->jabatan_level
                        );

                    $emailChanged =
                        strtolower((string) $user->email) !==
                        strtolower($validated['email']);

                    $user->update([
                        'name' => trim($validated['name']),
                        'email' => strtolower(trim($validated['email'])),
                        'username' => trim($validated['username']),
                        'jabatanID' => $jabatan->jabatanID,
                        'roleID' => $roleID,

                        /*
                         * Jika email berubah, email harus dianggap belum
                         * terverifikasi. Pada tahap berikutnya dapat ditambah
                         * OTP khusus verifikasi perubahan email.
                         */
                        'email_verified_at' => $emailChanged
                            ? null
                            : $user->email_verified_at,

                        'updated_at' => now(),
                    ]);

                    return [
                        'success' => true,
                        'user' => $user->fresh(),
                    ];
                }
            );

            if (!$result['success']) {
                return back()
                    ->withInput()
                    ->with('error', $result['message']);
            }

            Session::put([
                'user_name' => $result['user']->name,
                'username' => $result['user']->username,
                'role_id' => $result['user']->roleID,
            ]);

            return redirect()
                ->route('account.show')
                ->with('success', 'Informasi akun berhasil diperbarui.');
        } catch (\Throwable $e) {
            report($e);

            $message = 'Informasi akun gagal diperbarui. Silakan coba kembali.';

            if (config('app.debug')) {
                $message .= ' Error: ' . $e->getMessage();
            }

            return back()
                ->withInput()
                ->with('error', $message);
        }
    }

    public function deleteAccount(Request $request)
    {
        $userID = Session::get('user_id');

        if (!$userID) {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Sesi login telah berakhir. Silakan login kembali.'
                );
        }

        try {
            $result = DB::transaction(function () use ($userID) {
                $user = User::where('userID', $userID)
                    ->where('status', 'active')
                    ->whereNull('deleted_at')
                    ->lockForUpdate()
                    ->first();

                if (!$user) {
                    return [
                        'success' => false,
                        'message' => 'Akun tidak ditemukan atau sudah tidak aktif.',
                    ];
                }

                /*
             * Gunakan assignment langsung agar tidak terhalang
             * pengaturan $fillable pada model User.
             */
                $user->status = 'nonactive';
                $user->deleted_at = now();
                $user->save();

                DB::table('user_otps')
                    ->where('userID', $user->userID)
                    ->where('is_used', 0)
                    ->update([
                        'is_used' => 1,
                        'updated_at' => now(),
                    ]);

                return [
                    'success' => true,
                ];
            });

            if (!$result['success']) {
                return back()->with('error', $result['message']);
            }

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with(
                    'success',
                    'Akun berhasil dihapus dan Anda telah keluar dari sistem.'
                );
        } catch (\Throwable $e) {
            report($e);

            $message = 'Akun gagal dihapus. Silakan coba kembali.';

            if (config('app.debug')) {
                $message .= ' Error: ' . $e->getMessage();
            }

            return back()->with('error', $message);
        }
    }

    private function resolveRoleIDFromJabatanLevel(
        string $jabatanLevel
    ): string {
        $leaderLevels = [
            'JPT_UTAMA',
            'JPT_MADYA',
            'JPT_PRATAMA',
            'ADMINISTRATOR',
        ];

        return in_array($jabatanLevel, $leaderLevels, true)
            ? 'role0003'
            : 'role0002';
    }

    public function ShowDashboard()
    {
        return view('menu.dashboard.dashboard');
    }

    public function logout()
    {
        Session::flush();

        return redirect('/login')->with('success', 'Berhasil logout.');
    }
}
