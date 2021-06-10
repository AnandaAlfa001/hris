<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsenLogModel extends Model
{
    protected $table = 'absen_log';
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
						'img',
						'lat',
						'lng',
                        'keterangan',
                        'jns',
                        'tipe'
					];
}
