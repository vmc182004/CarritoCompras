<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



class LoginController extends Controller
{
    //
    public function register(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
    
        try {
            $user->save();
        } catch (\Exception $e) {
            // Si se produce un error, agrega un mensaje de error a la sesión
            return redirect(route('registro'))->with('error', 'Error al registrarse, verifique los datos y el correo electrónico único.');
        }
    
        Auth::login($user);
    
        // Agregar un mensaje de éxito si el registro se realizó correctamente
        return redirect(route('login'))->with('success', '¡Registro exitoso! Ya puedes iniciar sesión');
    }
    
    
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            // Verificar si el usuario es administrador
            if (Auth::user()->role === 'admin') {
                // Redireccionar al panel de administrador
                return redirect()->route('admin');
            } else {
                // Redireccionar al panel de usuario normal
                return redirect()->route('privada');
            }
        }
    
        return redirect()->route('login')->with('error', 'Credenciales incorrectas');
    }
    


    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('index'));
    }
}