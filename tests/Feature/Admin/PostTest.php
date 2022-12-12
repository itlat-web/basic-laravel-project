<?php

namespace Tests\Feature\Admin;

use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PostTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function testUnsuccessfulSimpleGuestVisit(): void
    {
        // posts index page - must be 302 redirect
        $this->get(route('admin.posts.index'))->assertStatus(Response::HTTP_FOUND);

        // posts creation page - must be 302 redirect
        $this->get(route('admin.posts.create'))->assertStatus(Response::HTTP_FOUND);
    }

    public function testSuccessfulAuthorizedUserVisit(): void
    {
        $this->actingAsAuthorizedUser();

        // posts index page - must be 200 status
        $this->get(route('admin.posts.index'))->assertStatus(Response::HTTP_OK);

        // posts creation page - must be 200 status
        $this->get(route('admin.posts.create'))->assertStatus(Response::HTTP_OK);
    }

    /**
     * @dataProvider getPostData
     * @param array $data
     * @return void
     */
    public function testSuccessfulPostCreation(array $data): void
    {
        $this->actingAsAuthorizedUser();

        // send post creation request
        $postCreationResponse = $this->post(route('admin.posts.store'), $data['valid-creation']);

        // session must be without errors
        $postCreationResponse->assertSessionDoesntHaveErrors();

        // user should be redirected after post creation
        $postCreationResponse->assertStatus(Response::HTTP_FOUND);

        // validate created (latest) post values
        $post = Post::all()->last();
        $this->assertEquals($data['valid-creation']['title'], $post->title);
        $this->assertEquals($data['valid-creation']['slug'], $post->slug);
        $this->assertEquals($data['valid-creation']['text'], $post->text);
        $this->assertEquals($data['valid-creation']['active'], $post->active);
    }

    /**
     * @dataProvider getPostData
     * @param array $data
     * @return void
     */
    public function testSuccessfulPostUpdate(array $data): void
    {
        $this->actingAsAuthorizedUser();

        // at first create post for future update
        $this->post(route('admin.posts.store'), $data['valid-creation']);

        // get created post for future usage
        $createdPost = Post::all()->last();

        // visit edit page and check if authenticated user can access it
        $this->get(route('admin.posts.edit', $createdPost))->assertStatus(Response::HTTP_OK);

        // send post update request
        $postUpdateResponse = $this->patch(route('admin.posts.update', $createdPost), $data['valid-update']);

        // session must be without errors
        $postUpdateResponse->assertSessionDoesntHaveErrors();

        // user should be redirected after post update
        $postUpdateResponse->assertStatus(Response::HTTP_FOUND);

        // get updated post with the same ID as created post
        $updatedPost = Post::find($createdPost->id);

        // validate updated post values
        $this->assertEquals($data['valid-update']['title'], $updatedPost->title);
        $this->assertEquals($data['valid-update']['slug'], $updatedPost->slug);
        $this->assertEquals($data['valid-update']['text'], $updatedPost->text);
        $this->assertEquals($data['valid-update']['active'], $updatedPost->active);
    }

    /**
     * @dataProvider getPostData
     * @param array $data
     * @return void
     */
    public function testSuccessfulPostDelete(array $data): void
    {
        $this->actingAsAuthorizedUser();

        $postsQuantityBeforeDelete = Post::all()->count();

        // at first create post for future update
        $this->post(route('admin.posts.store'), $data['valid-creation']);

        // get created post for future usage
        $post = Post::all()->last();

        // send post delete request
        $postDeleteResponse = $this->delete(route('admin.posts.destroy', $post));

        // session must be without errors
        $postDeleteResponse->assertSessionDoesntHaveErrors();

        // user should be redirected after post removing
        $postDeleteResponse->assertStatus(Response::HTTP_FOUND);

        // validate if there are no new posts
        $this->assertEquals($postsQuantityBeforeDelete, Post::all()->count());
    }

    /**
     * @dataProvider getPostData
     * @param array $data
     * @return void
     */
    public function testUnsuccessfulPostCreation(array $data): void
    {
        $this->actingAsAuthorizedUser();

        $postsQuantityBeforeCreation = Post::all()->count();

        // send invalid post creation request
        $postCreationResponse = $this->post(route('admin.posts.store'), $data['invalid-creation']);

        // session has errors
        $postCreationResponse->assertSessionHasErrors(array_keys($data['invalid-creation']));

        // user should be redirected after unsuccessful creation
        $postCreationResponse->assertStatus(Response::HTTP_FOUND);

        // validate if there are no new posts
        $this->assertEquals($postsQuantityBeforeCreation, Post::all()->count());
    }

    /**
     * @dataProvider getPostData
     * @param array $data
     * @return void
     */
    public function testUnsuccessfulPostUpdate(array $data): void
    {
        $this->actingAsAuthorizedUser();

        // create post
        $this->post(route('admin.posts.store'), $data['valid-creation']);

        // get created post for future usage
        $createdPost = Post::all()->last();

        // send invalid post creation request
        $postUpdateResponse = $this->patch(route('admin.posts.update', $createdPost), $data['invalid-update']);

        // session has errors
        $postUpdateResponse->assertSessionHasErrors(array_keys($data['invalid-update']));

        // user should be redirected after unsuccessful update
        $postUpdateResponse->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * @return void
     */
    public function testUnsuccessfulPostDelete(): void
    {
        $this->actingAsAuthorizedUser();

        // send post delete request
        $postDeleteResponse = $this->delete(route('admin.posts.destroy', ['post' => PHP_INT_MAX]));

        // there is no requested post (404 error)
        $postDeleteResponse->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @dataProvider getPostData
     * @param array $data
     * @return void
     */
    public function testUnsuccessfulPostCreationWithTheSameSlug(array $data): void
    {
        $this->actingAsAuthorizedUser();

        // send first valid post creation request
        $postCreationResponse = $this->post(route('admin.posts.store'), $data['valid-creation']);

        // session must be without errors
        $postCreationResponse->assertSessionDoesntHaveErrors();

        // send second post creation request with the same data (and the same slug)
        $postInvalidCreationResponse = $this->post(route('admin.posts.store'), $data['valid-creation']);

        // validate if there is the same slug session error
        $postInvalidCreationResponse->assertSessionHasErrors(['slug']);
    }

    /**
     * @dataProvider getPostData
     * @param array $data
     * @return void
     */
    public function testSuccessfulPostUpdateWithTheSameSlug(array $data): void
    {
        $this->actingAsAuthorizedUser();

        // send first valid post creation request
        $postCreationResponse = $this->post(route('admin.posts.store'), $data['valid-creation']);

        // session must be without errors
        $postCreationResponse->assertSessionDoesntHaveErrors();

        // get created post for future usage
        $createdPost = Post::all()->last();

        // send post update request with the same data (and the same slug)
        $postUpdateResponse = $this->patch(route('admin.posts.update', $createdPost), $data['valid-creation']);

        // validate if there is no errors in session
        $postUpdateResponse->assertSessionDoesntHaveErrors();
    }

    /**
     * @return array
     */
    public function getPostData(): array
    {
        return [
            [
                [
                    'valid-creation' => [
                        'title'  => 'First Valid Title',
                        'slug'   => 'first-valid-slug',
                        'text'   => 'First Valid Text',
                        'image'  => UploadedFile::fake()->image('image.jpg')->mimeType('image/jpeg'),
                        'active' => true
                    ],
                    'valid-update' => [
                        'title'  => 'Updated First Valid Title',
                        'slug'   => 'updated-first-valid-slug',
                        'text'   => 'Updated First Valid Text',
                        'image'  => UploadedFile::fake()->image('updated-image.png')->mimeType('image/jpeg'),
                        'active' => false
                    ],
                    'invalid-creation' => [
                        'title'  => ['Invalid Title'],
                        'slug'   => null,
                        'text'   => '',
                        'active' => 'wrong active'
                    ],
                    'invalid-update' => [
                        'title'  => 'title is longer than 255 symbols ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------',
                        'slug'   => 'slug-is-longer-than-255-symbols-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------',
                        'text'   => '',
                        'active' => ['wrong active value']
                    ]
                ],
            ],
            [
                [
                    'valid-creation' => [
                        'title'  => 'Title Contains Russian Language - Привет, Мир!',
                        'slug'   => 'slug-contains-numbers-123',
                        'text'   => 'Some Strange Characters - < > ! % abc',
                        'image'  => UploadedFile::fake()->image('image2.jpg')->mimeType('image/png'),
                        'active' => false
                    ],
                    'valid-update' => [
                        'title'  => 'Updated Title Contains Russian Language - Привет, Мир!',
                        'slug'   => 'updated-slug-contains-numbers-123',
                        'text'   => 'Updated Some Strange Characters - < > ! % abc',
                        'image'  => UploadedFile::fake()->image('updated-image2.jpg')->mimeType('image/jpeg'),
                        'active' => true
                    ],
                    'invalid-creation' => [
                        'title'  => '',
                        'slug'   => '',
                        'text'   => '',
                        'active' => ''
                    ],
                    'invalid-update' => [
                        'title'  => '',
                        'slug'   => '',
                        'text'   => '',
                        'active' => []
                    ]
                ]
            ],
        ];
    }
}
