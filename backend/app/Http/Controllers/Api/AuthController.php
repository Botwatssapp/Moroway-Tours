<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
class AuthController extends Controller {
 public function login(LoginRequest $request){if(!Auth::guard('web')->attempt($request->validated()))throw ValidationException::withMessages(['email'=>'Identifiants incorrects.']);$request->session()->regenerate();$user=Auth::guard('web')->user();if(!$user->is_admin){Auth::guard('web')->logout();$request->session()->invalidate();$request->session()->regenerateToken();abort(403);}return response()->json($user);}
 public function logout(Request $request){Auth::guard('web')->logout();$request->session()->invalidate();$request->session()->regenerateToken();return response()->noContent();}
}
