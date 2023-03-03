@if($field['show_rules']['showInCreate'])
@php
$selected = isset($field['selected'])?$field['selected']:null;
$belongsToAttr = $field['attribute'];
$checkIfNeedChildData = isset($field['fromParent']) && isset($field['fromParent']['parent']) && isset($field['fromParent']['column']);
@endphp
<div class="col-{{ isset($field['columnWhenUpdate'])?$field['columnWhenUpdate']:$field['column'] }}">
	@php
	$resource = $field['resource'];
	@endphp
	{{-- Fetch Belongs To Dropdown Select Start--}}
	@php
	$belongsToModal = resourceShortName($resource::$model);
	$belongsToTitle = $field['name']??resourceShortName($resource::$model);
	$placeholder = isset($field['placeholder'])?$field['placeholder']:$field['name'];
	@endphp
	<div class="form-group my-3 box_{{ $belongsToAttr }}">
		<label class="form-check-label text-dark" for="{{ $belongsToAttr }}">
			{{ $belongsToTitle }}
			@if(isset($field['rule']) && in_array('required',$field['rule']) || isset($field['ruleWhenCreate']) && in_array('required',$field['ruleWhenCreate']))
			<span class="text-danger text-sm">*</span>
			@endif
		</label>
@if(!$checkIfNeedChildData)
			@php
			if(isset($field['query'])){
				$query  = $field['query']($resource::$model)->get();
			}else{
				$query  = $resource::$model::all();
			}
			@endphp
