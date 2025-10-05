<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">User Management</h2>
    </x-slot>

    <div class="py-6 px-12">
        <a href="{{ route('admin.users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ Add User</a>

        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-md text-left border border-gray-200 rounded-lg shadow-md">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $user->id }}</td>
                            <td class="px-4 py-3">{{ $user->name }}</td>
                            <td class="px-4 py-3">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="px-2 py-1 rounded-full text-xs
                            @if ($user->role->name == 'admin') bg-red-100 text-red-600
                            @elseif($user->role->name == 'instructor') bg-blue-100 text-blue-600
                            @else bg-green-100 text-green-600 @endif">
                                    {{ $user->role->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center space-x-3">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                    class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Delete user?')"
                                        class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>

    </div>
</x-app-layout>
