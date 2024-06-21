<?php
/**
 *
 */
class ForgetPassword extends CI_Controller
{

  function __construct()
  {
    parent:: __construct();
  }

  public function index()
  {
    $data['header']=$this->load->view('templates/header',"",true);
    $data['bottom']=$this->load->view('templates/bottom',"",true);

    $this->load->view('auth/forget_password', $data);
  }
}

 ?>
