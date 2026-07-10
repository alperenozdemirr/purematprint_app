<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Banner;

use App\Enums\ContentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BannerStoreRequest;
use App\Http\Requests\Admin\BannerUpdateRequest;
use App\Http\Services\FileService;
use App\Models\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function __construct(protected FileService $fileService)
    {
    }

    public function index(): View
    {
        $banners = Banner::query()
            ->with('image')
            ->orderBy('number')
            ->orderByDesc('id')
            ->paginate(15);

        return view('admin.banner-list', compact('banners'));
    }

    public function storePage(): View
    {
        return view('admin.new-banner');
    }

    public function store(BannerStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $banner = Banner::create([
            'sub_title' => $validated['sub_title'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'redirect_url' => $validated['redirect_url'] ?? null,
            'number' => $validated['number'] ?? 0,
        ]);

        $fileRecord = $this->fileService->imageUpload(
            $request->file('image'),
            ContentType::BANNER,
            $banner->id,
            1
        );

        $banner->update(['image_id' => $fileRecord->id]);

        return redirect()->route('admin.bannerList')->with('success', 'Banner başarıyla kaydedildi.');
    }

    public function show(int $id): View
    {
        $banner = Banner::query()
            ->with('image')
            ->findOrFail($id);

        return view('admin.banner-edit', compact('banner'));
    }

    public function update(BannerUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $banner = Banner::query()->findOrFail($validated['id']);

        $banner->update([
            'sub_title' => $validated['sub_title'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'redirect_url' => $validated['redirect_url'] ?? null,
            'number' => $validated['number'] ?? 0,
        ]);

        if ($request->hasFile('image')) {
            if ($banner->image_id) {
                $this->fileService->imageDelete($banner->image_id, ContentType::BANNER);
            }

            $fileRecord = $this->fileService->imageUpload(
                $request->file('image'),
                ContentType::BANNER,
                $banner->id,
                1
            );

            $banner->update(['image_id' => $fileRecord->id]);
        }

        return redirect()->route('admin.bannerEditPage', $banner->id)
            ->with('success', 'Banner başarıyla güncellendi.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $banner = Banner::query()->findOrFail($id);

        if ($banner->image_id) {
            $this->fileService->imageDelete($banner->image_id, ContentType::BANNER);
        }

        $banner->delete();

        return redirect()->route('admin.bannerList')->with('success', 'Banner başarıyla silindi.');
    }
}
