<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberInviteRequest;
use App\Notifications\UserInviteNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $members = User::where('user_id', auth()->id())->get();

        return view('members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('members.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\MemberInviteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MemberInviteRequest $request)
    {
        $user = User::create([
            'name' => '',
            'email' => $request->input('email'),
            'password' => 'password',
            'user_id' => auth()->id(),
            'invitation_token' => Str::random(32),
        ]);

        $user->notify(new UserInviteNotification());

        return redirect()->route('members.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::where('user_id', auth()->id())->where('id', $id)->firstOrFail();
        $user->delete();

        return redirect()->route('members.index');
    }
}
