<?php

use App\Models\User;
use Livewire\Volt\Component;

new class extends Component {
    public array $userChartByDestination = [];

    public function mount(): void
    {
        $this->userChartByDestination = $this->getDataUserByDestination();
    }

    public function getDataUserByDestination(): array
    {
        $data = User::selectRaw('countries.name as country_name, COUNT(users.id) as total')
            ->join('countries', 'users.country_id', '=', 'countries.id')
            ->groupBy('countries.name')
            ->get()
            ->map(function ($user) {
                return [
                    'label' => $user->country_name,
                    'data' => $user->total,
                ];
            });
        // Default value if null response
        if ($data->isEmpty()) {
            $data = collect([['label' => 'No data', 'data' => 0]]);
        }

        return [
            'type' => 'pie',
            'data' => [
                'labels' => $data->pluck('label')->toArray(),
                'datasets' => [
                    [
                        'label' => '# of Users',
                        'data' => $data->pluck('data')->toArray(),
                    ],
                ],
            ],
        ];
    }
}; ?>

<div>
    <div class="flex gap-5">
        <x-card class="w-3/12" title="User" subtitle="{{ __('List user group by destination') }}" shadow>
            <x-chart wire:model="userChartByDestination" />
        </x-card>
    </div>
</div>
