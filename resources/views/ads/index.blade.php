<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Ads</title>
    @vite('resources/css/app.css')
    <style>
        .slide-down {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            opacity: 0;
        }
        
        .slide-down.show {
            max-height: 500px;
            transition: max-height 0.3s ease-in;
            opacity: 1;
        }
        
        .rotate-icon {
            transition: transform 0.3s ease;
        }
        
        .rotate-icon.rotated {
            transform: rotate(180deg);
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('toggleCreateAd');
            const createAdSection = document.getElementById('createAdSection');
            const toggleIcon = document.getElementById('toggleIcon');

            toggleBtn.addEventListener('click', function () {
                createAdSection.classList.toggle('show');
                toggleIcon.classList.toggle('rotated');
                
                // Update button text
                const buttonText = toggleBtn.querySelector('.button-text');
                if (createAdSection.classList.contains('show')) {
                    buttonText.textContent = 'Hide Create Ad';
                } else {
                    buttonText.textContent = 'Create New Ad';
                }
            });
        });
    </script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen">
<div class="max-w-5xl mx-auto py-8 px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Welcome, {{ session('user')['name'] }}</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</button>
        </form>
    </div>

    {{-- Toggle Button for Create Ad --}}
    <div class="mb-6 text-center">
        <button id="toggleCreateAd"
                class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-all duration-200 flex items-center justify-center gap-2 mx-auto shadow-lg hover:shadow-xl">
            <span class="button-text">Create New Ad</span>
            <svg id="toggleIcon" class="rotate-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
    </div>

    {{-- Create Ad Section (Hidden by default) --}}
    <div id="createAdSection" class="slide-down bg-gray-800 p-6 rounded-lg shadow-lg mb-8 max-w-lg mx-auto border border-gray-700">
        <h2 class="text-xl font-semibold mb-4 text-blue-400">Create New Advertisement</h2>
        <form method="POST" action="{{ route('ads.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Ad Text</label>
                <input type="text" name="text" placeholder="Enter your ad text here..." required
                       class="w-full border border-gray-600 bg-gray-700 text-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Remaining Users</label>
                <input type="number" name="remaining_users" placeholder="Number of users" required min="1"
                       class="w-full border border-gray-600 bg-gray-700 text-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Media Files (Optional)</label>
                <input type="file" name="media[]" multiple accept="image/*,video/*"
                       class="w-full border border-gray-600 bg-gray-700 text-gray-200 rounded-lg px-3 py-2 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all duration-200">
                <p class="text-xs text-gray-400 mt-1">Supported: JPEG, PNG, GIF, MP4, MOV (Max 20MB each)</p>
            </div>

            <button type="submit"
                    class="w-full bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl">
                Create Advertisement
            </button>
        </form>
    </div>

    {{-- My Ads --}}
    <h2 class="text-2xl font-semibold mb-4">My Ads</h2>
    <div class="grid gap-6 md:grid-cols-2">
        @foreach($ads as $ad)
            <div class="bg-gray-800 p-4 rounded-lg shadow">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-bold">{{ $ad->text }}</h3>
                    <span class="text-gray-400 text-sm">Remaining: {{ $ad->remaining_users }}</span>
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