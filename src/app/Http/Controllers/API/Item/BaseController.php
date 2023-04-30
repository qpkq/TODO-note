<?php

namespace App\Http\Controllers\API\Item;

use App\Http\Controllers\Controller;
use App\Services\Item\ItemService;

class BaseController extends Controller
{
    public ItemService $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ItemService $service)
    {
        $this->service = $service;
    }
}
