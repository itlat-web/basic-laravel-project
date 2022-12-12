<?php

namespace App\Http\Requests\Admin\Post;

use App\Models\Post;

class PostUpdateRequest extends BasicPostRequest
{
    /**
     * @return string[]
     */
    public function getSlugRule(): array
    {
        $postId = isset($this->post) && $this->post instanceOf Post ? (int)$this->post->id : 0;
        return ['required', 'string', 'max:255', 'unique:posts,slug,' . $postId, 'regex:/[a-z0-9_-]+/'];
    }

    /**
     * @return string[]
     */
    protected function getImageRule(): array
    {
        return ['nullable', 'image', 'mimes:jpg,png,jpeg|max:2048'];
    }
}
