@extends('dash::app')
@section('content')

  <div class="row">
@foreach($resource['resourceNameFull']::vertex() as $vertex)
   {!! $vertex !!}
@endforeach
  </div>

<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card my-4">

        <div class="card-body px-3 pb-2">
           <h4 class="text-dark text-capitalize {{ app()->getLocale()=='ar'?'px-3':'ps-3' }}">{{ $title??'' }} </h4>
           <hr />
          <div class="row">
            <div class="col-2">
            @if($pagesRules['create'] &&( !in_array($resourceName ,['Captains','Users','CaptainDocument'])))
             <a href="{{ url(app('dash')['DASHBOARD_PATH'].'/resource/'.$resourceName.'/new') }}" class="btn btn-info"><i class="fa fa-plus"></i> {{ __('dash::dash.create') }}</a>
            @endif
          </div>
          <div class="col-3">
          @include('dash::resource.actions.index_actions')
          </div>

          @if($multiSelectRecord && method_exists($resource['model'],'trashed'))
          <div class="col-3">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="withTrashed" value="yes" id="withTrashed{{ $resourceName }}">
            <label class="form-check-label mb-0 ms-3" for="withTrashed{{ $resourceName }}">{{ __('dash::dash.withTrashed') }}</label>
          </div>
          </div>
          @endif

          </div>
          {{-- <div class="table-responsive p-0"> --}}
            <table class="table d-table table-bordered table-striped table-hover align-items-center mb-0 datatable_resource{{ $resourceName }}" id="datatable_resource{{ $resourceName }}" style="width:100%;" >
              <thead>
                <tr>
                  @if($multiSelectRecord)
                  <th class="text-secondary text-xs col-1 center">
                      <input class="form-check-input p-2 border selectAll{{ $resourceName }}" type="checkbox" id="selectAll{{ $resourceName }}">
                    {{-- <div class="form-check form-switch d-flex align-items-center mb-3 is-filled">
                    </div> --}}
                  </th>
                  @endif

                  @foreach($fields as $key=>$value)
                  @if($value['show_rules']['showInIndex'])
                  <th class="text-secondary text-xs font-weight-bolder ">
                    {{ $value['name'] }}
                  </th>
                  @endif
                  @endforeach
                  <th>@lang('dash::dash.action')</th>
                </tr>
              </thead>
            </table>
         {{--  </div> --}}
        </div>
      </div>
    </div>
  </div>
</div>

@include('dash::resource.datatable')
@endsection
