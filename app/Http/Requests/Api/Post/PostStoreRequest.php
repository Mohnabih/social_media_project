<?php

namespace App\Http\Requests\Api\Post;

use App\Http\Requests\Api\BaseRequest;

class PostStoreRequest extends BaseRequest
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
            'media'=>'required_without:content',
            'media.*.src_url'=>'required_without:content|url',
            'media.*.src_thum'=>'url',
            'media.*.src_icon'=>'url',
            'media.*.media_type'=> 'required_without:content|string',
            'media.*.mime_type'=>'required_without:content|string',
            'media.*.size'=>'required_without:content'
        ];
    }
}
