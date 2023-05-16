<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index(Request $request)
    {

        $userLogged = auth()->user();
        $users = User::where('id', '!=', $userLogged->id)->get();

        return response()->json([
            'users' => $users,
        ], Response::HTTP_OK);
    }
}
