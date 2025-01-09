<?php

use App\Models\Post;
use Mary\Traits\Toast;
use Livewire\Volt\Component;
use App\Services\PostService;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;

new class extends Component {
    use Toast, WithFileUploads;

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

    public function save(): void
    {
        $data = $this->validate();

        $this->postService->createPost($data);

        $this->success(__('Post created.'), redirectTo: '/posts');
    }
}; ?>

<div>
    <x-header title="{{ __('Create new Post') }}" separator />

    <x-form wire:submit="save">
        <div class="lg:grid grid-cols-5">
            <div class="col-span-3 grid gap-3">
                <x-input wire:model="title" label="{{ __('Title') }}" />

                <x-editor wire:model="content" label="{{ __('Content') }}" hint="{{ __('Write your content') }}"
                    disk="local" folder="posts/content_img" />
            </div>

            <div class="col-span-2">

            </div>
        </div>

        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" link="/posts" />
            <x-button label="{{ __('Save') }}" icon="o-paper-airplane" spinner="save" type="submit"
                class="btn-primary" />
        </x-slot:actions>
    </x-form>
</div>
