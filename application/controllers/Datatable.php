<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datatable extends CI_Controller {

	function __construct()
	{
		parent:: __construct();
		$this->load->model('Datatable_model');
	}

	public function index()
	{
		$data['store']  = $this->Datatable_model->get_store();
		$data['status'] = $this->Datatable_model->get_status();
		$data['type']   = $this->Datatable_model->get_type();

		$this->load->view('datatable',$data);
	}

	public function list(){
    $post  = $this->input->post();

		//FIRST LAYER SEARCHING
		if ($post['VoucherNumber'] != '') {
			$post['voucher'] = $this->Datatable_model->get_voucher_number($post['VoucherNumber']);
		}

		if (!empty($post['PosNumber'])) {
			$post['pos'] = $this->Datatable_model->get_voucher_pos($post['PosNumber']);
		}

		if ($post['VoucherStatus'] == 3 || $post['VoucherStatus'] == 5 || $post['VoucherStatus'] == 6) {
			if ($post['StartDate'] != '' && $post['EndDate'] != '') {
				$post['history'] = $this->Datatable_model->get_voucher_history($post);
			}
		}
		//FIRST LAYER SEARCHING

		//FILTER BY CONDITION
		if ($post['VoucherStatus'] != '' && $post['StartDate'] != '' && $post['EndDate'] != '' ) {
			if ($post['VoucherStatus'] != 2 && $post['VoucherStatus'] != 3 && $post['VoucherStatus'] != 5 && $post['VoucherStatus'] != 6) {
				$post['vouchid'] = [];
				if ($post['ReceiptNumber'] != '') {
		      $post['vouchid'] = $this->Datatable_model->get_voucher_receipt($post['ReceiptNumber']);
		    }
				$post['activity'] = $this->Datatable_model->get_voucher_activity($post);
				if (!empty($post['activity'])) {
					$post['empty'] = 1;
				}else {
					$post['empty'] = 2;
				}
			}else {
				$post['empty'] = 3;
			}
		}else {
			$post['empty'] = 3;
		}
		//FILTER BY CONDITION

    $list  = $this->Datatable_model->get_datatables($post);
    $table = array();
    $no    = $post['start'];

		foreach ($list as $field) {

			$no++;
			$row   = array();

			$row[] = $field->VouchersNumber;
			$row[] = $field->StoreCode;
			$row[] = $field->VoucherShortName;
			$row[] = $field->VouchersValue;
			$row[] = date('d-m-Y', strtotime($field->ExpDate));
			$row[] = date('d-m-Y h:i A', strtotime($field->CreatedDate));
			$row[] = '<div class="ms-3 badge bg-label-'.$field->VoucherStatusColor.'">'.$field->VoucherStatusName.'</div>';

			$table[] = $row;
		}

    $output = array(
      "draw"                => $post['draw'],
      "recordsTotal"        => $this->Datatable_model->count_all($post),
      "recordsFiltered"     => $this->Datatable_model->count_filtered($post),
      "data"                => $table,
    );
    echo json_encode($output);
  }
}
