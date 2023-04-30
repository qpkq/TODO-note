<?php

namespace App\Http\Controllers\API\Tag;

use App\Http\Controllers\Controller;
use App\Services\Tag\TagService;

class BaseController extends Controller
{
    public TagService $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TagService $service)
    {
        $this->service = $service;
    }
}
