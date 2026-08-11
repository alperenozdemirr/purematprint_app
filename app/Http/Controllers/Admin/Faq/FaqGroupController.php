<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Faq;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqGroupStoreRequest;
use App\Http\Requests\Admin\FaqGroupUpdateRequest;
use App\Models\FaqGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FaqGroupController extends Controller
{
    public function index(): View
    {
        $faqGroups = FaqGroup::query()
            ->withCount('faqs')
            ->ordered()
            ->paginate(15);

        return view('admin.faq-group-list', compact('faqGroups'));
    }

    public function storePage(): View
    {
        return view('admin.new-faq-group');
    }

    public function store(FaqGroupStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        FaqGroup::create([
            'title' => $validated['title'],
            'number' => $validated['number'] ?? null,
        ]);

        return redirect()->route('admin.faqGroupList')->with('success', 'SSS grubu başarıyla kaydedildi.');
    }

    public function show(int $id): View
    {
        $faqGroup = FaqGroup::query()
            ->with(['faqs' => fn ($query) => $query->ordered()])
            ->findOrFail($id);

        return view('admin.faq-group-edit', compact('faqGroup'));
    }

    public function update(FaqGroupUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $faqGroup = FaqGroup::query()->findOrFail($validated['id']);

        $faqGroup->update([
            'title' => $validated['title'],
            'number' => $validated['number'] ?? null,
        ]);

        return redirect()->route('admin.faqGroupEditPage', $faqGroup->id)
            ->with('success', 'SSS grubu başarıyla güncellendi.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $faqGroup = FaqGroup::query()->findOrFail($id);
        $faqGroup->delete();

        return redirect()->route('admin.faqGroupList')->with('success', 'SSS grubu ve bağlı sorular silindi.');
    }
}
