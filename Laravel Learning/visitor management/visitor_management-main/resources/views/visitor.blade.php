@extends('dashboard')

@section('content')

<h2 class="mt-3">Visitor Management</h2>
<nav aria-label="breadcrumb">
  	<ol class="breadcrumb">
    	<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
    	<li class="breadcrumb-item active">Visitor Management</li>	
  	</ol>
</nav>
<div class="mt-4 mb-4">
	<div class="card">
		<div class="card-header">
			<div class="row">
				<div class="col col-md-8">Visitor Management</div>
				<div class="col col-md-2">

				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="visitor_table">
					<thead>
						<tr>
							<th>Visitor Name</th>
							<th>Meet Person Name</th>
							<th>Department</th>
							<th>In Time</th>
							<th>Out Time</th>
							<th>Status</th>
							<th>Enter By</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function(){

	var table = $('#visitor_table').DataTable({

		processing:true,
		serverSide:true,
		ajax:"{{ route('visitor.fetchall') }}",
		columns:[
			{
				data:'visitor_name',
				name: 'visitor_name'
			},
			{
				data: 'visitor_meet_person_name',
				name: 'visitor_meet_person_name'
			},
			{
				data:'visitor_department',
				name:'visitor_department'
			},
			{
				data:'visitor_enter_time',
				name: 'visitor_enter_time'
			},
			{
				data:'visitor_out_time',
				name:'visitor_out_time'
			},
			{
				data:'visitor_status',
				name:'visitor_status'
			},
			{
				data:'name',
				name:'name'
			},
			{
				data:'action',
				name:'action',
				orderable:false
			}
		]
	});

});
</script>

@endsection