<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Hash;

class AuthController extends Controller {

    public function getLogin(Request $request) {
		return view('auth/login');
	}

	public function getRegister(Request $request) {
		return view('auth/register');
	}

	public function postLogin(Request $request) {
		$request->validate([
			'email' => 'email',
			'password' => 'required'
		]);

		$user = User::where('email', $request->email)->first();
		if ($user && Hash::check($request->password, $user->password)) {
			// Verifica tipo de usuário, então faz o login de acordo
			// Verifique config/auth.php em guards
			if ($user->type == 'VET') {
				auth()->guard('vet')->login($user);
				auth()->login($user);
				return redirect()->route('vet');
			}
			else {
				auth()->login($user);
				return redirect()->route('client');
			}
		}
		else {
			return redirect()->back()->withInput()->withErrors([ 'email' => 'Usuário não encontrado' ]);
		}
	}

	public function postRegister(Request $request) {
		$request->validate([
			'name' => 'required',
			'email' => 'email',
			'password' => 'required|min:8'
		]);

		$user = User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => Hash::make($request->password),
		]);

		auth()->login($user);

		return redirect()->route('client')->with('toast', 'Cadastro efetuado com sucesso');
	}

	public function getLogout() {
		$guards = array_keys(config('auth.guards'));
		foreach ($guards as $guard) {
			if (auth()->guard($guard)->check()) {
				auth()->guard($guard)->logout();
			}
		}

		return redirect()->route('index')->with('toast', 'Sessão finalizada com sucesso!');
	}
}
