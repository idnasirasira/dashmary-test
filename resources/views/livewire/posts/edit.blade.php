<?php

use App\Models\Post;
use Mary\Traits\Toast;
use Livewire\Volt\Component;
use App\Services\PostService;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;

new class extends Component {
    use Toast, WithFileUploads;

    public Post $post;
    protected PostService $postService;

    // Constructor
    public function __construct()
    {
        parent::__construct();
        $this->postService = app(PostService::class);
    }

    #[Rule('required')]
    public string $title = '';

    #[Rule('sometimes')]
    public ?string $content = null;

    public function with(): array
    {
        return [];
    }

    public function mount(): void
    {
        $this->fill($this->post);
    }

    public function save(): void
    {
        $data = $this->validate();

        $this->postService->updatePost($this->post->id, $data);

        $this->success(__('Post updated.'), redirectTo: '/posts');
    }
}; ?>

<div>
    <x-header title="Update {{ $post?->title }}" separator />

    <x-form wire:submit="save">
        <div class="lg:grid grid-cols-5">
            <div class="col-span-3 grid gap-3">
                <x-input wire:model="title" label="{{ __('Title') }}" />

                <x-editor wire:model="content" label="{{ __('Content') }}" hint="Write your content" />
            </div>

            <div class="col-span-2">
                {{-- <x-header title="Basic" subtitle="Blog Post" size="text-2xl" /> --}}
            </div>
        </div>

        <x-slot:actions>
            <x-button label="Cancel" link="/posts" />
            <x-button label="Save" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
        </x-slot:actions>
    </x-form>
</div>
