<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query();

        return $users->where('id', '!=', auth()->id())->get();
    }

    public function me()
    {
        return Auth::user();
    }
}
