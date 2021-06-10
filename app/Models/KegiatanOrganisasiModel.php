<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanOrganisasiModel extends Model
{
    protected $table = 'tb_organisasi';
    protected $primaryKey = 'id_org';
    public $timestamps = false;
}
