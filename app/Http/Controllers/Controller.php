<?php

namespace App\Http\Controllers;

use App\Models\Category;

abstract class Controller
{
    public function index()
    {
        return Category::all();
    }
}
