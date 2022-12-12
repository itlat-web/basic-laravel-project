<?php

namespace App\Services;


use App\Models\Post;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class PostService
{
    /**
     * @param array $data ['title' => 'string', 'slug' => 'string', 'text' => 'string', 'active' => bool, 'image' => UploadedImage]
     * @param User $user
     * @return Post
     */
    public function store(array $data, User $user): Post
    {
        return Post::create([
            'user_id' => $user->id,
            'title'   => $data['title'],
            'slug'    => $data['slug'],
            'text'    => $data['text'],
            'active'  => $data['active'],
            'image'   => $this->uploadImage($data['image'])
        ]);
    }

    /**
     * @param Post $post
     * @param array $data ['title' => 'string', 'slug' => 'string', 'text' => 'string', 'active' => bool, 'image' => ?UploadedImage]
     * @return void
     */
    public function update(Post $post, array $data): void
    {
        // update post
        $post->update([
            'title'   => $data['title'],
            'slug'    => $data['slug'],
            'text'    => $data['text'],
            'active'  => $data['active'],
            'image'   => isset($data['image']) ? $this->uploadImage($data['image'], $post) : $post->image,
        ]);
    }

    /**
     * @param Post $post
     * @return void
     */
    public function destroy(Post $post): void
    {
        // always remove file before post destroying
        if (is_file($post->getImageFullPath())) {
            unlink($post->getImageFullPath());
        }

        $post->delete();
    }

    /**
     * @param UploadedFile $file
     * @param Post|null $post
     * @return string
     */
    private function uploadImage(UploadedFile $file, ?Post $post = null): string
    {
        // at first delete old image
        if ($post !== null && is_file($post->getImageFullPath())) {
            unlink($post->getImageFullPath());
        }

        $extension = $file->extension();
        $filename = $extension === '' ? Str::uuid() : sprintf('%s.%s', Str::uuid(), $extension);

        // file uploading
        $file->storeAs('images', $filename, 'public');
        return $filename;
    }
}
