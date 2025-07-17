<?php

namespace App\Models\Accounts;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Specify the fillable attributes for mass assignment
    protected $fillable = [
        'username', 
        'email', 
        'password', 
        'role_id', 
        'name', 
        'address', 
        'city', 
        'region', 
        'country', 
        'postbox', 
        'phone'
    ];

    /**
     * Define the relationship between Employee and Role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
