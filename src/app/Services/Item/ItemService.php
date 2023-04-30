<?php

namespace App\Services\Item;

use App\Http\Requests\Item\SortRequest;
use App\Http\Requests\Item\StoreRequest;
use App\Http\Requests\Item\UpdateRequest;
use App\Models\Item;
use App\Models\TodoList;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemService
{
    /**
     * Display a listing of the items current TO-DO list.
     *
     * @param int $id
     * @return Builder|Collection|Model|null
     */
    public function index(int $id): Model|Collection|Builder|null
    {
        return TodoList::with('items.tags')
            ->findOrFail($id);
    }

    /**
     * Store a newly created item current TO-DO list in storage.
     *
     * @param StoreRequest $request
     * @param int $id
     * @return Item
     */
    public function store(StoreRequest $request, int $id): Item
    {
        $data = $request->validated();

        // сохраняем изображение в Storage/app/public/images
        if (!empty($data['image']))
            $data['image'] = Storage::disk('public')->put('/images', $data['image']);

        $data['todo_list_id'] = $id;

        return Item::firstOrCreate($data);
    }

    /**
     * Update item current TO-DO list in storage.
     *
     * @param UpdateRequest $request
     * @param Item $item
     * @return Item
     */
    public function update(UpdateRequest $request, Item $item): Item
    {
        $data = $request->validated();

        // сохраняем изображение в Storage/app/public
        if (!empty($data['image']))
            $data['image'] = Storage::disk('public')->put('/images', $data['image']);

        $item->update($data);

        return $item;
    }

    /**
     * Remove the specified item from storage.
     *
     * @param Item $item
     * @return bool|null
     */
    public function destroy(Item $item): bool|null
    {
        return $item->delete();
    }

    /**
     * Remove the image of the specified item from storage.
     *
     * @param Item $item
     * @return Item
     */
    public function delete_image(Item $item): Item
    {
        $item->image = NULL;

        $item->update();

        return $item;
    }

    /**
     * Sort by Tag title.
     *
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function sort(Request $request, int $id): array
    {
        $data = Item::with('tags')
            ->where('todo_list_id', $id)
            ->whereHas('tags', function($query) use ($request) {
                $query->where('title', $request->title);
            })
            ->orderBy('id')
            ->get();

        return [
            'items' => $data,
        ];
    }

    /**
     * Search for Item title.
     *
     * @param SortRequest $request
     * @param int $id
     * @return array
     */
    public function search(SortRequest $request, int $id): array
    {
        $data = $request->validated();

        $items = Item::with('tags')
            ->where('todo_list_id', $id)
            ->where('title', 'LIKE', '%' . $data['title'] . '%')
            ->get();

        return [
            'items' => $items,
        ];
    }
}
