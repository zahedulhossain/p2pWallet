<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserSearchController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'excludeId' => ['nullable', 'exists:users,id'],
        ]);

        return User::filter($request->only('search', 'excludeId'))
            ->select('id', 'name', 'email', 'profile_photo_path')
            ->latest()
            ->take(10)
            ->get();
    }
}
