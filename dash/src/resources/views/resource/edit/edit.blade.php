@extends('dash::app')
@section('content')
<div class="container-fluid py-4">
	<div class="row">
		<div class="col-12">
			<div class="card my-4">
				<div class="card-header">
					<div class="row">
						<div class="col-9">
							<h6 class="text-dark text-capitalize {{ app()->getLocale()=='ar'?'px-3':'ps-3' }}">{{ $title??'' }} </h6>
						</div>
						<div class="col-3">
							@if($pagesRules['create'])
							<a href="{{ url(app('dash')['DASHBOARD_PATH'].'/resource/'.$resourceName.'/new') }}" class="btn btn-success btn-sm">
								<i class="fa fa-plus"></i>
							</a>
							@endif
							@if($pagesRules['show'])
							<a href="{{ url(app('dash')['DASHBOARD_PATH'].'/resource/'.$resourceName.'/'.$model->id) }}" class="btn btn-info btn-sm">
								<i class="fa fa-eye"></i>
							</a>
							@endif
							@if($pagesRules['destroy'])
							<a href="#void" action="{{ url(app('dash')['DASHBOARD_PATH'].'/resource/'.$resourceName.'/'.$model->id) }}" rowid="{{ $model->id }}"  class="btn btn-danger deleteRow{{ $resourceName }} btn-sm">
								<i class="fa fa-trash"></i>
							</a>
							@push('js')
							@include('dash::resource.relation_datatable.actions.row_delete',[
							'resourceName'=>$resourceName
							])
							@endpush
							@endif
						</div>
					</div>
				</div>
				<div class="card-body px-3 pb-2">
					<form id="form" action="{{ url(app('dash')['DASHBOARD_PATH'].'/resource/'.$resourceName.'/'.$model->id) }}" method="post" enctype="multipart/form-data">
						<div class="row">
							@csrf
							<input type="hidden" name="_method" value="put">
							@foreach($fields as $field)
							{!! $field !!}
							@endforeach
						</div>
						<button type="submit" name="save" value="edit" class="btn add btn-info"><i class="fa fa-save"></i> {{ __('dash::dash.save') }}</button>
						<button type="submit" name="save" value="edit_again" class="btn save_and_edit btn-info"><i class="fa fa-edit"></i> {{ __('dash::dash.save') }} & {{ __('dash::dash.and_edit') }}</button>

						<button type="submit" name="add" value="edit_show" class="btn save_and_edit btn-info">

							<i class="fa-solid fa-eye"></i>  {{ __('dash::dash.edit_show') }}
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@push('js')
@include('dash::resource.ajax.submit_form_ajax')
@endpush
@endsection