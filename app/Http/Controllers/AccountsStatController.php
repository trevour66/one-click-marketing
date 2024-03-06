<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

use App\Models\emailMarketingLink;

class AccountsStatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loggedIn_user = auth()->user();
        
        $user = User::find($loggedIn_user->id);

        $user_is_super_admin =  $user->hasRole('super-admin') || false;

        // logger($user->hasRole('super-admin'));
        
        if(!$user_is_super_admin){
            return redirect('dashboard');
        }

        $allUsers = DB::table('users')
            ->orderBy('users.id', 'asc');
            
        $allUsers = $allUsers->paginate(6);
        
        $allUsers_data = $allUsers->items() ?? [];
        
        
        $processed_marketing_links = [];
        
        foreach ($allUsers_data as $currentUser) {
    
            $dataToPush = [
                "user_data" => $currentUser,
                "marketing_links" => emailMarketingLink::where('user_unique_public_id', '=', $currentUser->user_unique_public_id)
                                        ->leftJoin("email_marketing_stats AS ES", "email_marketing_links.email_marketing_links_id", "=", "ES.email_marketing_links_id")

                                        ->leftJoin("email_marketing_platforms AS EP", "email_marketing_links.email_marketing_platforms_id", "=", "EP.email_marketing_platforms_id")

                                        ->groupBy('email_marketing_links.email_marketing_links_id')
                                        ->select([
                                            "email_marketing_links.*",
                                            DB::raw('COUNT(ES.email_marketing_stats_id) AS subscribers'),
                                            "EP.name AS platforms_name"
                                        ])
                                        ->get() ,
            ];

            array_push($processed_marketing_links, $dataToPush);
        }

        return Inertia::render('UserAccount/UserAccount', [
            "martketing_links_data" => [
                'data' => $processed_marketing_links,
                'links' => [
                    'prev' => $allUsers->previousPageUrl() ?? false,
                    'next' => $allUsers->nextPageUrl() ?? false
                ],
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\accountsStat  $accountsStat
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

    

}
