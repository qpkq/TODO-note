<?php

namespace App\Http\Controllers\Lists;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\TodoList;

class ListController extends Controller
{
    /**
     * Display TO-DO lists current user.
     */
    public function index()
    {
        return view('lists.index');
    }

    /**
     * Display the specified TO-DO list.
     */
    public function show(TodoList $list)
    {
        return view('lists.show', compact('list'));
    }
}
