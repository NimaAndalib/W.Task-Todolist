<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Task;
use App\Models\DailyNote; // 📌 این خط رو اصلاح کنید

// ================================================================
// MODEL: مدل کاربران سیستم
// ================================================================
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * ATTRIBUTE: فیلدهای قابل مقداردهی انبوه
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * ATTRIBUTE: فیلدهای مخفی شده در سریالایز
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * METHOD: تبدیل نوع فیلدها
     *
     * @return array<string,string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ================================================================
    // RELATIONSHIP: روابط مدل User
    // ================================================================

    /**
     * RELATIONSHIP: رابطه یک به چند با مدل Task
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * RELATIONSHIP: رابطه یک به چند با مدل DailyNote
     */
    public function dailyNotes()
    {
        return $this->hasMany(DailyNote::class);
    }

    /**
     * RELATIONSHIP: رابطه یک به یک با مدل DailyNote برای امروز
     * (اختیاری - اگر نیاز به رابطه جداگانه دارید)
     */
    public function todayNote()
    {
        return $this->hasOne(DailyNote::class)->where('date', now()->toDateString());
    }
}