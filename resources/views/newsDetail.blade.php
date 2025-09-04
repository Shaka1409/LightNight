@extends('layout.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- tin tức chính -->
        <div class="md:col-span-2 bg-white shadow-lg rounded-2xl overflow-hidden">
            <div class="w-full">
                @if ($new->image)
                    <img src="{{ asset('storage/' . $new->image) }}" 
                         alt="{{ $new->name }}" 
                         class="w-full max-h-[400px] object-cover">
                @else
                    <div class="w-full h-[250px] md:h-[400px] flex items-center justify-center bg-gray-200 text-gray-500 text-lg">
                        Chưa có ảnh
                    </div>
                @endif
            </div>

            <div class="p-6 md:p-8">
                <h1 class="text-2xl md:text-4xl font-bold text-gray-900 leading-tight mb-4 md:mb-6">
                    {{ $new->name }}
                </h1>
                <p class="text-base md:text-lg text-gray-700 leading-relaxed mb-4 md:mb-6">
                    {{ $new->description }}
                </p>
                <div class="border-l-4 border-blue-500 pl-4 text-gray-800 text-base md:text-lg leading-relaxed">
                    {{ $new->content }}
                </div>

                @if ($new->link)
                    <div class="mt-4 md:mt-6">
                        <a href="{{ $new->link }}" target="_blank" 
                           class="text-blue-600 font-medium sm:hover:underline">
                            ➝ Xem tin tức gốc
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- tin tức liên quan -->
        <div class="md:col-span-1">
            <h2 class="text-xl md:text-2xl font-semibold text-gray-900 mb-4 md:mb-6 text-center md:text-left">
                Tin tức liên quan
            </h2>
            <div class="space-y-4 md:space-y-6">
                @foreach (array_merge($previousNews->toArray(), $nextNews->toArray()) as $relatednew)
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                        <a href="{{ route('newsDetail', $relatednew['id']) }}" class="block">
                            @if ($relatednew['image'])
                                <img src="{{ asset('storage/' . $relatednew['image']) }}" 
                                     alt="{{ $relatednew['name'] }}" 
                                     class="w-full h-[180px] object-cover">
                            @else
                                <div class="w-full h-[180px] flex items-center justify-center bg-gray-200 text-gray-500 text-sm">
                                    Chưa có ảnh
                                </div>
                            @endif
                            <div class="p-4">
                                <h3 class="text-base md:text-lg font-semibold text-gray-800">
                                    {{ $relatednew['name'] }}
                                </h3>
                                <p class="text-sm text-gray-600">
                                    {{ \Illuminate\Support\Str::limit($relatednew['description'], 100) }}
                                </p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
