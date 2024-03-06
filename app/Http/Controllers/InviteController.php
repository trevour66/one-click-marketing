<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;


class InviteController extends Controller
{   

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

            $validated = $request->validate([
                'invite_email' => 'required|string|email|max:255',
                'invite_status' => 'required|string|max:255',
                'invite_sent_by' => 'required',
            ]);
            
            $hashSeed = $validated['invite_email'] . rand(1,1000000);
            
            $validated['invite_link_ref'] = hash('sha256', $hashSeed);
    
            $user_id = $validated['invite_sent_by'];

            $user = User::find($user_id);
            
            $user->invite()->updateOrCreate(
                [
                    'invite_email' => $validated['invite_email'],
                ],
                [
                    'invite_status' => $validated['invite_status'],
                    'invite_link_ref' => $validated['invite_link_ref'],
                ]
            );

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invite  $invite
     * @return \Illuminate\Http\Response
     */
    public function show(Invite $invite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invite  $invite
     * @return \Illuminate\Http\Response
     */
    public function edit(Invite $invite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invite  $invite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invite $invite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invite  $invite
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invite $invite)
    {
        //
    }
}
