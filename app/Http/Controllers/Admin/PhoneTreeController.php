<?php

namespace App\Http\Controllers\Admin;

use App\PhoneTree;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PhoneTreeController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('phonetree.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PhoneTree  $phoneTree
     * @return \Illuminate\Http\Response
     */
    public function show(PhoneTree $phoneTree)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PhoneTree  $phoneTree
     * @return \Illuminate\Http\Response
     */
    public function edit(PhoneTree $phoneTree)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PhoneTree  $phoneTree
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PhoneTree $phoneTree)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PhoneTree  $phoneTree
     * @return \Illuminate\Http\Response
     */
    public function destroy(PhoneTree $phoneTree)
    {
        //
    }
}
