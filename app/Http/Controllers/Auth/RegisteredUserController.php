<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

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
        // $elevatedUsers = config('elevated_user.emails');
        
        // $userIsElevated = false;

        // for ($i=0; $i < count($elevatedUsers) ; $i++) { 
        //     if($elevatedUsers[$i] === $request->input('email')){
        //         $userIsElevated = true;
        //         break;
        //     }
        // }

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

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
