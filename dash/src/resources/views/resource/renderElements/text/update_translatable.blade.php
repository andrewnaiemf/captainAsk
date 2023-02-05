@php
$transIndex = 0;
@endphp
<div class="col-{{ isset($field['columnWhenUpdate'])?$field['columnWhenUpdate']:$field['column'] }}"></div>
<ul class="nav nav-tabs" id="myTab{{ $field['attribute'] }}" role="tablist">
	@foreach($field['translatable'] as $key=>$value)
@php
$inputName = $key.'['.$field['attribute'].']';
$inputID = $key.'_'.$field['attribute'];
@endphp
	<li class="nav-item" role="tabinput">
		<button class="nav-link {{ $transIndex==0?'active':'' }}" id="{{ $inputID }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $inputID }}" type="button" role="tab" aria-controls="{{ $inputID }}" aria-selected="true">{{ $value }}</button>
	</li>
	@php $transIndex++; @endphp
	@endforeach
</ul>
<div class="tab-content" id="myTab{{ $field['attribute'] }}Content">
	@php $contenttransIndex = 0; @endphp
	@foreach($field['translatable'] as $key=>$value)
@php
$inputName = $key.'['.$field['attribute'].']';
$inputID = $key.'_'.$field['attribute'];
@endphp
	<div class="tab-pane fade {{ $contenttransIndex==0?'show active':'' }}" id="{{ $inputID }}" role="tabpanel" aria-labelledby="{{ $inputID }}-tab">


		<div class="col-12">
			<div class="form-group my-3 box_{{ $field['attribute'] }}">
				<label for="{{ $inputID }}"
					class="text-dark text-capitalize">{{ $field['name'] }}
					@if(isset($field['rule']) && in_array('required',$field['rule']) || isset($field['ruleWhenCreate']) && in_array('required',$field['ruleWhenCreate']))
					<span class="text-danger text-sm">*</span>
					@endif
				</label>
				<input type="text"
				name="{{ $key }}[{{ $field['attribute'] }}]"
				placeholder="{{ isset($field['placeholder'])?$field['placeholder']:$field['name'] }}"
				{{ isset($field['textAlign'])?'style="text-align:'.$field['textAlign'].'"':'' }}
				{{ isset($field['readonly'])?'readonly':'' }}
				{{ isset($field['disabled'])?'disabled':'' }}
				class="form-control border p-2 {{ $inputID }} {{ $errors->has($inputName)?'is-invalid':'' }}"
				id="{{ $inputName }}"
				value="{{ method_exists($model,'translate')? $model->translate($key)->{$field['attribute']}:$model->{$field['attribute']} }}" />
				{!! isset($field['help'])?$field['help']:'' !!}
				@error($inputName)
				<p class="invalid-feedback is-invalid">{{ $message }}</p>
				@enderror
			</div>
		</div>
	</div>
	@php $contenttransIndex++; @endphp
	@endforeach
</div>

