<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'full_name',
        'phone',
        'profile_image',
        'first_time_login',
        'account_type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getValidationRules($type, $params = [])
    {
        $rules = [
            'register'       => [
                'full_name'    => 'required|string',
                'email'        => ['required', 'email', Rule::unique('users')->whereNull('deleted_at')],
                'password'     => 'required|string|confirmed',
                'phone'        => 'string',
                'account_type' => 'integer|required',
            ],
            'registerShop'   => [
                'full_name'        => 'required|string',
                'email'            => ['required', 'email', Rule::unique('users')->whereNull('deleted_at')],
                'password'         => 'required|string|confirmed',
                'phone'            => 'string',
                'account_type'     => 'integer|required',
                'complete_address' => 'required|string',
                'city'             => 'required|string',
                'area'             => 'required|string',
                'lat'              => 'required|string',
                'lng'              => 'required|string'
            ],
            'login'          => [
                'email'    => 'required|string',
                'password' => 'required|string',
            ],
            'changePassword' => [
                'old_password'          => 'required',
                'password'              => 'required|min:6|confirmed',
                'password_confirmation' => 'required|min:6',
            ],
        ];

        return $rules[$type];
    }

    public function getProfileImageAttribute($value)
    {
        return $value ? asset("storage/profile-images/$value") : null;
    }

    public static function createOrUpdateRecord($data)
    {
        return self::updateOrCreate($data);
    }

    public static function getUser($condition)
    {
        return self::where($condition)->get()->first();
    }

    public static function updateProfileImage($userId, $fileName)
    {
        $user = self::query()->where('id','=',$userId)->get()->first();
        $user->profile_image = $fileName;
        $user->save();
    }

    public static function updateUser($attributes, $conditions)
    {
        return self::where($conditions)
            ->update($attributes);
    }

    public function shopProfile()
    {
        return $this->hasOne(ShopProfile::class, 'shop_id', 'id');
    }
}
