@extends('dash::app')
@section('content')
<div class="container-fluid py-4">
	<div class="row">
		<div class="col-12">
			<div class="card my-4">
				<div class="card-header">
					<div class="row">
						<div class="col-6">
							<h6 class="text-dark text-capitalize">{{ $type }} </h6>
						</div>
					</div>
				</div>
				<div class="card-body px-3 pb-2">
                    {{--  <div id="datatable_resourceCaptains_filter" class="dataTables_filter">
                        <label>ابحث:
                            <input type="search" class="form-control form-control-sm border" placeholder="" aria-controls="datatable_resourceCaptains">
                        </label>
                        <div class="row filtersCaptains"></div>
                    </div>  --}}
					<div class="row">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th width="100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
   $(document).ready(function(){
        $(function () {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('/captainAsk/resource/Captains/New') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'action', name: 'action', orderable: false, searchable: true},
                ]
            });
        });
   });
  </script>
@endpush
