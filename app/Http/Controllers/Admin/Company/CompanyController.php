<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompanyReorderRequest;
use App\Http\Requests\Admin\CompanyStoreRequest;
use App\Http\Requests\Admin\CompanyUpdateRequest;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(): View
    {
        $companies = Company::ordered()->get();

        return view('admin.company-list', compact('companies'));
    }

    public function storePage(): View
    {
        return view('admin.new-company');
    }

    public function store(CompanyStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Company::create([
            'name' => $validated['name'],
            'number' => ((int) Company::query()->max('number')) + 1,
        ]);

        return redirect()
            ->route('admin.companyList')
            ->with('success', 'Referans firma başarıyla eklendi.');
    }

    public function show(int $id): View
    {
        $company = Company::query()->findOrFail($id);

        return view('admin.company-edit', compact('company'));
    }

    public function update(CompanyUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $company = Company::query()->findOrFail($validated['id']);
        $company->update([
            'name' => $validated['name'],
        ]);

        return redirect()
            ->route('admin.companyEditPage', $company->id)
            ->with('success', 'Referans firma başarıyla güncellendi.');
    }

    public function destroy(int $id): RedirectResponse
    {
        Company::query()->findOrFail($id)->delete();

        return redirect()
            ->route('admin.companyList')
            ->with('success', 'Referans firma başarıyla silindi.');
    }

    public function reorder(CompanyReorderRequest $request): JsonResponse
    {
        foreach ($request->validated('order') as $index => $companyId) {
            Company::query()
                ->where('id', $companyId)
                ->update(['number' => $index + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sıralama güncellendi.',
        ]);
    }
}
