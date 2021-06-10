<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsenIjinModel extends Model
{
    protected $table = 'absen_izin';
    protected $fillable = [
        'id',
        'nama',
        'nik',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'jamlembur',
        'jamselesailembur',
        'stat',
        'ket',
        'statusApp',
        'tgl_input',
        'alasan_reject',
        'act_by',
        'status_email',
        'catatan_approve',
        'potong_cuti',
    ];
    public $timestamps = false;
}
