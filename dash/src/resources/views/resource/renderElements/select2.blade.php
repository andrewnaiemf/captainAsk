var {{$attribute}} = $('{{$element}}').select2({
    @if(!empty($dropdownParent))
     dropdownParent: $('{{$dropdownParent}}') ,
    @endif
    theme: 'bootstrap-5',
    language: "{{ app()->getLocale() == 'ar'?'ar':'en' }}",
    dir: "{{ app()->getLocale() == 'ar'?'rtl':'ltr' }}",
    placeholder:'{{ __('dash::dash.choose_from_list') }}',
    width:'100%',
    allowClear: true
    @if(!empty($dynamic))
    ,ajax: {
      quietMillis: 10,
      cache: true,
      url: '{{ dash("select2/load/data") }}',
      type:'post',
      delay: 250,
      dataType: 'json',
      data: function (params) {
        var model = $('{{$element}}').attr('model');
        var queryStr = $('{{$element}}').attr('query');
        var searchKey = $('{{$element}}').attr('searchKey');
        var withTrashed = $('#withTrashed{{ $attribute }}').is(':checked')?true:false;

        var column = $('{{$element}}').attr('column');
        var parent = $('{{$element}}').attr('parent');
        if(parent != ''){
         var parent_value = $('.'+parent+' option:selected').val();
        }else{
         var parent_value = '';
        }

        var query = {
          _token:'{{csrf_token()}}',
          search: params.term,
          searchKey: searchKey,

          // parent && Child
          column: column,
          parent_value: parent_value,
          // parent && Child End

          model: model,
          queryStr: queryStr,
          withTrashed: withTrashed,
          page: params.page || 1
        };


        // Query parameters will be ?search=[term]&type=public
        return query;
      },
      processResults: function (data) {
        return data;
        // return {
        //   results: data // id and text
        // };
      },
      initSelection : function (element, callback) {
          var data = {id: 1, text: 'tesst'};
          callback(data);
      }//
    },
    minimumInputLength: 0,
    maximumSelectionLength: 0
    @endif
});

@if(isset($field['disabled']) && $field['disabled'] == 'disabled')
$('{{$element}}').select2("enable", false);
  $("{{$element}}").prop("disabled", true);

  $("{{$element}}").on("click", function () {
   $("{{$element}}").prop("disabled", true);
  });
@endif