@extends('dashboard')

@section('content')

<h2 class="mt-3">Department Management</h2>
<nav aria-label="breadcrumb">
  	<ol class="breadcrumb">
    	<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
    	<li class="breadcrumb-item active">Department Management</li>	
  	</ol>
</nav>

<div class="mt-4 mb-4">

	@if(session()->has('success'))
		<div class="alert alert-success">
			{{ session()->get('success') }}
		</div>
	@endif

	<div class="card">
		<div class="card-header">
			<div class="row">
				<div class="col col-md-6">Department Management</div>
				<div class="col col-md-6">
					<a href="/department/add" class="btn btn-success btn-sm float-end">Add</a>
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="department_table">
					<thead>
						<tr>
							<th>Department Name</th>
							<th>Contact Person</th>
							<th>Created At</th>
							<th>Updated At</th>
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

	var table = $('#department_table').DataTable({
		processing:true,
		serverSide:true,
		ajax:'{{ route("department.fetch_all") }}',
		columns:[
			{
				data:'department_name',
				name:'department_name'
			},
			{
				data:'contact_person',
				name:'contact_person'
			},
			{
				data:'created_at',
				name:'created_at'
			},
			{
				data:'updated_at',
				name:'updated_at'
			},
			{
				data:'action',
				name:'action',
				orderable:false
			}
		]
	});

	$(document).on('click', '.delete', function(){

		var id = $(this).data('id');

		if(confirm("Are you sure you want to remove it?"))
		{
			window.location.href = '/department/delete/'+id;
		}

	});

</script>

@endsection