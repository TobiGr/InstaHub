<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Photo;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Storage;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|max:255|unique:users',
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'bio' => 'max:500',
            'gender' => 'required',
			'birthday' => 'date',
			'city' => 'string',
			'country' => 'string',
			'centimeters' => 'numeric',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        //$url = $data['avatar']->file('avatar')->store('avatars');
        if (array_key_exists('avatar', $data)) {
            $url = Storage::putFile('avatars', $data['avatar']);
        } else {
            $url = "avatar.png";
        }

        $user = User::create([
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'bio' => $data['bio'],
            'gender' => $data['gender'],
            'birthday' => $data['birthday_birthDay'],
            'city' => $data['city'],
            'country' => $data['country'],
            'centimeters' => $data['centimeters'],
            'avatar' => $url
        ]);
        
        return $user;
    }
}
