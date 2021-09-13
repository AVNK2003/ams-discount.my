<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'user_name', 'first_name', 'last_name',
        'active', 'current_action', 'company_telegram_id', 'company_name',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function getCreatedAtAttribute($value): string
    {
        return Carbon::parse($value)->translatedFormat('d M Y, H:i');
    }

    public static function getAllUsers()
    {
        return self::all(['id', 'user_id', 'user_name', 'first_name', 'last_name', 'active', 'created_at', 'updated_at']);
    }

    public static function getActiveUsers()
    {
        return self::where('active', true)->get(['id', 'user_id', 'user_name', 'first_name', 'last_name', 'active', 'created_at', 'updated_at']);
    }

    public function toggleActive()
    {
        $this->timestamps = false;
        $this->update(['active' => !$this->active]);
    }
}
