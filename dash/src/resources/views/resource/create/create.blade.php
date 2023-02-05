@extends('dash::app')
@section('content')
@push('js')
<script type="text/javascript">
$(document).ready(function(){
@if(!empty(request('relationField')) && count(request('relationField')) > 0)
@foreach(request('relationField') as $key=>$value)
@php
$fieldName = explode('.',$key)[0];
@endphp
$('.{{ $fieldName }}').val('{{ $value }}');
//.prop('disabled',true);
@endforeach
@endif
});
</script>
@endpush
<div class="container-fluid py-4">
	<div class="row">
		<div class="col-12">
			<div class="card my-4">
				<div class="card-header">
					<h6 class="text-dark text-capitalize {{ app()->getLocale()=='ar'?'px-3':'ps-3' }}">{{ $title??'' }} </h6>
				</div>
				<div class="card-body px-3 pb-2">
					<form id="form" action="{{ url(app('dash')['DASHBOARD_PATH'].'/resource/'.$resourceName) }}" method="post" enctype="multipart/form-data">
						<div class="row">
							@csrf
							<input type="hidden" name="_method" value="post">
							@foreach($fields as $field)
							{!! $field !!}
							@endforeach
						</div>
						<button type="submit" name="add" value="add"  class="btn add btn-info"><i class="fa fa-plus"></i> {{ __('dash::dash.add') }}</button>
						<button type="submit" name="add" value="add_again" class="btn add_other btn-info">

							<i class="fa fa-plus-circle"></i> {{ __('dash::dash.add') }} & {{ __('dash::dash.add_other') }}
						</button>


						<button type="submit" name="add" value="add_show" class="btn add_other btn-info">

							<i class="fa-solid fa-eye"></i>  {{ __('dash::dash.add_show') }}
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