@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Edit/Update Category</h4>
        </div>
        <div class="card-body">
            <form action="{{url('update-category/'.$category->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row col-lg-8">
                    <div class="col-md-6 mb-3">
                        <label for=""><b>Name</b></label>
                        <input type="text" name="name" class="form-control custom-border" value="{{$category->name}}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for=""><b>Slug</b></label>
                        <input type="text" name="slug" class="form-control custom-border" value="{{$category->slug}}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for=""><b>Status</b></label>
                        <input type="checkbox" name="status" {{$category->status == "1" ? 'checked':'' }}>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for=""><b>Popular</b></label>
                        <input type="checkbox" name="popular" {{$category->popular == "1" ? 'checked':'' }}>
                    </div>

                    <div class="col-md-10 mb-3">
                        <label for=""><b>Description</b></label>
                        <textarea name="description" rows="3" class="form-control custom-border" value="{{$category->description}}"></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for=""><b>Meta Title</b></label>
                        <input type="text" name="meta_title" class="form-control custom-border" value="{{$category->meta_title}}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for=""><b>Meta Keywords</b></label>
                        <textarea type="text" name="meta_keywords" class="form-control custom-border">{{$category->meta_keywords}}</textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for=""><b>Meta Description</b></label>
                        <textarea name="meta_description" rows="3" class="form-control custom-border">{{$category->meta_descrip}}</textarea>
                    </div>

                    @if ($category->image)
                        <img src="{{asset('assets/uploads/category/'.$category->image)}}" alt="Category Image">
                    @endif
                    <div class="col-md-6">
                        <input type="file" name="image" class="form-control custom-border">
                    </div>
                    <div class="col-md-12 mt-4">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection