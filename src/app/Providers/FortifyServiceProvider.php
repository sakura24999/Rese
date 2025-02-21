<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\RegisterResponse;
use \Laravel\Fortify\Contracts\LogoutResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Event;
use Laravel\Fortify\Contracts\LoginResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->instance(LoginResponse::class, new class implements LoginResponse {
            public function toResponse($request)
            {
                try {
                    /** @var \App\Models\User|null $user */
                    $user = auth()->user();

                    Log::error('Login Response Debug', [
                        'user' => $user ? 'exists' : 'null',
                        'email' => $user ? $user->email : 'none',
                        'roles' => $user ? $user->roles->pluck('name') : 'none',
                        'has_admin_role' => $user ? $user->hasRole('admin') : 'none',
                        'session_id' => session()->getId(),
                        'path' => $request->path(),
                        'intended_url' => session()->get('url.intended'),
                    ]);

                    if ($user && $user->roles->contains('name', 'admin')) {
                        Log::error('Redirecting admin user', [
                            'route' => 'admin.dashboard',
                            'user_id' => $user->id
                        ]);
                        return redirect()->intended(route('admin.dashboard'));
                    }

                    Log::error('Redirecting normal user', [
                        'route' => 'menu.user',
                        'user_id' => $user ? $user->id : 'none'
                    ]);
                    return redirect()->intended(route('menu.user'));
                } catch (\Exception $e) {
                    Log::error('Exception in LoginResponse', [
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }
            }
        });

        $this->app->singleton(LogoutResponse::class, function () {
            return new class implements LogoutResponse {
                public function toResponse($request)
                {
                    return redirect()->route('menu.guest');
                }
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::authenticateUsing(function (Request $request) {
            try {
                Log::error('Authentication Attempt Start', [
                    'email' => $request->email,
                    'path' => $request->path()
                ]);

                $user = User::where('email', $request->email)->first();

                if (!$user) {
                    Log::error('User not found', [
                        'email' => $request->email
                    ]);
                    return null;
                }

                $passwordValid = Hash::check($request->password, $user->password);

                Log::error('Password Check', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'password_valid' => $passwordValid,
                    'roles' => $user->roles()->pluck('name')
                ]);

                if ($passwordValid) {
                    Log::error('Authentication Successful', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'roles' => $user->roles()->pluck('name'),
                        'session_id' => session()->getId()
                    ]);
                    return $user;
                }

                Log::error('Password Invalid', [
                    'email' => $request->email
                ]);
                return null;
            } catch (\Exception $e) {
                Log::error('Exception in authenticateUsing', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()

                ]);
                throw $e;
            }
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::createUsersUsing(CreateNewUser::class);

        $this->app->singleton(
            RegisterResponse::class,
            function () {
                return new class implements RegisterResponse {
                    public function toResponse($request)
                    {
                        return redirect('/email/verify');
                    }
                };
            }
        );

        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
