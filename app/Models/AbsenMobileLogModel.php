<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsenMobileLogModel extends Model
{
    protected $table = 'absen_mobile_log';
    protected $primaryKey = 'id_absen';
    protected $fillable = [
        'log_id',
        'nik',
        'tgl_absen',
        'jam_absen',
        'action',
        'fp_id',
        'calc',
        'status',
        'approved_by',
        'approved_date',
        'img',
        'width',
        'height',
        'lat',
        'lng',
        'keterangan',
        'tipe',
        'jns',
        'status_email',
        'alasan_reject',
    ];
}
