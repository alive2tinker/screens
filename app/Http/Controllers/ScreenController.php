<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScreen;
use App\Http\Resources\ScreenResource;
use App\Screen;
use Illuminate\Http\Request;

class ScreenController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ScreenResource::collection(Screen::orderby('created_at','desc')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreScreen $request)
    {
        $screen = Screen::create($request->only('title'));

        return response()->json(new ScreenResource($screen), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Screen  $screen
     * @return \Illuminate\Http\Response
     */
    public function show(Screen $screen)
    {
        return response()->json(new ScreenResource($screen), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Screen  $screen
     * @return \Illuminate\Http\Response
     */
    public function update(StoreScreen $request, Screen $screen)
    {
        $screen->update($request->input('title'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Screen  $screen
     * @return \Illuminate\Http\Response
     */
    public function destroy(Screen $screen)
    {
        $screen->attachments()->detach($screen->attachments->get('id'));

        $screen->delete();

        return response()->json(['message' => "deleted successfully"], 200);
    }
}
