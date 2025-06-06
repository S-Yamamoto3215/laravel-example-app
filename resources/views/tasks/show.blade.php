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

            <span class="ml-2 {{ $task->status == 'todo' ? 'bg-gray-100 text-gray-800' : '' }}
                        {{ $task->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $task->status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                        text-xs font-medium px-2.5 py-0.5 rounded">
                {{ App\Models\Task::getStatusOptions()[$task->status] ?? '未設定' }}
            </span>

            @if($task->category)
                <span class="ml-2 text-xs font-medium px-2.5 py-0.5 rounded" style="background-color: #{{ $task->category->color }}40; color: #{{ $task->category->color }};">
                    {{ $task->category->name }}
                </span>
            @endif
        </div>

        @if($task->description)
            <div class="mb-4">
                <h4 class="text-gray-600 font-medium mb-1">説明</h4>
                <div class="text-gray-700 whitespace-pre-wrap">{{ $task->description }}</div>
            </div>
        @endif

        <div class="mb-4">
            <h4 class="text-gray-600 font-medium mb-1">優先度</h4>
            <form action="{{ route('tasks.update.priority', $task->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <select name="priority"
                        class="rounded border-gray-300 w-full md:w-1/3
                            {{ $task->priority == 'high' ? 'bg-red-100' : '' }}
                            {{ $task->priority == 'medium' ? 'bg-yellow-100' : '' }}
                            {{ $task->priority == 'low' ? 'bg-green-100' : '' }}">
                    @foreach(App\Models\Task::getPriorityOptions() as $value => $label)
                        <option value="{{ $value }}" {{ ($task->priority ?? 'medium') == $value ? 'selected' : '' }}
                            class="{{ $value == 'high' ? 'text-red-800' : '' }}
                                   {{ $value == 'medium' ? 'text-yellow-800' : '' }}
                                   {{ $value == 'low' ? 'text-green-800' : '' }}">
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="mt-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    優先度更新
                </button>
            </form>
        </div>

        <div class="mb-4">
            <h4 class="text-gray-600 font-medium mb-1">カテゴリ</h4>
            <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                @csrf
                @method('PUT')
                <select name="category_id" class="rounded border-gray-300 w-full md:w-1/3">
                    <option value="">カテゴリなし</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $task->category_id == $category->id ? 'selected' : '' }}
                            style="color: #{{ $category->color }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="mt-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    カテゴリ更新
                </button>
            </form>
        </div>

        <div class="mb-4">
            <h4 class="text-gray-600 font-medium mb-1">期限日</h4>
            <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="flex flex-col md:flex-row md:items-end md:space-x-2">
                    <div>
                        <input type="date" name="due_date"
                            value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}"
                            class="rounded border-gray-300 {{ $task->due_date && $task->due_date->isPast() && $task->status !== 'completed' ? 'border-red-300 bg-red-50' : '' }}" />
                        @if($task->due_date && $task->due_date->isPast() && $task->status !== 'completed')
                            <p class="text-red-600 text-sm mt-1">期限が過ぎています！</p>
                        @endif
                    </div>
                    <button type="submit" class="mt-2 md:mt-0 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        期限日更新
                    </button>
                </div>
            </form>
        </div>

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
            <div>作成日時: {{ $task->created_at->format('Y年m月d日 H:i') }}</div>
            <div>最終更新: {{ $task->updated_at->format('Y年m月d日 H:i') }}</div>
            @if($task->due_date)
                <div class="{{ $task->due_date->isPast() && $task->status !== 'completed' ? 'text-red-600 font-semibold' : '' }}">
                    期限日: {{ $task->due_date->format('Y年m月d日') }}
                    @if($task->due_date->isPast() && $task->status !== 'completed')
                        （期限切れ）
                    @endif
                </div>
            @endif
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
                <button type="submit" class="ml-2 text-white {{ $task->status === App\Models\Task::STATUS_COMPLETED ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} px-4 py-2 rounded">
                    {{ $task->status === App\Models\Task::STATUS_COMPLETED ? '未完了に戻す' : '完了にする' }}
                </button>
            </form>
        </div>
    </div>
@endsection
