<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        Log::info('Tentando registrar usuário', $request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            Log::warning('Falha na validação do registro', $validator->errors()->toArray());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Log para checar se o request está chegando corretamente
        Log::info('Dados validados para registro', $request->only('name', 'email'));

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Log::info('Usuário registrado com sucesso', ['user_id' => $user->id]);
        Auth::login($user);
        return redirect('/dashboard');
    }

    public function login(Request $request)
    {
        Log::info('Tentando login', $request->only('email'));
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            Log::info('Login bem-sucedido', ['user_id' => Auth::id()]);
            return redirect()->intended('/dashboard');
        }
        Log::warning('Falha no login', ['email' => $request->email]);
        return back()->withErrors([
            'email' => 'E-mail ou senha inválidos.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function listUsers()
    {
        $users = \App\Models\User::all();
        $roles = ['admin' => 'Admin', 'funcionario' => 'Funcionário', 'estagiario' => 'Estagiário'];
        return view('users', compact('users', 'roles'));
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,funcionario,estagiario',
        ]);
        $user = \App\Models\User::findOrFail($id);
        $user->role = $request->role;
        $user->save();
        return redirect()->route('users')->with('success', 'Permissão atualizada com sucesso!');
    }
}
