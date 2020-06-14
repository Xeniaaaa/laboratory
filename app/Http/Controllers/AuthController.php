<?php

namespace App\Http\Controllers;

use Auth;
use GuzzleHttp\Psr7\Request;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        $data = $request->all();
        $login = $data['login'];
        $password = $data['password'];

        if (Auth::attempt(['ulogin' => $login, 'password' => $password])) {
            // Аутентификация прошла успешно
            return redirect()->intended('dashboard');
        }
    }
}
