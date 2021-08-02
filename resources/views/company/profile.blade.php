@extends('layouts.index')
@section('content')
@include('layouts.function')
<div class="content-wrapper">
  <section class="content-header">
    <h1>{{ !is_null($company) ? $company->COMPANY_COMPANY_NAME : '-' }}</h1>
    <ol class="breadcrumb">
      <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li class="active">Perusahaan</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Company Profile</h3>
          </div>
          <div class="box-body">
            <div class="table-responsive">
              <div class="col-md-6">
                <table class="table table-bordered">
                  <tbody>
                    <tr>
                      <td>Nama</td>
                      <td>{{ !is_null($company) ? $company->COMPANY_COMPANY_NAME : '-' }}</td>
                    </tr>
                    <tr>
                      <td>Email</td>
                      <td>{{ !is_null($company) ? $company->COMPANY_EMAIL : '-' }}</td>
                    </tr>
                    <tr>
                      <td>Website</td>
                      <td>{{ !is_null($company) ? $company->COMPANY_WEBSITE : '-' }}</td>
                    </tr>
                    <tr>
                      <td>Telepon</td>
                      <td>{{ !is_null($company) ? $company->COMPANY_PHONE : '-' }}</td>
                    </tr>
                    <tr>
                      <td>Fax</td>
                      <td>{{ !is_null($company) ? $company->COMPANY_FAX : '-' }}</td>
                    </tr>
                    <tr>
                      <td>PIC</td>
                      <td>{{ !is_null($company) ? $company->COMPANY_PIC : '-' }}</td>
                    </tr>
                    <tr>
                      <td>PIC Email</td>
                      <td>{{ !is_null($company) ? $company->COMPANY_PIC_EMAIL : '-' }}</td>
                    </tr>
                    <tr>
                      <td>PIC Phone</td>
                      <td>{{ !is_null($company) ? $company->COMPANY_PIC_PHONE : '-' }}</td>
                    </tr>
                    <tr>
                      <td>Status</td>
                      <td>{{ !is_null($company) ? $company->COMPANY_STATUS : '-' }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection