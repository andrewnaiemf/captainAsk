@if($field['show_rules']['showInCreate'])
<div class="col-{{ isset($field['columnWhenCreate'])?$field['columnWhenCreate']:$field['column'] }}">
	<div class="form-group my-3 box_{{ $field['attribute'] }}">
		<label for="{{ $field['attribute'] }}"
		class="text-dark text-capitalize">{{ $field['name'] }}
		@if(isset($field['rule']) && in_array('required',$field['rule']) || isset($field['ruleWhenCreate']) && in_array('required',$field['ruleWhenCreate']))
		<span class="text-danger text-sm">*</span>
		@endif
		</label>
		<input type="file"
		name="{{ $field['attribute'] }}"
		accept="{{ isset($field['accept'])?implode(',',$field['accept']):'' }}"
		placeholder="{{ isset($field['placeholder'])?$field['placeholder']:$field['name'] }}"
		{{ isset($field['textAlign'])?'style="text-align:'.$field['textAlign'].'"':'' }}
		{{ isset($field['readonly'])?'readonly':'' }}
		{{ isset($field['disabled'])?'disabled':'' }}

		class="form-control border p-2
		{{ isset($field['hideIf']) && $field['hideIf']?'d-none':'' }}
		{{ $field['attribute'] }} {{ $errors->has($field['attribute'])?'is-invalid':'' }}"
		id="{{ $field['attribute'] }}" />
		@error($field['attribute'])
		<p class="invalid-feedback">{{ $message }}</p>
		@enderror
	</div>
</div>
@endif