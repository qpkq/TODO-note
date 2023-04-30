<?php

namespace App\Http\Controllers\API\List;

use App\Http\Controllers\Controller;
use App\Services\List\ListService;

class BaseController extends Controller
{
    public ListService $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ListService $service)
    {
        $this->service = $service;
    }
}
