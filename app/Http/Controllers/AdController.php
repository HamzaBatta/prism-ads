<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdController extends Controller
{

    public function index()
    {
        $token = session('jwt_token');
        $payload = JWTAuth::setToken($token)->getPayload();
        $userId = $payload->get('sub');

        $ads = Ad::where('user_id', $userId)->with('media')->get();

        // Add full URL for each media
        $ads->map(function ($ad) {
            $ad->media->map(function ($media) {
                $media->url = url("storage/{$media->path}");
                return $media;
            });
            return $ad;
        });

        return view('ads.index', compact('ads'));
    }

    public function store(Request $request)
    {
        $token = session('jwt_token');
        $payload = JWTAuth::setToken($token)->getPayload();
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

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('ads', 'public');
                $type = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';
                $ad->media()->create([
                    'path' => $path,
                    'type' => $type,
                ]);
            }
        }

        return redirect()->route('ads.index');
    }

    public function destroy($id)
    {
        $token = session('jwt_token');
        $payload = JWTAuth::setToken($token)->getPayload();
        $userId = $payload->get('sub');

        $ad = Ad::where('user_id', $userId)->findOrFail($id);
        $ad->media()->delete();
        $ad->delete();

        return redirect()->route('ads.index');
    }

    //    public function update(Request $request, $id)
    //    {
    //        try {
    //            $payload = JWTAuth::parseToken()->getPayload();
    //        } catch (\Exception $e) {
    //            return response()->json(['message' => 'Unauthorized'], 401);
    //        }
    //
    //        $userId = $payload->get('sub');
    //        $ad = Ad::where('user_id', $userId)->findOrFail($id);
    //
    //        $request->validate([
    //            'text' => 'sometimes|required|string',
    //            'remaining_users' => 'sometimes|required|integer|min:1',
    //            'media' => 'nullable|array',
    //            'media.*' => 'file|mimes:jpeg,png,gif,mp4,mov|max:20480',
    //        ]);
    //
    //        $ad->update($request->only('text', 'remaining_users'));
    //
    //        if ($request->hasFile('media')) {
    //            $ad->media()->delete();
    //            foreach ($request->file('media') as $file) {
    //                $path = $file->store('posts', 'public');
    //                $type = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';
    //                $ad->media()->create([
    //                    'path' => $path,
    //                    'type' => $type,
    //                ]);
    //            }
    //        }
    //
    //        return response()->json(['message' => 'Ad updated', 'ad' => $ad->load('media')]);

    //    }

    public function decrementRemainingUsers($id)
    {
        $ad = Ad::findOrFail($id);
        if ($ad->remaining_users > 0) {
            $ad->decrement('remaining_users');
        }
        return response()->json(['remaining_users' => $ad->remaining_users]);
    }
}
