<!DOCTYPE html>
<html>
<head>
    <title>My Ads</title>
</head>
<body>
<h1>Welcome, {{ session('user')['name'] }}</h1>
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>

<h2>Create Ad</h2>
<form method="POST" action="{{ route('ads.store') }}" enctype="multipart/form-data">
    @csrf
    <input type="text" name="text" placeholder="Ad text" required>
    <input type="number" name="remaining_users" placeholder="Remaining users" required min="1">
    <input type="file" name="media[]" multiple>
    <button type="submit">Create Ad</button>
</form>

<h2>My Ads</h2>
<ul>
    @foreach($ads as $ad)
        <li>
            <strong>{{ $ad->text }}</strong> - Remaining: {{ $ad->remaining_users }}
            @if($ad->media)
                <div>
                    @foreach($ad->media as $media)
                        @if($media->type === 'image')
                            <img src="{{ $media->url }}" alt="Ad Image" width="150">
                        @elseif($media->type === 'video')
                            <video width="150" controls>
                                <source src="{{ $media->url }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                    @endforeach
                </div>
            @endif
            <form method="POST" action="{{ route('ads.destroy', $ad->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </li>
    @endforeach
</ul>
</body>
</html>
