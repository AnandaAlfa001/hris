<?php

namespace App\Http\Controllers;

use App\Jobs\Company\UpdateCompanyProfile;
use App\Models\CompanyModel;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function showProfile(int $id)
    {
        $data['company'] = CompanyModel::where('ID',$id)->first();
        return view('company/detail', $data);
    }

    public function update(Request $request)
    {
        $company = CompanyModel::where('ID', $request->ID)->first();
        $job = new UpdateCompanyProfile($company, $request->except(['_token']));
        dispatch_now($job);
        return redirect('/company/'. $job->company->ID)->with('message','Update Data Success')->with('alert','green')->with('icon','fa fa-thumbs-o-up');
    }
}
