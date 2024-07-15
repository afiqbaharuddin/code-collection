<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function index()
    {
        $data = Student::latest()->paginate(5);
        return view('index', compact('data'))->with('i', (request()->input('page',1) - 1) * 5);
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_name'  => 'required|string|max:255',
            'student_email' => 'required|email|unique:students',
            'student_image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $file_name = time().'.'.request()->student_image->getClientOriginalExtension();

        request()->student_image->move(public_path('images'),$file_name);

        $student = new Student;

        $student->student_name   = $request->student_name;
        $student->student_email  = $request->student_email;
        $student->student_gender = $request->student_gender;
        $student->student_image  = $file_name;

        $student->save();
        return redirect()->route('students.index')->with('success', 'Student Added Successfully.');
    }

    public function show(Student $student)
    {
        return view('show',compact('student'));
    }

    public function edit(Student $student)
    {
        return view('edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'student_name'  => 'required|string|max:255',
            'student_email' => 'required|email',
            'student_image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $student_image = $request->hidden_student_image;
        
        if ($request->student_image != '') {
            $student_image = time().'.'.request()->student_image->getClientOriginalExtension();
            request()->student_image->move(public_path('images'),$student_image);
        }

        $student = Student::find($request->hidden_id);

        $student->student_name   = $request->student_name;
        $student->student_email  = $request->student_email;
        $student->student_gender = $request->student_gender;
        $student->student_image  = $student_image;
        $student->save();

        return redirect()->route('students.index')->with('success','Student Updated Successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student Deleted Successfully.');
    }
}
