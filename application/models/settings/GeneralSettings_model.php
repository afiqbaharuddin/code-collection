<?php

  class GeneralSettings_model extends CI_Model
  {
    function changeStatus($statusdb)
    {
      return $this->db->update('general_settings', $statusdb);
    }
    function changeStatusCancel($statusdb)
    {
      return $this->db->update('general_settings', $statusdb);
    }

    function generalSettings($data){
      // if (($get['validation'] && $get['campaign'] && $get['limit'] && $get['time'] != null) && ($get['validation'] && $get['campaign'] && $get['limit'] && $get['time'] == null)) {

        $this->db->insert('general_settings',$data);
        return $this->db->insert_id();
      // }
    }

    function CancelgeneralSettings($data, $get){

      $data = array(
                      'CampaignValidationCheck' => 'N',
                      'CampaignSetDueCheck'     => 'N',
                      // 'CampaignSetDueDays'      => 'N',
                      'LimitUserLogCheck'       => 'N',
                      'IdleTimeoutCheck'     => 'N',
                      'NumFailedLoginCheck'     => 'N',
                      'UnlockManuallyCheck'     => 'N',
                      'StatusId' => 1,
                      'AppLoggerId' => $data['AppLoggerId'],
               );
        $this->db->insert('general_settings',$data);
        return $this->db->insert_id();
    }

    function get_generaldata() {
      $this->db->where("StatusId",1);
      $query = $this->db->get('general_settings');
      return $query->row();
    }

    function getTimeIdle()
    {
      $this->db->select("IdleTimeoutNum");
      $this->db->where("StatusId",1);
      $query = $this->db->get('general_settings');
      return $query->row()->IdleTimeoutNum;
    }
  }
 ?>
