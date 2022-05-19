@extends('layouts.index')
@section('content')
    @include('layouts.function')
    <div class="content-wrapper">



        <section class="content-header">
            <h1>{{ $company->COMPANY_COMPANY_NAME }}</h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li class="active">Perusahaan</li>
            </ol>
        </section>

        <section>
            @if (session('message'))
                <div class="col-md-12 col-sm-6 col-12">
                    <div class="info-box bg-{{ session('alert') }}">
                        <span class="info-box-icon"><i class="{{ session('icon') }}"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><strong>Information!</strong> {{ session('message') }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
            @endif
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <form action="/company/update" method="POST" role="form">
                        {{ csrf_field() }}
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Company Profile</h3>
                            </div>
                            <div class="box-body">
                            <!-- form start -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Company Name</label>
                                            <input type="text" required name="COMPANY_COMPANY_NAME" value="{{ $company->COMPANY_COMPANY_NAME }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" required name="COMPANY_EMAIL" value="{{ $company->COMPANY_EMAIL }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input type="number" required name="COMPANY_PHONE" value="{{ $company->COMPANY_PHONE }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Fax</label>
                                            <input type="number" required name="COMPANY_FAX" value="{{ $company->COMPANY_FAX }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Website</label>
                                            <input type="text" required name="COMPANY_WEBSITE" value="{{ $company->COMPANY_WEBSITE }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box">
                            <div class="box-body">
                                <!-- form start -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>PIC Name</label>
                                            <input type="text" required name="COMPANY_PIC" value="{{ $company->COMPANY_PIC }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" required name="COMPANY_PIC_EMAIL" value="{{ $company->COMPANY_PIC_EMAIL }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Fax</label>
                                            <input type="number" required name="COMPANY_PIC_PHONE" value="{{ $company->COMPANY_PIC_PHONE }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Company Status</label>
                                            <input type="text" required name="COMPANY_STATUS" value="{{ $company->COMPANY_STATUS }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                            <input type="hidden" name="ID" value="{{ $company->ID }}">
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
