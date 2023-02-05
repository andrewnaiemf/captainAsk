@if($field['show_rules']['showInUpdate'])
@php
if(isset($field['valueWhenUpdate'])){
$value = $field['valueWhenUpdate'];
}elseif(isset($field['value'])){
$value = $field['value'];
}else{
$value = $model->{$field['attribute']};
}
@endphp
@if(isset($field['translatable']) && count($field['translatable']) > 0)
 @include('dash::resource.renderElements.ckeditor.update_translatable')
 @foreach($field['translatable'] as $key=>$value)
 @include('dash::resource.renderElements.ckeditor.ckeditor_js',[
	'editor'=>$key.'_'.$field['attribute'].'_content',
	'placeholder'=>isset($field['placeholder'])?$field['placeholder']:''
	])
 @endforeach
@else
<div class="col-{{ isset($field['columnWhenUpdate'])?$field['columnWhenUpdate']:$field['column'] }}">
	<div class="form-group my-3 box_{{ $field['attribute'] }}">
		<label for="{{ $field['attribute'] }}"
		class="text-dark text-capitalize">{{ $field['name'] }}
		@if(isset($field['rule']) && in_array('required',$field['rule']) || isset($field['ruleWhenUpdate']) && in_array('required',$field['ruleWhenUpdate']))
		<span class="text-danger text-sm">*</span>
		@endif
		</label>
		<textarea class="area_{{ $field['attribute'] }} {{ $field['attribute'] }}" name="{{ $field['attribute'] }}" id="{{ $field['attribute'] }}">{{ $value }}</textarea>
		{!! isset($field['help'])?$field['help']:'' !!}
		@error($field['attribute'])
		<p class="invalid-feedback">{{ $message }}</p>
		@enderror
	</div>
</div>
@include('dash::resource.renderElements.ckeditor.ckeditor_js',[
	'editor'=>$field['attribute'],
	'placeholder'=>isset($field['placeholder'])?$field['placeholder']:''
	])

@endif
@endif