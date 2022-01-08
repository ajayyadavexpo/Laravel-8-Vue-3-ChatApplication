<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;

use App\Events\SendMessage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function chat(){
        return view('chat');
    }

    public function messages(){
        return Message::with('user')->get();
    }

    public function messageStore(Request $request){
        $user = Auth::user();

        $messages = $user->messages()->create([
            'message' => $request->message
        ]);

        broadcast(new SendMessage($user, $messages))->toOthers();

        return 'message sent';
    }
}
