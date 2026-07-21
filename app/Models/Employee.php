<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employee';

    protected $primaryKey = 'emp_id';

    public $timestamps = false;

    protected $fillable = [
        'comp_id',
        'branch_id',
        'emp_code',
        'emp_name',
        'emp_sex',
        'emp_phone',
        'emp_email',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }
}
