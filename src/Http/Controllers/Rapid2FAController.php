<?php

namespace Jrebs\Rapid2FA\Http\Controllers;

use Crypt;
use Google2FA;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use \ParagonIE\ConstantTime\Base32;

class Rapid2FAController extends Controller
{
    use ValidatesRequests;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('web');
    }

    /**
     * Generate a secret and QR image and render a confirmation view.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function enableTwoFactor(Request $request)
    {
        $user = $request->user();
        $secret = $this->generateSecret();
        $imageDataUri = Google2FA::getQRCodeInline(
            $request->getHttpHost(),
            $user->email,
            $secret,
            200
        );

        return view('rapid2fa::enable', [
            'image' => $imageDataUri,
            'secret' => $secret
        ]);
    }

    /**
     * Verify the provided confirmation and then save the secret.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function confirmTwoFactor(Request $request)
    {
        $user = $request->user();
        $secret = $request->input('token');
        $code = $request->input('rapid2fa');
        if (!$code || !$secret || !Google2FA::verifyKey($secret, $code)) {
            return redirect()->back()
                ->with('error', config('rapid2fa.failed_text'));
        }
        $user->google2fa_secret = Crypt::encrypt($secret);
        $user->save();

        // The intended.url may be set by the require2fa middleware
        return redirect(session()->pull('intended.url', 'home'))
            ->with('status', config('rapid2fa.enabled_text'));
    }

    /**
     * Turn off two-factor auth for this user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function disableTwoFactor(Request $request)
    {
        $user = $request->user();
        $user->google2fa_secret = null;
        $user->save();

        return redirect()->back()
            ->with('status', config('rapid2fa.disabled_text'));
    }

    /**
     * Generate a secret key in Base32 format.
     *
     * @return string
     */
    private function generateSecret()
    {
        $randomBytes = random_bytes(10);

        return Base32::encodeUpper($randomBytes);
    }
}
