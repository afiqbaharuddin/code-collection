<?php
//ni tak pakai
  class CreateCampaign extends CI_Controller
  {

    function __construct() {
      parent:: __construct();
      $this->load->model('campaign/Campaign_Model');

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

    $this->global_data['CreatedDate'] = date("Y-m-d H:i:s", time() );
    $this->global_data['CreatedBy']   = $this->global_data['UserId'];
    $this->global_data['EditedDate']  = date("Y-m-d H:i:s", time() );
    $this->global_data['EditedBy']    = $this->global_data['UserId'];
    // $this->output->enable_profiler(TRUE);
    }

    function index()
    {
       $data = array_merge($this->global_data);

       // $this->load->view('templates/header');
       $data['header']=$this->load->view('templates/main-header',"",true);
       $data['topbar']=$this->load->view('templates/main-topbar',"",true);
       $data['sidebar']=$this->load->view('templates/main-sidebar',"",true);
       $data['footer']=$this->load->view('templates/main-footer',"",true);
       $data['bottom']=$this->load->view('templates/main-bottom',"",true);


       $this->load->view('campaign/create_campaign', $data);
    }

  }


 ?>
