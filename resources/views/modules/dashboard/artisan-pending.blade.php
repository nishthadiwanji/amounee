@extends('layouts.main')
@section('page_title')
@lang('heading.dashboard')
@stop
@section('page_specific_css')
@stop 
@section('page_specific_body')
<div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
	<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
		<div class="d-flex align-items-center flex-wrap mr-2">
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Dashboard</h5>
        </div>
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="row">
            <div class="col-sm-9">
                @include('modules.dashboard._partials.artisan-pending-common-row')
            </div>
        </div>
    </div>
</div>
@stop
@section('page_specific_js')

@stop
