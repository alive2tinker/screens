<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\Http\Requests\StoreAttachment;
use App\Http\Resources\AttachmentResource;
use App\Screen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Thujohn\Twitter\Twitter;

class AttachmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAttachment $request, Screen $screen)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function show(Attachment $attachment)
    {
        return response()->json(new AttachmentResource($attachment), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attachment $attachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Attachment  $attachment
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Attachment $attachment)
    {
        Storage::delete('link');

        $attachment->delete();

        return response()->json(['message' => "deleted successfully"], 200);
    }
}
