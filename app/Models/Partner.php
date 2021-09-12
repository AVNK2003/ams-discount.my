<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Partner
 *
 * @property int $id
 * @property string $name
 * @property string $tel
 * @property string $email
 * @property int $is_admin
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Company[] $Companies
 * @property-read int|null $companies_count
 * @method static \Illuminate\Database\Eloquent\Builder|Partner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Partner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Partner query()
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereTel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Partner extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'name', 'tel', 'email', 'telegram_id'
    ];

    public function Companies()
    {
        return $this->hasMany(Company::class, 'user_id', 'id');
    }
}
