<?php

use App\Models\User;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Cache;

new class extends Component {
    public int $chartQueryCacheExpired = 3600;

    public array $userChartByDestination = [];

    public function mount(): void
    {
        $this->userChartByDestination = $this->getDataUserByDestination();
    }

    private function formatChartData($data): array
    {
        return [
            'labels' => $data->pluck('label')->toArray(),
            'datasets' => [
                [
                    'label' => '# of Users',
                    'data' => $data->pluck('data')->toArray(),
                ],
            ],
        ];
    }

    public function getDataUserByDestination(string $chartType = 'pie'): array
    {
        return Cache::remember('user_chart_by_destination', $this->chartQueryCacheExpired, function () use ($chartType) {
            $data = User::selectRaw('countries.name as label, COUNT(users.id) as data')->join('countries', 'users.country_id', '=', 'countries.id')->groupBy('countries.name')->get();

            if ($data->isEmpty()) {
                $data = collect([['label' => 'No data', 'data' => 0]]);
            }

            return [
                'type' => $chartType,
                'data' => $this->formatChartData($data),
            ];
        });
    }
}; ?>

<div>
    <div class="flex gap-5">
        <x-card class="w-3/12" title="User" subtitle="{{ __('List user group by destination') }}" shadow>
            <x-chart wire:model="userChartByDestination" />
        </x-card>
    </div>
</div>
