@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Category Page</h5>
        </div>

        <div class="card-body">
            <table class="table table-responsive table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($category as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->description}}</td>
                            <td>
                                <img src="{{asset('assets/uploads/category/'.$item->image)}}" class="cate-image">
                            </td>
                            <td>
                                <a href="{{url('edit-prod/'.$item->id)}}" class="btn btn-primary">Edit</a>
                                <button class="btn btn-danger">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection