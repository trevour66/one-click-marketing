<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\emailMarketingLink;
use App\Models\emailMarketingStat;
use App\Models\emailMarketingPlatform;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Error;
use Inertia\Inertia;
use Illuminate\Http\Request;

class EmailMarketingLinkController extends Controller{
    
    private function generateUniqueId() {
        // Generate a random string for the 'Ec' part
        $randomString = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2);

        // Generate a unique ID using uniqid
        $uniqueId = uniqid();

        // Base64 encode the unique ID
        $encodedId = base64_encode($uniqueId);

        // Replace any characters not allowed in base64 encoded strings
        $encodedId = str_replace(array('+', '/', '='), array('-', '_', ''), $encodedId);

        // Form the final ID string
        $finalId = $randomString . '-' . $encodedId ;

        return $finalId;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $providers = emailMarketingPlatform::get();
        $links = DB::table("email_marketing_links AS EL")
                    ->where("user_unique_public_id", "=", $user->user_unique_public_id)
                    ->leftJoin("email_marketing_stats AS ES", "EL.email_marketing_links_id", "=", "ES.email_marketing_links_id") 
                    ->groupBy('EL.email_marketing_links_id')
                    ->select([
                        "EL.*",
                        DB::raw('COUNT(ES.email_marketing_stats_id) AS subscribers')
                    ])
                    ->latest()
                    ->get();

