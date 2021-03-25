<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\Events\AttachmentDeleted;
use App\Events\NewAttachment;
use App\Http\Requests\StoreAttachment;
use App\Http\Resources\AttachmentResource;
use App\Screen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Thujohn\Twitter\Facades\Twitter;

class AttachmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Screen $screen)
    {
        return AttachmentResource::collection($screen->attachments);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAttachment $request, Screen $screen)
    {
        Log::info($request);
        $attachment = null;
        switch ($request->input('type'))
        {
            case 'quote':
                $attachment = $screen->attachments()->create([
                    'title' => $request->input('title'),
                    'type' => $request->input('type'),
                    'text' => $request->input('text'),
                    'quote_author' => $request->input('quoteAuthor'),
                    'image_link' => $request->hasFile('image')
                        ? "storage/" . Storage::disk('public')->putFileAs(
                            "screen_" .$screen->id . "_attachments",
                            $request->file('image'),
                            strtolower(str_replace(" ", "_", $request->input('title'))) . '.' . $request->file('image')->getClientOriginalExtension()) : ''
                ]);
                break;
            case 'image':
                $attachment = $screen->attachments()->create([
                    'title' => $request->input('title'),
                    'type' => $request->input('type'),
                    'image_link' => $request->hasFile('image')
                        ? "storage/" . Storage::disk('public')->putFileAs(
                            "screen_" .$screen->id . "_attachments",
                            $request->file('image'),
                            strtolower(str_replace(" ", "_", $request->input('title'))) . '.' .$request->file('image')->getClientOriginalExtension()) : ''
                ]);
                break;
            case 'employee':
                $employeeScreen = Attachment::where('type','employee')->first();
                if($employeeScreen)
                    return response()->json("You already have employee attachment. kindly update that one", 401);

                $attachment = $screen->attachments()->create([
                    'employee_name' => $request->input('employeeName'),
                    'title' => $request->input('title'),
                    'type' => $request->input('type'),
                    'image_link' => $request->hasFile('image')
                    ? "storage/" . Storage::disk('public')->putFileAs(
                        "screen_" .$screen->id . "_attachments",
                        $request->file('image'),
                        strtolower(str_replace(" ", "_", $request->input('employeeName'))) . '.' .$request->file('image')->getClientOriginalExtension()) : ''
                ]);
                break;
            case 'youtube':
                $attachment = $screen->attachments()->create([
                    'title' => $request->input('title'),
                    'type' => $request->input('type'),
                    'youtube_link' => substr($request->input('link'), 17, strlen($request->input('link')))
                ]);
                break;
            case 'tweet':
                $link = explode('/', $request->input('link'));
                $tweetId = end($link);
                $tweet = Twitter::getTweet($tweetId, ['tweet_mode' => "extended"]);
                $tweetUserInfo = ['profileImage' => $tweet->user->profile_image_url, 'user' => $tweet->user->name] ;
                $tweetImages = [];
                if(isset($tweet->extended_entities->media)) {
                    foreach ($tweet->extended_entities->media as $key => $media) {
                        $image = file_get_contents($media->media_url);
                        $filename = "screen_" . $screen->id . "_tweet_image_" . $key . ".jpg";
                        Storage::disk('public')->put(strtolower(str_replace(" ", "_", $request->input('title'))) . "_attachments/" . $filename,$image);
                        $path = url('/') . '/storage/' . strtolower(str_replace(" ", "_", $request->input('title'))) . "_attachments" . '/' . $filename;
                        array_push($tweetImages, $path);
                    }
                }

                $attachment = $screen->attachments()->create([
                    'title' => $request->input('title'),
                    'type' => $request->input('type'),
                    'text' => $tweet->full_text,
                    'tweet_info' => ['user' => $tweetUserInfo, 'images' => $tweetImages]
                ]);
                break;
        }
        if(env('APP_ENV') == 'production')
            event(new NewAttachment(new AttachmentResource($attachment), $screen));

        return response()->json(new AttachmentResource($attachment), 201);
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
    public function destroy(Attachment $attachment, Screen $screen)
    {
        if ($attachment->type === 'tweet')
        {
            foreach($attachment->tweet_info['images'] as $image)
                Storage::delete($image);
        }
        Storage::delete('link');

        $attachment->delete();

        event(new AttachmentDeleted($attachment, $screen));

        return response()->json($attachment, 200);
    }
}
