<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $user = User::where("usuario", $request->usuario)->first();
        if (!is_null($user) && Hash::check($request->password, $user->password)) {
            $token = $user->createToken("Laravel Password Grant Client");

            $permissions = $user->getAllPermissions()->map(function ($item, $key) {
                return $item->only('id', 'name', 'guard_name', 'action', 'subject');
            });
            $roles = $user->roles->map(function ($item, $key) {
                return $item->only('id', 'name', 'guard_name', 'action', 'subject');
            });
            return response()->json([
                'res' => true,
                'usuario' => $user->makeHidden(['permissions', 'roles', 'email_verified_at']),
                'token' => $token->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString(),
                'permissions' => $permissions,
                'roles' => $roles,
                'message' => "Bienvenido al sistema"
            ]);
        } else
            return response()->json(['res' => false, 'message' => "Cuenta o password incorrectos"], 401);
    }

    public function logout()
    {
        //dd(auth()->usuario());
        $user = auth()->user(); //->getAllPermissions();
        $user->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
        // $user->tokens->each(function ($token, $key) {
        //     $token->delete();
        // });
        //return response()->json(['res' => true, 'message' => "Adios"]);
    }
    public function usuario_datos()
    {
        $usuario = auth()->user()->makeHidden(['created_at', 'updated_at', 'permissions', 'roles', 'email_verified_at']);
        $permissions = $usuario->getAllPermissions()->map(function ($item, $key) {
            return $item->only('id', 'name', 'guard_name', 'action', 'subject');
        });
        $roles = $usuario->roles->map(function ($item, $key) {
            return $item->only('id', 'name', 'guard_name', 'action', 'subject');
        });
        return response()->json([
            'usuario' => $usuario->makeHidden('perfil'),
            'permissions' => $permissions,
            'roles' => $roles
        ]);
    }
    public function password_update(Request $request)
    {
        //return $request->old_password;
        if (Hash::check($request->old_password, auth()->user()->password)) {
            auth()->user()->password = $request->new_password;
            auth()->user()->save();
            return response()->json(['res' => true, 'message' => "Contraseña actualizada"]);
        } else {
            return response()->json(['res' => false, 'message' => "Contraseña actual incorrecta"], 401);
        }
    }
    public function img_update(Request $request)
    {
        $request->validate([
            'foto'  => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'firma' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $centinela = false;
        if ($request->has('foto') && $request->file('foto')->isValid()) {
            Storage::delete(auth()->user()->foto);
            $path = $request->foto->store('public/usuarios/fotos');
            $data['foto'] = $path;
            $centinela = true;
        }
        if ($request->has('firma') && $request->file('firma')->isValid()) {
            Storage::delete(auth()->user()->firma);
            $path = $request->firma->store('public/usuarios/firmas');
            $data['firma'] = $path;
            $centinela = true;
        }
        if ($centinela) {
            auth()->user()->update($data);
            return response()->json(['res' => true, 'message' => "Imagen actualizada", "data" => auth()->user()]);
        }
        return response()->json(['res' => false, 'message' => "Imagen no actualizada"], 401);
    }
}
