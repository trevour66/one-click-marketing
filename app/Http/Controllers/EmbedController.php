<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use App\Models\emailMarketingLink;
use App\Models\emailMarketingStat;
use App\Models\emailMarketingPlatform;
use App\Models\User;
use Error;
use Inertia\Inertia;
use Illuminate\Http\Request;

class EmbedController extends Controller
{
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $user_unique_public_id)
    {
        try {
            //code...
            $providers = emailMarketingPlatform::get();
            $user_unique_public_id = $user_unique_public_id ?? false;

            // logger($user_unique_public_id);
            
            $user = User::where("user_unique_public_id", "=", $user_unique_public_id)->first() ?? false;
    
            if(!$user){
                throw new Error('System error. Please try again and if problem persist, contact support');
            }

            return Inertia::render('Link/Embed', [
                "email_providers" => $providers,
                "embedded" => true,
                "user_unique_public_id" => $user_unique_public_id
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
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
            $validated = $request->validate([
                'name' => 'required|string',
                'user_unique_public_id' => 'required|string',
                'campaign' => 'required|string',
                'zapier_webhook_url' => 'required|url:http,https',
                'partner_email_service' => 'required|numeric',
                'success_page_url' => 'required|url:http,https',
                'failure_page_url' => 'required|url:http,https',
                // 'g_recaptcha_response' => 'required|recaptcha' // Recaptcha Disabled. To enable, please complete the integration @https://serdarcevher.medium.com/how-to-make-laravel-vue-inertia-js-use-google-recaptcha-without-installing-a-package-e6131bae1fd8
            ]);

            $user = User::where("user_unique_public_id", "=", $validated["user_unique_public_id"])->first() ?? false;

            if(!$user){
                throw new Error('System error. Please try again and if problem persist, contact support');
            }

            $doesLinkWithSameNameExist = emailMarketingLink::where("name", "=", $validated["name"])->first() ?? false;

            if($doesLinkWithSameNameExist){
                throw new Error('Link with the same name already exist. Please change the name');
            }

            $provider = emailMarketingPlatform::where("email_marketing_platforms_id", "=", $validated["partner_email_service"])->first() ?? false;

            // logger($provider);

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

            $domain = "http://127.0.0.1:8000/";

            $full_link = $domain."cl/".$uniqueId."/?email=".$merge_tag_email."&fname=".$merge_tag_firstname."&lname=".$merge_tag_lastname;


            $newLink = emailMarketingLink::create(
                [
                    'name' => $validated["name"],
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
}
