<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class CompanyModel
 * @package App\Models
 *
 *
 * @property int $ID
 * @property string $UUID
 * @property string $COMPANY_NPWP
 * @property string $COMPANY_COMPANY_NAME
 * @property string $COMPANY_WEBSITE
 * @property string $COMPANY_EMAIL
 * @property string $COMPANY_PHONE
 * @property string $COMPANY_FAX
 * @property string $COMPANY_PIC
 * @property string $COMPANY_PIC_EMAIL
 * @property string $COMPANY_PIC_PHONE
 * @property string $COMPANY_PIC_MOBILE
 * @property string $COMPANY_PASSWORD
 * @property string $COMPANY_LOGO
 * @property string $COMPANY_STATUS
 * @property Carbon $COMPANY_EXPIRED_DATE
 * @property string $EMAIL_REG_FLAG
 * @property string $EMAIL_APP_FLAG
 * @property int $DIALOG_FIRST
 * @property int $APPROVED_BY_ID
 * @property Carbon $APPROVE_DATE
 * @property string $SECRET_ID
 * @property int $COMPANY_USE_ID
 * @property string $TEST_FLAG
 * @property string $CREATED_BY
 * @property Carbon $CREATED_AT
 * @property string $UPDATED_BY
 * @property Carbon $UPDATED_AT
 *
 */
class CompanyModel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_company';

    public $primaryKey = 'ID';


    /**
     * @var string[]
     */
    protected $fillable = [
        'UUID',
        'COMPANY_NPWP',
        'COMPANY_COMPANY_NAME',
        'COMPANY_WEBSITE',
        'COMPANY_EMAIL',
        'COMPANY_PHONE',
        'COMPANY_FAX',
        'COMPANY_PIC',
        'COMPANY_PIC_EMAIL',
        'COMPANY_PIC_PHONE',
        'COMPANY_PIC_MOBILE',
        'COMPANY_PASSWORD',
        'COMPANY_LOGO',
        'COMPANY_STATUS',
        'EMAIL_REG_FLAG',
        'EMAIL_APP_FLAG',
        'DIALOG_FIRST',
        'APPROVED_BY_ID',
        'SECRET_ID',
        'COMPANY_USE_ID',
        'TEST_FLAG',
        'CREATED_BY',
        'UPDATED_BY',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'COMPANY_EXPIRED_DATE' => 'datetime',
        'APPROVE_DATE' => 'datetime',
        'CREATED_AT' => 'datetime',
        'UPDATED_AT' => 'datetime',
    ];

    public function created_by(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'CREATED_BY', 'id');
    }

    public function updated_by(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'UPDATED_BY', 'id');
    }


}
