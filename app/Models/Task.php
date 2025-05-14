<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'status',
        'priority',
        'category_id',
        'due_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * このタスクが属するカテゴリを取得
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected $casts = [
        'due_date' => 'date',
    ];

    // ステータスの定数を定義
    public const STATUS_TODO = 'todo';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';

    // 優先度の定数を定義
    public const PRIORITY_HIGH = 'high';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_LOW = 'low';

    // ステータス表示用の配列
    public static function getStatusOptions()
    {
        return [
            self::STATUS_TODO => '未着手',
            self::STATUS_IN_PROGRESS => '進行中',
            self::STATUS_COMPLETED => '完了',
        ];
    }

    // 優先度表示用の配列
    public static function getPriorityOptions()
    {
        return [
            self::PRIORITY_HIGH => '高',
            self::PRIORITY_MEDIUM => '中',
            self::PRIORITY_LOW => '低',
        ];
    }
}
