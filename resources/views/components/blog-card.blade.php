<a href="{{ route('blog', ['id' => $blog->id]) }}">
<div class="bg-white rounded-lg shadow-lg overflow-hidden transform transition sm:hover:scale-105">
    <img loading="lazy" src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="w-full h-48 object-cover">
    <div class="p-4">
        <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $blog->name }}</h4>
        <p class="text-gray-600 mb-4">
            {{ \Illuminate\Support\Str::limit($blog->excerpt, 100) }}
        </p>
    </div>
</div>
</a>