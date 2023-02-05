{{-- <script type="text/javascript" src="{{ url('dashboard/assets/datatables/js/jquery.min.js') }}"></script> --}}
<script type="text/javascript" src="{{ url('dashboard/assets/datatables/js/datatables_lib.min.js') }}"></script>

<!-- Datatable CSS -->
<link rel="stylesheet" type="text/css" href="{{ url('dashboard/assets/datatables/css/datatables.min.css') }}"/>
@if(app()->getLocale() == 'ar')
<link rel="stylesheet" type="text/css" href="{{ url('dashboard/assets/datatables/css/dataTables.bootstrap5_ar.min.css') }}">
@else
<link rel="stylesheet" type="text/css" href="{{ url('dashboard/assets/datatables/css/dataTables.bootstrap5.min.css') }}">
@endif

<script type="text/javascript" src="{{ url('dashboard/assets/datatables/js/dataTables.bootstrap5.min.js') }}"></script>
<script type="text/javascript" src="{{ url('dashboard/assets/datatables/js/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ url('dashboard/assets/datatables/js/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ url('dashboard/assets/datatables/js/datatables.min.js') }}"></script>

<link rel="stylesheet" type="text/css" href="{{ url('dashboard/assets/datatables/css/buttons.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ url('dashboard/assets/datatables/css/stateRestore.bootstrap5.min.css') }}">

