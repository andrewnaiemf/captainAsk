<div class="row d-none filters{{ $resourceName }}">
  @foreach($filters as $filter)
  @foreach($filter['options'] as $key=>$value)
  @php
  $column = $key;
  $options = $value;
  $field = searchInFields($column,$fields);
  if($field === false){
    $field['name']=$column;
  }
  @endphp
  <div class="col-3">
    <label for="{{ $column }}">{{ $filter['label']??$field['name'] }}</label>
    <select attribute="{{ $column }}" id="{{ $column }}" name="{{ $column }}" class="form-select  {{ app()->getLocale() == 'en'?'ps-2':'' }} filter{{ $resourceName }}">
      <option value="" disabled selected>{{ $filter['label']??$field['name'] }}</option>
      <option value="">{{ __('dash::dash.all') }}</option>
      @foreach($options as $optkey=>$optvalue)
      <option value="{{ $optkey }}">{{ $optvalue }} </option>
      @endforeach
    </select>
  </div>
  @endforeach
  @endforeach
</div>