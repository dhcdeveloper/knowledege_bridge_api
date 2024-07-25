<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    protected $dateFormat = 'U';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'group',
        'sex',
        'birthday',
        'rest_token',
        'profile_picture',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'rest_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
        // 'password' => 'hashed',
    ];

    /**
     * メールアドレスでアプリユーザーを取得する
     * @param $email
     * @return $this
     */
    public static function getUserByMail($email)
    {
        $query = self::query()
            ->where('email', $email);

        $user = $query->first();

        return $user;
    }

    /**
     * メールアドレスとパスワードでアプリユーザーを取得する
     * @param $email
     * @param $password
     * @return $this|null
     */
    public static function getUserByMailPassword($email, $password)
    {
        $user = static::getUserByMail($email);

        if ($user && $user->password === $password) {
            // パスワードが一致している場合
            return $user;
        }

        return null;
    } 
}
