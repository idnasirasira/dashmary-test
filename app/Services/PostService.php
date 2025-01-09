<?php

namespace App\Services;

use App\Repositories\PostRepository;

class PostService
{
    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getAllPosts()
    {
        return $this->postRepository->getAllPosts();
    }

    public function getPostById($id)
    {
        return $this->postRepository->getPostById($id);
    }

    public function createPost(array $data)
    {
        return $this->postRepository->createPost($data);
    }

    public function updatePost($id, array $data)
    {
        return $this->postRepository->updatePost($id, $data);
    }

    public function deletePost($id)
    {
        return $this->postRepository->deletePost($id);
    }

    public function getPaginate($perPage = 10, $filters, $sortBy = [])
    {
        return $this->postRepository->getPaginatedPost($perPage, $filters, $sortBy);
    }
}
