<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

/**
 * @OA\Schema(
 *   schema="NewUser",
 *   required={"firstname","email","phone","gender","password"}
 * )
 *
 * @OA\Schema(
 *   schema="UserLogin",
 *   required={"email","password"}
 * )
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */
    protected $visible = [
        'firstname', 'lastname', 'email', 'phone', 'dob', 'gender', 'active', 'last_login'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'phone', 'dob', 'gender', 'active', 'last_login', 'password', 'api_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'dob'   => 'date',
        'active' => 'boolean',
        'last_login' => 'datetime'
    ];

    /**
     * Regenerate random API Token
     *
     * @return string
     */
    public function generateApiToken()
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $key  = substr(str_shuffle(str_repeat($pool, 5)), 0, 40);
        $this->attributes['api_token'] = base64_encode($key);
        return base64_encode($key);
    }
}

/**
 *  @OA\Schema(
 *   schema="User",
 *   type="object",
 *   allOf={
 *       @OA\Schema(ref="#/components/schemas/NewUser"),
 *       @OA\Schema(
 *           @OA\Property(property="id", format="int64", type="integer"),
 *           @OA\Property(property="firstname", type="string"),
 *           @OA\Property(property="lastname", type="string"),
 *           @OA\Property(property="email", type="string"),
 *           @OA\Property(property="phone", type="string"),
 *           @OA\Property(property="dob", type="string"),
 *           @OA\Property(property="gender", type="string"),
 *           @OA\Property(property="active", type="boolean"),
 *           @OA\Property(property="password", type="string", format="password"),
 *           @OA\Property(property="api_token", type="string", format="password"),
 *           @OA\Property(property="remember_token", type="string", format="password"),
 *           @OA\Property(property="created_at", type="string", format="date-time"),
 *           @OA\Property(property="updated_at", type="string", format="date-time")
 *       )
 *   }
 * )
 */
