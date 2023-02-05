@if($field['show_rules']['showInUpdate'])
@php
// $selected = isset($field['selected'])?$field['selected']:null;
$belongsToMany = $field['attribute'];
@endphp
<div class="col-{{ isset($field['columnWhenUpdate'])?$field['columnWhenUpdate']:$field['column'] }}">
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
			@if(isset($field['rule']) && in_array('required',$field['rule']) || isset($field['ruleWhenUpdate']) && in_array('required',$field['ruleWhenUpdate']))
			<span class="text-danger text-sm">*</span>
			@endif
		</label>
		@php
		 $selectName = strtolower($belongsToMany);
		 $fetchSelected = $model->{$selectName}->pluck('id')->toArray();
		@endphp
		<select id="{{ $selectName }}" multiple name="{{ $selectName }}[]" class="form-select ps-2

		{{ isset($field['hideIf']) && $field['hideIf']?'d-none':'' }}
		{{ strtolower($selectName) }} {{ $errors->has($selectName)?'is-invalid':'' }}" >
		  @php
			if(isset($field['query'])){
				$query  = $field['query']($resource::$model)->get();
			}else{
				$query  = $resource::$model::all();
			}
			@endphp
			@foreach($query as $rmodel)
			<option value="{{ $rmodel->id }}"
			 {{ !empty($model->{$selectName}->pluck('id')) &&
			 	in_array($rmodel->id,$fetchSelected) ?'selected':'' }}
			 >{{ $rmodel->{$resource::$title} }}</option>
			@endforeach
		</select>
		{!! isset($field['help'])?$field['help']:'' !!}
		@error($selectName)
		<p class="invalid-feedback">{{ $message }}</p>
		@enderror

		@if(method_exists($resource::$model, 'trashed'))
		<div class="mb-1 mt-1" style="{{  !isset($field['columnWhenUpdate']) && !isset($field['column'])   ?'width:40%':'' }}">
		<div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="withTrashed{{ $belongsToMany }}" value="yes" checked id="withTrashed{{ $belongsToMany }}">
            <label class="form-check-label mb-0 ms-3" for="withTrashed{{ $belongsToMany }}">{{ __('dash::dash.withTrashed') }}</label>
          </div>
		</div>
		@endif

	</div>

<script type="text/javascript">
$(document).ready(function(){
@if(method_exists($resource::$model, 'trashed'))
	function {{ $belongsToMany }}loadWithTrashed(){
		var model_value =  '{{ str_replace('\\','\\\\',$resource::$model)  }}';
		var model_name  =  '{{ $belongsToManyModel }}';
		var withTrashed = $('#withTrashed{{ $belongsToMany }}').is(':checked')?true:false;
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
				$('.{{ $selectName }}').prop('readonly',true).html('');
			},success: function(data){
				var selectedValue = '{{ old($belongsToManyModel) }}';
				var options = '';
				console.log(selectedValue);
				$.each(data,function(key,value){
					if(selectedValue == key){
						var selected = 'selected';
					}else{
						var selected = '';
					}
					options += `<option value="`+key+`" `+selected+` >`+value+`</option>`;
				});
				$('.{{ $selectName }}').prop('readonly',false).html(options);
// reSelected multiple valeu from old data
 @if(!empty($fetchSelected))
 var Values = new Array();
  @foreach($fetchSelected as $select)
		Values.push("{{ $select }}");
  @endforeach
  $("#{{ $selectName }}").val(Values).trigger("change");
 @endif
			}
		});
	}
	// auto load withtrashed
	{{ $belongsToMany }}loadWithTrashed();


$(document).on('change','#withTrashed{{ $belongsToMany }}',function(){
  {{ $belongsToMany }}loadWithTrashed();
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
  @if(!empty($fetchSelected))
   var Values = new Array();
   @foreach($fetchSelected as $select)
	  Values.push("{{ $select }}");
   @endforeach
  $("#{{ $selectName }}").val(Values).trigger("change");
 @endif
</script>
	{{-- Fetch BelongsToMany To Dropdown Select End --}}
</div>
@endif