<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SearchRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SearchController extends Controller
{
    /**
     * Perform search based on the query string.
     */
    public function __invoke(SearchRequest $request): AnonymousResourceCollection
    {
        $accounts = Account::search((string) $request->string('q'))
            ->paginate(10);

        return AccountResource::collection($accounts);
    }
}