@endif
		<select id="{{ $belongsToAttr }}"

		{{-- search Select2 Query Start --}}
 model="{{ $resource::$model }}"
 query="{{ isset($field['query'])?
		(new SuperClosure\Serializer())->serialize($field['query']):null }}"
 searchKey="{{ $resource::$title }}"

 column = "{{ isset($field['fromParent']) && isset($field['fromParent']['parent']) && isset($field['fromParent']['column'])?$field['fromParent']['column']:null  }}"
 parent="{{ isset($field['fromParent']) && isset($field['fromParent']['parent'])?$field['fromParent']['parent']:null }}"

		{{-- search Select2 Query End --}}

		name="{{ strtolower($belongsToAttr) }}" class="form-select ps-2
			{{ isset($field['hideIf']) && $field['hideIf']?'d-none':'' }}
			{{ strtolower($belongsToAttr) }} {{ $errors->has($belongsToAttr)?'is-invalid':'' }}"
			{{ $checkIfNeedChildData?'disabled':'' }}
			>
			<option selected value>{{ $placeholder }}</option>
			@if(!$checkIfNeedChildData)
			@php
			if(isset($field['query'])){
				$query  = $field['query']($resource::$model)->limit(50);
			}else{
				$query  = $resource::$model::limit(50);
			}
			$query = $query->where(function($q)use($model,$belongsToAttr,$belongsToModal){
				if(!empty($model->{$belongsToAttr})){
					$q->where('id',$model->{$belongsToAttr}->id);
				}elseif(!empty($model->{$belongsToModal}->id)){
					$q->where('id',$model->{$belongsToModal}->id);
				}
			})->get();
			@endphp
			@foreach($query as $rmodel)
			<option value="{{ $rmodel->id }}"
				@if(!empty($model->{$belongsToAttr}))
				{{ $model->{$belongsToAttr}->id == $rmodel->id?'selected':'' }}
				@elseif(!empty($model->{$belongsToModal}->id))
				{{ $model->{$belongsToModal}->id == $rmodel->id?'selected':'' }}
				@endif
			>{{ $rmodel->{$resource::$title} }}</option>
			@endforeach
			@endif
		</select>
		{!! isset($field['help'])?$field['help']:'' !!}
		@error($belongsToAttr)
		<p class="invalid-feedback">{{ $message }}</p>
		@enderror
		@if(!isset($field['fromParent']) && !isset($field['fromParent']['parent']) && !isset($field['fromParent']['column']))
		@if(method_exists($resource::$model, 'trashed'))
		<div class="mb-1 mt-1" style="{{ !isset($field['fromParent']['column']) && !isset($field['columnWhenUpdate']) && !isset($field['column'])   ?'width:40%':'' }}">
			<div class="form-check form-switch">
				<input class="form-check-input" type="checkbox" name="withTrashed{{ $belongsToAttr }}" value="yes" checked id="withTrashed{{ $belongsToAttr }}">
				<label class="form-check-label mb-0 ms-3" for="withTrashed{{ $belongsToAttr }}">{{ __('dash::dash.withTrashed') }}</label>
			</div>
		</div>
		@endif
		@endif
	</div>
	<script type="text/javascript">
	$(document).ready(function(){
	function get_{{ $belongsToAttr }}_data(parent=null,column=null){
		var model_value =  '{{ str_replace('\\','\\\\',$resource::$model)  }}';
			var model_name  =  '{{ $belongsToModal }}';
			var withTrashed = $('#withTrashed{{ $belongsToAttr }}').is(':checked')?true:false;
			$.ajax({
				url:'{{ url(app('dash')['DASHBOARD_PATH'].'/load/model') }}',
				dataType:'json',
				type:'post',
				data:{
					_token:'{{ csrf_token() }}',
					parent:parent,
					column:column,
					model_name:model_name,
					model_value:model_value,
					withTrashed:withTrashed,
					stringName:'{{ $resource::$title }}'
				},
				beforeSend: function(){
					$('.{{ $belongsToAttr }}').prop('disabled',true).html('');
				},success: function(data){
					var selectedValue = '{{ !empty($model->{$belongsToAttr}->id)? $model->{$belongsToAttr}->id:'' }}';
					var options = '<option selected value>{{ $placeholder }}</option>';
					$.each(data,function(key,value){
						if(selectedValue == key){
							var selected = 'selected';
						}else{
							var selected = '';
						}
						options += `<option value="`+key+`" `+selected+` >`+value+`</option>`;
					});
					$('.{{ strtolower($belongsToAttr) }}').prop('disabled',false).html(options);

					@include('dash::resource.renderElements.select2',[
						'dynamic'=>true,
						'element'=>'.'.strtolower($belongsToAttr) ,
						'attribute'=>strtolower($belongsToAttr)
					])
				}
			});
	}
	@if($checkIfNeedChildData)
@include('dash::resource.renderElements.select2',[
	'dynamic'=>true,
	'element'=>'.'.$field['fromParent']['parent'] ,
	'attribute'=>$field['fromParent']['parent']
])

	{{ $field['fromParent']['parent'] }}.on('select2:select',function(){
			var parentValue = $(this,' option:selected').val();
			if(parentValue != ''){
				// load Data By Parent
				get_{{ $belongsToAttr }}_data(parentValue,'{{ $field['fromParent']['column'] }}');
			}
	});
	@if(!empty(request('relationField')) && !empty(request('relationField.'.$field['fromParent']['parent'])))
		// load Data By Parent
		get_{{ $belongsToAttr }}_data('{{ request('relationField.'.$field['fromParent']['parent']) }}','{{ $field['fromParent']['column'] }}');
	@endif
	// autoload data on edit  Start
	@php
	$parent_column = $field['fromParent']['column'];
	$parent_value  = $model->{$field['fromParent']['column']};
	@endphp
	get_{{ $belongsToAttr }}_data('{{ $parent_value }}','{{ $parent_column }}');
	// autoload data on edit  End
	@endif
	// Here can childe remove old elements or selected values
	@if(isset($field['child']))
@include('dash::resource.renderElements.select2',[
	'dynamic'=>true,
	'element'=>'.'.$belongsToAttr ,
	'attribute'=>$belongsToAttr
])

	{{ $belongsToAttr }}.on('select2:select',function(){
		@foreach($field['child'] as $child)
		$('.{{ $child }}').prop('disabled',true).html('');
		@endforeach
	});




	@endif


@if(!$checkIfNeedChildData)
	@include('dash::resource.renderElements.select2',[
		'dynamic'=>true,
		'element'=>'.'.$belongsToAttr,
		'attribute'=>$belongsToAttr
	])
@endif

	@if(method_exists($resource::$model, 'trashed'))
		// load withtrashed
		@if(old('withTrashed'.$belongsToAttr))
			get_{{ $belongsToAttr }}_data();
		@endif
		$(document).on('change','#withTrashed{{ $belongsToAttr }}',function(){
			get_{{ $belongsToAttr }}_data();
		});
	@endif
	});
	</script>
	{{-- Fetch Belongs To Dropdown Select End --}}
</div>
@endif
