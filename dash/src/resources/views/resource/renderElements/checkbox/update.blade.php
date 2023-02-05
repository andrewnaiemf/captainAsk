@if($field['show_rules']['showInUpdate'])
@php
$default = isset($field['default'])?$field['default']:$model->{$field['attribute']};
$value = isset($field['trueVal'])?$field['trueVal']:$model->{$field['attribute']};
@endphp
<div class="col-{{ isset($field['columnWhenUpdate'])?$field['columnWhenUpdate']:$field['column'] }}">
	<div class="form-check my-3 box_{{ $field['attribute'] }}">

		<input class="form-check-input
		{{ isset($field['hideIf']) && $field['hideIf']?'d-none':'' }}
		{{ $field['attribute'] }} {{ $errors->has($field['attribute'])?'is-invalid':'' }}"

		{{ isset($field['checkedIf']) && $field['checkedIf']?'checked':'' }}
		{{ isset($field['readOnlyIf']) && $field['readOnlyIf']?'readonly':'' }}

		name="{{ $field['attribute'] }}"
		{{ isset($field['readonly'])?'readonly':'' }}
		{{ isset($field['disabled'])?'disabled':'' }}
		type="checkbox" value="{{ $value }}" {{ $default == $value?'checked':'' }} id="{{ $field['attribute'] }}">
		<label class="form-check-label text-dark" for="{{ $field['attribute'] }}">
			{{ $field['name'] }}
			@if(isset($field['rule']) && in_array('required',$field['rule']) || isset($field['ruleWhenUpdate']) && in_array('required',$field['ruleWhenUpdate']))
			<span class="text-danger text-sm">*</span>
			@endif
		</label>
		{!! isset($field['help'])?$field['help']:'' !!}
	</div>
</div>
@endif

