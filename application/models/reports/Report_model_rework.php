<?php

class Report_model extends CI_Model {

  function get_filtertype() {
    $this->db->order_by('VoucherName', 'asc');
    $query = $this->db->get('voucher_type');
    return $query;
  }

  function get_filterstatus() {
    $this->db->order_by('VoucherStatusName', 'asc');
    $query = $this->db->get('voucher_status');
    return $query;
  }

  function get_filterreason() {
    $array =[1,2,3];
    $this->db->order_by('ReasonName', 'asc');
    $this->db->where_in('ReasonId',$array);
    $query = $this->db->get('reason');
    return $query;
  }

  function get_filtercampaign() {
    $this->db->order_by('CampaignId', 'asc');
    $query = $this->db->get('campaign');
    return $query;
  }

  function get_filtercategory() {
    $this->db->order_by('VoucherActivityCategoryName', 'asc');
    $query = $this->db->get('voucher_activity_category');
    return $query;
  }

  function get_filterstore() {
    $query = $this->db->get('store');
    return $query;
  }

  public function user_report($obj,$permission) {
    $this->db->select('*');
    $this->db->join("user_role","user_role.UserRoleId = user.UserRoleId");
    $this->db->join("app_logger","app_logger.AppLoggerId = user.AppLoggerId");
    $this->db->join("status","status.StatusId = user.StatusId");
    $this->db->join("user_store","user_store.UserId = user.UserId");
    $this->db->join("store","store.StoreId = user_store.StoreId");

    if (isset($obj['start']) && $obj['start'] != null) {
      $this->db->where('DATE(app_logger.CreatedDate) >=',$obj['start']);
    }

    if (isset($obj['end']) && $obj['end'] != null) {
      $this->db->where('DATE(app_logger.CreatedDate) <=',$obj['end']);
    }

    if ($permission->UserRoleId != 1 || $permission->StoreId != 0) {
      $this->db->where("store.StoreId", $permission->StoreId);
    }

    $this->db->order_by('DATE(app_logger.CreatedDate)', 'asc');
    $query = $this->db->get('user');
    return $query->result();
  }

