@extends('layouts.app')

@section('content')
    <div class="mb-4 flex justify-between items-center">
        <h2 class="text-2xl font-semibold">タスク一覧</h2>
        <a href="{{ route('tasks.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            新規タスク作成
        </a>
    </div>

    @if($tasks->isEmpty())
        <p class="text-center py-8 text-gray-500">タスクはありません。新しいタスクを作成しましょう！</p>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <ul class="divide-y divide-gray-200">
                @foreach($tasks as $task)
                    <li class="p-4 hover:bg-gray-50 flex items-center justify-between">
                        <div class="flex items-center">
                            <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="mr-3">
                                    @if($task->is_completed)
                                        <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" stroke-width="2"></circle>
                                        </svg>
                                    @endif
                                </button>
                            </form>
                            <div>
                                <a href="{{ route('tasks.show', $task) }}" class="text-lg font-medium {{$task->is_completed ? 'line-through text-gray-400' : 'text-gray-700' }}">
                                    {{ $task->title }}
                                </a>
                                @if($task->description)
                                    <p class="text-sm text-gray-500 truncate max-w-md">{{ $task->description }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('tasks.edit', $task) }}" class="text-blue-500 hover:text-blue-600">
                                編集
                            </a>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-600">削除</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection
