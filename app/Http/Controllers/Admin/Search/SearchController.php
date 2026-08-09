<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Search;

use App\Http\Controllers\Controller;
use App\Http\Services\AdminSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(protected AdminSearchService $searchService)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $q = (string) $request->query('q', '');

        return response()->json($this->searchService->search($q));
    }
}
