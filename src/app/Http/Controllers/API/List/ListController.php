<?php

namespace App\Http\Controllers\API\List;

use App\Http\Requests\List\StoreRequest;
use App\Http\Requests\List\UpdateRequest;
use App\Models\TodoList;
use Illuminate\Http\JsonResponse;

class ListController extends BaseController
{
    /**
     * Display a listing of the TO-DO lists.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return new JsonResponse(
            $this->service->index()
        );
    }

    /**
     * Store a newly created TO-DO list in storage.
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        return new JsonResponse(
            $this->service->store(
                $request
            )
        );
    }

    /**
     * Update the specified TO-DO list in storage.
     *
     * @param UpdateRequest $request
     * @param TodoList $list
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, TodoList $list): JsonResponse
    {
        return new JsonResponse(
            $this->service->update(
                $request, $list
            )
        );
    }

    /**
     * Remove the specified TO-DO list from storage.
     *
     * @param TodoList $list
     * @return JsonResponse
     */
    public function destroy(TodoList $list): JsonResponse
    {
        return new JsonResponse(
            $this->service->destroy(
                $list
            ), 204
        );
    }
}
