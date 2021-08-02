<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function showProfile(int $id)
    {
        $data['company'] = DB::table('tb_company AS A')
            ->select(
                'A.ID',
                'A.UUID',
                'A.COMPANY_NPWP',
                'A.COMPANY_COMPANY_NAME',
                'A.COMPANY_WEBSITE',
                'A.COMPANY_EMAIL',
                'A.COMPANY_PHONE',
                'A.COMPANY_FAX',
                'A.COMPANY_PIC',
                'A.COMPANY_PIC_EMAIL',
                'A.COMPANY_PIC_PHONE',
                'A.COMPANY_PIC_MOBILE',
                'A.COMPANY_PASSWORD',
                'A.COMPANY_LOGO',
                'A.COMPANY_STATUS',
                'A.COMPANY_EXPIRED_DATE',
                'A.EMAIL_REG_FLAG',
                'A.EMAIL_APP_FLAG',
                'A.DIALOG_FIRST',
                'A.APPROVED_BY_ID',
                'A.APPROVE_DATE',
                'A.SECRET_ID',
                'A.COMPANY_USE_ID',
                'A.CREATED_BY',
                'A.CREATED_AT',
                'A.UPDATED_BY',
                'A.UPDATED_AT',
            )
            ->where('id', '=', $id)
            ->first();

        return view('company/profile', $data);
    }
}
