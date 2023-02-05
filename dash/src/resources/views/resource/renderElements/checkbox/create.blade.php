@if($field['show_rules']['showInCreate'])
@php
$default = isset($field['default'])?$field['default']:null;
$value = isset($field['trueVal'])?$field['trueVal']:true;
@endphp
<div class="col-{{ isset($field['columnWhenCreate'])?$field['columnWhenCreate']:$field['column'] }}">
	<div class="form-check my-3 box_{{ $field['attribute'] }}">

		<input class="form-check-input
		{{ $field['attribute'] }} {{ $errors->has($field['attribute'])?'is-invalid':'' }}"

		{{ isset($field['readonly']) && $field['readonly']?'readonly':'' }}
		{{ isset($field['checked']) && $field['checked']?'checked':'' }}


		name="{{ $field['attribute'] }}"
		type="checkbox" value="{{ old($field['attribute'],$value) }}"
		{{ $default == $value?'checked':'' }}
		id="{{ $field['attribute'] }}">
		<label class="form-check-label text-dark" for="{{ $field['attribute'] }}">
			{{ $field['name'] }}
			@if(isset($field['rule']) && in_array('required',$field['rule']) || isset($field['ruleWhenCreate']) && in_array('required',$field['ruleWhenCreate']))
			<span class="text-danger text-sm">*</span>
			@endif
		</label>
	</div>
	{!! isset($field['help'])?$field['help']:'' !!}
</div>
@endif