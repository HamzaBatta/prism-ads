<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdController extends Controller
{
    public function store(Request $request)
    {
        try {
            $payload = JWTAuth::parseToken()->getPayload();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userId = $payload->get('sub');


        $request->validate([
            'text' => 'required|string',
            'remaining_users' => 'required|integer|min:1',
            'media'     => 'nullable|array',
            'media.*'   => 'file|mimes:jpeg,png,gif,mp4,mov|max:20480',
        ]);

        $ad = Ad::create([
            'text' => $request->text,
            'remaining_users' => $request->remaining_users,
            'user_id' => $userId,
        ]);

        $mediaFiles = $request->file('media', []);
        foreach ((array) $mediaFiles as $file) {
            try {
                $path = $file->store('posts', 'public');
                $type = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';
                $media = $ad->media()->create([
                    'path' => $path,
                    'type' => $type,
                ]);
            } catch (\Exception $e) {
                $ad->delete();
                $ad->media()->delete();
                return response()->json([
                    'message' => 'Post not created'
                ], 400);
            }
        }



        return response()->json([
            'message' => 'Ad created',
            'ad' => [
                'id' => $ad->id,
                'text' => $ad->text,
                'remaining_users' => $ad->remaining_users,
                'created_at' => $ad->created_at,
                'media' => $ad->media->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'type' => $media->type,
                        'url' => url("storage/{$media->path}"),
                    ];
                }),
            ],

        ], 201);
    }

    public function decrementRemainingUsers($id)
    {
        $ad = Ad::findOrFail($id);
        if ($ad->remaining_users > 0) {
            $ad->decrement('remaining_users');
        }
        return response()->json(['remaining_users' => $ad->remaining_users]);
    }
}
