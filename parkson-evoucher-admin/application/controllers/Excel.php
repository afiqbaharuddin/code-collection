<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require FCPATH  . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Excel extends CI_Controller {

	function __construct()
	{
		parent:: __construct();
		$this->load->model('Excel_model');
	}

	public function index()
	{
		$data['store']  = $this->Excel_model->get_store();
		$data['status'] = $this->Excel_model->get_status();
		$data['type']   = $this->Excel_model->get_type();
		$data['reason'] = $this->Excel_model->get_reason();

		$this->load->view('excel',$data);
	}

	function export(){
		$data = array_merge($this->global_data);
		$get = $this->input->post();

		

		die;

		$customer = $this->Transaction_model->get_customer();

		$cust = [];
		foreach ($customer as $row) {
			$cust[] = $row->CustomerId;
		}

		$transaction = $this->Transaction_model->get_transaction($get,$cust);
		$usage = $this->Transaction_model->get_usage($get,$cust);

		$extension = 'xlsx';
		$this->load->helper('download');
    $data = array();
    $data['title'] = 'Export Excel Sheet';
    $fileName = 'Accounting Transaction History - '.time();
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A1', 'NO');
    $sheet->setCellValue('B1', 'Date');
    $sheet->setCellValue('C1', 'Client');
    $sheet->setCellValue('D1', 'Top-Up');
		$sheet->setCellValue('E1', 'Usage History');

    $rowCount = 2;
		$count = 1;
		foreach ($customer as $row) {

			$searchFor    = $row->CustomerId;
			$filterbytran =
			array_values(array_filter($transaction, function($element) use($searchFor){
				return isset($element->CustomerId) && $element->CustomerId == $searchFor;
			}));

			if (!empty($filterbytran)) {
				foreach ($filterbytran as $row1) {
					$sheet->setCellValue('A' . $rowCount, $count);
					$sheet->setCellValue('B' . $rowCount, $row1->TransactionDate);
		      $sheet->setCellValue('C' . $rowCount, $row->CustomerName);
		      $sheet->setCellValue('D' . $rowCount, $row1->TransactionTypeName);
		      $sheet->setCellValue('E' . $rowCount, $row1->Total);

					$rowCount++;
				  $count++;

					$searchFor    = $row->CustomerId;
					$filterbycust =
					array_values(array_filter($usage, function($element) use($searchFor){
						return isset($element->CustomerId) && $element->CustomerId == $searchFor;
					}));

					$searchFor    = date('Y-m-d', strtotime($row1->TransactionDate));
					$filterbydate =
					array_values(array_filter($filterbycust, function($element) use($searchFor){
						return isset($element->UsageDate) && date('Y-m-d', strtotime($element->UsageDate)) == $searchFor;
					}));

					if (!empty($filterbydate)) {
						foreach ($filterbydate as $row2) {
							$sheet->setCellValue('A' . $rowCount, $count);
							$sheet->setCellValue('B' . $rowCount, $row2->UsageDate);
				      $sheet->setCellValue('C' . $rowCount, $row->CustomerName);
				      $sheet->setCellValue('D' . $rowCount, '');
				      $sheet->setCellValue('E' . $rowCount, $row2->Amount);

							$rowCount++;
						  $count++;
						}
					}
			  }
				// $rowCount++;
				// $count++;
			}
    }

		// die;

		if($extension == 'csv'){
      $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
      $fileName = $fileName.'.csv';
    } elseif($extension == 'xlsx') {
      $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
      $fileName = $fileName.'.xlsx';
    } else {
      $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
      $fileName = $fileName.'.xls';
    }

		$this->output->set_header('Content-Type: application/vnd.ms-excel');
    $this->output->set_header("Content-type: application/csv");
    $this->output->set_header('Cache-Control: max-age=0');
    $writer->save(ROOT_UPLOAD_PATH.$fileName);
    /*redirect(HTTP_UPLOAD_PATH.$fileName); */
    $filepath = file_get_contents(ROOT_UPLOAD_PATH.$fileName);
    force_download($fileName, $filepath);

		// echo "<pre>";
		// print_r($customer);
		// print_r($transaction);
		// print_r($usage);
		// echo "</pre>";
		//
		// $extension = 'xlsx';
		//
		// $return['token']  = $data['csrf']['hash'];
		// echo json_encode($return);
	}

	public function list(){
    $post  = $this->input->post();

		//FIRST LAYER SEARCHING
		if ($post['VoucherNumber'] != '') {
			$post['voucher'] = $this->Excel_model->get_voucher_number($post['VoucherNumber']);
		}

		if (!empty($post['PosNumber'])) {
			$post['pos'] = $this->Excel_model->get_voucher_pos($post['PosNumber']);
		}

		if ($post['VoucherStatus'] == 3 || $post['VoucherStatus'] == 5 || $post['VoucherStatus'] == 6) {
			if ($post['StartDate'] != '' && $post['EndDate'] != '') {
				$post['history'] = $this->Excel_model->get_voucher_history($post);
			}
		}
		//FIRST LAYER SEARCHING

		//FILTER BY CONDITION
		if ($post['VoucherStatus'] != '' && $post['StartDate'] != '' && $post['EndDate'] != '' ) {
			if ($post['VoucherStatus'] != 2 && $post['VoucherStatus'] != 3 && $post['VoucherStatus'] != 5 && $post['VoucherStatus'] != 6) {
				$post['vouchid'] = [];
				if ($post['ReceiptNumber'] != '') {
		      $post['vouchid'] = $this->Excel_model->get_voucher_receipt($post['ReceiptNumber']);
		    }
				$post['activity'] = $this->Excel_model->get_voucher_activity($post);
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

    $list  = $this->Excel_model->get_datatables($post);
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
      "recordsTotal"        => $this->Excel_model->count_all($post),
      "recordsFiltered"     => $this->Excel_model->count_filtered($post),
      "data"                => $table,
    );
    echo json_encode($output);
  }
}
