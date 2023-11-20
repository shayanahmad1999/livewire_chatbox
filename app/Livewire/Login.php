<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Login')]
class Login extends Component
{

    #[Rule('required|email')]
    public $email;

    #[Rule('required')]
    public $password;

    public $remember;
    
    public $showPassword = false;

    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            // Authentication successful
            return $this->redirect('/dashboard', navigate: true); 
        }

        // Authentication failed
        session()->flash('error', 'Invalid email or password.');
    }

    public function render()
    {
        return view('livewire.login');
    }
}
