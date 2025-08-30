<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ================================================================
// MODEL: مدل DailyNote برای مدیریت یادداشت‌های روزانه کاربران
// ================================================================
class DailyNote extends Model
{
    /**
     * ATTRIBUTE: فیلدهای قابل مقداردهی انبوه
     * 
     * @var array<string>
     */
    protected $fillable = ['user_id', 'note', 'date'];

    // ================================================================
    // RELATIONSHIP: روابط مدل DailyNote
    // ================================================================

    /**
     * RELATIONSHIP: رابطه چند به یک با مدل User
     * هر یادداشت روزانه متعلق به یک کاربر است
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}