<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Registraion')]
class Registraion extends Component
{
    #[Rule('required|min:3|max:255')]
    public $name;

    #[Rule('required|email|unique:users,email')]
    public $email;

    #[Rule('required|min:6|max:12')]
    public $password;

    public $showPassword = false;

    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function registraion()
    {
        $validated = $this->validate();
        User::create($validated);
        $this->reset();
        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.registraion');
    }
}
