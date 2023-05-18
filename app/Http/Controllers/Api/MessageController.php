<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\User;

class MessageController extends Controller
{
    public function listMessages(User $user)
    {
        $userFrom = Auth::user()->id;
        $userTo = $user->id;

        $messages = Message::where(function ($query) use ($userFrom, $userTo) {
            $query->where([
                'from' => $userFrom,
                'to' => $userTo
            ]);
        })->orWhere(function ($query) use ($userFrom, $userTo) {
            $query->where([
                'to' => $userFrom,
                'from' => $userTo
            ]);
        })->orderBy('created_at', 'ASC')->get();

        return response()->json([
            'messages' => $messages
        ], Response::HTTP_OK);
    }

    public function index(Request $request)
    {
        //
    }

    public function store(Request $request)
    {
        $message = new Message();
        $message->from = Auth::user()->id;
        $message->to = $request->to;
        $message->content = $request->content;

        $message->save();
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
