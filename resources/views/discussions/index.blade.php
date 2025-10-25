<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">All Discussions</h2>
    </x-slot>

    <div class="p-6">
        @foreach($discussions as $post)
            <div class="border-b mb-4 pb-2">
                <p>
                    <strong>{{ $post->user->name }}</strong>
                    • <a href="{{ route('courses.discussions.index', $post->course) }}" class="text-blue-600">{{ $post->course->title }}</a>
                    • {{ $post->created_at->diffForHumans() }}
                </p>
                <p>{{ $post->content }}</p>
            </div>
        @endforeach
    </div>
</x-app-layout>
