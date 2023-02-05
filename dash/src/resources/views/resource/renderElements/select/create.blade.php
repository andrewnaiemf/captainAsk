@if($field['show_rules']['showInCreate'])
@php
$selected = isset($field['selected'])?$field['selected']:null;
@endphp
<div class="col-{{ isset($field['columnWhenCreate'])?$field['columnWhenCreate']:$field['column'] }}">
	<div class="form-group my-3 box_{{ $field['attribute'] }}">
		<label class="form-check-label text-dark" for="{{ $field['attribute'] }}">
			{{ $field['name'] }}
			@if(isset($field['rule']) && in_array('required',$field['rule']) || isset($field['ruleWhenCreate']) && in_array('required',$field['ruleWhenCreate']))
			<span class="text-danger text-sm">*</span>
			@endif
		</label>
		<select id="{{ $field['attribute'] }}"
		{{ isset($field['disabled'])?'disabled':'' }}
		 name="{{ $field['attribute'] }}" class="form-select ps-2 {{ $field['attribute'] }} {{ $errors->has($field['attribute'])?'is-invalid':'' }}" >
			<option selected disabled value>{{ $field['name'] }}</option>
			@if(isset($field['options']))
			@foreach($field['options'] as $key=>$value)
			<option value="{{ $key }}"
			{{ $selected == $key?'selected':'' }}
			{{ old($field['attribute']) == $key?'selected':'' }}

			>{{ $value }}</option>
			@endforeach
			@endif
		</select>
		{!! isset($field['help'])?$field['help']:'' !!}
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
@include('dash::resource.renderElements.select2',[
	'element'=>'.'.$field['attribute'],
	'attribute'=>$field['attribute']
])
});
</script>
@endif