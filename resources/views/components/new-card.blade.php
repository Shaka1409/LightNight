<a href="{{ route('newsDetail', ['id' => $new->id]) }}">
<div class="bg-white rounded-lg shadow-lg overflow-hidden transform transition sm:hover:scale-105">
    <img loading="lazy" src="{{ asset('storage/' . $new->image) }}" alt="{{ $new->title }}" class="w-full h-48 object-cover">
    <div class="p-4">
        <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $new->name }}</h4>
        <p class="text-gray-600 mb-4">
            {{ \Illuminate\Support\Str::limit($new->excerpt, 100) }}
        </p>
    </div>
</div>
</a>