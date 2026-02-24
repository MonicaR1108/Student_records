<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'name',
        'father_name',
        'mother_name',
        'gender',
        'email',
        'phone',
        'degree',
        'branch',
        'register_number',
        'photo',
    ];
    protected $useTimestamps = false;
}