  public function voucher_report($obj,$permission,$storecode)
  {
      $this->db->select('*');
      $this->db->join("voucher_issuance","voucher_issuance.VoucherIssuanceId = vouchers.VoucherIssuanceId");
      $this->db->join("voucher_status","voucher_status.VoucherStatusId = vouchers.VoucherStatusId");
      $this->db->join("store","store.StoreId = voucher_issuance.IssuanceStoreId");
      $this->db->join("pos","pos.POSId = voucher_issuance.IssuancePOSId","inner");
      $this->db->join("redeem_type","redeem_type.RedeemTypeId = vouchers.RedeemTypeId");
      $this->db->join("app_logger","app_logger.AppLoggerId = vouchers.AppLoggerId");
      $this->db->join("voucher_type","voucher_type.VoucherTypeId = vouchers.VoucherTypeId");

    if (isset($obj['status'])) {
      if ($obj['status'] == 7) {
        $this->db->join("voucher_activity","voucher_activity.VouchersId = vouchers.VoucherId");

        $this->db->where('voucher_activity.VoucherActivityCategoryId',3);
        $this->db->order_by('vouchers.RedemptionStore','asc');
        $this->db->order_by('vouchers.RedemptionDateTime','asc');

        if (isset($obj['start']) && $obj['start'] != null) {
          $this->db->where('DATE(vouchers.RedemptionDateTime) >=',$obj['start']);
        }
        if (isset($obj['end']) && $obj['end'] != null) {
          $this->db->where('DATE(vouchers.RedemptionDateTime) <=',$obj['end']);
        }
        if (isset($obj['store']) && $obj['store'] != 0) {
          $this->db->where('voucher_activity.StoreId', $storecode);
        }
      } elseif ($obj['status'] == 8) {
        $this->db->join("voucher_status_history","voucher_status_history.VoucherId = vouchers.VoucherId");
        $this->db->order_by("(store.StoreCode)", "asc");
        $this->db->order_by("DATE(voucher_status_history.RefundDate)", "asc");

        if (isset($obj['start']) && $obj['start'] != null) {
          $this->db->where('DATE(voucher_status_history.RefundDate) >=',$obj['start']);
        }
        if (isset($obj['end']) && $obj['end'] != null) {
          $this->db->where('DATE(voucher_status_history.RefundDate) <=',$obj['end']);
        }
        if (isset($obj['store']) && $obj['store'] != 0) {
          $this->db->where('voucher_issuance.IssuanceStoreId', $obj['store']);
        }
      } elseif ($obj['status'] == 4) {
        $this->db->join("voucher_status_history","voucher_status_history.VoucherId = vouchers.VoucherId");
        $this->db->order_by("(store.StoreCode)", "asc");
        $this->db->order_by("DATE(voucher_status_history.VoidDate)", "asc");

        if (isset($obj['start']) && $obj['start'] != null) {
          $this->db->where('DATE(voucher_status_history.VoidDate) >=',$obj['start']);
        }
        if (isset($obj['end']) && $obj['end'] != null) {
          $this->db->where('DATE(voucher_status_history.VoidDate) <=',$obj['end']);
        }
        if (isset($obj['store']) && $obj['store'] != 0) {
          $this->db->where('voucher_issuance.IssuanceStoreId', $obj['store']);
        }
      } elseif ($obj['status'] == 6) {
        $this->db->join("voucher_status_history","voucher_status_history.VoucherId = vouchers.VoucherId");
        $this->db->join("reason","reason.ReasonId = voucher_status_history.BlockReasons");
        $this->db->order_by("(store.StoreCode)", "asc");
        $this->db->order_by("DATE(voucher_status_history.BlockDate)", "asc");

        if (isset($obj['start']) && $obj['start'] != null) {
          $this->db->where('DATE(voucher_status_history.BlockDate) >=',$obj['start']);
        }
        if (isset($obj['end']) && $obj['end'] != null) {
          $this->db->where('DATE(voucher_status_history.BlockDate) <=',$obj['end']);
        }
        if (isset($obj['store']) && $obj['store'] != 0) {
          $this->db->where('voucher_issuance.IssuanceStoreId', $obj['store']);
        }
      } elseif ($obj['status'] == 5) {
        $this->db->join("voucher_status_history","voucher_status_history.VoucherId = vouchers.VoucherId");
        $this->db->join("reason","reason.ReasonId = voucher_status_history.CancelReasons");

        $this->db->order_by("store.StoreCode","asc");
        $this->db->order_by("DATE(voucher_status_history.CancelDate)", "asc");
        $this->db->order_by("vouchers.Numbering","asc");
        $this->db->order_by("vouchers.VouchersValue","asc");

        if (isset($obj['start']) && $obj['start'] != null) {
          $this->db->where('DATE(voucher_status_history.CancelDate) >=',$obj['start']);
        }
        if (isset($obj['end']) && $obj['end'] != null) {
          $this->db->where('DATE(voucher_status_history.CancelDate) <=',$obj['end']);
        }
        if (isset($obj['store']) && $obj['store'] != 0) {
          $this->db->where('voucher_issuance.IssuanceStoreId', $obj['store']);
        }
      } elseif ($obj['status'] == 2) {
        $this->db->order_by("(store.StoreCode)", "asc");
        $this->db->order_by("DATE(vouchers.ExpDate)", "asc");

        if (isset($obj['start']) && $obj['start'] != null) {
          $this->db->where('DATE(vouchers.ExpDate) >=',$obj['start']);
        }
        if (isset($obj['end']) && $obj['end'] != null) {
          $this->db->where('DATE(vouchers.ExpDate) <=',$obj['end']);
        }
        if (isset($obj['store']) && $obj['store'] != 0) {
          $this->db->where('voucher_issuance.IssuanceStoreId', $obj['store']);
        }
      } elseif ($obj['status'] == 3) {
        $this->db->join("voucher_status_history","voucher_status_history.VoucherId = vouchers.VoucherId");
        $this->db->order_by("(store.StoreCode)", "asc");
        $this->db->order_by("DATE(voucher_status_history.ExtendDate)", "asc");

        if (isset($obj['start']) && $obj['start'] != null) {
          $this->db->where('DATE(voucher_status_history.ExtendDate) >=',$obj['start']);
        }
        if (isset($obj['end']) && $obj['end'] != null) {
          $this->db->where('DATE(voucher_status_history.ExtendDate) <=',$obj['end']);
        }
        if (isset($obj['store']) && $obj['store'] != 0) {
          $this->db->where('voucher_issuance.IssuanceStoreId', $obj['store']);
        }
      } else {
        $this->db->order_by("store.StoreCode","asc");
        $this->db->order_by("DATE(voucher_issuance.ReceiptDateTime)","asc");
        $this->db->order_by("vouchers.Numbering","asc");
        $this->db->order_by("vouchers.VouchersValue","asc");

        if (isset($obj['start']) && $obj['start'] != null) {
          $this->db->where('DATE(voucher_issuance.ReceiptDateTime) >=',$obj['start']);
        }
        if (isset($obj['end']) && $obj['end'] != null) {
          $this->db->where('DATE(voucher_issuance.ReceiptDateTime) <=',$obj['end']);
        }
      }
    } else {
      $this->db->order_by("store.StoreCode","asc");
      $this->db->order_by("DATE(voucher_issuance.ReceiptDateTime)","asc");
      $this->db->order_by("vouchers.Numbering","asc");
      $this->db->order_by("vouchers.VouchersValue","asc");
      if (isset($obj['start']) && $obj['start'] != null) {
        $this->db->where('DATE(voucher_issuance.ReceiptDateTime) >=',$obj['start']);
      }
      if (isset($obj['end']) && $obj['end'] != null) {
        $this->db->where('DATE(voucher_issuance.ReceiptDateTime) <=',$obj['end']);
      }
    }

    if (isset($obj['status']) != 7) {
      if (isset($obj['store']) && $obj['store'] != 0) {
        $this->db->order_by("store.StoreCode", "asc");
        $this->db->where('voucher_issuance.IssuanceStoreId', $obj['store']);
      }
    }

    if (isset($obj['status']) != 7) {
      if (isset($obj['status']) && $obj['status'] != 0) {
        $this->db->where('voucher_status.VoucherStatusId', $obj['status']);
      }
    } else {
      $this->db->where('voucher_status.VoucherStatusId', $obj['status']);
    }

    if (isset($obj['type']) && $obj['type'] != 0) {
      $this->db->where('voucher_type.VoucherTypeId', $obj['type']);
    }

    if (isset($obj['terminal']) && $obj['terminal'] != 'ALL') {
      $this->db->where('voucher_issuance.Source', $obj['terminal']);
    }

    if (isset($obj['reason']) && $obj['reason'] != 0) {
      $this->db->where('reason.ReasonId', $obj['reason']);
    }

    if (isset($obj['receipt']) && $obj['receipt'] != 0) {
      $this->db->join("voucher_activity","voucher_activity.VouchersId = vouchers.VoucherId");
        $this->db->where('voucher_activity.ReceiptNumber', $obj['receipt']);
    }

    if ($permission->UserRoleId != 1) {
      if ($permission->StoreId != 0) {
          if (isset($obj['status']) && $obj['status'] == 7) {
            $this->db->where("voucher_issuance.IssuanceStoreId", $permission->StoreCode);
            $this->db->or_where("voucher_activity.StoreId", $permission->StoreId);
          }else {
            $this->db->where("voucher_issuance.IssuanceStoreId", $permission->StoreId);
          }

          if (isset($obj['status'])) {
            if ($obj['status'] == 7) {
              $this->db->where('VoucherActivityCategoryId',3);
              $this->db->order_by('vouchers.RedemptionStore','asc');
              $this->db->order_by('vouchers.RedemptionDateTime','asc');
              if (isset($obj['start']) && $obj['start'] != null) {
                $this->db->where('DATE(vouchers.RedemptionDateTime) >=',$obj['start']);
              }
              if (isset($obj['end']) && $obj['end'] != null) {
                $this->db->where('DATE(vouchers.RedemptionDateTime) <=',$obj['end']);
              }
              if (isset($obj['store']) && $obj['store'] != 0) {
                $this->db->where('voucher_activity.StoreId', $obj['store']);
              }
            } elseif ($obj['status'] == 8) {
              $this->db->order_by("DATE(voucher_status_history.RefundDate)", "asc");
              if (isset($obj['start']) && $obj['start'] != null) {
                $this->db->where('DATE(voucher_status_history.RefundDate) >=',$obj['start']);
              }
              if (isset($obj['end']) && $obj['end'] != null) {
                $this->db->where('DATE(voucher_status_history.RefundDate) <=',$obj['end']);
              }
              if (isset($obj['store']) && $obj['store'] != 0) {
                $this->db->where('voucher_issuance.IssuanceStoreId', $obj['store']);
              }
            } elseif ($obj['status'] == 4) {
              $this->db->order_by("DATE(voucher_status_history.VoidDate)", "asc");
              if (isset($obj['start']) && $obj['start'] != null) {
                $this->db->where('DATE(voucher_status_history.VoidDate) >=',$obj['start']);
              }
              if (isset($obj['end']) && $obj['end'] != null) {
                $this->db->where('DATE(voucher_status_history.VoidDate) <=',$obj['end']);
              }
              if (isset($obj['store']) && $obj['store'] != 0) {
                $this->db->where('voucher_issuance.IssuanceStoreId', $obj['store']);
              }
            } elseif ($obj['status'] == 6) {
              $this->db->order_by("DATE(voucher_status_history.BlockDate)", "asc");
              if (isset($obj['start']) && $obj['start'] != null) {
                $this->db->where('DATE(voucher_status_history.BlockDate) >=',$obj['start']);
              }
              if (isset($obj['end']) && $obj['end'] != null) {
                $this->db->where('DATE(voucher_status_history.BlockDate) <=',$obj['end']);
              }
              if (isset($obj['store']) && $obj['store'] != 0) {
                $this->db->where('voucher_issuance.IssuanceStoreId', $obj['store']);
              }
            } elseif ($obj['status'] == 5) {
              $this->db->order_by("DATE(voucher_status_history.CancelDate)", "asc");
              if (isset($obj['start']) && $obj['start'] != null) {
                $this->db->where('DATE(voucher_status_history.CancelDate) >=',$obj['start']);
              }
              if (isset($obj['end']) && $obj['end'] != null) {
                $this->db->where('DATE(voucher_status_history.CancelDate) <=',$obj['end']);
              }
              if (isset($obj['store']) && $obj['store'] != 0) {
                $this->db->where('voucher_issuance.IssuanceStoreId', $obj['store']);
              }
            } elseif ($obj['status'] == 3) {
              $this->db->order_by("DATE(voucher_status_history.ExtendDate)", "asc");
              if (isset($obj['start']) && $obj['start'] != null) {
                $this->db->where('DATE(voucher_status_history.ExtendDate) >=',$obj['start']);
              }
              if (isset($obj['end']) && $obj['end'] != null) {
                $this->db->where('DATE(voucher_status_history.ExtendDate) <=',$obj['end']);
              }
              if (isset($obj['store']) && $obj['store'] != 0) {
                $this->db->where('voucher_issuance.IssuanceStoreId', $obj['store']);
              }
            } else {
              $this->db->order_by("store.StoreCode","asc");
              $this->db->order_by("DATE(app_logger.CreatedDate)","asc");
              $this->db->order_by("vouchers.Numbering","asc");
              $this->db->order_by("vouchers.VouchersValue","asc");
              if (isset($obj['start']) && $obj['start'] != null) {
                $this->db->where('DATE(voucher_issuance.ReceiptDateTime) >=',$obj['start']);
              }
              if (isset($obj['end']) && $obj['end'] != null) {
                $this->db->where('DATE(voucher_issuance.ReceiptDateTime) <=',$obj['end']);
              }
            }
          } else {
            $this->db->order_by("store.StoreCode","asc");
            $this->db->order_by("DATE(voucher_issuance.ReceiptDateTime)","asc");
            $this->db->order_by("vouchers.Numbering","asc");
            $this->db->order_by("vouchers.VouchersValue","asc");
            if (isset($obj['start']) && $obj['start'] != null) {
              $this->db->where('DATE(voucher_issuance.ReceiptDateTime) >=',$obj['start']);
            }
            if (isset($obj['end']) && $obj['end'] != null) {
              $this->db->where('DATE(voucher_issuance.ReceiptDateTime) <=',$obj['end']);
            }
            if (isset($obj['store']) && $obj['store'] != 0) {
              $this->db->where('voucher_issuance.IssuanceStoreId', $obj['store']);
            }
          }

          if (isset($obj['status']) && $obj['status'] != 0) {
            $this->db->where('voucher_status.VoucherStatusId', $obj['status']);
          }

          if (isset($obj['type']) && $obj['type'] != 0) {
            $this->db->where('voucher_type.VoucherTypeId', $obj['type']);
          }
          if (isset($obj['terminal']) && $obj['terminal'] != 'ALL') {
            $this->db->where('voucher_issuance.Source', $obj['terminal']);
          }

          if (isset($obj['reason']) && $obj['reason'] != 0) {
            $this->db->where('reason.ReasonId', $obj['reason']);
          }

          if (isset($obj['receipt']) && $obj['receipt'] != 0) {
            $this->db->join("voucher_activity","voucher_activity.VouchersId = vouchers.VoucherId");
              $this->db->where('voucher_activity.ReceiptNumber', $obj['receipt']);
          }
        }
      }

    $query = $this->db->get('vouchers');
    return $query->result();
  }

