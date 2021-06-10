<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingModel extends Model
{
    protected $table = 'tb_training';
    protected $primaryKey = 'ID';
    public $timestamps = false;
}
