<?php

use App\Models\User;
use App\Models\Country;
use Livewire\Volt\Component;
use Livewire\Attributes\Rule;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public User $user;

    #[Rule('required')]
    public string $name = '';

    #[Rule('required', 'email')]
    public string $email = '';

    // optional
    #[Role('sometimes')]
    public ?int $country_id = null;

    public function with(): array
    {
        return [
            'countries' => Country::all(),
        ];
    }

    public function mount(): void
    {
        $this->fill($this->user);
    }

    public function save(): void
    {
        $data = $this->validate();

        $this->user->update($data);

        $this->success('User updated.', position: 'toast-bottom', redirectTo: '/users');
    }
}; ?>

<div>
    <x-header title="Update {{ $user->name }}" separator />

    <x-form wire:submit="save">
        <x-input wire:model="name" label="Name" />
        <x-input wire:model="email" label="E-mail" />
        <x-select wire:model="country_id" label="Country" :options="$countries" placeholder="---" />

        <x-slot:actions>
            <x-button label="Cancel" link="/users" />
            <x-button label="Save" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
        </x-slot:actions>
    </x-form>
</div>
