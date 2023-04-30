<?php

namespace App\Http\Controllers\API\Tag;

use App\Http\Requests\Tag\StoreRequest;
use App\Models\Item;
use App\Models\Tag;
use App\Models\TodoList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends BaseController
{
    /**
     * Display a listing of the tags of item.
     *
     * @param TodoList $list
     * @param Item $item
     * @return JsonResponse
     */
    public function index(TodoList $list, Item $item): JsonResponse
    {
        return new JsonResponse(
            $this->service->index(
                $item->id
            )
        );
    }

    /**
     * Store a newly created tag in storage.
     *
     * @param TodoList $list
     * @param StoreRequest $request
     * @param Item $item
     * @return JsonResponse
     */
    public function store(TodoList $list, StoreRequest $request, Item $item): JsonResponse
    {
        return new JsonResponse(
            $this->service->store(
                $request, $item->id
            )
        );
    }

    /**
     * Remove the specified tag from storage.
     *
     * @param TodoList $list
     * @param Item $item
     * @param Tag $tag
     * @return JsonResponse
     */
    public function destroy(TodoList $list, Item $item, Tag $tag): JsonResponse
    {
        return new JsonResponse(
            $this->service->destroy(
                $tag
            ), 204
        );
    }
}
