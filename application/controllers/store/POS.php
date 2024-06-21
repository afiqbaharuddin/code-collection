<?php

class POS extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->model('store/POS_Model');
    $this->load->model('App_logger_model');
    $this->load->model('logs/ActivityLog_Model');


    if (get_cookie('UserId') != "" || get_cookie('UserId') != null) {
    $this->session->set_userdata('UserId', get_cookie('UserId'));
    $this->session->set_userdata('Fullname', get_cookie('Fullname'));
    $this->session->set_userdata('Role', get_cookie('Role'));
    $this->session->set_userdata('is_logged_in', true);
    }else {
    if ($this->session->userdata('is_logged_in') == false) {
      redirect();
    }
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
    $this->global_data['AppType']     = 2;
    $this->global_data['UpdatedDate'] = date("Y-m-d H:i:s", time() );
    $this->global_data['UpdatedBy']   = $this->global_data['UserId'];
  }

  public function create()
  {
    $data = array_merge($this->global_data);

    $data['IpAddress']       = $this->input->ip_address();
    $data['OperatingSystem'] = $this->agent->platform();
    $data['Browser']         = $this->agent->browser();

    $this->form_validation->set_rules('posNumber', 'POS Number', 'required');

    $get           = $this->input->post();
    $id            = $get['storeid'];

    if($this->form_validation->run() == TRUE){

      $pos       = $get['posNumber'];
      $store     = $get['storeid'];
      $storepos  = $get['storecode'].'-'.$get['posNumber'];

      $count    = $this->POS_Model->count_pos($storepos);
      $countpos = $this->POS_Model->count_countpos($storepos);

      if ($count != 0) {

          $message = ' POS already Exist in the Store!!';

          $status    = false;
          $response  = $message;
          $errorcode = 400;
      }
      else
      {
        $get['loggerid'] = $this->App_logger_model->insert_app_logger_data($data);//wajib letak applogger for create

        if ($get['loggerid'] = $this->App_logger_model->insert_app_logger_data($data))
        {
          if ($this->POS_Model->insertPOS($get, $id, $storepos, $countpos))
          {
            $storename  = $this->POS_Model->get_storename($get['storeid']);

            $status     = true;
            $response   = "POS has been created.";
            $errorcode  = 200;
            $actmsg     = " create POS, " .$storepos. " for ".$storename;
          } else {
            $status     = false;
            $response   = [
              "type"    => "authentication",
              "error"   => array('error' => 'Something went wrong. Please try again later.'),
            ];
            $errorcode  = 500;
            $actmsg     = " is trying to create POS, ".$storepos." for ".$storename.". Failed.";
          }
        } else
        {
          $status    = false;
          $response  = [
            "type"   => "authentication",
            "error"  => array('error' => 'Something went wrong. Please try again later.'),
          ];
          $errorcode = 500;
          $actmsg    = " is trying to create POS, ".$storepos." for ".$storename.". Applogger update failed.";
        }
        $act = [
          'UserId'           => $data['UserId'],
          'ActivityTypeId'   => 23,
          'ActivityDetails'  => $data['Fullname'].$actmsg,
          'ActiveDate'       => date('Y-m-d h:i:s'),
          'IpAddress'        => $data['IpAddress'],
          'OperatingSystem'  => $data['OperatingSystem'],
          'Browser'          => $data['Browser'],
        ];

        $this->ActivityLog_Model->insert_activity($act);
      }
    }
    else {
      $error = $this->form_validation->error_array();
      $salah = '';
      foreach ($error as $keye) {
        $salah .= $keye.'<br>';
        }

      $status    = false;
      $response  = $salah;
      $errorcode = 400;
    }

    $result['token']    = $data['csrf']['hash'];
    $result['status']   = $status;
    $result['message']  = $response;

    echo json_encode($result);
  }

  function removePos() {

    $data = array_merge($this->global_data);

    $data['IpAddress']       = $this->input->ip_address();
    $data['OperatingSystem'] = $this->agent->platform();
    $data['Browser']         = $this->agent->browser();

    $get   = $this->input->post();
    $id    = $get['posid'];
    $store = $get['storeid'];

    // example for update logger
    $data['AppLoggerId'] = $get['loggerid'];

    if ($this->App_logger_model->update_app_logger_data($data))
        {
          if ($posid = $this->POS_Model->removePos($get))
          {
            $posnumber  = $this->POS_Model->get_posnumber($get['posid']);
            $storename  = $this->POS_Model->get_storename($get['storeid']);

            $status     = true;
            $response   = "POS has been removed.";
            $errorcode  = 200;
            $actmsg     = " remove POS, ".$posnumber. " from " .$storename.".";

            $result['token']    = $data['csrf']['hash'];
            $result['status']   = true;
            $result['message']  = 'Success';
          } else {
            $status     = false;
            $response   = [
              "type"    => "authentication",
              "error"   => array('error' => 'Something went wrong. Please try again later.'),
            ];
            $errorcode  = 500;
            $actmsg     = " is trying to remove POS ".$posnumber. "from " .$storename. ". Failed.";

            $result['token']    = $data['csrf']['hash'];
            $result['status']   = false;
            $result['message']  = 'Something went wrong';
          }
        } else
        {
          $status    = false;
          $response  = [
            "type"   => "authentication",
            "error"  => array('error' => 'Something went wrong. Please try again later.'),
          ];
          $errorcode = 500;
          $actmsg    = " is trying to remove POS ".$posnumber.". Applogger update failed.";
        }
        $act = [
          'UserId'           => $data['UserId'],
          'ActivityTypeId'   => 10,
          'ActivityDetails'  => $data['Fullname'].$actmsg,
          'ActiveDate'       => date('Y-m-d h:i:s'),
          'IpAddress'        => $data['IpAddress'],
          'OperatingSystem'  => $data['OperatingSystem'],
          'Browser'          => $data['Browser'],
        ];

        $this->ActivityLog_Model->insert_activity($act);
        echo json_encode($result);
  }
}

 ?>
