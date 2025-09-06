<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('app.transcript') }} — {{ __('app.select_student') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <form method="GET" class="mb-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.search_students') }}" class="w-full border-gray-300 rounded-md" />
                </form>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-3 py-2 text-left">{{ __('app.student') }}</th>
                                <th class="px-3 py-2 text-left">{{ __('app.class') }}</th>
                                <th class="px-3 py-2 text-left"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                <tr class="border-b">
                                    <td class="px-3 py-2">{{ $student->user->name }}</td>
                                    <td class="px-3 py-2">{{ $student->class->full_name ?? $student->class->name }}</td>
                                    <td class="px-3 py-2 text-right">
                                        <a href="{{ route('students.transcript', $student->id) }}" class="px-3 py-1 bg-blue-600 text-white rounded">{{ __('app.view') }}</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-3 py-6 text-center text-gray-500">{{ __('app.no_students_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(method_exists($students, 'links'))
                    <div class="mt-4">{{ $students->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
