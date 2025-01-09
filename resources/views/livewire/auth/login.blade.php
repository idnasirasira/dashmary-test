<?php

use Illuminate\View\View;
use Livewire\Volt\Component;
use Livewire\Attributes\Rule;

new class extends Component {
    public function rendering(View $view): void
    {
        $view->title('Login');
        $view->layout('components.layouts.empty');
    }

    #[Rule('required|email')]
    public string $email = '';

    #[Rule('required|min:3')]
    public string $password = '';

    public function mount()
    {
        if (auth()->user()) {
            return redirect('/');
        }
    }

    public function login()
    {
        $credentials = $this->validate();

        if (auth()->attempt($credentials)) {
            request()->session()->regenerate();

            return redirect('/');
        }

        $this->addError('email', 'These credentials do not match our records.');
    }
}; ?>

<div>
    <div class="mx-auto mt-20 md:w-96">

        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <div class="flex items-center gap-2 justify-center">
                <x-icon name="o-square-3-stack-3d" class="w-6 -mb-1 text-purple-500" />
                <span
                    class="font-bold text-3xl me-3 bg-gradient-to-r from-purple-500 to-pink-300 bg-clip-text text-transparent ">
                    {{ env('APP_NAME') }}
                </span>
            </div>
            <h2 class="mt-5 text-center text-2xl/9 font-bold tracking-tight">Sign in to your account</h2>
        </div>

        <x-form wire:submit="login" class="mt-5">
            <x-input label="E-mail" wire:model="email" icon="o-envelope" inline />
            <x-input label="Password" wire:model="password" type="password" icon="o-key" inline />

            <x-slot:actions>
                <x-button label="Create an account" class="btn-ghost" link="/register" />
                <x-button label="Login" type="submit" icon="o-paper-airplane" class="btn-primary" spinner="login" />
            </x-slot>
        </x-form>
    </div>
</div>
