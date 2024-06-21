<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ActivityLog extends CI_Controller
{
  function __construct()
  {
    parent:: __construct();
    $this->load->model('log/activityLog_model');

    //CSRF PROTECTION\\
    $this->global_data['csrf'] =
    [
      'name' => $this->security->get_csrf_token_name(),
      'hash' => $this->security->get_csrf_hash()
    ];
    //CSRF PROTECTION\\

    $this->global_data['UserAdminId']      = $this->session->userdata('UserAdminId');
    $this->global_data['UserTypeId']  = $this->session->userdata('UserTypeId');
    $this->global_data['FullName']    = $this->session->userdata('FullName');

    // $this->global_data['user']    	  = $this->Customer_model->select_user_info($this->global_data['UserId'],$this->global_data['UserTypeId']);

    $this->global_data['CreatedDate'] = date("Y-m-d H:i:s", time() );
    $this->global_data['CreatedBy']   = $this->global_data['UserAdminId'];
    $this->global_data['EditedDate']  = date("Y-m-d H:i:s", time() );
    $this->global_data['EditedBy']    = $this->global_data['UserAdminId'];
  }

  function index(){
    $data = array_merge($this->global_data);
    $data['top']  = $this->load->view('templates/main-top',"",true);
    $data['sidebar']  = $this->load->view('templates/main-sidebar',"",true);
    $data['navbar']  = $this->load->view('templates/main-navbar',"",true);
    $data['footer']  = $this->load->view('templates/main-footer',"",true);
    $data['bottom']  = $this->load->view('templates/main-bottom',"",true);

    $this->load->view('log/activity_log', $data);
  }

  public function ActivityLogList(){

    $data  = array_merge($this->global_data);
    $post  = $this->input->post();

    $list  = $this->activityLog_model->get_datatables($post);
    $table = array();
    $no    = $post['start'];

    if (isset($post['date_from'])) {
      $this->session->set_userdata('Filter_DateFrom',$post['date_from']);
    }

    if (isset($post['date_to'])) {
      $this->session->set_userdata('Filter_DateTo',$post['date_to']);
    }

    foreach ($list as $field) {
      $no++;
      $row   = array();

      $row[] = $field->FullName;
      $row[] = $field->AdminRoleName;
      $row[] = $field->ActivityDate;
      $row[] = $field->ActivityTypeName;
      $row[] = $field->ActivityDetails;
      $row[] = $field->IpAddress;
      $row[] = $field->OperatingSystem;

      $table[] = $row;
    }

    $output = array(
        "draw"                => $post['draw'],
        "recordsTotal"        => $this->activityLog_model->count_all($post),
        "recordsFiltered"     => $this->activityLog_model->count_filtered($post),
        "data"                => $table,
        $data['csrf']['name'] => $data['csrf']['hash']
      );
      echo json_encode($output);
  }

//   function filteractivitylog() {
//   $data = array_merge($this->global_data);
//
//   $get = $this->input->post();
//
//   $this->session->set_userdata('Filter_DateFrom',$get['date_from']);
//   $this->session->set_userdata('Filter_DateTo',$get['date_to']);
//
//   $output['token'] = $data['csrf']['hash'];
//   echo json_encode($output);
// }

  function export(){
    $data = array_merge($this->global_data);
    $this->load->helper('download');

    if ($this->session->userdata('Filter_DateFrom') != null) {
      $data['start'] = $this->session->userdata('Filter_DateFrom');

      $from = $this->session->userdata('Filter_DateFrom');
    } else {
      $from = '';
    }

    if ($this->session->userdata('Filter_DateTo') != null) {
      $data['end'] = $this->session->userdata('Filter_DateTo');
      $to = $this->session->userdata('Filter_DateTo');
    } else {
      $to = '';
    }

    // Create a new PhpSpreadsheet instance
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

    // Create a worksheet
    $sheet = $spreadsheet->getActiveSheet();

    // Define styles
    $titleStyle = [
        'font' => ['bold' => true, 'size' => 16],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
    ];

    $headerStyle = [
        'font' => ['bold' => true],
        'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E0E0E0']],
    ];

    $borderStyle = [
        'borders' => ['outline' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
    ];

    // Apply title style to cell A1
    $sheet->getStyle('A1')->applyFromArray($titleStyle);

    // Add a title to the worksheet
    if ($from == "" && $to == "") {
      $sheet->setCellValue('A1', 'Activity Log');
    }else {
      $sheet->setCellValue('A1', 'Activity Log  ('.$from.') - ('.$to.')');
    }
    $sheet->mergeCells('A1:F1');

    // Set headers and add border style
    $sheet->setCellValue('A2', 'Name');
    $sheet->setCellValue('B2', 'Date');
    $sheet->setCellValue('C2', 'Activity Tpe');
    $sheet->setCellValue('D2', 'Details');
    $sheet->setCellValue('E2', 'Ip Address');
    $sheet->setCellValue('F2', 'Operating System');

    $sheet->getStyle('A2:F2')->applyFromArray($headerStyle);

    // Fetch transaction data
    $transactionInfo = $this->activityLog_model->get_exportLog($data);

    // Populate data
    $rowCount = 3;
    foreach ($transactionInfo as $element) {
        $sheet->setCellValue('A' . $rowCount, $element['AdminRoleName']);
        $sheet->setCellValue('B' . $rowCount, $element['ActivityDate']);
        $sheet->setCellValue('C' . $rowCount, $element['ActivityTypeName']);
        $sheet->setCellValue('D' . $rowCount, $element['ActivityDetails']);
        $sheet->setCellValue('E' . $rowCount, $element['IpAddress']);
        $sheet->setCellValue('F' . $rowCount, $element['OperatingSystem']);

        $sheet->getStyle('A' . $rowCount . ':F' . $rowCount)->applyFromArray($borderStyle); // Add border to the entire row
        $rowCount++;
    }

    // Add border to the bottom of the list
    $sheet->getStyle('A2:F' . ($rowCount - 1))->applyFromArray($borderStyle);

    // Set auto width for columns
    foreach (range('A', 'F') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }

    // Create a writer
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $fileName = 'ActivityLog-' . time() . '.xlsx';

    // Save the Excel file
    $writer->save(ROOT_UPLOAD_PATH . $fileName);

    // Download the file
    $filepath = file_get_contents(ROOT_UPLOAD_PATH . $fileName);
    force_download($fileName, $filepath);
  }
}
 ?>
