<?php

use App\Models\User;
use Livewire\Volt\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

new class extends Component {
    public function rendering(View $view): void
    {
        $view->title('Register');
        $view->layout('components.layouts.empty');
    }

    #[Rule('required')]
    public string $name = '';

    #[Rule('required|email|unique:users')]
    public string $email = '';

    #[Rule('required|min:3')]
    public string $password = '';

    #[Rule('required')]
    public string $password_confirmation = '';

    public function mount()
    {
        if (auth()->user()) {
            return redirect('/');
        }
    }

    public function register()
    {
        $data = $this->validate();

        $data['avatar'] = 'static/img/empty-user.jpg';
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        auth()->login($user);

        request()->session()->regenerate();

        return redirect('/');
    }
}; ?>

<div>
    <div class="mx-auto mt-20 md:w-96">
        <h1 class="text-3xl text-slate-700">Register</h1>

        <x-form wire:submit="register" class="mt-5">
            <x-input label="Name" wire:model="name" icon="o-user" inline />
            <x-input label="E-mail" wire:model="email" icon="o-envelope" inline />
            <x-input label="Password" wire:model="password" type="password" icon="o-key" inline />
            <x-input label="Confirm Password" wire:model="password_confirmation" type="password" icon="o-key"
                inline />

            <x-slot:actions>
                <x-button label="Already registered?" class="btn-ghost" link="/login" />
                <x-button label="Register" type="submit" icon="o-paper-airplane" class="btn-primary"
                    spinner="register" />
            </x-slot>
        </x-form>
    </div>
</div>
