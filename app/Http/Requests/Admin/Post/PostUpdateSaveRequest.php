<?php

namespace App\Http\Requests\Admin\Post;

use App\Models\Post;

class PostUpdateSaveRequest extends BasicPostSaveRequest
{
    /**
     * @return string[]
     */
    public function getSlugRule(): array
    {
        $postId = isset($this->post) && $this->post instanceOf Post ? (int)$this->post->id : 0;
        return ['required', 'string', 'max:255', 'unique:posts,slug,' . $postId, 'regex:/[a-z0-9_-]+/'];
    }
}
