<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthModel extends Model
{
    protected $table = 'tb_kesehatan';
    protected $primaryKey = 'ID';
    public $timestamps = false;
}
