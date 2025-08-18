<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LotNumberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $sum = 1;
        return view('admin.lots.index', compact('sum'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $sum = 1;
        return view('admin.lots.create', compact('sum'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $sum = 1;
        return view('admin.lots.edit', compact('sum'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
