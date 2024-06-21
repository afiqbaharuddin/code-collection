<?php

class Profile extends CI_Controller
{

  function __construct() {
    parent:: __construct();
    $this->load->model('profile/Profile_Model');
    $this->load->model('App_logger_model');


    if ($this->session->userdata('is_logged_in') == false) {
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
    $this->global_data['AppType']     = 2;
    $this->global_data['CreatedDate'] = date("Y-m-d H:i:s", time() );
    $this->global_data['CreatedBy']   = $this->global_data['UserId'];
    $this->global_data['UpdatedDate'] = date("Y-m-d H:i:s", time() );
    $this->global_data['UpdatedBy']   = $this->global_data['UserId'];
  }


  function index()
  {
    $data['header']          = $this->load->view('templates/main-header',"",true);
    $data['topbar']          = $this->load->view('templates/main-topbar',"",true);
    $data['sidebar']         = $this->load->view('templates/main-sidebar',"",true);
    $data['footer']          = $this->load->view('templates/main-footer',"",true);
    $data['bottom']          = $this->load->view('templates/main-bottom',"",true);

    $id                      = $this->session->userdata('UserId');
    $data['userdetails']     = $this->Profile_Model->get_userdetails($id);
    $data['ownPass']      = $this->Profile_Model->get_ownPass();

    $this->load->view('profile/profile', $data);
  }


  function editprofile() {

    $data = array_merge($this->global_data);
    $get = $this->input->post();

    $userid       = $get['userid'];

    $sent = array (
                    'Fullname'    => $this->input->post('fullname'),
                    'Email'       => $this->input->post('email'),
                    'PhoneNo'     => $this->input->post('phoneNumber')
                  );


    $this->Profile_Model->edit_profile($userid, $sent);

    $status    = true;
    $response  = "Profile has been updated.";
    $errorcode = 200;

    $result['token']    = $data['csrf']['hash'];
    $result['status']   = $status;
    $result['message']  = $response;

    echo json_encode($result);
    }
  }
?>
