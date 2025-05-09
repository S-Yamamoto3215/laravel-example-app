<!-- filepath: resources/views/dashboard.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-lg font-medium mb-4">{{ __('Task Summary') }}</h2>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                            <div class="text-3xl font-bold text-blue-600">{{ Auth::user()->tasks()->count() }}</div>
                            <div class="text-sm text-blue-600">全タスク</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <div class="text-3xl font-bold text-gray-600">{{ Auth::user()->tasks()->where('status', App\Models\Task::STATUS_TODO)->count() }}</div>
                            <div class="text-sm text-gray-600">未着手</div>
                        </div>
                        <div class="bg-blue-100 p-4 rounded-lg border border-blue-200">
                            <div class="text-3xl font-bold text-blue-700">{{ Auth::user()->tasks()->where('status', App\Models\Task::STATUS_IN_PROGRESS)->count() }}</div>
                            <div class="text-sm text-blue-700">進行中</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                            <div class="text-3xl font-bold text-green-600">{{ Auth::user()->tasks()->where('status', App\Models\Task::STATUS_COMPLETED)->count() }}</div>
                            <div class="text-sm text-green-600">完了</div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('tasks.create') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                            新規タスク作成
                        </a>
                        <a href="{{ route('tasks.index') }}" class="inline-block ml-2 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded">
                            全タスクを見る
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
