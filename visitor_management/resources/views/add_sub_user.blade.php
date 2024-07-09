@extends('dashboard')

@section('content')

<h2 class="mt-3">Sub User Management</h2>
<nav aria-label="breadcrumb">
  	<ol class="breadcrumb">
    	<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
    	<li class="breadcrumb-item"><a href="/sub_user">Sub Management</a></li>
    	<li class="breadcrumb-item active">Add New Sub User</li>
  	</ol>
</nav>

<div class="row mt-4">
	<div class="col-md-4">
		<div class="card">
			<div class="card-header">Add New User</div>
			<div class="card-body">
				<form method="POST" action="{{ route('sub_user.add_validation') }}">
					@csrf
					<div class="form-group mb-3">
		        		<label><b>User Name</b></label>
		        		<input type="text" name="name" class="form-control" placeholder="Name" />
		        		@if($errors->has('name'))
		        		<span class="text-danger">{{ $errors->first('name') }}</span>
		        		@endif
		        	</div>
		        	<div class="form-group mb-3">
		        		<label><b>User Email</b></label>
		        		<input type="text" name="email" class="form-control" placeholder="Email">
		        		@if($errors->has('email'))
		        			<span class="text-danger">{{ $errors->first('email') }}</span>
		        		@endif
		        	</div>
		        	<div class="form-group mb-3">
		        		<label><b>Password</b></label>
		        		<input type="password" name="password" class="form-control" placeholder="Password">
		        		@if($errors->has('password'))
		        			<span class="text-danger">{{ $errors->first('password') }}</span>
		        		@endif
		        	</div>
		        	<div class="form-group mb-3">
		        		<input type="submit" class="btn btn-primary" value="Add" />
		        	</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection