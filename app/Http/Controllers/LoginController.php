<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Users;
 
class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {

            if (Auth::guard('api')->attempt($credentials)) {
                $user = Auth::guard('api')->user();
                Cache::put('user', $user, 70 - 60);
            }

            $request->session()->regenerate();

            return response()->json(['message' => 'Authenticated', 'user' => $user], 200);

        }
 
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}