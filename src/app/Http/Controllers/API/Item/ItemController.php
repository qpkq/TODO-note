<?php

namespace App\Http\Controllers\API\Item;

use App\Http\Requests\Item\SortRequest;
use App\Http\Requests\Item\StoreRequest;
use App\Http\Requests\Item\UpdateRequest;
use App\Models\Item;
use App\Models\TodoList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemController extends BaseController
{
    /**
     * Display a listing of the items current TO-DO list.
     *
     * @param TodoList $list
     * @return JsonResponse
     */
    public function index(TodoList $list): JsonResponse
    {
        return new JsonResponse(
            $this->service->index(
                $list->id
            )
        );
    }

    /**
     * Store a newly created item in storage.
     *
     * @param StoreRequest $request
     * @param TodoList $list
     * @return JsonResponse
     */
    public function store(TodoList $list, StoreRequest $request): JsonResponse
    {
        return new JsonResponse(
            $this->service->store(
                $request, $list->id
            )
        );
    }

    /**
     * Update item current TO-DO list in storage.
     *
     * @param UpdateRequest $request
     * @param TodoList $list
     * @param Item $item
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, TodoList $list, Item $item): JsonResponse
    {
        return new JsonResponse(
            $this->service->update(
                $request, $item
            )
        );
    }

    /**
     * Remove the specified item from storage.
     *
     * @param TodoList $list
     * @param Item $item
     * @return JsonResponse
     */
    public function destroy(TodoList $list, Item $item): JsonResponse
    {
        return new JsonResponse(
            $this->service->destroy(
                $item
            ), 204
        );
    }

    /**
     * Remove the image of the specified item from storage.
     *
     * @param TodoList $list
     * @param Item $item
     * @return JsonResponse
     */
    public function delete_image(TodoList $list, Item $item): JsonResponse
    {
        return new JsonResponse(
            $this->service->delete_image(
                $item
            ), 204
        );
    }

    /**
     * Sort by Tag title.
     *
     * @param TodoList $list
     * @param Request $request
     * @return JsonResponse
     */
    public function sort(TodoList $list, Request $request): JsonResponse
    {
        return new JsonResponse(
            $this->service->sort(
                $request, $list->id
            )
        );
    }

    /**
     * Search item for title.
     *
     * @param TodoList $list
     * @param SortRequest $request
     * @return JsonResponse
     */
    public function search(TodoList $list, SortRequest $request): JsonResponse
    {
        return new JsonResponse(
            $this->service->search(
                $request, $list->id
            )
        );
    }
}
