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
        'is_completed',
        'user_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    // ステータスの定数を定義
    public const STATUS_TODO = 'todo';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';

    // ステータス表示用の配列
    public static function getStatusOptions()
    {
        return [
            self::STATUS_TODO => '未着手',
            self::STATUS_IN_PROGRESS => '進行中',
            self::STATUS_COMPLETED => '完了',
        ];
    }
}
