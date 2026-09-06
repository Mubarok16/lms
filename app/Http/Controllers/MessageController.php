<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $messages = Message::with('sender')->where(fn($q)=>$q->where('recipient_id',$user->id)->orWhereNull('recipient_id'))->latest()->paginate(15);
        $users = $user->role === 'admin' ? User::where('id', '<>', $user->id)->orderBy('name')->get() : collect();
        return view('messages.index', compact('messages','users'));
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->role === 'admin', 403);
        $data = $request->validate(['recipient_id'=>'nullable|exists:users,id','subject'=>'required|string|max:150','body'=>'required|string|max:5000']);
        $data['sender_id']=Auth::id(); Message::create($data);
        return back()->with('success','Pesan berhasil dikirim.');
    }

    public function read(Message $message)
    {
        $user=Auth::user();
        abort_unless($message->recipient_id === null || $message->recipient_id === $user->id,403);
        if (!$message->read_at) $message->update(['read_at'=>now()]);
        return back();
    }
}
