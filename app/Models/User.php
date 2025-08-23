<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\LogsActivity;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'fname', 'p_x', 'lname', 'type_car', 'user_type', 'no_car', 'cat_id', 'email', 'password', 'email_verified_at', 'is_admin', 'provider', 'provider_id', 'access_token', 'avatar', 'phone', 'address', 'birthday', 'zipcode', 'point', 'idcard', 'code_user', 'shop_id',
    ];

    public function sendPasswordResetNotification($token)
    {
      $this->notify(new ResetPasswordNotification($token));
    }

    public function hasPermission($menu)
{
    $role = $this->roles[0]->name ?? null;

    $permissions = [
        'qr.view'     => ['SuperAdmin', 'Supervisor', 'Admin'],
        'qr.edit'     => ['SuperAdmin', 'Supervisor'],
        'qr.delete'   => ['SuperAdmin'],
        'qr.download' => ['SuperAdmin', 'Supervisor', 'Admin'],
        'qr.create'   => ['SuperAdmin', 'Supervisor', 'Admin'],

        'lot.view'    => ['SuperAdmin', 'Supervisor', 'Admin'],
        'lot.edit'    => ['SuperAdmin', 'Supervisor'],
        'lot.delete'  => ['SuperAdmin'],
        'lot.create'  => ['SuperAdmin', 'Supervisor', 'Admin']
    ];

    return in_array($role, $permissions[$menu] ?? []);
}

    public function roles()
    {
      return $this->belongsToMany(Role::class);
    }

    public function category()
    {
        return $this->belongsTo(category::class, 'cat_id');
    }

    /**
    * @param string|array $roles
    */
    public function authorizeRoles($roles)
    {
      if (is_array($roles)) {
          return $this->hasAnyRole($roles) ||
                 abort(401, 'This action is unauthorized.');
      }
      return $this->hasRole($roles) ||
             abort(401, 'This action is unauthorized.');
    }
    /**
    * Check multiple roles
    * @param array $roles
    */
    public function hasAnyRole($roles)
    {
      return null !== $this->roles()->whereIn('name', $roles)->first();
    }
    /**
    * Check one role
    * @param string $role
    */
    public function hasRole($role)
    {
      return null !== $this->roles()->where('name', $role)->first();
    }

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
}
