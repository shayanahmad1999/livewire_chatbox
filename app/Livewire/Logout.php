<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{
    public function mount()
    {
        // Check if the user is not authenticated, then redirect to the login page
        if (!Auth::check()) {
            return redirect('/login');
        }
    }
    
    public function logout()
    {
        if (Auth::check()) {
            Auth::logout();
        }

        // Redirect to the login page
        return $this->redirect(route('login'), navigate: true);
    }
    public function render()
    {
        return view('livewire.logout');
    }
}
