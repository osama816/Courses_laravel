<?php

namespace App\Livewire;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Login extends Component
{    #[Rule('required|string|email')]
    public $email = '';

    #[Rule('required|string|min:8')]
    public $password = '';

    public $remember = false;
    public $errorMessage = '';

    public function login()
    {
       $validate= $this->validate();

        if (Auth::attempt(['email' => $validate['email'], 'password' =>$validate["password"]], $this->remember)) {
            session()->regenerate();
            return redirect()->intended(route('home'));
        }
        //$this->reset(['email','password']);

        $this->errorMessage = 'Email or password Not Found';
    }

    public function render()
    {
        return view('livewire.login');
    }
}
