<?php

use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class BlogTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    /**
     * @return void
     */
    public function testSuccessfulEmptyIndexVisit(): void
    {
        $this->get(route('blog.index'))->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function testSuccessfulIndexWithPostVisit(): void
    {
        $this->createPost(true);

        $this->get(route('blog.index'))->assertStatus(Response::HTTP_OK)->assertSee('Post Title');
    }

    /**
     * @return void
     */
    public function testSuccessfulIndexWithoutDisabledPostVisit(): void
    {
        $this->createPost(false);

        $this->get(route('blog.index'))->assertStatus(Response::HTTP_OK)->assertDontSee('Post Title');
    }

    /**
     * @return void
     */
    public function testSuccessfulActivePostVisit(): void
    {
        $post = $this->createPost(true);

        $this->get(route('blog.show', $post))
            ->assertStatus(Response::HTTP_OK)->assertSee('Post Title');
    }

    /**
     * @return void
     */
    public function testUnsuccessfulDisabledPostVisit(): void
    {
        $post = $this->createPost(false);

        $this->get(route('blog.show', $post))->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @param bool $active
     * @return Post
     */
    public function createPost(bool $active): Post
    {
        return Post::create([
            'title' => 'Post Title',
            'slug' => 'post-slug',
            'text' => 'text',
            'active' => $active,
            'image' => UploadedFile::fake()->image('image.jpg')->mimeType('image/jpeg'),
        ]);
    }
}
