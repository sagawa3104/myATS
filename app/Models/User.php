<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use Validatable;

    const IS_ADMIN = [
        0 => '×',
        1 => '〇'
    ];

    private function rules()
    {
        $unique = Rule::unique('users', 'email');
        $unique = is_null($this->id) ? $unique : $unique->ignore($this->id);
        return
            [
                'name' => ['required', 'max:255'],
                'email' => ['required', 'max:255', 'email', $unique],
                'password' => ['required'],
                'is_admin' => ['required', 'boolean'],
            ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'is_admin',
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
        'email_verified_at' => 'datetime',
    ];

    public function workRecords()
    {
        return $this->hasMany('App\Models\WorkRecord');
    }

    public function getStrIsAdmin()
    {
        return self::IS_ADMIN[$this->is_admin];
    }
}
