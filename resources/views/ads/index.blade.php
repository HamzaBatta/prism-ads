<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Ads</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen">
<div class="max-w-5xl mx-auto py-8 px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Welcome, {{ session('user')['name'] }}</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</button>
        </form>
    </div>

    {{-- Create Ad --}}
    <div class="bg-white p-6 rounded-lg shadow mb-8 max-w-lg mx-auto">
        <h2 class="text-xl font-semibold mb-4">Create Ad</h2>
        <form method="POST" action="{{ route('ads.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="text" name="text" placeholder="Ad text" required
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">

            <input type="number" name="remaining_users" placeholder="Remaining users" required min="1"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">

            <input type="file" name="media[]" multiple
                   class="w-full border border-gray-300 rounded px-3 py-2">

            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Create Ad
            </button>
        </form>
    </div>

    {{-- My Ads --}}
    <h2 class="text-2xl font-semibold mb-4">My Ads</h2>
    <div class="grid gap-6 md:grid-cols-2">
        @foreach($ads as $ad)
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-bold">{{ $ad->text }}</h3>
                    <span class="text-gray-500 text-sm">Remaining: {{ $ad->remaining_users }}</span>
                </div>

                @if($ad->media && $ad->media->count())
                    <div class="flex gap-2 flex-wrap mb-3">
                        @foreach($ad->media as $media)
                            @if($media->type === 'image')
                                <img src="{{ $media->url }}" alt="Ad Image" class="w-32 h-32 object-cover rounded">
                            @elseif($media->type === 'video')
                                <video class="w-32 h-32 rounded" controls>
                                    <source src="{{ $media->url }}" type="video/mp4">
                                </video>
                            @endif
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('ads.destroy', $ad->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                        Delete
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</div>
</body>
</html>
