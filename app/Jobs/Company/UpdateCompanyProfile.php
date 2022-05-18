<?php

namespace App\Jobs\Company;

use App\Models\CompanyModel;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Validator;

class UpdateCompanyProfile
{
    use Dispatchable;

    /**
     * @var CompanyModel
     */
    public CompanyModel $company;

    /**
     * Filtered attributes.
     *
     * @var array
     */
    public array $attributes;

    public function __construct(CompanyModel $company, $inputs = [])
    {
        $this->attributes = Validator::make($inputs, [
            'ID' => ['filled'],
            'COMPANY_NPWP' => ['filled'],
            'COMPANY_COMPANY_NAME' => ['filled'],
            'COMPANY_WEBSITE' => ['filled'],
            'COMPANY_EMAIL' => ['filled', 'email'],
            'COMPANY_PHONE' => ['filled', 'numeric'],
            'COMPANY_FAX' => ['filled'],
            'COMPANY_PIC' => ['filled'],
            'COMPANY_PIC_EMAIL' => ['filled'],
            'COMPANY_PIC_PHONE' => ['filled'],
            'COMPANY_PIC_MOBILE' => ['filled'],
            'COMPANY_PASSWORD' => ['filled'],
            'COMPANY_LOGO' => ['filled'],
            'COMPANY_STATUS' => ['filled'],
            'COMPANY_EXPIRED_DATE' => ['filled', 'date'],
            'EMAIL_REG_FLAG' => ['filled'],
            'EMAIL_APP_FLAG' => ['filled'],
            'DIALOG_FIRST' => ['filled'],
            'APPROVED_BY_ID' => ['filled'],
            'APPROVE_DATE' => ['filled', 'date'],
            'SECRET_ID' => ['filled'],
            'COMPANY_USE_ID' => ['filled'],
            'TEST_FLAG' => ['filled'],
        ])->validate();

        $this->company = $company;

    }

    /**
     * Handle the job.
     *
     * @return bool
     */
    public function handle(): bool
    {
        $this->company->fill($this->attributes);
        $this->company->save();

        return $this->company->exists;
    }
}
