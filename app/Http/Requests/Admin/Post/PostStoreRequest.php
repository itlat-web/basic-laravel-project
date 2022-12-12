<?php

namespace App\Http\Requests\Admin\Post;

class PostStoreRequest extends BasicPostRequest
{
    /**
     * @return string[]
     */
    public function getSlugRule(): array
    {
        return ['required', 'string', 'max:255', 'unique:posts', 'regex:/[a-z0-9_-]+/'];
    }

    /**
     * @return string[]
     */
    protected function getImageRule(): array
    {
        return ['required', 'image', 'mimes:jpg,png,jpeg|max:2048'];
    }


}
