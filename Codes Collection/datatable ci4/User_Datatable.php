<?php
   namespace App\Controllers\datatable;

   use App\Controllers\BaseController;
   use App\Models\userDatatable_Model;
   use App\Models\Export_Model;
   use Config\Services;


   class User_Datatable extends BaseController
   {

     public function index(){

       $data = ['title' => 'User List'];

       $user = new Export_Model();
       $data['user'] = $user->select('*')->findAll();

        return view('user_datatable', $data);
     }

     public function list()
    {
        $request = Services::request();
        $datatable = new userDatatable_Model($request);

        if ($request->getMethod(true) === 'POST') {
            $lists = $datatable->getDatatables();
            $data = [];
            $no = $request->getPost('start');

            foreach ($lists as $list) {
                $no++;
                $row = [];

                $row[] = $no;
                $row[] = $list->name;
                $row[] = $list->email;
                $row[] = $list->hobby;
                $row[] = '<a href="'.base_url().'edit_user/'.$list->userid.'">Edit</a>';

                $data[] = $row;
            }

            $output = [
                'draw' => $request->getPost('draw'),
                'recordsTotal' => $datatable->countAll(),
                'recordsFiltered' => $datatable->countFiltered(),
                'data' => $data
            ];

            echo json_encode($output);
        }
    }

    public function export_csv(){

      // file name
     $filename = 'users_'.date('Ymd').'.csv';
     header("Content-Description: File Transfer");
     header("Content-Disposition: attachment; filename=$filename");
     header("Content-Type: application/csv; ");

     // get data
     $user = new Export_Model();
     $userData = $user->select('name, email, hobby')
                ->join('hobbies', 'hobbies.hobbyid = user.hobbyid')
                ->get()
                ->getResultArray();

     // file creation
     $file = fopen('php://output', 'w');

     $header = array( "Name","Email","Hobby");
     fputcsv($file, $header);
     foreach ($userData as $key=>$line){
        fputcsv($file,$line);
     }
     fclose($file);
     exit;
    }
   }

   

 ?>