        return Inertia::render('Link/Link', [
            "email_providers" => $providers,
            "links" => $links,
            "user" => $user,
        ]);
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
        try {
            // logger('here');
            $validated = $request->validate([
                'user_unique_public_id' => 'required|string',
                'name' => 'required|string',
                'campaign' => 'required|string',
                'zapier_webhook_url' => 'required|url:http,https',
                'partner_email_service' => 'required|numeric',
                'success_page_url' => 'required|url:http,https',
                'failure_page_url' => 'required|url:http,https',
            ]);

            $user = User::where("user_unique_public_id", "=", $validated["user_unique_public_id"])->first() ?? false;

            logger($user);

            if(!$user){
                throw new Error('System error. Please try again and if problem persist, contact support');
            }

            $doesLinkWithSameNameExist = emailMarketingLink::where("name", "=", $validated["name"])->first() ?? false;

            if($doesLinkWithSameNameExist){
                throw new Error('Link with the same name already exist. Please change the name');
            }

            $provider = emailMarketingPlatform::where("email_marketing_platforms_id", "=", $validated["partner_email_service"])->first() ?? false;

            logger($provider);

            if(!$provider){
                throw new Error('We experienced a problem identifying the email provider. Please try again and if problem persist, contact support');
            }

            $name = $provider->name ?? false;
            $merge_tag_email = $provider->merge_tag_email ?? false;
            $merge_tag_firstname = $provider->merge_tag_firstname ?? false;
            $merge_tag_lastname = $provider->merge_tag_lastname ?? false ;

            if(
                !$name ||
                !$merge_tag_email ||
                !$merge_tag_firstname ||
                !$merge_tag_lastname
            ){
                throw new Error('We experienced a problem identifying the email provider. Please try again and if problem persist, contact support');
            }

            $uniqueId = "";        
            $doesLinkWithSameUniqueIdExist = true;
            
            while ($doesLinkWithSameUniqueIdExist) {            
                $uniqueId = $this->generateUniqueId();        

                $doesLinkWithSameUniqueIdExist = emailMarketingLink::where("link_identifier", "=", $uniqueId)->first() ?? false;
            }

            if(!$uniqueId){
                throw new Error('System error. Please try again and if problem persist, contact support');
            }

            $domain = config('app.url');

            $full_link = $domain."/"."cl/".$uniqueId."/?email=".$merge_tag_email."&fname=".$merge_tag_firstname."&lname=".$merge_tag_lastname;


            $newLink = emailMarketingLink::create(
                [
                    'name' => $validated["name"],
                    'user_unique_public_id' => $validated["user_unique_public_id"],
                    'failure_page_url' => $validated["failure_page_url"],
                    'success_page_url' => $validated["success_page_url"],
                    'link_identifier' => $uniqueId,
                    'full_link' => $full_link,
                    'email_marketing_platforms_id' => $validated["partner_email_service"],
                    'campaign' => $validated["campaign"],
                    'zapier_webhook_url' => $validated["zapier_webhook_url"],
                    "user_unique_public_id" =>  $validated["user_unique_public_id"]
                ]
            );

             $resData = response(json_encode([
                    'status' => "success",
                    "data" => [
                        'name' => $newLink->name,
                        'failure_page_url' => $newLink->failure_page_url,
                        'success_page_url' => $newLink->success_page_url,
                        'link_identifier' => $newLink->link_identifier,
                        'full_link' => $newLink->full_link,
                        'email_marketing_platforms_id' => $newLink->email_marketing_platforms_id,
                        'campaign' => $newLink->campaign,
                        'zapier_webhook_url' => $newLink->zapier_webhook_url,
                    ],
                ]
            ), 200)
            ->header('Content-Type', 'application/json');
    
            return $resData;   
        } catch (\Throwable $th) {
            logger($th->getMessage());
            $resData = response(json_encode([
                    'status' => "error",
                    "data" => null,
                    "message" => $th->getMessage()
                ]
            ), 400)
            ->header('Content-Type', 'application/json');

            return $resData;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\emailMarketingLink  $emailMarketingLink
     * @return \Illuminate\Http\Response
     */
    public function show(emailMarketingLink $emailMarketingLink)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\emailMarketingLink  $emailMarketingLink
     * @return \Illuminate\Http\Response
     */
    public function edit(emailMarketingLink $emailMarketingLink)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String $emailMarketingLink_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $emailMarketingLink_id)
    {
        try {
            $emailMarketingLink_id = $emailMarketingLink_id ?? false;
            
            $emailMarketingLink = emailMarketingLink::where("email_marketing_links_id", "=", $emailMarketingLink_id)->first() ?? false;

            if(! $emailMarketingLink){
                throw new Error('No match found. Please contact support');
            }

            $validated = $request->validate([
                'link_identifier' => 'required|string',
                'user_unique_public_id' => 'required|string',
                'name' => 'required|string',
                'campaign' => 'required|string',
                'zapier_webhook_url' => 'required|url:http,https',
                'partner_email_service' => 'required|numeric',
                'success_page_url' => 'required|url:http,https',
                'failure_page_url' => 'required|url:http,https',
            ]);

            $user = User::where("user_unique_public_id", "=", $validated["user_unique_public_id"])->first() ?? false;


            if(!$user){
                throw new Error('System error. Please try again and if problem persist, contact support');
            }

            $doesLinkWithSameNameExist = emailMarketingLink::where("name", "=", $validated["name"])->first() ?? false;

            if($doesLinkWithSameNameExist){
                if($doesLinkWithSameNameExist->email_marketing_links_id != $emailMarketingLink_id){

                    throw new Error('Link with the same name already exist. Please change the name');
                }
            }

            $provider = emailMarketingPlatform::where("email_marketing_platforms_id", "=", $validated["partner_email_service"])->first() ?? false;


            if(!$provider){
                throw new Error('We experienced a problem identifying the email provider. Please try again and if problem persist, contact support');
            }

            $name = $provider->name ?? false;
            $merge_tag_email = $provider->merge_tag_email ?? false;
            $merge_tag_firstname = $provider->merge_tag_firstname ?? false;
            $merge_tag_lastname = $provider->merge_tag_lastname ?? false ;

            if(
                !$name ||
                !$merge_tag_email ||
                !$merge_tag_firstname ||
                !$merge_tag_lastname
            ){
                throw new Error('We experienced a problem identifying the email provider. Please try again and if problem persist, contact support');
            }


            $domain = config('app.url');

            $full_link = $domain."/"."cl/".$emailMarketingLink->link_identifier."/?email=".$merge_tag_email."&fname=".$merge_tag_firstname."&lname=".$merge_tag_lastname;


            $emailMarketingLink->update(               
                [
                    'name' => $validated["name"],
                    'failure_page_url' => $validated["failure_page_url"],
                    'success_page_url' => $validated["success_page_url"],
                    'full_link' => $full_link,
                    'email_marketing_platforms_id' => $validated["partner_email_service"],
                    'campaign' => $validated["campaign"],
                    'zapier_webhook_url' => $validated["zapier_webhook_url"],
                ]
            );

            $resData = response(json_encode([
                    'status' => "success",
                    "data" => [
                        'name' => $emailMarketingLink->name,
                        'failure_page_url' => $emailMarketingLink->failure_page_url,
                        'success_page_url' => $emailMarketingLink->success_page_url,
                        'link_identifier' => $emailMarketingLink->link_identifier,
                        'full_link' => $emailMarketingLink->full_link,
                        'email_marketing_platforms_id' => $emailMarketingLink->email_marketing_platforms_id,
                        'campaign' => $emailMarketingLink->campaign,
                        'zapier_webhook_url' => $emailMarketingLink->zapier_webhook_url,
                    ],
                ]
            ), 200)
            ->header('Content-Type', 'application/json');
    
            return $resData;   
        } catch (\Throwable $th) {
            logger($th->getMessage());
            $resData = response(json_encode([
                    'status' => "error",
                    "data" => null,
                    "message" => $th->getMessage()
                ]
            ), 400)
            ->header('Content-Type', 'application/json');

            return $resData;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\emailMarketingLink  $emailMarketingLink
     * @return \Illuminate\Http\Response
     */
    public function destroy(emailMarketingLink $emailMarketingLink)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleHit(Request $request, string $link_identifier)
    {
        try {
            $link_identifier = $link_identifier ?? false;
            $email = $request->query('email') ?? '';
            $fname = $request->query('fname') ?? '';
            $lname = $request->query('lname') ?? '';

            if(! $link_identifier){
                throw new Error("link identifier not provided");
            }

            if(! $email){
                throw new Error("Email is required but not provided");
            }

            $link = emailMarketingLink::where("link_identifier", "=", $link_identifier)->first() ?? false;

            if(! $link){
                throw new Error("Could not fetch link data");
            }

            // logger($link);
            // logger($email);
            // logger($fname);
            // logger($lname);
            
            try {
                //code...
                emailMarketingStat::create([
                    'email' => $email,
                    'firstname'  => $fname,
                    'lastname'  => $lname,
                    'email_marketing_links_id' => $link->email_marketing_links_id,
                ]);
            } catch (\Throwable $th) {
                //throw $th;
            }

            if($link->zapier_webhook_url ?? false){
                Http::post($link->zapier_webhook_url, [
                    'email' => $email,
                    'firstname'  => $fname,
                    'lastname'  => $lname,
                    
                ]);
            }


            return redirect()->away($link->success_page_url);
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->away($link->failure_page_url);
        }
    }    

    
}
