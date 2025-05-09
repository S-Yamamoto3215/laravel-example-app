@extends('layouts.app')

@section('content')
    <div class="mb-4 flex justify-between items-center">
        <h2 class="text-2xl font-semibold">タスク詳細</h2>
        <div class="flex space-x-2">
            <a href="{{ route('tasks.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                戻る
            </a>
            <a href="{{ route('tasks.edit', $task) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                編集
            </a>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="mb-4 flex items-center">
            <h3 class="text-xl font-semibold mr-2">{{ $task->title }}</h3>
            @if($task->is_completed)
                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">完了済み</span>
            @else
                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">未完了</span>
            @endif

            <span class="ml-2 {{ $task->status == 'todo' ? 'bg-gray-100 text-gray-800' : '' }}
                        {{ $task->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $task->status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                        text-xs font-medium px-2.5 py-0.5 rounded">
                {{ App\Models\Task::getStatusOptions()[$task->status] ?? '未設定' }}
            </span>
        </div>

        @if($task->description)
            <div class="mb-4">
                <h4 class="text-gray-600 font-medium mb-1">説明</h4>
                <div class="text-gray-700 whitespace-pre-wrap">{{ $task->description }}</div>
            </div>
        @endif

        <div class="mb-4">
            <h4 class="text-gray-600 font-medium mb-1">ステータス</h4>
            <form action="{{ route('tasks.update.status', $task->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <select name="status"
                        class="rounded border-gray-300 w-full md:w-1/3
                               {{ $task->status == 'todo' ? 'bg-gray-100' : '' }}
                               {{ $task->status == 'in_progress' ? 'bg-blue-100' : '' }}
                               {{ $task->status == 'completed' ? 'bg-green-100' : '' }}">
                    @foreach(App\Models\Task::getStatusOptions() as $value => $label)
                        <option value="{{ $value }}" {{ $task->status == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="mt-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    ステータス更新
                </button>
            </form>
        </div>

        <div class="text-sm text-gray-500">
            <div>作成日時: {{ $task->created_at }}</div>
            <div>最終更新: {{ $task->updated_at }}</div>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-200">
            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-white bg-red-500 hover:bg-red-600 px-4 py-2 rounded">削除</button>
            </form>

            <form action="{{ route('tasks.toggle', $task) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="ml-2 text-white {{ $task->is_completed ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} px-4 py-2 rounded">
                    {{ $task->is_completed ? '未完了に戻す' : '完了にする' }}
                </button>
            </form>
        </div>
    </div>
@endsection
