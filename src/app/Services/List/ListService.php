<?php

namespace App\Services\List;

use App\Http\Requests\List\StoreRequest;
use App\Http\Requests\List\UpdateRequest;
use App\Models\TodoList;
use Illuminate\Support\Facades\Auth;

class ListService
{
    /**
     * Display a listing of the TO-DO lists.
     *
     * @return mixed
     */
    public function index(): mixed
    {
        return TodoList::where('user_id', '=', Auth::id())->get();
    }

    /**
     * Store a newly created TO-DO list in storage.
     *
     * @param StoreRequest $request
     * @return TodoList
     */
    public function store(StoreRequest $request): TodoList
    {
        $data = $request->validated();

        $data['user_id'] = Auth::id();

        return TodoList::firstOrCreate($data);
    }

    /**
     * Update the specified list in storage.
     *
     * @param UpdateRequest $request
     * @param TodoList $list
     * @return TodoList
     */
    public function update(UpdateRequest $request, TodoList $list): TodoList
    {
        $list->update(
            $request->validated()
        );

        return $list;
    }

    /**
     * Remove the specified list from storage.
     *
     * @param TodoList $list
     * @return bool|null
     */
    public function destroy(TodoList $list): bool|null
    {
        return $list->delete();
    }
}