  function get_voucher_value($data) {
    $this->db->select('vouchers.VoucherIssuanceId,vouchers.VouchersValue,COUNT(VouchersValue) as Total,voucher_type.VoucherShortName');
    $this->db->join("voucher_type","voucher_type.VoucherTypeId = vouchers.VoucherTypeId");
    $schunk = array_chunk($data,1000);
    foreach($schunk as $rowchunk)
    {
      $this->db->or_where_in('vouchers.VoucherId', $rowchunk);
    }
    $this->db->group_by(array('vouchers.VoucherIssuanceId','vouchers.VoucherTypeId','vouchers.VouchersValue'));
    // $this->db->where_in('vouchers.VoucherId',$data);
    $query = $this->db->get('vouchers');
    return $query->result();
  }

  function get_staffid($id,$category){
    $this->db->join("user","user.UserId = voucher_activity.UserId");

    $this->db->where("voucher_activity.VouchersId", $id);
    $this->db->where("voucher_activity.VoucherActivityCategoryId", $category);
    $this->db->order_by("VoucherLogsId","desc");
    $query = $this->db->get('voucher_activity');

    if (isset($query->row()->StaffId)) {
      return $query->row()->StaffId;
    } else {
      return '';
    }
  }

  function get_redeembystatus($id){
    $this->db->join("store","store.StoreId = voucher_activity.StoreId");
    $this->db->join("pos","pos.POSId = voucher_activity.PosId");

    $this->db->where('voucher_activity.VouchersId',$id);
    $this->db->where('voucher_activity.VoucherActivityCategoryId',3);
    $query = $this->db->get('voucher_activity');
    return $query->result();
  }

  function get_receipt_by_status($id,$category){
    $this->db->where("voucher_activity.VouchersId", $id);
    $this->db->where("voucher_activity.VoucherActivityCategoryId", $category);
    $this->db->order_by("VoucherLogsId","desc");
    $query = $this->db->get('voucher_activity');

    if (isset($query->row()->ReceiptNumber)) {
      return $query->row()->ReceiptNumber;
    } else {
      return '';
    }
  }

  function get_store_by_status($id,$category){
    $this->db->join("store","store.StoreId = voucher_activity.StoreId");
    $this->db->where("voucher_activity.VouchersId", $id);
    $this->db->where("voucher_activity.VoucherActivityCategoryId", $category);

    $this->db->order_by("VoucherLogsId","desc");
    $query = $this->db->get('voucher_activity');

    if (isset($query->row()->StoreName)) {
      return $query->row()->StoreName;
    } else {
      return '';
    }
  }

  function get_redeemstore_activity($id,$category){
    $this->db->select('voucher_activity.VouchersId,voucher_activity.VoucherActivityCategoryId,voucher_activity.StoreId,store.StoreCode');
    $this->db->join("store","store.StoreId = voucher_activity.StoreId");
    $this->db->where("voucher_activity.VouchersId", $id);
    $this->db->where("voucher_activity.VoucherActivityCategoryId", $category);
    $this->db->order_by("VoucherLogsId","desc");
    $query = $this->db->get('voucher_activity');

    if (isset($query->row()->StoreCode)) {
      return $query->row()->StoreCode;
    } else {
      return '';
    }
  }

