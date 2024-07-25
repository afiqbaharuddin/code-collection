<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\UserImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function import(Request $request){
      $file = $request->file('file');
      Excel::import(new UserImport, $file);

      return redirect()->back()->with('success','User Imported Successfully');
    }
}
