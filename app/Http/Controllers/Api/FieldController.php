<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FieldResource;
use App\Models\Field;

class FieldController extends Controller
{
    public function index()
    {
        return FieldResource::collection(
            Field::where('is_active', true)->get()
        );
    }
}