  function get_redeemdatetime_activity($id,$category){
    $this->db->select('voucher_activity.VouchersId,voucher_activity.VoucherActivityCategoryId,voucher_activity.ReceiptDateTime');
    $this->db->where("voucher_activity.VouchersId", $id);
    $this->db->where("voucher_activity.VoucherActivityCategoryId", $category);
    $this->db->order_by("VoucherLogsId","desc");
    $query = $this->db->get('voucher_activity');

    if (isset($query->row()->ReceiptDateTime)) {
      return $query->row()->ReceiptDateTime;
    } else {
      return '';
    }
  }

  function get_type() {
    $query = $this->db->get('voucher_type');
    return $query->row();
  }

  public function giftvoucherupload_report($obj,$permission)
  {
    $this->db->select("*");
    $this->db->join("store","store.StoreId = gift_voucher.StoreId");
    $this->db->join("voucher_status","voucher_status.VoucherStatusId = gift_voucher.VoucherStatusId");
    $this->db->join("voucher_type","voucher_type.VoucherTypeId = gift_voucher.VoucherTypeId");
    $this->db->join("app_logger","app_logger.AppLoggerId = gift_voucher.AppLoggerId");

    if (isset($obj['start']) && $obj['start'] != null) {
      $this->db->where('DATE(app_logger.CreatedDate) >=',$obj['start']);
    }

    if (isset($obj['end']) && $obj['end'] != null) {
      $this->db->where('DATE(app_logger.CreatedDate) <=',$obj['end']);
    }

    if (isset($obj['status']) && $obj['status'] != 0) {
      $this->db->where('voucher_status.VoucherStatusId', $obj['status']);
    }

    if (isset($obj['type']) && $obj['type'] != 0) {
      $this->db->where('voucher_type.VoucherTypeId', $obj['type']);
    }

    if (isset($obj['store']) && $obj['store'] != 0) {
      $this->db->where('store.StoreId', $obj['store']);
    }

    if ($permission->UserRoleId != 1) {
      if ($permission->StoreId != 0) {
        $this->db->where("store.StoreId", $permission->StoreId);
      }
    }

    $this->db->order_by('DATE(gift_voucher.IssuedDate)', 'asc');
    $query = $this->db->get('gift_voucher');
    return $query->result();
  }

  public function giftvouchersold_report($obj,$permission) {
    $this->db->select("*");
    $this->db->join("store","store.StoreId = gift_voucher.StoreId");
    $this->db->join("voucher_status","voucher_status.VoucherStatusId = gift_voucher.VoucherStatusId");
    $this->db->join("voucher_type","voucher_type.VoucherTypeId = gift_voucher.VoucherTypeId");
    $this->db->join("app_logger","app_logger.AppLoggerId = gift_voucher.AppLoggerId");

    if (isset($obj['start']) && $obj['start'] != null) {
      $this->db->where('DATE(gift_voucher.IssuedDate) >=',$obj['start']);
    }

    if (isset($obj['end']) && $obj['end'] != null) {
      $this->db->where('DATE(gift_voucher.IssuedDate) <=',$obj['end']);
    }

    if (isset($obj['status']) && $obj['status'] != 0) {
      $this->db->where('voucher_status.VoucherStatusId', $obj['status']);
    }

    if (isset($obj['type']) && $obj['type'] != 0) {
      $this->db->where('voucher_type.VoucherTypeId', $obj['type']);
    }

    if (isset($obj['store']) && $obj['store'] != 0) {
      $this->db->where('store.StoreId ', $obj['store']);
    }

    if ($permission->UserRoleId != 1) {
      if ($permission->StoreId != 0) {
        $this->db->where("store.StoreId", $permission->StoreId);
      }
    }

    $this->db->order_by('DATE(gift_voucher.IssuedDate)', 'asc');
    $this->db->where('gift_voucher.VoucherStatusId !=',10);
    $query = $this->db->get('gift_voucher');
    return $query->result();
  }

  public function reconciliation_report($obj, $permission) {
    $this->db->select("store.*");
    $this->db->from('vouchers');
    $this->db->join("voucher_issuance","voucher_issuance.VoucherIssuanceId = vouchers.VoucherIssuanceId");
    $this->db->join("store", "store.StoreId = voucher_issuance.IssuanceStoreId");

    if (!empty($obj['store']) && $obj['store'] != 0) {
        $this->db->where('store.StoreId', $obj['store']);
    }

    if ($permission->UserRoleId != 1 && !empty($permission->StoreId)) {
        $this->db->where("store.StoreId", $permission->StoreId);
    }

    $this->db->order_by('store.StoreCode', 'asc');
    $this->db->group_by('store.StoreCode');
    $query = $this->db->get();
    return $query->result();
  }

  public function get_total_voucher_by_status_group($type,$store,$date,$status,$column) {
    if ($column == 'P') {
      $first = date('Y-m',strtotime($date.' - 1 month')).'-16';
      $last  = date('Y-m-t',strtotime($date));
    }elseif ($column == 'D') {
      $first = date('Y-m',strtotime($date.' - 1 month')).'-16';
      $last  = date('Y-m-t',strtotime($date));
    }elseif ($column == 'E') {
      $first = date('Y-m',strtotime($date.' - 2 months')).'-16';
      $last  = date('Y-m',strtotime($date.' - 1 month')).'-15';
    }else {
      $first = date('Y-m',strtotime($date)).'-01';
      $last  = date('Y-m-t',strtotime($date));
    }

    $this->db->select("IssuanceStoreId,SUM(VouchersValue) as VouchersValue");
    $this->db->join("voucher_issuance","voucher_issuance.VoucherIssuanceId = vouchers.VoucherIssuanceId");

    if ($column == 'B') {
      $this->db->join("voucher_status_history","voucher_status_history.VoucherId = vouchers.VoucherId");
      $this->db->where('voucher_status_history.RedeemDate !=', null);
      $this->db->where('DATE(voucher_status_history.RedeemDate) >=',$first);
      $this->db->where('DATE(voucher_status_history.RedeemDate) <=',$last);
    }

    $this->db->where_in('IssuanceStoreId',$store);
    $this->db->where('DATE(voucher_issuance.ReceiptDateTime) >=',$first);
    $this->db->where('DATE(voucher_issuance.ReceiptDateTime) <=',$last);
    if ($status != 0) {
      if ($column == 'D') {
        $array = [1,2,3];
        $this->db->where_in('vouchers.VoucherStatusId', $array);
      } else {
        $this->db->where('VoucherStatusId',$status);
      }
    }
    if ($type != 0) {
      $this->db->where('vouchers.VoucherTypeId',$type);
    }
    $this->db->group_by('IssuanceStoreId');
    $query = $this->db->get('vouchers');
    return $query->result();
  }

