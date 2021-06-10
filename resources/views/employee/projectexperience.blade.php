@extends('layouts.index')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Project Experience
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ url('employeelist') }}">All Employee</a></li>
        <li class="active">Project Experience</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
      <?php if(session('project')){ $z=0; }  ?>
        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li @if(session('a')) class="active" @elseif(isset($z) && $z==1) class="active" @endif><a href="#headerpe" data-toggle="tab">Header PE</a></li></button>
              <li @if(session('project')) class="active" @else @endif><a href="#dataprojectexperience" data-toggle="tab">Project Experience</a></li>             
            </ul>
            <div class="tab-content">
              <div class="@if(session('a')) active @elseif(isset($z) && $z==1) active @endif tab-pane" id="headerpe">
                @include('employee.dataheaderpe')
              </div>
              <div class="@if(session('project')) active @else @endif tab-pane" id="dataprojectexperience">
                @include('employee.dataprojectexperience')
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->

      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
