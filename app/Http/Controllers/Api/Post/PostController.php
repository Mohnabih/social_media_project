<?php

namespace App\Http\Controllers\Api\Post;

use App\ApiCode;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Api\Post\PostStoreRequest;
use App\Http\Requests\Api\Post\PostUpdateRequest;
use App\Interfaces\Post\PostRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends AppBaseController
{
    private PostRepositoryInterface $postRepository;

    /**
     * Create a new controller instance.
     *
     * @param \App\Interfaces\Post\postRepositorysInterface $postRepository
     * @return void
     *
     */
    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->middleware('auth:api')->except('index');
        $this->postRepository = $postRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $this->postRepository->getAllPosts($request);
        return  $this->sendResponse(
            $data,
            [__("here are all posts!")],
            ApiCode::SUCCESS,
            0
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($post_id)
    {
        $post = $this->postRepository->getPostById($post_id);
        if ($post)
            return  $this->sendResponse(
                $post,
                ['Here is the post.'],
                ApiCode::SUCCESS,
                0
            );
        else
            return  $this->sendResponse(
                null,
                ['Post not found.'],
                ApiCode::NOT_FOUND,
                1
            );
    }

    /**
     * Display a listing of the resource by user id.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile_posts_by_profileId(Request $request,$profile_id)
    {
        $data = $this->postRepository->getPostsByProfileId($request, $profile_id);
        return $this->sendResponse(
            $data,
            [__('here are all posts!')],
            ApiCode::SUCCESS,
            0
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostStoreRequest $request)
    {
        $post = $this->postRepository->createPost($request);
        return $this->sendResponse(
            $post,
            [__("post added successfully")],
            ApiCode::CREATED,
            0
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostUpdateRequest $request, $post_id)
    {
        $postupdate = $this->postRepository->updatePost($request, $post_id);
        if ($postupdate)
            return $this->sendResponse(
                $postupdate,
                [__('post deleted successfully')],
                ApiCode::SUCCESS,
                0
            );
        else
            return $this->sendResponse(
                null,
                [__('Bad request!!')],
                ApiCode::BAD_REQUEST,
                1
            );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($post_id)
    {
        $status = $this->postRepository->deletePost($post_id);
        if ($status)
            return $this->sendResponse(
                null,
                [__('post deleted successfully')],
                ApiCode::SUCCESS,
                0
            );
        else
            return $this->sendResponse(
                null,
                [__('Bad request!!')],
                ApiCode::BAD_REQUEST,
                1
            );
    }
}