  public function unredeemed_notexpired_report($type,$store,$date,$status,$column,$permission) {

    if ($column == 'D') {
      $first = date('Y-m', strtotime($date.' - 1 month')).'-16';
      $last  = date('Y-m-t', strtotime($date));
    } elseif ($column == 'E') {
      $first = date('Y-m',strtotime($date.' - 2 months')).'-16';
      $last  = date('Y-m',strtotime($date.' - 1 month')).'-15';
    } else {
      $first = date('Y-m',strtotime($date)).'-01';
      $last  = date('Y-m-t',strtotime($date));
    }

    $this->db->join("voucher_issuance","voucher_issuance.VoucherIssuanceId = vouchers.VoucherIssuanceId");
    $this->db->join("store","store.StoreId = voucher_issuance.IssuanceStoreId");
    $this->db->join("voucher_type","voucher_type.VoucherTypeId = vouchers.VoucherTypeId");
    $this->db->join("app_logger","app_logger.AppLoggerId = vouchers.AppLoggerId");

    if ($store != 0) {
      $this->db->where('IssuanceStoreId',$store);
    }
    if ($date != null) {
      $this->db->where('DATE(app_logger.CreatedDate) >=',$first);
    }
    if ($date != null) {
      $this->db->where('DATE(app_logger.CreatedDate) <=',$last);
    }

    $array = [1,2,3];
    $this->db->where_in('vouchers.VoucherStatusId', $array);

    if ($type != 0) {
      $this->db->where('vouchers.VoucherTypeId',$type);
    }

    if ($permission->UserRoleId != 1) {
        if ($permission->StoreId != 0) {
          $this->db->where("store.StoreId", $permission->StoreId);
        }
      }

    $this->db->order_by('DATE(app_logger.CreatedDate)', 'asc');
    $query = $this->db->get('vouchers');
    return $query->result();
  }

  public function unredeemed_expired_report($type,$store,$date,$status,$column,$permission) {

    if ($column == 'D') {
      $first = date('Y-m', strtotime($date.' - 1 month')).'-16';
      $last  = date('Y-m-t', strtotime($date));
    } elseif ($column == 'E') {
      $first = date('Y-m',strtotime($date.' - 2 months')).'-16';
      $last  = date('Y-m',strtotime($date.' - 1 month')).'-15';
    } else {
      $first = date('Y-m',strtotime($date)).'-01';
      $last  = date('Y-m-t',strtotime($date));
    }

    $this->db->join("voucher_issuance","voucher_issuance.VoucherIssuanceId = vouchers.VoucherIssuanceId");
    $this->db->join("store","store.StoreId = voucher_issuance.IssuanceStoreId");
    $this->db->join("voucher_type","voucher_type.VoucherTypeId = vouchers.VoucherTypeId");
    $this->db->join("app_logger","app_logger.AppLoggerId = vouchers.AppLoggerId");

    if ($column == 'B') {
      $this->db->join("voucher_status_history","voucher_status_history.VoucherId = vouchers.VoucherId");
      $this->db->where('voucher_status_history.RedeemDate !=', null);
      $this->db->where('DATE(voucher_status_history.RedeemDate) >=',$first);
      $this->db->where('DATE(voucher_status_history.RedeemDate) <=',$last);
    }

    if ($store != 0) {
      $this->db->where('IssuanceStoreId',$store);
    }
    if ($date != null) {
      $this->db->where('DATE(app_logger.CreatedDate) >=',$first);
    }
    if ($date != null) {
      $this->db->where('DATE(app_logger.CreatedDate) <=',$last);
    }

    if ($type != 0) {
      $this->db->where('vouchers.VoucherTypeId',$type);
    }

    if ($permission->UserRoleId != 1) {
        if ($permission->StoreId != 0) {
          $this->db->where("store.StoreId", $permission->StoreId);
        }
      }

    $this->db->where('vouchers.VoucherStatusId',2);
    $this->db->order_by('DATE(app_logger.CreatedDate)', 'asc');
    $query = $this->db->get('vouchers');
    return $query->result();
  }


  public function get_expired_nonexpired($type,$store,$date,$status,$column) {

    if ($column == 'D') {
      $first = date('Y-m', strtotime($date.'- 1 month')).'-16';
      $last  = date('Y-m-t', strtotime($date));
    } elseif ($column == 'E') {
      $first = date('Y-m',strtotime($date.' - 2 months')).'-16';
      $last  = date('Y-m',strtotime($date.' - 1 month')).'-15';
    } else {
      $first = date('Y-m',strtotime($date)).'-01';
      $last  = date('Y-m-t',strtotime($date));
    }

    $this->db->select_sum("VouchersValue");
    $this->db->join("voucher_issuance","voucher_issuance.VoucherIssuanceId = vouchers.VoucherIssuanceId");
    $this->db->join("app_logger","app_logger.AppLoggerId = vouchers.AppLoggerId");

    if ($column == 'B') {
      $this->db->join("voucher_status_history","voucher_status_history.VoucherId = vouchers.VoucherId");
      $this->db->where('voucher_status_history.RedeemDate !=', null);
      $this->db->where('DATE(voucher_status_history.RedeemDate) >=',$first);
      $this->db->where('DATE(voucher_status_history.RedeemDate) <=',$last);
    }

    $this->db->where('IssuanceStoreId',$store);
    $this->db->where('DATE(app_logger.CreatedDate) >=',$first);
    $this->db->where('DATE(app_logger.CreatedDate) <=',$last);
    if ($status != 0) {
      if ($column == 'D') {
        $array = [1,2,3];
        $this->db->where_in('vouchers.VoucherStatusId', $array);
      } else {
        $this->db->where('VoucherStatusId',$status);
      }
    }
    if ($type != 0) {
      $this->db->where('vouchers.VoucherTypeId',$type);
    }
    $query = $this->db->get('vouchers');
    return $query->row()->VouchersValue;
  }

  // public function unredeemed_notexpired_report($type,$store,$date,$status,$column,$permission)
  // {
  //   if ($column == 'D') {
  //     $first = date('Y-m', strtotime($date.' - 1 month')).'-16';
  //     $last  = date('Y-m-t', strtotime($date));
  //   } elseif ($column == 'E') {
  //     $first = date('Y-m',strtotime($date.' - 2 months')).'-16';
  //     $last  = date('Y-m',strtotime($date.' - 1 month')).'-15';
  //   } else {
  //     $first = date('Y-m',strtotime($date)).'-01';
  //     $last  = date('Y-m-t',strtotime($date));
  //   }
  //
  //   $this->db->join("voucher_issuance","voucher_issuance.VoucherIssuanceId = vouchers.VoucherIssuanceId");
  //   $this->db->join("store","store.StoreId = voucher_issuance.IssuanceStoreId");
  //   $this->db->join("voucher_type","voucher_type.VoucherTypeId = vouchers.VoucherTypeId");
  //   $this->db->join("app_logger","app_logger.AppLoggerId = vouchers.AppLoggerId");
  //
  //   if ($column == 'B') {
  //     $this->db->join("voucher_status_history","voucher_status_history.VoucherId = vouchers.VoucherId");
  //     $this->db->where('voucher_status_history.RedeemDate !=', null);
  //     $this->db->where('DATE(voucher_status_history.RedeemDate) >=',$first);
  //     $this->db->where('DATE(voucher_status_history.RedeemDate) <=',$last);
  //   }
  //
  //   // $this->db->where('DATE(app_logger.CreatedDate) >=',$first);
  //   // $this->db->where('DATE(app_logger.CreatedDate) <=',$last);
  //
  //   if ($store != 0) {
  //     $this->db->where('IssuanceStoreId',$store);
  //   }
  //   if ($date != null) {
  //     $this->db->where('DATE(app_logger.CreatedDate) >=',$first);
  //   }
  //   if ($date != null) {
  //     $this->db->where('DATE(app_logger.CreatedDate) <=',$last);
  //   }
  //
  //   $array = [1,2,3];
  //   $this->db->where_in('vouchers.VoucherStatusId', $array);
  //
  //   if ($type != 0) {
  //     $this->db->where('vouchers.VoucherTypeId',$type);
  //   }
  //
  //   if ($permission->UserRoleId != 1) {
  //       if ($permission->StoreId != 0) {
  //         $this->db->where("store.StoreId", $permission->StoreId);
  //       }
  //     }
  //
  //   $this->db->order_by('DATE(app_logger.CreatedDate)', 'asc');
  //   $query = $this->db->get('vouchers');
  //   return $query->result();
  // }

