<?php

namespace App\Repositories\Post;

use App\Interfaces\Post\PostRepositoryInterface;
use App\Models\Media;
use App\Models\Post\Post;

class PostRepository implements PostRepositoryInterface
{

    public function getAllPosts($request)
    {
        $user = auth()->user();
        $posts = Post::withCount('shares')
            ->where('status', 0)->latest();

        $limit = 12;
        if ($request->query('limit') != null)
            $limit = $request->query('limit');
        $page = 1;
        if ($request->query('page') != null)
            $page = $request->query('page');
        $data = $posts->paginate($limit)->toArray();
        $data['items'] =   $data['data'];
        unset($data['data']);
        return $data;
    }
    public function getPostById($post_id)
    {
        $post = Post::where('id', $post_id)
            ->withCount('shares')->first();
        return $post;
    }
    public function deletePost($post_id)
    {
        $user = auth()->user();
        $post = Post::findOrFail($post_id);
        if ($user->id != $post->user_id)
            return false;
        else {
            $post->media()->delete();
            $post->delete();
            return true;
        }
    }
    public function createPost($request)
    {
        $user = auth()->user();
        $profile = $user->profile;
        $input = $request->only('content');
        $input['user_id'] = $user->id;
        $post = $profile->posts()->create($input);
        if ($request->has('media')) {
            $mediaInput = $request->only('media');
            foreach ($mediaInput['media'] as $postmedia) {
                $post->media()->create($postmedia);
            }
        }
        return Post::withCount('shares')->find($post->id);
    }
    public function updatePost($post_id, $request)
    {
        $user = auth()->user();
        $post = Post::findOrFail($post_id);
        if ($user->id != $post->user_id)
            return false;
        else {
            if ($request->has('content'))
                $post->content = $request->content;

            if ($request->has('delete_media'))
                if ($request->delete_media != null) {
                    foreach ($request->delete_media as $num) {
                        $media = Media::findOrFail($num);
                        $media->delete();
                    }
                }
            if ($request->has('media')) {
                $mediaInput = $request->only('media');
                foreach ($mediaInput['media'] as $postmedia) {
                    $post->media()->create($postmedia);
                }
            }
            $post->save();
            return Post::withCount('shares')->find($post->id);
        }
    }
    public function getPostsByProfileId($request, $user)
    {
        $auth_user = auth()->user()->profile;
        $posts = Post::where('model_type', 'Profile')
            ->where('model_id', $user->profile->id)
            ->withCount('shares')
            ->where('status', 0)->latest();
        $limit = 12;
        if ($request->query('limit') != null)
            $limit = $request->query('limit');
        $page = 1;
        if ($request->query('page') != null)
            $page = $request->query('page');
        $data = $posts->paginate($limit)->toArray();
        $data['items'] =   $data['data'];
        unset($data['data']);
        return $data;
    }
}
