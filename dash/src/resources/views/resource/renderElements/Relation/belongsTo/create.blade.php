@if($field['show_rules']['showInCreate'])
@php
$selected = isset($field['selected'])?$field['selected']:null;
$belongsToAttr = $field['attribute'];

$checkIfNeedChildData = isset($field['fromParent']) && isset($field['fromParent']['parent']) && isset($field['fromParent']['column']);

@endphp
<div class="col-{{ isset($field['columnWhenCreate'])?$field['columnWhenCreate']:$field['column'] }}">
	 @php
 	 	$resource = $field['resource'];
	 @endphp
	{{-- Fetch Morph To Dropdown Select Start--}}
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

		<select id="{{ $belongsToAttr }}"

		{{-- search Select2 Query Start --}}
 model="{{ $resource::$model }}"
 query="{{ isset($field['query'])?
		(new SuperClosure\Serializer())->serialize($field['query']):null }}"
 searchKey="{{ $resource::$title }}"
 column = "{{ isset($field['fromParent']) && isset($field['fromParent']['parent']) && isset($field['fromParent']['column'])?$field['fromParent']['column']:null  }}"
 parent="{{ isset($field['fromParent']) && isset($field['fromParent']['parent'])?$field['fromParent']['parent']:null }}"

 		{{-- search Select2 Query End --}}

		name="{{ strtolower($belongsToAttr) }}" class="form-select ps-2 {{ strtolower($belongsToAttr) }} {{ $errors->has($belongsToAttr)?'is-invalid':'' }}"
			{{ $checkIfNeedChildData?'disabled':'' }}
			>
			{{-- <option selected value>{{ $placeholder }}</option>
			@if(!$checkIfNeedChildData)
			@foreach($query as $model)
			<option value="{{ $model->id }}"
			 {{ old($belongsToAttr) == $model->id?'selected':'' }}
			 >{{ $model->{$resource::$title} }}</option>
			@endforeach
			@endif --}}
		</select>
		{!! isset($field['help'])?$field['help']:'' !!}
		@error($belongsToModal)
		<p class="invalid-feedback">{{ $message }}</p>
		@enderror

		@if(!isset($field['fromParent']) && !isset($field['fromParent']['parent']) && !isset($field['fromParent']['column']))
		@if(method_exists($resource::$model, 'trashed'))
		<div class="mb-1 mt-1" style="{{ !isset($field['fromParent']['column']) && !isset($field['columnWhenCreate']) && !isset($field['column'])  ?'width:40%':'' }}">
		<div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="withTrashed{{ $belongsToAttr }}" value="yes" {{ old('withTrashed'.$belongsToAttr)?'checked':'' }} id="withTrashed{{ $belongsToAttr }}">
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
				$('.{{ $belongsToModal }}').prop('disabled',true).html('');
			},success: function(data){
				var selectedValue = '{{ old($belongsToModal) }}';
				var options = '<option selected value>{{ $placeholder }}</option>';

				$.each(data,function(key,value){
					if(selectedValue == key){
						var selected = 'selected';
					}else{
						var selected = '';
					}
					options += `<option value="`+key+`" `+selected+` >`+value+`</option>`;
				});


				$('.{{ strtolower($belongsToModal) }}').prop('disabled',false).html(options);

				@include('dash::resource.renderElements.select2',[
					'element'=>'.'.strtolower($belongsToModal) ,
					'attribute'=>strtolower($belongsToModal),
					'dynamic'=>true
				])

			}
		});
 }

@if($checkIfNeedChildData)

@include('dash::resource.renderElements.select2',[
	'element'=>'.'.$field['fromParent']['parent'],
	'attribute'=>$field['fromParent']['parent'],
	'dynamic'=>true
])

	{{ $field['fromParent']['parent'] }}.on('select2:select',function(e){

	var parent = '.{{ $field['fromParent']['parent'] }}';
	var parentValue = $(parent+' option:selected').val();
		if(parentValue != ''){
			// load Data By Parent
			get_{{ $belongsToAttr }}_data(parentValue,'{{ $field['fromParent']['column'] }}');
		}else{
			// $('.{{ $belongsToAttr }}').prop('disabled',true);
		}

	});

@if(!empty(request('relationField')) && !empty(request('relationField.'.$field['fromParent']['parent'])))
	// load Data By Parent
			get_{{ $belongsToAttr }}_data('{{ request('relationField.'.$field['fromParent']['parent']) }}','{{ $field['fromParent']['column'] }}');
@endif

@else

@include('dash::resource.renderElements.select2',[
	'element'=>'.'.$belongsToAttr,
	'attribute'=>$belongsToAttr,
	// 'parent'=>$field['fromParent']['parent'],
	// 'column'=>$field['fromParent']['column'],
	'dynamic'=>true
])

@endif

 // Here can childe remove old elements or selected values
 @if(isset($field['child']))



 @include('dash::resource.renderElements.select2',[
	'element'=>'.'.$belongsToAttr,
	'attribute'=>$belongsToAttr,

	'dynamic'=>true
])

  {{ $belongsToAttr }}.on('select2:select',function(){
  	 @foreach($field['child'] as $child)
  	  $('.{{ $child }}').prop('disabled',true).html('');
  	 @endforeach
  });
 @endif


@if(method_exists($resource::$model, 'trashed'))
	// load withtrashed
	@if(old('withTrashed'.$belongsToAttr))
		get_{{ $field['attribute'] }}_data();
	@endif

	$(document).on('change','#withTrashed{{ $belongsToAttr }}',function(){
		get_{{ $field['attribute'] }}_data();
	});
@endif



});
</script>
	{{-- Fetch Morph To Dropdown Select End --}}
</div>


@endif