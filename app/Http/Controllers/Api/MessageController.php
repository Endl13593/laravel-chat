<?php

namespace App\Http\Controllers\Api;

use App\Events\Chat\SendMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MessageFormRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class MessageController extends Controller
{
    public function listMessages(User $user)
    {
        $userFrom = auth()->id();
        $userTo = $user->id;

        $messages = Message::query();

        $messages->where(
            function ($query) use ($userFrom, $userTo){
                $query->where([
                    'from' => $userFrom,
                    'to' => $userTo
                ]);
            }
        )->orWhere(
            function ($query) use ($userFrom, $userTo){
                $query->where([
                    'from' => $userTo,
                    'to' => $userFrom
                ]);
            }
        )->orderBy('created_at');

        return $messages->get();
    }

    public function store(MessageFormRequest $request)
    {
        $message = new Message();

        $message->from = auth()->id();
        $message->to = $request->to;
        $message->content = $request->content;
        $message->save();

        Event::dispatch(new SendMessage($message, $request->to));
    }
}
