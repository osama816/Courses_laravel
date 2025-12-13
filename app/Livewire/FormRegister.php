<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Rule;
use Livewire\Component;


class FormRegister extends Component
{
    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|string|email|max:255|min:8|unique:users,email')]
    public $email = '';

    #[Rule('required|string|min:8|confirmed')]
    public $password = '';

    public $password_confirmation = '';


    // public function updated($propertyName)
    // {
    //     $this->validateOnly($propertyName);
    // }

    public function register()
    {
        $validate = $this->validate();
        $user = User::create([
            'name' =>  $validate['name'],
            'email' =>  $validate['email'],
            'password' => Hash::make($validate['password']),
            'role' => 'student'
        ]);
        event(new \Illuminate\Auth\Events\Registered($user));
        Auth::login($user);
        session()->regenerate();
        return redirect()->intended(route('home'));
    }

    public function render()
    {
        return view('livewire.form-register');
    }
}
