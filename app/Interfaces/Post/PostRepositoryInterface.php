<?php

namespace App\Interfaces\Post;

interface PostRepositoryInterface
{
    public function getAllPosts($request);
    public function getPostById($post_id);
    public function deletePost($post_id);
    public function createPost($request);
    public function updatePost($post_id,$request);
    public function getPostsByProfileId($request,$user);
}
