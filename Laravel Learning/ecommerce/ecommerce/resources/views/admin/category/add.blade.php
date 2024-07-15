@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Add Category</h4>
        </div>
        <div class="card-body">
            <form action="{{url('insert-category')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row col-lg-8">
                    <div class="col-md-6 mb-3">
                        <label for=""><b>Name</b></label>
                        <input type="text" name="name" class="form-control custom-border">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for=""><b>Slug</b></label>
                        <input type="text" name="slug" class="form-control custom-border">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for=""><b>Status</b></label>
                        <input type="checkbox" name="status">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for=""><b>Popular</b></label>
                        <input type="checkbox" name="popular">
                    </div>

                    <div class="col-md-10 mb-3">
                        <label for=""><b>Description</b></label>
                        <textarea name="description" rows="3" class="form-control custom-border"></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for=""><b>Meta Title</b></label>
                        <input type="text" name="meta_title" class="form-control custom-border">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for=""><b>Meta Keywords</b></label>
                        <input type="text" name="meta_keywords" class="form-control custom-border">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for=""><b>Meta Description</b></label>
                        <textarea name="meta_description" rows="3" class="form-control custom-border"></textarea>
                    </div>
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