<?php

namespace App\Services\Admin;


use App\Models\Post;
use App\Models\User;

class PostService
{
    /**
     * @param array $data['title' => 'string', 'slug' => 'string', 'text' => 'string', 'active' => 'bool']
     * @param User $user
     * @return Post
     */
    public function store(array $data, User $user): Post
    {
        // add user id for post creation
        $data['user_id'] = $user->id;

        // create post
        return Post::create($data);
    }

    /**
     * @param Post $post
     * @param array $data['title' => 'string', 'slug' => 'string', 'text' => 'string', 'active' => 'bool']
     * @return void
     */
    public function update(Post $post, array $data): void
    {
        // if $data has user_id parameter - delete it - it is not allowed to update post author (user)
        if (isset($data['user_id'])) {
            unset($data['user_id']);
        }

        // update post
        $post->update($data);
    }

    /**
     * @param Post $post
     * @return void
     */
    public function destroy(Post $post): void
    {
        $post->delete();
    }
}
