<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Discussion - {{ $course->title }}</h2>
    </x-slot>

    <div class="p-6">
        <form action="{{ route('courses.discussions.store', $course) }}" method="POST" class="mb-6">
            @csrf
            <textarea name="content" rows="3" class="w-full border rounded p-2" placeholder="Start a discussion..."></textarea>
            <button class="mt-2 bg-blue-600 text-white px-4 py-2 rounded">Post</button>
        </form>

        @foreach ($discussions as $post)
            <div class="border-b mb-4 pb-2">
                <p><strong>{{ $post->user->name }}</strong> • {{ $post->created_at->diffForHumans() }}</p>
                <p>{{ $post->content }}</p>

                @can('discussion_management')
                    <form action="{{ route('courses.discussions.destroy', [$course, $post]) }}" method="POST" class="mt-1">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-500 text-sm">Delete</button>
                    </form>
                @endcan
            </div>
        @endforeach
    </div>
</x-app-layout>
