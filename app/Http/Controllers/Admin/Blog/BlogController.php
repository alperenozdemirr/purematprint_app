<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Blog;

use App\Enums\ContentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogStoreRequest;
use App\Http\Requests\Admin\BlogUpdateRequest;
use App\Http\Services\FileService;
use App\Models\Blog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function __construct(protected FileService $fileService)
    {
    }

    public function index(): View
    {
        $blogs = Blog::query()
            ->with('image')
            ->latest()
            ->paginate(15);

        return view('admin.blog-list', compact('blogs'));
    }

    public function storePage(): View
    {
        return view('admin.new-blog');
    }

    public function store(BlogStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $blog = Blog::create([
            'subtitle' => $validated['subtitle'],
            'title' => $validated['title'],
            'slug' => $this->generateSlug($validated['title']),
            'content' => $validated['content'],
        ]);

        $fileRecord = $this->fileService->imageUpload(
            $request->file('image'),
            ContentType::BLOG,
            $blog->id,
            1
        );

        $blog->update(['image_id' => $fileRecord->id]);

        return redirect()->route('admin.blogList')->with('success', 'Blog yazısı başarıyla kaydedildi.');
    }

    public function show(int $id): View
    {
        $blog = Blog::query()
            ->with('image')
            ->findOrFail($id);

        return view('admin.blog-edit', compact('blog'));
    }

    public function update(BlogUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $blog = Blog::query()->findOrFail($validated['id']);

        $blog->update([
            'subtitle' => $validated['subtitle'],
            'title' => $validated['title'],
            'slug' => $this->generateSlug($validated['title'], $blog->id),
            'content' => $validated['content'],
        ]);

        if ($request->hasFile('image')) {
            if ($blog->image_id) {
                $this->fileService->imageDelete($blog->image_id, ContentType::BLOG);
            }

            $fileRecord = $this->fileService->imageUpload(
                $request->file('image'),
                ContentType::BLOG,
                $blog->id,
                1
            );

            $blog->update(['image_id' => $fileRecord->id]);
        }

        return redirect()->route('admin.blogEditPage', $blog->id)
            ->with('success', 'Blog yazısı başarıyla güncellendi.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $blog = Blog::query()->findOrFail($id);

        if ($blog->image_id) {
            $this->fileService->imageDelete($blog->image_id, ContentType::BLOG);
        }

        $blog->delete();

        return redirect()->route('admin.blogList')->with('success', 'Blog yazısı başarıyla silindi.');
    }

    private function generateSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Blog::query()
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
