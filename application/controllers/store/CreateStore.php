<?php
/**
 *
 */
class CreateStore extends CI_Controller
{

  function __construct()
  {
    parent:: __construct();
  }

  function create()
  {
    $data['header']=$this->load->view('templates/header',"",true);
    $data['topbar']=$this->load->view('templates/topbar',"",true);
    $data['sidebar']=$this->load->view('templates/sidebar',"",true);
    $data['footer']=$this->load->view('templates/footer',"",true);
    $data['bottom']=$this->load->view('templates/bottom',"",true);
    $this->load->view('store/create_store', $data);
  }
}

 ?>