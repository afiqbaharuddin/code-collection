<?php

namespace App\Http\Controllers;

use App\Models\cr;
use Illuminate\Http\Request;

class FileUploadProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('file_upload');
    }

    function upload(Request $request){
      $rules = array(
        'file' = 'required|image|max:2048'
      );

      $error = Validator::make($request->all(),$rules);

      if ($error->fails()) {
        return response()->json(['errors'=>$error->errors()->all()]);
      }

      $image = $request->file('file');

      $new_name = rand().'.'.$image->getClientOriginalExtension();
      $image->move(public_path('images'),$new_name);

      $output = array(
        'success' => 'Image Import Successfully',
        'image'   => '<img src="/images/'.$new_name.'" class="img-thumbnail"/>'
      );

      return response()->json($output);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(cr $cr)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(cr $cr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, cr $cr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cr $cr)
    {
        //
    }
}
