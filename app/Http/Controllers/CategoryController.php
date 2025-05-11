<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());
        return redirect()->route('categories.index')->with('success', 'カテゴリが作成されました！');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return redirect()->route('categories.index')->with('success', 'カテゴリが更新されました！');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // トランザクション開始
        \DB::beginTransaction();

        try {
            // このカテゴリを使用しているタスクのカテゴリIDをnullに設定
            $tasksCount = $category->tasks()->count();
            $category->tasks()->update(['category_id' => null]);

            // カテゴリを削除
            $category->delete();

            // トランザクション完了
            \DB::commit();

            $message = 'カテゴリが削除されました！';
            if ($tasksCount > 0) {
                $message .= " {$tasksCount}件のタスクからカテゴリが削除されました。";
            }

            return redirect()->route('categories.index')->with('success', $message);
        } catch (\Exception $e) {
            // エラーが発生した場合はロールバック
            \DB::rollBack();
            return redirect()->route('categories.index')->with('error', 'カテゴリの削除に失敗しました: ' . $e->getMessage());
        }
    }
}
