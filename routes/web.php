<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/setup', function () {
    $credentials = [
        'email' => 'admin@gmail.com',
        'password' => 'password'
    ];
    if (!auth()->attempt($credentials)) {
        $user = new User();
        $user->name = "Admin";
        $user->email = $credentials['email'];
        $user->password = Hash::make($credentials['password']);
        $user->save();
        $admin = $user->createToken('admin-token', ['read', 'create', 'update', 'delete'])->plainTextToken;
        $update = $user->createToken('update-token', ['read', 'create', 'update'])->plainTextToken;
        $basic = $user->createToken('basic-token', ['none'])->plainTextToken;
        return [
            'admin' => $admin,
            'update' => $update,
            'basic' => $basic
        ];
    }
});
