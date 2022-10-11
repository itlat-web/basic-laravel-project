<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class BlogController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $posts = Post::orderBy('id', 'desc')->where('active', true)->with('user')->paginate(9);

        return view('blog.index', compact('posts'));
    }

    /**
     * @param Post $post
     * @return Factory|View|Application
     */
    public function show(Post $post): Factory|View|Application
    {
        if (!$post->active) {
            abort(404);
        }

        return view('blog.show', compact('post'));
    }
}
