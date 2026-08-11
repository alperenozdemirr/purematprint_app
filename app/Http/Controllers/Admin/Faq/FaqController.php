<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Faq;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqStoreRequest;
use App\Http\Requests\Admin\FaqUpdateRequest;
use App\Models\Faq;
use App\Models\FaqGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function storePage(): View
    {
        $groupOptions = FaqGroup::query()->ordered()->get();
        $selectedGroupId = (int) request()->query('group_id', 0);

        return view('admin.new-faq', compact('groupOptions', 'selectedGroupId'));
    }

    public function store(FaqStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Faq::create([
            'group_id' => $validated['group_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'number' => $validated['number'] ?? null,
            'fixed_status' => (bool) ($validated['fixed_status'] ?? false),
        ]);

        return redirect()->route('admin.faqGroupEditPage', $validated['group_id'])
            ->with('success', 'Soru başarıyla kaydedildi.');
    }

    public function show(int $id): View
    {
        $faq = Faq::query()->with('group')->findOrFail($id);
        $groupOptions = FaqGroup::query()->ordered()->get();

        return view('admin.faq-edit', compact('faq', 'groupOptions'));
    }

    public function update(FaqUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $faq = Faq::query()->findOrFail($validated['id']);

        $faq->update([
            'group_id' => $validated['group_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'number' => $validated['number'] ?? null,
            'fixed_status' => (bool) ($validated['fixed_status'] ?? false),
        ]);

        return redirect()->route('admin.faqEditPage', $faq->id)
            ->with('success', 'Soru başarıyla güncellendi.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $faq = Faq::query()->findOrFail($id);
        $groupId = $faq->group_id;
        $faq->delete();

        return redirect()->route('admin.faqGroupEditPage', $groupId)
            ->with('success', 'Soru başarıyla silindi.');
    }
}
