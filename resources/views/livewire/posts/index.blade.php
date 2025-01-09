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

    // Options list
    public Collection $usersSearchable;

    // Selected User ID
    public ?int $user_id = 0;

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
        $this->searchFilterUser();
        $this->user_id = Auth::user()->id;
        $this->totalActiveFilter = $this->totalActive();
    }

    // Clear filters
    public function clear(): void
    {
        $this->reset();
        $this->resetPage();
        $this->searchFilterUser();
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
            'user_id' => $this->user_id,
        ];

        return $this->postService->getPaginate(10, $filters, $this->sortBy);
    }

    public function with(): array
    {
        return [
            'posts' => $this->posts(),
            'headers' => $this->headers(),
            'users' => User::select('name', 'id')->get(),
            'totalActiveFilter' => $this->totalActiveFilter,
        ];
    }

    // Reset Pagination when any component property changes
    public function updated($property): void
    {
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

        if (isset($this->user_id)) {
            $counter++;
        }

        return $counter;
    }

    function searchFilterUser(string $value = '')
    {
        $selectedOption = User::where('id', $this->user_id)->get();

        $this->usersSearchable = User::query()
            ->where('name', 'like', "%$value%")
            ->take(5)
            ->orderBy('name')
            ->get()
            ->merge($selectedOption);
    }

    function edit(Post $post)
    {
        if ($post->user_id != Auth::user()->id) {
            $this->error('You are not allowed to edit this post.');
            return;
        }

        return redirect()->route('posts.edit', $post);
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
        <x-table class="dark:text-white" :headers="$headers" :rows="$posts" :sort-by="$sortBy" with-pagination>
            @scope('actions', $post)
                <div class="flex justify-end space-x-1">
                    @if (Auth::user()->id == $post['user_id'])
                        <x-button icon="o-pencil" wire:click="edit({{ $post['id'] }})" spinner
                            class="btn-ghost btn-sm text-green-500" />

                        <x-button icon="o-trash" wire:click="delete({{ $post['id'] }})" wire:confirm="Are you sure?"
                            spinner class="btn-ghost btn-sm text-red-500" />
                    @endif
                </div>
            @endscope

            <x-slot:empty>
                <x-icon name="o-cube" label="{{ __('No posts available.') }}" />
            </x-slot:empty>
        </x-table>
    </x-card>

    <!-- FILTER DRAWER -->
    <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">
        <div class="grid gap-5">
            <x-input placeholder="Search..." wire:model.live.debounce="search" icon="o-magnifying-glass"
                @keydown.enter="$wire.drawer = false" />

            <x-choices label="Filter User" wire:model.live="user_id" search-function="searchFilterUser" debounce="300ms"
                min-chars="2" :options="$usersSearchable" placeholder="Search ..." single searchable>
                {{-- Item slot --}}
                @scope('item', $user)
                    <x-list-item :item="$user" sub-value="bio">
                        <x-slot:avatar>
                            <x-icon name="o-user" class="bg-orange-100 p-2 w-8 h8 rounded-full" />
                        </x-slot:avatar>
                        <x-slot:actions>
                            <x-badge :value="$user->email" />
                        </x-slot:actions>
                    </x-list-item>
                @endscope

                {{-- Selection slot --}}
                @scope('selection', $user)
                    {{ $user->name }} ({{ $user->email }})
                @endscope
            </x-choices>

        </div>
        <x-slot:actions>
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner />
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>
</div>
