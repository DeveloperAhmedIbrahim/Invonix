<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return Application|Factory|View|\Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        if (getSuperAdminSettingKeyValue('enable_google_recaptcha')) {
            $validator = Validator::make($request->all(), [
                'g-recaptcha-response' => ['required'],
            ], [
                'g-recaptcha-response.required' => __('messages.setting.google_captcha_required'),
                'g-recaptcha-response.recaptcha' => __('messages.setting.captcha_failed'),
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }
        $request->authenticate();

        $request->session()->regenerate();
        if ($request->user()->hasRole('super_admin')) {
            return redirect()->intended(getSuperAdminDashboardURL());
        } elseif ($request->user()->hasRole('admin')) {
            return redirect()->intended(getAdminDashboardURL());
        } elseif ($request->user()->hasRole('client')) {
            return redirect()->intended(getClientDashboardURL());
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
