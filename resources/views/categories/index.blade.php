@extends('layouts.app')

@section('content')
    <div class="mb-4 flex justify-between items-center">
        <h2 class="text-2xl font-semibold">カテゴリ一覧</h2>
        <a href="{{ route('categories.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            新規カテゴリ作成
        </a>
    </div>

    @if($categories->isEmpty())
        <p class="text-center py-8 text-gray-500">カテゴリはありません。新しいカテゴリを作成しましょう！</p>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">カラー</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">名前</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">タスク数</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">アクション</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($categories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="w-6 h-6 rounded-full" style="background-color: #{{ $category->color }}"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-medium">{{ $category->name }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $category->tasks_count ?? $category->tasks->count() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex space-x-2">
                                    <a href="{{ route('categories.edit', $category) }}" class="text-blue-500 hover:text-blue-600">
                                        編集
                                    </a>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('このカテゴリを削除してもよろしいですか？関連するタスクからカテゴリが削除されます。');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-600">削除</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
