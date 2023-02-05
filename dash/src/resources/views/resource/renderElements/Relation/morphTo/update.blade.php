@if($field['show_rules']['showInUpdate'])
@php
$selected = isset($field['selected'])?$field['selected']:null;
$morphName = $field['attribute'];
$selectedClass = get_class($model->{$field['attribute']});
$selectedClassName = resourceShortName($selectedClass);
@endphp
<div class="col-{{ isset($field['columnWhenUpdate'])?$field['columnWhenUpdate']:$field['column'] }}">
	<div class="form-group my-3 box_{{ $morphName }}">
		<label class="form-check-label text-dark" for="{{ $morphName }}">
			{{ $field['name'] }}
			@if(isset($field['rule']) && in_array('required',$field['rule']) || isset($field['ruleWhenUpdate']) && in_array('required',$field['ruleWhenUpdate']))
			<span class="text-danger text-sm">*</span>
			@endif
		</label>
		<select id="{{ $morphName }}" name="{{ $morphName }}" class="form-select ps-2 {{ strtolower($morphName) }} {{ $errors->has($morphName)?'is-invalid':'' }}" >
			<option selected value>{{ $field['name'] }}</option>
			@if(isset($field['types']))
			@foreach($field['types'] as $type)

			@php
			$morphTitle = $field['name']??resourceShortName($type::$model);
			@endphp
			<option value="{{ $type::$model }}"
				model_name="{{ resourceShortName($type::$model) }}"
				{{ $selected == $type::$model?'selected':'' }}
				{{ $selectedClass == $type::$model?'selected':'' }}
			>{{ $morphTitle }}</option>
			@endforeach
		</select>
		@error($morphName)
		<p class="invalid-feedback">{{ $message }}</p>
		@enderror
		@endif
	</div>
	{{-- Fetch Morph To Dropdown Select Start--}}
	@foreach($field['types'] as $resource)
	@php
	$morphNameModel = resourceShortName($resource::$model);

	$morphTitleModel = $resource::customName()??resourceShortName($resource::$model);
	@endphp

	<div class="form-group my-3 box_{{ $morphNameModel }} box_{{ $morphName }}_select {{ $errors->has($morphNameModel) || old($morphNameModel)?'':'d-none' }}">
		<label class="form-check-label text-dark" for="{{ $morphNameModel }}">
			{{ $morphTitleModel }}
			@if(isset($field['rule']) && in_array('required',$field['rule']) || isset($field['ruleWhenUpdate']) && in_array('required',$field['ruleWhenUpdate']))
			<span class="text-danger text-sm">*</span>
			@endif
		</label>
		<select id="{{ $morphNameModel }}" name="{{ $morphNameModel }}" class="form-select ps-2
{{ isset($field['hideIf']) && $field['hideIf']?'d-none':'' }}
		 {{ strtolower($morphNameModel) }} {{ $errors->has($morphNameModel)?'is-invalid':'' }}" >
			<option selected value>{{ $morphTitleModel }}</option>
			@foreach($resource::$model::all() as $model)
			<option value="{{ $model->id }}"
			 {{ old($morphNameModel) == $model->id?'selected':'' }}
			 >{{ $model->{$resource::$title} }}</option>
			@endforeach
		</select>
		{!! isset($field['help'])?$field['help']:'' !!}
		@error($morphNameModel)
		<p class="invalid-feedback">{{ $message }}</p>
		@enderror
		@if(method_exists($resource::$model, 'trashed'))
		<div class="mb-1 mt-1" style="width:40%">
		<div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="withTrashed{{ $morphName }}" value="yes" checked id="withTrashed{{ $morphName }}">
            <label class="form-check-label mb-0 ms-3" for="withTrashed{{ $morphName }}">{{ __('dash::dash.withTrashed') }}</label>
          </div>
		</div>
		@endif

	</div>
<script type="text/javascript">
$(document).ready(function(){
@if(method_exists($resource::$model, 'trashed'))
	function {{ $morphNameModel }}loadWithTrashed(model_value=null,model_name=null,withTrashed=null){
		var model_value =  model_value??$('.{{ $morphName }} option:selected').val();
		var model_name  =  model_name??$('.{{ $morphName }} option:selected').attr('model_name');
		var withTrashed = withTrashed??$('#withTrashed{{ $morphName }}').is(':checked')?true:false;
		$.ajax({
			url:'{{ url(app('dash')['DASHBOARD_PATH'].'/load/model') }}',
			dataType:'json',
			type:'post',
			data:{
				_token:'{{ csrf_token() }}',
				model_name:model_name,
				model_value:model_value,
				withTrashed:withTrashed,
				stringName:'{{ $resource::$title }}'
			},
			beforeSend: function(){
				$('.{{ $morphNameModel }}').prop('readonly',true).html('');
			},success: function(data){
				var selectedValue = '{{ $model->{$field['attribute']}->id }}';
				var options = '<option selected value>{{ $morphTitleModel }}</option>';
				$.each(data,function(key,value){
					if(selectedValue == key){
						var selected = 'selected';
					}else{
						var selected = '';
					}
					options += `<option value="`+key+`" `+selected+` >`+value+`</option>`;
				});
				$('.{{ $morphNameModel }}').prop('readonly',false).html(options);
			}
		});
	}
	// load withtrashed

		{{ $morphNameModel }}loadWithTrashed('{{ str_replace('\\','\\\\',$selectedClass) }}','{{ $selectedClassName }}',true);


	$(document).on('change','#withTrashed{{ $morphName }}',function(){
		{{ $morphNameModel }}loadWithTrashed();
	});
@endif

@include('dash::resource.renderElements.select2',[
	'element'=>'.'.$morphName,
	'attribute'=>$morphName
])


	{{ $morphName }}.on('select2:select',function(){
		var model_value =  $('.{{ $morphName }} option:selected').val();
		var model_name  =  $('.{{ $morphName }} option:selected').attr('model_name');

		$('.box_{{ $morphName }}_select').addClass('d-none');
		$('.box_'+model_name).removeClass('d-none');

	});

	$('.box_{{ $morphName }}_select').addClass('d-none');
	$('.box_{{ $selectedClassName }}').removeClass('d-none');
});
</script>
	@endforeach
	{{-- Fetch Morph To Dropdown Select End --}}
</div>


@endif