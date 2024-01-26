<?php

namespace App\Http\Controllers;

use App\Models\Eps;
use Illuminate\Http\Request;

class EpsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eps = Eps::all();
        return response()->json([
            'status' => true,
            'data' => $eps
        ],200);
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
     * @param  \App\Models\Eps  $eps
     * @return \Illuminate\Http\Response
     */
    public function show(Eps $eps)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eps  $eps
     * @return \Illuminate\Http\Response
     */
    public function edit(Eps $eps)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Eps  $eps
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Eps $eps)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eps  $eps
     * @return \Illuminate\Http\Response
     */
    public function destroy(Eps $eps)
    {
        //
    }
}
