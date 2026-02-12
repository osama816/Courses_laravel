<?php

namespace App\Livewire;

use App\Models\User;
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
        \Log::info('Login attempt for: ' . $this->email);
        $validate = $this->validate();

        if (Auth::attempt(['email' => $validate['email'], 'password' => $validate['password']], $this->remember)) {
            \Log::info('Login successful for: ' . $this->email);
            session()->regenerate();

            if (!Auth::user()->hasAnyRole(Auth::user()->roles)) {
                 Auth::user()->assignRole('student');
            }
            
            return $this->redirect(route('home'), navigate: true);
        }

        \Log::warning('Login failed for: ' . $this->email);
        $this->errorMessage = 'Email or password Not Found';
    }

    public function render()
    {
        return view('livewire.login');
    }
}
