<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\HomepageDemoReview;

use App\Enums\ContentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HomepageDemoReviewStoreRequest;
use App\Http\Requests\Admin\HomepageDemoReviewUpdateRequest;
use App\Http\Services\FileService;
use App\Models\HomepageDemoReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomepageDemoReviewController extends Controller
{
    public function __construct(protected FileService $fileService)
    {
    }

    public function index(): View
    {
        $reviews = HomepageDemoReview::query()->ordered()->with('image')->get();

        return view('admin.homepage-demo-review-list', compact('reviews'));
    }

    public function storePage(): View
    {
        return view('admin.new-homepage-demo-review');
    }

    public function store(HomepageDemoReviewStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $review = HomepageDemoReview::create([
            'quote' => trim($validated['quote']),
            'author' => trim($validated['author']),
            'stars' => (int) $validated['stars'],
            'is_visible' => $request->boolean('is_visible', true),
            'sort_order' => ((int) HomepageDemoReview::query()->max('sort_order')) + 1,
        ]);

        if ($request->hasFile('image')) {
            $fileRecord = $this->fileService->imageUpload(
                $request->file('image'),
                ContentType::HOMEPAGE_DEMO_REVIEW,
                $review->id,
                1,
            );

            $review->update(['image_id' => $fileRecord->id]);
        }

        return redirect()
            ->route('admin.homepageDemoReviewList')
            ->with('success', 'Demo yorum başarıyla eklendi.');
    }

    public function show(int $id): View
    {
        $review = HomepageDemoReview::query()->with('image')->findOrFail($id);

        return view('admin.homepage-demo-review-edit', compact('review'));
    }

    public function update(HomepageDemoReviewUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $review = HomepageDemoReview::query()->findOrFail($validated['id']);
        $review->update([
            'quote' => trim($validated['quote']),
            'author' => trim($validated['author']),
            'stars' => (int) $validated['stars'],
            'is_visible' => $request->boolean('is_visible'),
        ]);

        if ($request->hasFile('image')) {
            if ($review->image_id) {
                $this->fileService->imageDelete($review->image_id, ContentType::HOMEPAGE_DEMO_REVIEW);
            }

            $fileRecord = $this->fileService->imageUpload(
                $request->file('image'),
                ContentType::HOMEPAGE_DEMO_REVIEW,
                $review->id,
                1,
            );

            $review->update(['image_id' => $fileRecord->id]);
        }

        return redirect()
            ->route('admin.homepageDemoReviewEditPage', $review->id)
            ->with('success', 'Demo yorum başarıyla güncellendi.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $review = HomepageDemoReview::query()->findOrFail($id);

        if ($review->image_id) {
            $this->fileService->imageDelete($review->image_id, ContentType::HOMEPAGE_DEMO_REVIEW);
        }

        $review->delete();

        return redirect()
            ->route('admin.homepageDemoReviewList')
            ->with('success', 'Demo yorum başarıyla silindi.');
    }
}
