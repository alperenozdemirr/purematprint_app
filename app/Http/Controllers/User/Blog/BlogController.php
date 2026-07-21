<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $blogs = Blog::query()
            ->with('image')
            ->latest()
            ->paginate(9);

        return view('user.blog-list', compact('blogs'));
    }

    public function show(string $slug): View
    {
        $blog = Blog::query()
            ->with('image')
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedBlogs = Blog::query()
            ->with('image')
            ->where('id', '!=', $blog->id)
            ->latest()
            ->limit(3)
            ->get();

        return view('user.blog-detail', compact('blog', 'relatedBlogs'));
    }
}