  // public function unredeemed_expired_report($type,$store,$date,$status,$column,$permission) {
  //   if ($column == 'D') {
  //     $first = date('Y-m', strtotime($date.' - 1 month')).'-16';
  //     $last  = date('Y-m-t', strtotime($date));
  //   } elseif ($column == 'E') {
  //     $first = date('Y-m',strtotime($date.' - 2 months')).'-16';
  //     $last  = date('Y-m',strtotime($date.' - 1 month')).'-15';
  //   } else {
  //     $first = date('Y-m',strtotime($date)).'-01';
  //     $last  = date('Y-m-t',strtotime($date));
  //   }
  //
  //   $this->db->join("voucher_issuance","voucher_issuance.VoucherIssuanceId = vouchers.VoucherIssuanceId");
  //   $this->db->join("store","store.StoreId = voucher_issuance.IssuanceStoreId");
  //   $this->db->join("voucher_type","voucher_type.VoucherTypeId = vouchers.VoucherTypeId");
  //   $this->db->join("app_logger","app_logger.AppLoggerId = vouchers.AppLoggerId");
  //
  //   if ($column == 'B') {
  //     $this->db->join("voucher_status_history","voucher_status_history.VoucherId = vouchers.VoucherId");
  //     $this->db->where('voucher_status_history.RedeemDate !=', null);
  //     $this->db->where('DATE(voucher_status_history.RedeemDate) >=',$first);
  //     $this->db->where('DATE(voucher_status_history.RedeemDate) <=',$last);
  //   }
  //
  //   // $this->db->where('DATE(app_logger.CreatedDate) >=',$first);
  //   // $this->db->where('DATE(app_logger.CreatedDate) <=',$last);
  //
  //   if ($store != 0) {
  //     $this->db->where('IssuanceStoreId',$store);
  //   }
  //   if ($date != null) {
  //     $this->db->where('DATE(app_logger.CreatedDate) >=',$first);
  //   }
  //   if ($date != null) {
  //     $this->db->where('DATE(app_logger.CreatedDate) <=',$last);
  //   }
  //
  //   if ($type != 0) {
  //     $this->db->where('vouchers.VoucherTypeId',$type);
  //   }
  //
  //   if ($permission->UserRoleId != 1) {
  //       if ($permission->StoreId != 0) {
  //         $this->db->where("store.StoreId", $permission->StoreId);
  //       }
  //     }
  //
  //   $this->db->where('vouchers.VoucherStatusId',2);
  //   $this->db->order_by('DATE(app_logger.CreatedDate)', 'asc');
  //   $query = $this->db->get('vouchers');
  //   return $query->result();
  // }

  // public function get_expired_nonexpired($type,$store,$date,$status,$column) {
  //   if ($column == 'D') {
  //     $first = date('Y-m', strtotime($date.'- 1 month')).'-16';
  //     $last  = date('Y-m-t', strtotime($date));
  //   } elseif ($column == 'E') {
  //     $first = date('Y-m',strtotime($date.' - 2 months')).'-16';
  //     $last  = date('Y-m',strtotime($date.' - 1 month')).'-15';
  //   } else {
  //     $first = date('Y-m',strtotime($date)).'-01';
  //     $last  = date('Y-m-t',strtotime($date));
  //   }
  //
  //   $this->db->select_sum("VouchersValue");
  //   $this->db->join("voucher_issuance","voucher_issuance.VoucherIssuanceId = vouchers.VoucherIssuanceId");
  //   $this->db->join("app_logger","app_logger.AppLoggerId = vouchers.AppLoggerId");
  //   if ($column == 'B') {
  //     $this->db->join("voucher_status_history","voucher_status_history.VoucherId = vouchers.VoucherId");
  //     $this->db->where('voucher_status_history.RedeemDate !=', null);
  //     $this->db->where('DATE(voucher_status_history.RedeemDate) >=',$first);
  //     $this->db->where('DATE(voucher_status_history.RedeemDate) <=',$last);
  //   }
  //   $this->db->where('IssuanceStoreId',$store);
  //   $this->db->where('DATE(app_logger.CreatedDate) >=',$first);
  //   $this->db->where('DATE(app_logger.CreatedDate) <=',$last);
  //   if ($status != 0) {
  //     if ($column == 'D') {
  //       $array = [1,2,3];
  //       $this->db->where_in('vouchers.VoucherStatusId', $array);
  //     } else {
  //       $this->db->where('VoucherStatusId',$status);
  //     }
  //   }
  //   if ($type != 0) {
  //     $this->db->where('vouchers.VoucherTypeId',$type);
  //   }
  //   $query = $this->db->get('vouchers');
  //   return $query->row()->VouchersValue;
  // }

  public function userlog_report($obj,$permission) {
    $this->db->select('*');
    $this->db->join("user","user.UserId = user_activity.UserId");
    $this->db->join("app_logger","app_logger.AppLoggerId = user.AppLoggerId");
    $this->db->join("user_role", "user_role.UserRoleId = user.UserRoleId");
    $this->db->join("activity_type", "activity_type.ActivityTypeId = user_activity.ActivityTypeId");
    $this->db->join("user_Store", "user_Store.UserId = user.UserId");
    $this->db->join("store", "store.StoreId = user_Store.StoreId");

    if (isset($obj['start']) && $obj['start'] != null) {
      $this->db->order_by("DATE(user_activity.ActiveDate)");
      $this->db->where('DATE(user_activity.ActiveDate) >=',$obj['start']);
    }

    if (isset($obj['end']) && $obj['end'] != null) {
      $this->db->order_by("DATE(user_activity.ActiveDate)");
      $this->db->where('DATE(user_activity.ActiveDate) <=',$obj['end']);
    }

    if ($permission->UserRoleId != 1) {
      if ($permission->StoreId != 0) {
        $this->db->where("user_Store.StoreId", $permission->StoreId);
      }
    }

    $query = $this->db->get('user_activity');
    return $query->result();
  }

