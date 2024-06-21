<?php
  class ActivityLog extends CI_Controller
  {

    function __construct()
    {
      parent:: __construct();
      $this->load->model('logs/ActivityLog_Model');

      if ($this->session->userdata('is_logged_in') == false)
      {
        redirect();
      }

      //CSRF PROTECTION\\
      $this->global_data['csrf'] = [
        'name' => $this->security->get_csrf_token_name(),
        'hash' => $this->security->get_csrf_hash()
      ];
      //CSRF PROTECTION\\

      $this->global_data['UserId']      = $this->session->userdata('UserId');
      $this->global_data['Fullname']    = $this->session->userdata('Fullname');
      $this->global_data['Role']        = $this->session->userdata('Role');
      $this->global_data['CreatedDate'] = date("Y-m-d H:i:s", time() );
    	$this->global_data['CreatedBy']   = $this->global_data['UserId'];
    	$this->global_data['EditedDate']  = date("Y-m-d H:i:s", time() );
    	$this->global_data['EditedBy']    = $this->global_data['UserId'];
      // $this->output->enable_profiler(TRUE);
    }

    function activity()
    {
       $data = array_merge($this->global_data);

       $data['header']          = $this->load->view('templates/main-header',"",true);
       $data['topbar']          = $this->load->view('templates/main-topbar',"",true);
       $data['sidebar']         = $this->load->view('templates/main-sidebar',"",true);
       $data['footer']          = $this->load->view('templates/main-footer',"",true);
       $data['bottom']          = $this->load->view('templates/main-bottom',"",true);

       $data['role']            = $this->ActivityLog_Model->get_role()->result();
       $data['category']        = $this->ActivityLog_Model->get_category()->result();
       $data['store']           = $this->ActivityLog_Model->get_store()->result();

       $data['permission']      =$this->ActivityLog_Model->get_permission($this->session->userdata('UserId'));

      $this->load->view('logs/activity_log', $data);
    }

    public function activityloglisting()
    {
      $data = array_merge($this->global_data);
      $post = $this->input->post();

      $permission = $this->ActivityLog_Model->get_permissionactivitylog($this->session->userdata('UserId'));
      $userid     = $this->ActivityLog_Model->userstore($this->session->userdata('UserId'));

      $list  = $this->ActivityLog_Model->get_datatables($post,$userid->UserId,$permission);
      $table = array();
      $no    = $post['start'];

      if ($this->session->set_userdata('Filter_Store_Activity') != null) {
        $post['filter_store'] = $this->session->set_userdata('Filter_Store_Activity');
      }

      $this->session->set_userdata('Filter_Role',$post['filter_role']);
      $this->session->set_userdata('Filter_Name',$post['filter_name']);
      $this->session->set_userdata('Filter_Category',$post['filter_category']);
      $this->session->set_userdata('Filter_DateFrom',$post['date_from']);
      $this->session->set_userdata('Filter_DateTo',$post['date_to']);

      foreach ($list as $field) {

        if ($field->Fullname == 'SCHEDULER'|| $field->Fullname == 'Scheduler') {
          $role= '';
        }else {
          $role= $field->Role;
        }

        $no++;
        $row   = array();
        // $row[] = $no;
        $row[] = $field->Fullname;
        $row[] = $role;
        $row[] = date('Y-m-d H:i:s', strtotime($field->ActiveDate));
        $row[] = $field->ActivityTypeName;
        $row[] = $field->ActivityDetails;
        $row[] = $field->IpAddress;
        $row[] = $field->Browser;

        $table[] = $row;
      }

      $output = array(
        "draw"                => $post['draw'],
        "recordsTotal"        => $this->ActivityLog_Model->count_all($post,$userid->UserId,$permission),
        "recordsFiltered"     => $this->ActivityLog_Model->count_filtered($post,$userid->UserId,$permission),
        "data"                => $table,
        $data['csrf']['name'] => $data['csrf']['hash']
      );
      echo json_encode($output);
    }

    public function update_faq_post()
    {
      $import              = $this->input->post();
      $data['AppLoggerId'] = $data['AppLoggerId'];
      $data['UpdatedBy']   = $data['UserId'];
      $data['UpdatedDate'] = date("Y-m-d H:i:s", time() );

      if ($this->App_logger_model->update_app_logger_data($data)) {
        $sendata = array(
          'FaqQuestion'   => $data['FaqQuestion'],
          'FaqAnswer'     => $data['FaqAnswer'],
          'FaqCategoryId' => $data['FaqCategoryId'],
          'StatusId'      => $data['StatusId'],
        );

        if ($this->Faq_model->update_faq($sendata,$data['FaqId']))
        {
          $status    = true;
          $response  = [ ];
          $errorcode = 200;
          $actmsg    = " update FAQ ID ".$data['FaqId'];
        } else {
          $status    = false;
          $response  = [
            "type"  => "authentication",
            "error" => array('error' => 'Something went wrong. Please try again later.'),
          ];
          $errorcode = 500;
          $actmsg = " is trying to update FAQ ID ".$data['FaqId'].". Failed.";
        }
        } else
        {
          $status    = false;
          $response  = [
            "type"  => "authentication",
            "error" => array('error' => 'Something went wrong. Please try again later.'),
          ];
          $errorcode = 500;
          $actmsg = " is trying to update FAQ ID ".$data['FaqId'].". Applogger update failed.";
        }
        $act = [
          'UserId'           => $data['UserId'],
          'ActivityTypeId'   => 13,
          'ActivityDetails'  => $data['Fullname'].$actmsg,
          'ActivityDateTime' => date('Y-m-d H:i:s'),
          'IpAddress'        => $data['IpAddress'],
          'OperatingSystem'  => $data['OperatingSystem'],
          'Browser'          => $data['Browser'],
        ];
        $this->ActivityLog_Model->insert_activity($act);
    }

    public function export_userlog_csv()
    {
      $data            = array_merge($this->global_data);

      if ($this->session->userdata('Filter_DateFrom') != null) {
        $data['start'] = $this->session->userdata('Filter_DateFrom');
      }

      if ($this->session->userdata('Filter_DateTo') != null) {
        $data['end'] = $this->session->userdata('Filter_DateTo');
      }

      if ($this->session->userdata('Filter_Category') != null) {
        $data['category'] = $this->session->userdata('Filter_Category');
      }

      if ($this->session->userdata('Filter_Role') != null) {
        $data['role'] = $this->session->userdata('Filter_Role');
      }

      if ($this->session->userdata('Filter_Name') != null) {
        $data['name'] = $this->session->userdata('Filter_Name');
      }

      if ($this->session->userdata('Filter_Store_Activity') != null) {
        $data['store'] = $this->session->userdata('Filter_Store_Activity');
      } else {
        $data['store'] = '';
      }

      $permission      = $this->ActivityLog_Model->get_permissionactivitylog($this->session->userdata('UserId'));
      $response        = $this->ActivityLog_Model->userlog_csv($data,$permission);
      $arraylist       = [];
      $no              = 1;

      if ($this->session->userdata('Filter_DateFrom') != null) {
        $from = $this->session->userdata('Filter_DateFrom');
      } else {
        $from = '';
      }

      if ($this->session->userdata('Filter_DateTo') != null) {
        $to = $this->session->userdata('Filter_DateTo');
      } else {
        $to = '';
      }

      $bystore  = $this->ActivityLog_Model->get_bystore($data['store']);
      $category = $this->ActivityLog_Model->get_bycategory($data['category']);

      echo "\r"; echo "\r"; echo strtoupper("USER LOG REPORT FOR " .$bystore);
      echo "\n";
      echo "FOR  ".$from."  TO  ".$to;
      echo "\n";
      echo "CATEGORY: " .$category;
      echo "\n";
      echo "\n";

      $i = 0;
      foreach ($response as $key) {

        $array = array(
         'NO'                => $no,
         'STAFF ID'          => "'".$key->StaffId."'",
         'STORE'             => $key->StoreName,
         'NAME'              => $key->Fullname,
         'ROLE'              => $key->Role,
         'DATE'              => date('d/m/Y',strtotime($key->ActiveDate)),
         'CATEGORY'          => $key->ActivityTypeName,
         'DETAILS'           => $key->ActivityDetails,
         'IP ADDRESS'        => $key->IpAddress,
         'BROWSER'           => $key->Browser,
       );

       $no++;
       array_push($arraylist,$array);
       $i++;
      }

      if ($from == "" && $to == "") {
        $this->download_send_headers("User Activity Log Report.csv");
      }else {
        $this->download_send_headers("User Activity Log Report  ".$from." - ".$to.".csv");
      }

      echo $this->array2csv($arraylist);
    }

    function filteractivitylog() {
      $data = array_merge($this->global_data);

      $get = $this->input->post();
      $this->session->set_userdata('Filter_Role',$get['filter_role']);
      $this->session->set_userdata('Filter_Name',$get['filter_name']);
      $this->session->set_userdata('Filter_Category',$get['filter_category']);
      $this->session->set_userdata('Filter_DateFrom',$get['date_from']);
      $this->session->set_userdata('Filter_DateTo',$get['date_to']);
      $this->session->set_userdata('Filter_Store_Activity',$get['filter_store']);

      $output['token'] = $data['csrf']['hash'];
      echo json_encode($output);
    }

    function download_send_headers($filename) { //fix code
      // disable caching

      $now = gmdate("D, d M Y H:i:s");
      header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
      header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
      header("Last-Modified: {$now} GMT");

      // force download
      header("Content-Type: application/force-download");
      header("Content-Type: application/octet-stream");
      header("Content-Type: application/download");

      // disposition / encoding on response body
      header("Content-Disposition: attachment;filename={$filename}");
      header("Content-Transfer-Encoding: binary");
    }

    function array2csv(array &$array){ //fix code
     if (count($array) == 0) {
       return null;
     }
     ob_start();
     $df = fopen("php://output", 'w');

     $headers = 'test,test2';

     fputcsv($df,array_keys(reset($array)));
     foreach ($array as $row) {
       fputcsv($df, $row);
     }
     fclose($df);
     return ob_get_clean();
   }
  }
 ?>
