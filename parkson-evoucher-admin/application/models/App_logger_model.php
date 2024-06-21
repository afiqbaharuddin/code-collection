<?php
class App_logger_model extends CI_Model {

  function __construct()
  {
    parent::__construct();

    $this->load->database();
    // $CI = &get_instance();
    // $this->db = $CI->load->database('db', TRUE);
  }

  public function insert_app_logger_data($obj){
  	$this->load->helper('url');
    $data = array(
      'CreatedDate'       => $obj['CreatedDate'],
      'CreatedBy'         => $obj['CreatedBy'],
      'AppType'           => $obj['AppType']
    );
    $this->db->insert('app_logger', $data);
  	$last_id = $this->db->insert_id();
  	return $last_id;
  }

  public function update_app_logger_data($obj){
  	$this->load->helper('url');
    $data = array(
      'UpdatedDate'       => $obj['UpdatedDate'],
      'UpdatedBy'         => $obj['UpdatedBy'],
      // 'AppType' => $obj['AppType']
    );
  	$this->db->where('AppLoggerId', $obj['AppLoggerId']);
  	return $this->db->update('app_logger', $data);
  }

  public function get_app_logger_data($id){
    $this->db->select('*');
    $this->db->where('AppLoggerId', $id);
    $query = $this->db->get('app_logger');
    return $query->row();
  }
}
