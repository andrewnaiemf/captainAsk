<div class="row">
  <div class="col-3">


    @if($pagesRules['create'] && !empty($unsetFields))
    <a href="{{ url(app('dash')['DASHBOARD_PATH'].'/resource/'.$resourceName.'/new?relationField['.$unsetFields['attribute'].']='.$loadByResourceRelation['record_id']) }}" class="btn btn-info"><i class="fa fa-plus"></i> {{ __('dash::dash.create') }}</a>
    @endif
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
<table class="table d-table table-bordered table-striped table-hover align-items-center mb-0" id="datatable_resource{{ $resourceName }}" style="width:100%;" data-turbolinks="false" >
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
@include('dash::resource.relation_datatable.sub_datatable')