  public function voucherlog_report($obj,$permission) {
    $this->db->select("*");
    $this->db->join("user","user.UserId = voucher_activity.UserId");
    $this->db->join("user_role","user_role.UserRoleId = user.UserRoleId");
    $this->db->join("voucher_type", "voucher_type.VoucherTypeId = voucher_activity.VoucherTypeId");
    $this->db->join("voucher_activity_category", "voucher_activity_category.VoucherActivityCategoryId = voucher_activity.VoucherActivityCategoryId");
    $this->db->join("user_store", "user_store.UserId = user.UserId");
    $this->db->join("store", "store.StoreId = user_store.StoreId");

    if (isset($obj['start']) && $obj['start'] != null) {
      $this->db->order_by("DATE(voucher_activity.ActivityDate)");
      $this->db->where('DATE(voucher_activity.ActivityDate) >=',$obj['start']);
    }

    if (isset($obj['end']) && $obj['end'] != null) {
      $this->db->order_by("DATE(voucher_activity.ActivityDate)");
      $this->db->where('DATE(voucher_activity.ActivityDate) <=',$obj['end']);
    }

    if (isset($obj['category']) && $obj['category'] != 0) {
      $this->db->where('voucher_activity.VoucherActivityCategoryId', $obj['category']);
    }

    // if ($permission->UserRoleId != 1 || $permission->StoreId != 0) {
    //   $this->db->where("user_store.StoreId", $permission->StoreId);
    // }

    if ($permission->UserRoleId != 1) {
      if ($permission->StoreId != 0) {
        $this->db->where("user_Store.StoreId", $permission->StoreId);
      }
    }

    $query = $this->db->get('voucher_activity');
    return $query->result();
  }

  public function voucherissuance_campaign_report($obj,$permission) {
    $this->db->select("*");
    $this->db->join("campaign", "campaign.CampaignId = vouchers.CampaignId");
    $this->db->join("voucher_type", "voucher_type.VoucherTypeId = vouchers.VoucherTypeId");
    $this->db->join("voucher_status", "voucher_status.VoucherStatusId = vouchers.VoucherStatusId");
    $this->db->join("voucher_issuance","voucher_issuance.VoucherIssuanceId = vouchers.VoucherIssuanceId");
    $this->db->join("store","store.StoreId = voucher_issuance.IssuanceStoreId");

    if (isset($obj['start']) && $obj['start'] != null) {
      $this->db->where('DATE(campaign.StartDate) >=',$obj['start']);
    }

    if (isset($obj['end']) && $obj['end'] != null) {
      $this->db->where('DATE(campaign.EndDate) <=',$obj['end']);
    }

    if (isset($obj['type']) && $obj['type'] != 0) {
      $this->db->where('voucher_type.VoucherTypeId', $obj['type']);
    }

    if (isset($obj['campaign']) && $obj['campaign'] != 0) {
      $this->db->where('vouchers.CampaignId', $obj['campaign']);
    }

    if ($permission->UserRoleId != 1) {
      if ($permission->StoreId != 0) {
        $this->db->where("store.StoreId", $permission->StoreId);
      }
    }

    $this->db->order_by('DATE(campaign.StartDate)', 'asc');
    $query = $this->db->get('vouchers');
    return $query->result();
  }

  public function duplicatevoucher_report($obj,$permission) {
    $this->db->select("*");
    $this->db->select('COUNT(vouchers.VouchersNumber) as Total');
    $this->db->join("voucher_issuance","voucher_issuance.VoucherIssuanceId = vouchers.VoucherIssuanceId");
    $this->db->join("voucher_status","voucher_status.VoucherStatusId = vouchers.VoucherStatusId");
    $this->db->join("store","store.StoreId = voucher_issuance.IssuanceStoreId");
    $this->db->join("app_logger","app_logger.AppLoggerId = vouchers.AppLoggerId");
    $this->db->join("voucher_type","voucher_type.VoucherTypeId = vouchers.VoucherTypeId");
    $this->db->join("pos","pos.POSId = voucher_issuance.IssuancePOSId","inner");

    if (isset($obj['start']) && $obj['start'] != null) {
      $this->db->where('DATE(app_logger.CreatedDate) >=',$obj['start']);
    }

    if (isset($obj['end']) && $obj['end'] != null) {
      $this->db->where('DATE(app_logger.CreatedDate) <=',$obj['end']);
    }

    if (isset($obj['status']) && $obj['status'] != 0) {
      $this->db->where('voucher_status.VoucherStatusId', $obj['status']);
    }

    if (isset($obj['type']) && $obj['type'] != 0) {
      $this->db->where('voucher_type.VoucherTypeId', $obj['type']);
    }

    if (isset($obj['store']) && $obj['store'] != 0) {
      $this->db->where('voucher_issuance.IssuanceStoreId', $obj['store']);
    }

    if ($permission->UserRoleId != 1) {
      if ($permission->StoreId != 0) {
        $this->db->where("voucher_issuance.IssuanceStoreId", $permission->StoreId);
      }
    }

    $this->db->group_by('vouchers.VouchersNumber');
    $query = $this->db->get('vouchers');
    return $query->result();
  }

  function get_duplicate($data) {
    $this->db->select("*");
    $this->db->join("voucher_issuance","voucher_issuance.VoucherIssuanceId = vouchers.VoucherIssuanceId");
    $this->db->join("voucher_status","voucher_status.VoucherStatusId = vouchers.VoucherStatusId");
    $this->db->join("store","store.StoreId = voucher_issuance.IssuanceStoreId");
    $this->db->join("app_logger","app_logger.AppLoggerId = vouchers.AppLoggerId");
    $this->db->join("voucher_type","voucher_type.VoucherTypeId = vouchers.VoucherTypeId");
    $this->db->join("pos","pos.POSId = voucher_issuance.IssuancePOSId","inner");

    $this->db->where_in('vouchers.VouchersNumber',$data);
    $query = $this->db->get('vouchers');
    return $query->result();
  }

