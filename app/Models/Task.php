<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\User;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'title',
    'description',
    'date',
    'start_time',
    'priority',
    'completed',
];

    // رابطه: هر تسک متعلق به یه یوزره
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
