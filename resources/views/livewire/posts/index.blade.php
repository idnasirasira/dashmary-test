<?php

use App\Models\Post;
use App\Models\User;
use Mary\Traits\Toast;
use App\Models\Country;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Services\PostService;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

new class extends Component {
    use Toast, WithPagination;

    protected PostService $postService;

    public string $search = '';

    public bool $drawer = false;

    public array $sortBy = ['column' => 'title', 'direction' => 'asc'];

    public int $totalActiveFilter = 0;

    // Constructor
    public function __construct()
    {
        parent::__construct();
        $this->postService = app(PostService::class);
    }

    // Mount
    public function mount(): void
    {
        // Mounting
    }

    // Clear filters
    public function clear(): void
    {
        $this->reset();
        $this->resetPage();
        $this->success('Filters cleared.');
    }

    // Delete action
    public function delete($id): void
    {
        $this->postService->deletePost($id);

        $this->success("Post #$id deleted.");
    }

    // Table headers
    public function headers(): array
    {
        return [['key' => 'id', 'label' => '#', 'class' => 'w-1'], ['key' => 'title', 'label' => 'Title', 'class' => ''], ['key' => 'user_name', 'label' => 'User', 'class' => '']];
    }

    public function posts(): LengthAwarePaginator
    {
        $filters = [
            'search' => $this->search,
        ];

        return $this->postService->getPaginate(5, $filters, $this->sortBy);
    }

    public function with(): array
    {
        return [
            'posts' => $this->posts(),
            'headers' => $this->headers(),
            'totalActiveFilter' => $this->totalActiveFilter,
        ];
    }

    // Reset Pagination when any component property changes
    public function updated($property): void
    {
        // $this->postService = app(PostService::class);

        if (!is_array($property) && $property != '') {
            $this->resetPage();
            $this->totalActiveFilter = $this->totalActive();
        }
    }

    public function totalActive(): int
    {
        $counter = 0;

        if ($this->search) {
            $counter++;
        }

        return $counter;
    }
}; ?>

<div>
    <!-- HEADER -->
    <x-header title="{{ __('List Post') }}" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Filters" @click="$wire.drawer = true" responsive badge="{{ $totalActiveFilter }}"
                icon="o-funnel" />
            <x-button label="Create" link="/posts/create" responsive icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table class="dark:text-white" :headers="$headers" :rows="$posts" :sort-by="$sortBy" with-pagination
            link="posts/{id}/edit">

            @scope('actions', $post)
                <x-button icon="o-trash" wire:click="delete({{ $post['id'] }})" wire:confirm="Are you sure?" spinner
                    class="btn-ghost btn-sm text-red-500" />
            @endscope
        </x-table>
    </x-card>

    <!-- FILTER DRAWER -->
    <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">
        <div class="grid gap-5">
            <x-input placeholder="Search..." wire:model.live.debounce="search" icon="o-magnifying-glass"
                @keydown.enter="$wire.drawer = false" />
        </div>
        <x-slot:actions>
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner />
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>
</div>
