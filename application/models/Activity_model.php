<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity_model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
    $CI = &get_instance();
		$this->db1 = $CI->load->database('db1', TRUE);
    $this->activity = 'activity_log';
	}

  function get_activity(){
    $this->db1->select('*');
    // $this->db1->where('StatusId',1);
    $query = $this->db1->get($this->activity);
    return $query->result();
  }

  function insert_activity($obj){
    return $this->db1->insert($this->activity,$obj);
  }

  function get_user($id){
    $this->db1->select('*');
    // $this->db1->join('group_user','group_user.UserId = user.UserId');
    $this->db1->where('user.UserId',$id);
    $query = $this->db1->get('user');
    return $query->row();
  }

  function get_customer_details($id){
    $this->db1->select('*');
    $this->db1->join('group','group.CustomerId = customer.CustomerId');
    $this->db1->where('customer.CustomerId',$id);
    $query = $this->db1->get('customer');
    return $query->row();
  }

  function get_water_level(){
    $this->db1->select('*');
    $query = $this->db1->get('waterlevel');
    return $query->result();
  }
}
