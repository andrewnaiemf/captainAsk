<div class="{{ app()->getLocale()=='ar'?'':'' }} col-5 pe-md-3 d-flex align-items-center">
  <div class="input-group input-group-outline">
    <label class="form-label">@lang('dash::dash.search')...</label>
    <input type="text" name="search" class="form-control search">
  </div>
  <div id="output_main_search" class="dropdown-menu border border-top-0 border-dark p-2" style="width: 34%;max-height: 300px;overflow: auto;position: absolute;top: 59px;">

  </div>
</div>

@push('js')
<script type="text/javascript">
$(document).ready(function(){
  var keytimer;
  $(document).on('keydown keyup','.search',function(){
      $("#output_main_search").empty().removeClass('show');
       clearTimeout(keytimer);
      keytimer = setTimeout(doSearch, 500);
  });

function doSearch() {
  var search = $('.search').val();
  if(search != ''){
  $.ajax({
    url:'{{ url(app('dash')['DASHBOARD_PATH'].'/main/search') }}',
    dataType:'html',
    type:'post',
    data:{_token:'{{ csrf_token() }}',search:search},
    beforeSend:function(){
      $('#output_main_search').addClass('show');
      $("#output_main_search").html(`<center><span class="spinner-border text-primary" role="status"></span></center>`);
    },success: function(data){
      $("#output_main_search").html(data).css('height: 195px;');
    }
  });
  }
}

$(document).on('click','body',function(){
 $("#output_main_search").empty().removeClass('show');
});

});
</script>
@endpush