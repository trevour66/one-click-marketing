<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Invite;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RegisteredUserController extends Controller
{   

    private function generateUniqueId() {
        // Generate a random string for the 'Ec' part
        $randomString = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'), 0, 2);

        // Generate a unique ID using uniqid
        $uniqueId = uniqid();

        // Base64 encode the unique ID
        $encodedId = base64_encode($uniqueId);

        // Replace any characters not allowed in base64 encoded strings
        $encodedId = str_replace(array('+', '/', '='), array('-', '_', ''), $encodedId);

        // Form the final ID string
        $finalId = $randomString . $encodedId ;

        return $finalId;
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $elevatedUsers = config('elevated_user.emails');
        $regOnlyOnInvite = config('app.regOnlyOnInvite');
        
        $userIsElevated = false;

        for ($i=0; $i < count($elevatedUsers) ; $i++) { 
            if($elevatedUsers[$i] === $request->input('email')){
                $userIsElevated = true;
                break;
            }
        }        

        if($userIsElevated){
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:'.User::class,
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_unique_public_id' => $this->generateUniqueId()
            ]);
            
            $role_superAdmin = Role::findOrCreate('super-admin');

            $permissions[] = Permission::findOrCreate('invite user');
            $permissions[] = Permission::findOrCreate('view users account stats');
            $permissions[] = Permission::findOrCreate('create personal links');
            $permissions[] = Permission::findOrCreate('manage personal links');
            $permissions[] = Permission::findOrCreate('delete personal links');
            
            $role_superAdmin->syncPermissions($permissions);

            $user->assignRole($role_superAdmin);

            event(new Registered($user));

            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);

        }else if(!($regOnlyOnInvite ?? false)) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:'.User::class,
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_unique_public_id' => $this->generateUniqueId()
            ]);

            $entry_level_user = Role::findOrCreate('entry-level-user');          

            $permissions_entry_level_user[] = Permission::findOrCreate('create personal links');
            $permissions_entry_level_user[] = Permission::findOrCreate('manage personal links');
            $permissions_entry_level_user[] = Permission::findOrCreate('delete personal links');

            $entry_level_user->syncPermissions($permissions_entry_level_user);

            $user->assignRole($entry_level_user);

            event(new Registered($user));

            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);
        } else{
            $request->validate([
                'name' => 'required|string|max:255',
                'invite_link' => 'required|string|exists:invites,invite_link_ref',
                'email' => 'required|string|email|max:255|unique:'.User::class,
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $invitedUser = Invite::where('invite_link_ref', htmlspecialchars($request->input('invite_link')))->limit(1)->first();

            if(!$invitedUser){                
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'general' => 'invalid invite link!'
                ]);
            }

            if($invitedUser->invite_status !== 'pending'){                
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'general' => 'This invite has already being processed!'
                ]);
            }
            
            $invitedUser_email = $invitedUser->invite_email;

            $invitedUser_email = trim($invitedUser_email) ?? '';

            if($request->input('email') !== $invitedUser_email){
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => 'This email was not invited with the invite link used'
                ]);
            }

            return $this->registerInvitedUser($request);            
        }
        
    }

    private function registerInvitedUser (Request $request) {    

        try {
            $invitedUser_invite_link_ref = htmlspecialchars($request->input('invite_link'));

            //code...
            DB::beginTransaction();

             $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_unique_public_id' => $this->generateUniqueId()
            ]);

            $entry_level_user = Role::findOrCreate('entry-level-user');          

            $permissions_entry_level_user[] = Permission::findOrCreate('create personal links');
            $permissions_entry_level_user[] = Permission::findOrCreate('manage personal links');
            $permissions_entry_level_user[] = Permission::findOrCreate('delete personal links');

            $entry_level_user->syncPermissions($permissions_entry_level_user);

            $user->assignRole($entry_level_user);


            DB::table('invites')
              ->where('invite_link_ref', $invitedUser_invite_link_ref)
              ->update(
                [
                    'invite_status' => 'accepted',
                ]
            );                                  

            DB::commit(); 

            event(new Registered($user));

            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);

        } catch (\Exception $th) {
            DB::rollBack();
            throw \Illuminate\Validation\ValidationException::withMessages([
                    'general' => $th->getMessage()
                ]);
        }
    }

    private function getInvitedUserByInviteLink ($invite_link) {
        $invitedUser = Invite::where('invite_link_ref', $invite_link)->limit(1)->get();

        return $invitedUser;
    }

    /**
     * Display the registration view.
     */
    public function create(Request $request): Response
    {
        $invite_link = $request->query('invite_link');
        $invite_link_is_valid = ($invite_link) ? true : false;

        $invitedUser = $this->getInvitedUserByInviteLink ($invite_link) ;
        $invitedUser_email = '';
        $invitedUser_invite_already_processed = '';

        if(count($invitedUser)){

            foreach ($invitedUser as $user) {
                $invitedUser_email = $user->invite_email;   
                
                if($user->invite_status !== 'pending'){                
                    $invitedUser_invite_already_processed = 'This invite has already being processed!';
                    $invite_link_is_valid = false;
                }                 
            }            
                    
            $invite_link_is_valid = true;
        }else{
            $invite_link_is_valid = false;
        } 


        
        return Inertia::render('Auth/Register', [
            'regOnlyOnInvite' => config('app.regOnlyOnInvite'),
            'invite_link_is_valid' => $invite_link_is_valid,
            'invite_link' => $invite_link,
            'invitedUser_email' => $invitedUser_email,
            'invitedUser_invite_already_processed' => $invitedUser_invite_already_processed
        ]);
    }

}
