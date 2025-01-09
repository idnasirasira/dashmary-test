<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class PostRepository
{
    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function getAllPosts()
    {
        return $this->post->all();
    }

    public function getPostById($id)
    {
        return $this->post->find($id);
    }

    public function createPost(array $data)
    {
        if (!isset($data['user_id'])) {
            $data['user_id'] = Auth::user()->id;
        }

        return $this->post->create($data);
    }

    public function updatePost($id, array $data)
    {
        $post = $this->post->find($id);
        if ($post) {
            $post->update($data);
            return $post;
        }
        return null;
    }

    public function deletePost($id)
    {
        $post = $this->post->find($id);
        if ($post) {
            $post->delete();
            return true;
        }
        return false;
    }

    public function getPaginatedPost($perPage = 10, $filters = [], $sortBy = null)
    {
        return $this->post
            ->withAggregate('user', 'name')
            ->with(['user'])
            ->when(isset($filters['search']), function (Builder $q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%');
            })
            ->when($filters['user_id'], function (Builder $q) use ($filters) {
                $q->where('user_id', $filters['user_id']);
            })
            ->when($sortBy, function (Builder $q) use ($sortBy) {
                $q->orderBy($sortBy['column'], $sortBy['direction']);
            })
            ->paginate($perPage);
    }
}
