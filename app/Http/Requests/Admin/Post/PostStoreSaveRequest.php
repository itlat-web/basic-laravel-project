<?php

namespace App\Http\Requests\Admin\Post;

class PostStoreSaveRequest extends BasicPostSaveRequest
{
    /**
     * @return string[]
     */
    public function getSlugRule(): array
    {
        return ['required', 'string', 'max:255', 'unique:posts', 'regex:/[a-z0-9_-]+/'];
    }
}
