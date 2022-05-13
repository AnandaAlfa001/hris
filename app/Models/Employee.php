<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table        = 'tb_datapribadi';
    protected $primaryKey   = 'NIK';
    protected $keyType      = 'string';
    public $incrementing    = false;
}