  public function duplicatereceipt_report($obj,$permission) {
      $this->db->select("*");
      $this->db->select('COUNT(ReceiptNumber) as Total');
      // $this->db->join("vouchers","vouchers.VoucherIssuanceId = voucher_issuance.VoucherIssuanceId");
      $this->db->join("store","store.StoreId = voucher_issuance.IssuanceStoreId");
      $this->db->join("pos","pos.POSId = voucher_issuance.IssuancePOSId", "inner");
      // $this->db->join("voucher_type","voucher_type.VoucherTypeId = vouchers.VoucherTypeId");
      // $this->db->join("voucher_status","voucher_status.VoucherStatusId = vouchers.VoucherStatusId");
      // $this->db->join("app_logger","app_logger.AppLoggerId = vouchers.AppLoggerId");

      if (isset($obj['start']) && $obj['start'] != null) {
        $this->db->where('DATE(voucher_issuance.ReceiptDateTime) >=',$obj['start']);
      }

      if (isset($obj['end']) && $obj['end'] != null) {
        $this->db->where('DATE(voucher_issuance.ReceiptDateTime) <=',$obj['end']);
      }

      // if (isset($obj['status']) && $obj['status'] != 0) {
      //   $this->db->where('voucher_status.VoucherStatusId', $obj['status']);
      // }

      if (isset($obj['type']) && $obj['type'] != 0) {
        $this->db->where('voucher_type.VoucherTypeId', $obj['type']);
      }

      if (isset($obj['store']) && $obj['store'] != 0) {
        $this->db->where('voucher_issuance.IssuanceStoreId', $obj['store']);
      }

      if ($permission->UserRoleId != 1) {
        if ($permission->StoreId != 0) {
          $this->db->where("voucher_issuance.IssuanceStoreId", $permission->StoreId);
        }
      }

      $this->db->group_by('voucher_issuance.ReceiptNumber');
      $query = $this->db->get('voucher_issuance');
      return $query->result();
    }

    function get_duplicatereceipt_group($data) {
      $this->db->select("*");
      $this->db->join("vouchers","vouchers.VoucherIssuanceId = voucher_issuance.VoucherIssuanceId");
      $this->db->join("store","store.StoreId = voucher_issuance.IssuanceStoreId");
      $this->db->join("pos","pos.POSId = voucher_issuance.IssuancePOSId", "inner");
      $this->db->join("voucher_type","voucher_type.VoucherTypeId = vouchers.VoucherTypeId");
      $this->db->join("voucher_status","voucher_status.VoucherStatusId = vouchers.VoucherStatusId");
      // $this->db->join("app_logger","app_logger.AppLoggerId = vouchers.AppLoggerId");

      $this->db->where_in('voucher_issuance.ReceiptNumber',$data);
      $this->db->group_by('voucher_issuance.ReceiptNumber');
      $this->db->order_by('voucher_issuance.VoucherIssuanceId');
      $query = $this->db->get('voucher_issuance');
      return $query->result();
    }

  function get_duplicatereceipt($data) {
    $this->db->select("*");
    $this->db->join("vouchers","vouchers.VoucherIssuanceId = voucher_issuance.VoucherIssuanceId");
    $this->db->join("store","store.StoreId = voucher_issuance.IssuanceStoreId");
    $this->db->join("pos","pos.POSId = voucher_issuance.IssuancePOSId", "inner");
    $this->db->join("voucher_type","voucher_type.VoucherTypeId = vouchers.VoucherTypeId");
    $this->db->join("voucher_status","voucher_status.VoucherStatusId = vouchers.VoucherStatusId");
    // $this->db->join("app_logger","app_logger.AppLoggerId = vouchers.AppLoggerId");

    $this->db->where_in('voucher_issuance.ReceiptNumber',$data);
    $this->db->order_by('voucher_issuance.VoucherIssuanceId');
    $query = $this->db->get('voucher_issuance');
    return $query->result();
  }

  function get_permissionVoucherList($id) {
    $query = $this->db->join("user","user.UserRoleId = user_store.UserRoleId");
    $query = $this->db->join("store","store.StoreId = user_store.StoreId");

    $this->db->where("user_store.UserId" , $id);
    $query = $this->db->get('user_store');
    return $query->row();
  }

  function get_vouchertype($id) {
    $this->db->where('voucher_type.VoucherTypeId',$id);
    $query = $this->db->get('voucher_type');
    if (isset($query->row()->VoucherName)) {
      return $query->row()->VoucherName;
    } else {
      return 'All Voucher';
    }
  }

  function get_voucherstatus($id) {
    $this->db->where('voucher_status.VoucherStatusId',$id);
    $query = $this->db->get('voucher_status');
    if (isset($query->row()->VoucherStatusName)) {
      return $query->row()->VoucherStatusName;
    } else {
      return 'ALL STATUS';
    }
  }

  function get_campaignname($id) {
    $this->db->where('campaign.CampaignId',$id);
    $query = $this->db->get('campaign');
    if (isset($query->row()->CampaignName)) {
      return $query->row()->CampaignName;
    } else {
      return 'ALL CAMPAIGN';
    }
  }

  function get_bystoreadmin($id) {
    $this->db->where('store.StoreId',$id);
    $query = $this->db->get('store');
    if (isset($query->row()->StoreName)) {
      return $query->row()->StoreName;
    } else {
      return 'ALL STORE';
    }
  }

  function get_bystore($id) {
    $this->db->join("user_store","user_store.UserId = user.UserId");
    $this->db->join("store","store.StoreId = user_store.StoreId");

    $this->db->where('user.UserId',$id);
    $query = $this->db->get('user');
    return $query->row()->StoreName;
  }

  function get_user($id) {
    $this->db->join("user_role","user_role.UserRoleId = user.UserRoleId");

    $this->db->where("user.UserId",$id);
    $query = $this->db->get('user');
    return $query->row();
  }

  function get_permission($id) {
    $query = $this->db->join("user","user.UserRoleId = user_store.UserRoleId");

    $this->db->where("user_store.UserId" , $id);
    $query = $this->db->get('user_store');
    return $query->row();
  }

  function get_loginid($id) {
    $this->db->where("user.UserId", $id);
    $query = $this->db->get('user');

    if (isset($query->row()->StaffId)) {
      return $query->row()->StaffId;
    } else {
      return '';
    }
  }

  function get_storecode($id) {
    $this->db->where("store.StoreId", $id);
    $query = $this->db->get('store');

    if (isset($query->row()->StoreId)) {
      return $query->row()->StoreId;
    } else {
      return '';
    }
  }

  public function get_vouchers(){
    $this->db->limit(1);
    $query = $this->db->get('vouchers');
    return $query->result();
  }

  public function vouchers_report($obj,$permission,$storecode){
    $this->db->select('*');
    $this->db->join("vouchers","vouchers.VoucherId = voucher_activity.VouchersId");
    $this->db->join("voucher_status","voucher_status.CategoryId = voucher_activity.VoucherActivityCategoryId");
    $this->db->join("redeem_type","redeem_type.RedeemTypeId = vouchers.RedeemTypeId");
    $this->db->join("store","store.StoreId = voucher_activity.StoreId");
    $this->db->join("pos","pos.POSId = voucher_activity.PosId");

    if (isset($obj['start']) && $obj['start'] != null) {
      $this->db->where('DATE(voucher_activity.ActivityDate) >=',$obj['start']);
    }
    if (isset($obj['end']) && $obj['end'] != null) {
      $this->db->where('DATE(voucher_activity.ActivityDate) <=',$obj['end']);
    }

    $this->db->where('CategoryId',1);
    $query = $this->db->get('voucher_activity');
    return $query->result();
  }

  public function get_voucherdetail(){
    $this->db->join("voucher_activity","voucher_activity.VouchersId = vouchers.VoucherId");

    $this->db->limit(1);
    $query = $this->db->get('vouchers');
    return $query->result();
  }
}
?>
