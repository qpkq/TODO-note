<?php

namespace App\Services\Tag;

use App\Http\Requests\Tag\StoreRequest;
use App\Models\Tag;

class TagService
{
    /**
     * Display a listing of the tags of item.
     *
     * @param int $id
     * @return mixed
     */
    public function index(int $id): mixed
    {
        return Tag::where('item_id', '=', $id)->get();
    }

    /**
     * Store a newly created tag in storage.
     *
     * @param StoreRequest $request
     * @param int $id
     * @return Tag
     */
    public function store(StoreRequest $request, int $id): Tag
    {
        $data = $request->validated();

        $data['item_id'] = $id;

        return Tag::firstOrCreate($data);
    }

    /**
     * Remove the specified tag from storage.
     *
     * @param Tag $tag
     * @return bool|null
     */
    public function destroy(Tag $tag): bool|null
    {
        return $tag->delete();
    }
}
