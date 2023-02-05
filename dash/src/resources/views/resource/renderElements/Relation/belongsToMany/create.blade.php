@if($field['show_rules']['showInCreate'])
@php
$selected = isset($field['selected'])?$field['selected']:null;
$belongsToManyName = $field['attribute'];
@endphp
<div class="col-{{ isset($field['columnWhenCreate'])?$field['columnWhenCreate']:$field['column'] }}">
	 @php
 	 	$resource = $field['resource'];
	 @endphp
	{{-- Fetch BelongsToMany To Dropdown Select Start --}}
	@php
	$belongsToManyModel = resourceShortName($resource::$model);
	$belongsToManyTitle = $field['name']??resourceShortName($resource::$model);
	@endphp

	<div class="form-group my-3 box_{{ $belongsToManyModel }} {{ $errors->has($belongsToManyModel)?'is-invalid':'' }}">
		<label class="form-check-label text-dark" for="{{ $belongsToManyModel }}">
			{{ $belongsToManyTitle }}
			@if(isset($field['rule']) && in_array('required',$field['rule']) || isset($field['ruleWhenCreate']) && in_array('required',$field['ruleWhenCreate']))
			<span class="text-danger text-sm">*</span>
			@endif
		</label>
		@php
		 $selectName = strtolower($belongsToManyName);
		@endphp
		<select id="{{ $selectName }}"
    	{{-- search Select2 Query Start --}}
	 	{{-- model="{{ $resource::$model }}"
	 	query="{{ isset($field['query'])?
		(new SuperClosure\Serializer())->serialize($field['query']):null }}"
 searchKey="{{ $resource::$title }}" --}}
		{{-- search Select2 Query End --}}
		 multiple
		 name="{{ $selectName }}[]" class="form-select ps-2 {{ strtolower($belongsToManyModel) }} {{ $errors->has($belongsToManyModel)?'is-invalid':'' }}" >
			@php
			if(isset($field['query'])){
				$query  = $field['query']($resource::$model)->get();
			}else{
				$query  = $resource::$model::all();
			}
			@endphp
			@foreach($query as $model)
			<option value="{{ $model->id }}"
			 {{ !empty(old($selectName)) &&
			 	in_array($model->id,old($selectName)) ?'selected':'' }}
			 >{{ $model->{$resource::$title} }}</option>
			@endforeach
		</select>
		{!! isset($field['help'])?$field['help']:'' !!}
		@error(strtolower($belongsToManyModel))
		<p class="invalid-feedback">{{ $message }}</p>
		@enderror

		@if(method_exists($resource::$model, 'trashed'))
		<div class="mb-1 mt-1" style="{{  !isset($field['columnWhenCreate']) && !isset($field['column'])   ?'width:40%':'' }}">
		<div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="withTrashed{{ $belongsToManyName }}" value="yes" {{ old('withTrashed'.$belongsToManyName)?'checked':'' }} id="withTrashed{{ $belongsToManyName }}">
            <label class="form-check-label mb-0 ms-3" for="withTrashed{{ $belongsToManyName }}">{{ __('dash::dash.withTrashed') }}</label>
          </div>
		</div>
		@endif

	</div>

<script type="text/javascript">
$(document).ready(function(){
@if(method_exists($resource::$model, 'trashed'))
	function {{ $belongsToManyName }}loadWithTrashed(){
		var model_value =  '{{ str_replace('\\','\\\\',$resource::$model)  }}';
		var model_name  =  '{{ $belongsToManyModel }}';
		var withTrashed = $('#withTrashed{{ $belongsToManyName }}').is(':checked')?true:false;
		$.ajax({
			url:'{{ url(app('dash')['DASHBOARD_PATH'].'/load/model') }}',
			dataType:'json',
			type:'post',
			data:{
				_token:'{{ csrf_token() }}',
				model_name:model_name,
				model_value:model_value,
				withTrashed:withTrashed,
				locale:'{{ app()->getLocale() }}',
				stringName:'{{ $resource::$title }}'
			},
			beforeSend: function(){
				$('.{{ strtolower($belongsToManyModel) }}').prop('readonly',true).html('');
			},success: function(data){
				var selectedValue = '{{ old($belongsToManyModel) }}';
				var options = '';

				$.each(data,function(key,value){
					if(selectedValue == key){
						var selected = 'selected';
					}else{
						var selected = '';
					}
					options += `<option value="`+key+`" `+selected+` >`+value+`</option>`;
				});

				$('.{{ strtolower($belongsToManyModel) }}').prop('readonly',false).html(options);
// reSelected multiple valeu from old data
 @if(!empty(old($selectName)))
 var Values = new Array();
  @foreach(old($selectName) as $select)
		Values.push("{{ $select }}");
  @endforeach
  $("#{{ $belongsToManyModel }}").val(Values).trigger("change");
 @endif
			}
		});
	}
	// load withtrashed
	@if(old('withTrashed'.$belongsToManyName))
		{{ $belongsToManyName }}loadWithTrashed();
	@endif

	$(document).on('change','#withTrashed{{ $belongsToManyName }}',function(){
		{{ $belongsToManyName }}loadWithTrashed();
	});
@endif


});
/////////////////////////////////////////
$('#{{ $selectName }}').select2({
 	 theme: "classic",
	 placeholder: '{{ $belongsToManyTitle }}',
	 allowClear: true
  });

	// reSelected multiple valeu from old data
  @if(!empty(old($selectName)))
   var Values = new Array();
   @foreach(old($selectName) as $select)
	  Values.push("{{ $select }}");
   @endforeach
  $("#{{ $selectName }}").val(Values).trigger("change");
 @endif
</script>
	{{-- Fetch BelongsToMany To Dropdown Select End --}}
</div>
@endif