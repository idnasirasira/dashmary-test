<?php

use App\Models\User;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Cache;

new class extends Component {
    public int $chartQueryCacheExpired = 600;

    public int $totalUser = 0;

    public array $userChartByDestination = [];

    public int $totalPost = 0;

    public array $postChartByUser = [];

    public function mount(): void
    {
        $this->userChartByDestination = $this->getDataUserByDestination();
        $this->totalUser = $this->totalUser();

        $this->postChartByUser = $this->getPostChartByUser();
        $this->totalPost = $this->totalPost();
        // dd('a');
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
            $data = User::query()->selectRaw('count(*) as data, countries.name as label')->join('countries', 'countries.id', '=', 'users.country_id')->groupBy('country_id')->get();

            if ($data->isEmpty()) {
                $data = collect([['label' => 'No data', 'data' => 0]]);
            }

            return [
                'type' => $chartType,
                'data' => $this->formatChartData($data),
            ];
        });
    }

    public function refreshDataUserByDestination(): void
    {
        Cache::forget('user_chart_by_destination');
        $this->userChartByDestination = $this->getDataUserByDestination();
    }

    public function totalUser(): int
    {
        return User::count();
    }

    public function getPostChartByUser(string $chartType = 'pie'): array
    {
        return Cache::remember('post_chart_by_user', $this->chartQueryCacheExpired, function () use ($chartType) {
            $data = User::query()->selectRaw('count(*) as data, users.name as label')->join('posts', 'posts.user_id', '=', 'users.id')->groupBy('user_id')->get();

            if ($data->isEmpty()) {
                $data = collect([['label' => 'No data', 'data' => 0]]);
            }

            return [
                'type' => $chartType,
                'data' => $this->formatChartData($data),
            ];
        });
    }

    public function refreshPostChartByUser(): void
    {
        Cache::forget('post_chart_by_user');
        $this->postChartByUser = $this->getPostChartByUser();
    }

    public function totalPost(): int
    {
        return User::count();
    }
}; ?>

<div class="flex flex-col gap-5 mt-10">

    <div class="flex gap-5">
        <x-stat class="w-3/12" title="{{ __('Users') }}" :value="$totalUser" icon="o-users"
            tooltip="{{ __('Total Registered User') }}" />

        <x-stat class="w-3/12" title="{{ __('Posts') }}" :value="$totalUser" icon="o-document" color="text-pink-500"
            tooltip="{{ __('Total Posts') }}" />
    </div>

    <div class="flex flex-wrap gap-5">
        <x-card class="w-3/12" title="{{ __('Users') }}" shadow>
            <small>{{ __('Total user - Group by destination') }}</small>
            <x-slot:menu>
                <x-button icon="o-arrow-path" wire:click="refreshDataUserByDestination" class="btn-circle btn-sm"
                    tooltip="{{ __('Refresh') }}" />
            </x-slot:menu>
            <x-chart wire:model="userChartByDestination" />
        </x-card>

        <x-card class="w-3/12" title="{{ __('Posts') }}" shadow>
            <small>{{ __('Total post - Group by user') }}</small>
            <x-slot:menu>
                <x-button icon="o-arrow-path" wire:click="refreshPostChartByUser" class="btn-circle btn-sm"
                    tooltip="{{ __('Refresh') }}" />
            </x-slot:menu>
            <x-chart wire:model="postChartByUser" />
        </x-card>
    </div>
</div>
