<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Laravel\Airlock\HasApiTokens;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone_number', 'password', 'register_ip', 'last_login_ip', 'last_login_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    /**
     * 设置用户密码
     *
     * @param string $value
     *
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        if (null === $value || '' === $value) {
            return $this->attributes['password'] = null;
        }

        if (Hash::needsRehash($value)) {
            return $this->attributes['password'] = Hash::make($value);
        }

        $this->attributes['password'] = $value;
    }

    /**
     * 确认此用户密码是否正确.
     *
     * @param string $password
     *
     * @return bool
     */
    public function checkPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }
}
