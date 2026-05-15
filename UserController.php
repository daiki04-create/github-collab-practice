<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        return DB::transaction(function () use ($validated) {
            
            $user = $this->createUser($validated);

            return redirect('/users')->with('success', 'ユーザーを登録しました');
        });
    }

    private function validateRequest(Request $request)
    {
        return $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);
    }

    private function createUser(array $data)
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->save();

        return $user;
    }
}