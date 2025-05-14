@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h2 class="text-2xl font-semibold">タスク編集</h2>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <form action="{{ route('tasks.update', $task) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-medium mb-2">タイトル</label>
                <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300" required>
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-medium mb-2">説明</label>
                <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300">{{ old('description', $task->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="block text-gray-700 font-medium mb-2">ステータス</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300">
                    @foreach(App\Models\Task::getStatusOptions() as $value => $label)
                        <option value="{{ $value }}" {{ old('status', $task->status) == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="priority" class="block text-gray-700 font-medium mb-2">優先度</label>
                <select name="priority" id="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300">
                    @foreach(App\Models\Task::getPriorityOptions() as $value => $label)
                        <option value="{{ $value }}" {{ old('priority', $task->priority ?? 'medium') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="due_date" class="block text-gray-700 font-medium mb-2">期限日</label>
                <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300">
                @error('due_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="category_id" class="block text-gray-700 font-medium mb-2">カテゴリ</label>

                <div class="relative" x-data="{
                    open: false,
                    selectedId: '{{ old('category_id', $task->category_id ?? '') }}',
                    selectedName: '{{ old('category_id', $task->category_id) ? ($categories->firstWhere('id', old('category_id', $task->category_id))?->name ?? 'カテゴリなし') : 'カテゴリなし' }}'
                }">
                    <!-- カスタムセレクトの表示部分 -->
                    <div
                        @click="open = !open"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300 flex items-center justify-between cursor-pointer"
                    >
                        <div>
                            <!-- 選択されたカテゴリ -->
                            <span x-text="selectedName || 'カテゴリなし'" :class="selectedId ? '' : 'text-gray-500'"></span>
                        </div>
                        <!-- 下向き矢印アイコン -->
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>

                    <!-- 隠しフィールド - 実際に送信される値 -->
                    <input type="hidden" name="category_id" x-model="selectedId">

                    <!-- ドロップダウンの選択肢 -->
                    <div
                        x-show="open"
                        @click.away="open = false"
                        class="absolute z-10 w-full mt-1 bg-white shadow-lg rounded-md border border-gray-300 py-1 max-h-60 overflow-auto"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                    >
                        <!-- カテゴリなしオプション -->
                        <div
                            @click="selectedId = ''; selectedName = 'カテゴリなし'; open = false"
                            class="px-3 py-2 cursor-pointer hover:bg-gray-100"
                            :class="selectedId === '' ? 'bg-blue-50 text-blue-700' : ''"
                        >
                            カテゴリなし
                        </div>

                        <!-- カテゴリオプション -->
                        @foreach($categories as $category)
                        <div
                            @click="selectedId = '{{ $category->id }}'; selectedName = '{{ $category->name }}'; open = false"
                            class="px-3 py-2 cursor-pointer hover:bg-gray-100 flex items-center"
                            :class="selectedId === '{{ $category->id }}' ? 'bg-blue-50 text-blue-700' : ''"
                        >
                            <div class="w-4 h-4 rounded-full mr-2" style="background-color: #{{ $category->color }}"></div>
                            <span>{{ $category->name }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Alpine.js の読み込み -->
            <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>


            <div class="flex items-center justify-between">
                <a href="{{ route('tasks.index') }}" class="text-gray-600 hover:text-gray-800">キャンセル</a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">更新する</button>
            </div>
        </form>
    </div>
@endsection
