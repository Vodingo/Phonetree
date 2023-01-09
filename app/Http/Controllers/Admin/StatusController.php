<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index()
    {
        $statuses = Status::where('active', true)->get(['id as status_id', 'name as status_description']);

        return response()->json($statuses);
    }
}
