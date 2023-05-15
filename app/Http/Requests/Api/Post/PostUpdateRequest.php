<?php

namespace App\Http\Requests\Api\Post;

use App\Http\Requests\Api\BaseRequest;

class PostUpdateRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content',
            'delete_media' => 'array|exists:media,id',
            'media.*.src_url' => 'url',
            'media.*.src_thum' => 'url',
            'media.*.src_icon' => 'url',
            'media.*.media_type' => 'string',
            'media.*.mime_type' => 'string',
        ];
    }
}
