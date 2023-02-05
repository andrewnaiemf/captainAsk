@if($field['show_rules']['showInCreate'])
@php
if(isset($field['valueWhenCreate'])){
$value = $field['valueWhenCreate'];
}elseif(isset($field['value'])){
$value = $field['value'];
}else{
$value = $field['attribute'];
}

@endphp
<div class="col-{{ isset($field['columnWhenCreate'])?$field['columnWhenCreate']:$field['column'] }}">
	<div class="form-group my-3 box_{{ $field['attribute'] }}">
		<label for="{{ $field['attribute'] }}"
		class="text-dark text-capitalize">{{ $field['name'] }}
		@if(isset($field['rule']) && in_array('required',$field['rule']) || isset($field['ruleWhenCreate']) && in_array('required',$field['ruleWhenCreate']))
		<span class="text-danger text-sm">*</span>
		@endif
		</label>
		<input type="search"
		name="{{ $field['attribute'] }}"
		placeholder="{{ isset($field['placeholder'])?$field['placeholder']:$field['name'] }}"

		{{ isset($field['textAlign'])?'style="text-align:'.$field['textAlign'].'"':'' }}
		{{ isset($field['readonly'])?'readonly':'' }}
		{{ isset($field['disabled'])?'disabled':'' }}

		{{ isset($field['disabledIf']) && $field['disabledIf']?'disabled':'' }}
		{{ isset($field['readOnlyIf']) && $field['readOnlyIf']?'readonly':'' }}

		class="form-control border
{{ isset($field['hideIf']) && $field['hideIf']?'d-none':'' }}

		p-2 {{ $field['attribute'] }} {{ $errors->has($field['attribute'])?'is-invalid':'' }}"
		id="{{ $field['attribute'] }}"
		value="{{ $value }}" />
		{!! isset($field['help'])?$field['help']:'' !!}
		@error($field['attribute'])
		<p class="invalid-feedback">{{ $message }}</p>
		@enderror
	</div>
</div>
@endif