$('.dataTables_filter input').addClass('border ');
$('.dataTables_filter input').attr('placeholder','@lang('dash::dash.search')');
$('.dt-buttons').addClass('btn-sm').removeClass('btn-group').show();
$('.buttons-excel').html(`
<span><i class="fa-solid fa-file-excel"></i> {{ __('dash::dash.excel') }}</span>
`).addClass('bg-gradient-success');
$('.buttons-csv').html(`
<span><i class="fa-solid fa-file-excel"></i> {{ __('dash::dash.csv') }} </span>
`).addClass('bg-gradient-primary');
$('.buttons-print').html(`
<span><i class="fa fa-print"></i> {{ __('dash::dash.print') }} </span>
`).addClass('bg-gradient-info');
$('.buttons-pdf').html(`
<span><i class="fa-solid fa-file-pdf"></i> {{ __('dash::dash.pdf') }} </span>
`).addClass('bg-gradient-danger');
$('.buttons-copy').html(`
<span><i class="fa fa-copy"></i> {{ __('dash::dash.copy') }} </span>
`).addClass('bg-gradient-info');
$('.buttons-collection').addClass('bg-gradient-dark');
@if(app()->getLocale()=='ar')
$('.dataTables_filter').addClass('float-right');
//$('.dataTables_filter input').addClass('ps-6');
@endif