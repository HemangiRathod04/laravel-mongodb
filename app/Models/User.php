<?php
namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Eloquent\Model as Eloquent;
use MongoDB\Laravel\Eloquent\SoftDeletes; // Use Moloquent namespace
class User extends Eloquent
{
    use Authenticatable, CanResetPassword, Notifiable, SoftDeletes;
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'users';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'country_id',
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'password',
        'gender',
        'date_of_birth',
        'status',
        'address_1',
        'address_2',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        // 'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        // 'phone' => 'integer'
    ];


    /**
     * Define the relationship with the Country model.
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', '_id');
    }
}
