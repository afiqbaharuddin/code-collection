<?php
// require_once('vendor/autoload.php');
//
// use Mike42\Escpos\Printer;
// use Mike42\Escpos\EscposImage;
// use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

require_once('webprint/WebClientPrint.php');

use Neodynamic\SDK\Web\WebClientPrint;
use Neodynamic\SDK\Web\DefaultPrinter;
use Neodynamic\SDK\Web\InstalledPrinter;
use Neodynamic\SDK\Web\ClientPrintJob;
use Neodynamic\SDK\Web\PrintFile;

class VoucherLayout extends CI_Controller {

    function __construct()
    {
      parent:: __construct();
      $this->load->model('voucher/voucherLayout_Model');
      $this->load->model('voucher/reprintVoucher_Model');


      //CSRF PROTECTION\\
      $this->global_data['csrf'] =
      [
        'name' => $this->security->get_csrf_token_name(),
        'hash' => $this->security->get_csrf_hash()
      ];
      //CSRF PROTECTION\\

      $this->global_data['UserId']      = $this->session->userdata('UserId');
      $this->global_data['Fullname']    = $this->session->userdata('Fullname');
      $this->global_data['Role']        = $this->session->userdata('Role');

      $this->global_data['CreatedDate'] = date("Y-m-d H:i:s", time() );
      $this->global_data['CreatedBy']   = $this->global_data['UserId'];
      $this->global_data['AppType']     = 2;
      $this->global_data['UpdatedDate']  = date("Y-m-d H:i:s", time() ); //for edit part
      $this->global_data['UpdatedBy']    = $this->global_data['UserId'];
    }

    function print_page(){
      try {
          // Enter the share name for your USB printer here
          // $connector = null;
          $connector = new WindowsPrintConnector("EPSON TM-H6000IV Receipt - USB");

          /* Print a "Hello world" receipt" */
          $printer = new Printer($connector);
          $printer -> text("test!\n");
          $printer -> cut();
          $printer -> text("test!\n");
          $printer -> cut();
          $printer -> text("test!\n");
          $printer -> cut();
          $printer -> text("test!\n");
          $printer -> cut();

          /* Close printer */
          $printer -> close();
      } catch (Exception $e) {
          echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
      }

      echo "string";
    }

    public function index()
    {
     $data = array_merge($this->global_data);

     // $data['voucherdetails']=$this->uri->segment(3);
     // $data['printdetails'] = $this->voucherLayout_Model->printVoucherDetails($data['voucherdetails']);

     // $reprintid = $this->uri->segment(3);
     // $data['sucessReprint']= $this->reprintVoucher_Model->get_reprintSuccess($reprintid);

     // $this->load->view('voucher/voucher_layout', $data);
    }

    function voucher_details(){
      $data = array_merge($this->global_data);
      // $id   = $this->uri->segment(3);
      // $data['sucessReprint']= $this->voucherLayout_Model->printVoucherDetails($id);

      $data['reprintid'] = $this->uri->segment(4);
      $data['sucessReprint']= $this->voucherLayout_Model->get_reprintSuccess($data['reprintid']);
      $data['duplicateVoucher']= $this->voucherLayout_Model->get_duplicateVoucher($data['reprintid']);
      $data['extendDate']       = $this->voucherLayout_Model->get_extendDate($data['reprintid']);

      $data['printStaffId']     = $this->voucherLayout_Model->get_printStaffId($this->session->userdata('UserId'));

      // echo "<pre>";
      // print_r($data['extendDate']);
      // echo "</pre>";

       $this->load->view('voucher/voucher_layout', $data);
      // $this->voucherLayout_Model->printVoucherDetails($id
    }

    function print_settings(){
      //IMPORTANT SETTINGS:
      //===================
      //Set wcpcache folder RELATIVE to WebClientPrint.php file
      //FILE WRITE permission on this folder is required!!!
      WebClientPrint::$wcpCacheFolder = getcwd().'/wcpcache/';

      if (file_exists(WebClientPrint::$wcpCacheFolder) == false) {
        //create wcpcache folder
        $old_umask = umask(0);
        mkdir(WebClientPrint::$wcpCacheFolder, 0777);
        umask($old_umask);
      }

      //===================

      // Clean built-in Cache
      // NOTE: Remove it if you implement your own cache system
      WebClientPrint::cacheClean(30); //in minutes

      // Process WebClientPrint Request

      $urlParts = parse_url($_SERVER['REQUEST_URI']);
      if (isset($urlParts['query'])){
        $query = $urlParts['query'];
        parse_str($query, $qs);

        //get session id from querystring if any
        $sid = NULL;
        if (isset($qs[WebClientPrint::SID])){
            $sid = $qs[WebClientPrint::SID];
        }

        try{
            //get request type
            $reqType = WebClientPrint::GetProcessRequestType($query);

            if($reqType == WebClientPrint::GenPrintScript ||
               $reqType == WebClientPrint::GenWcppDetectScript){
                //Let WebClientPrint to generate the requested script

                //Get Absolute URL of this file
                $currentAbsoluteURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
                $currentAbsoluteURL .= $_SERVER["SERVER_NAME"];
                if($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443")
                {
                    $currentAbsoluteURL .= ":".$_SERVER["SERVER_PORT"];
                }
                $currentAbsoluteURL .= $_SERVER["REQUEST_URI"];
                $currentAbsoluteURL = substr($currentAbsoluteURL, 0, strrpos($currentAbsoluteURL, '?'));

                ob_start();
                ob_clean();
                header('Content-type: text/javascript');
                echo WebClientPrint::generateScript($currentAbsoluteURL, $query);
                return;
            }
            else if ($reqType == WebClientPrint::ClientSetWcppVersion)
            {
                //This request is a ping from the WCPP utility
                //so store the session ID indicating this user has the WCPP installed
                //also store the WCPP Version if available
                if(isset($qs[WebClientPrint::WCPP_SET_VERSION]) && strlen($qs[WebClientPrint::WCPP_SET_VERSION]) > 0){
                    WebClientPrint::cacheAdd($sid, WebClientPrint::WCP_CACHE_WCPP_VER, $qs[WebClientPrint::WCPP_SET_VERSION]);
                }
                return;
            }
            else if ($reqType == WebClientPrint::ClientSetInstalledPrinters)
            {
                //WCPP Utility is sending the installed printers at client side
                //so store this info with the specified session ID
                WebClientPrint::cacheAdd($sid, WebClientPrint::WCP_CACHE_PRINTERS, strlen($qs[WebClientPrint::WCPP_SET_PRINTERS]) > 0 ? $qs[WebClientPrint::WCPP_SET_PRINTERS] : '');
                return;
            }
            else if ($reqType == WebClientPrint::ClientSetInstalledPrintersInfo)
            {
                //WCPP Utility is sending the installed printers at client side with detailed info
                //so store this info with the specified session ID
                //Printers Info is in JSON format
                $printersInfo = $_POST['printersInfoContent'];

                WebClientPrint::cacheAdd($sid, WebClientPrint::WCP_CACHE_PRINTERSINFO, $printersInfo);
                return;
            }
            else if ($reqType == WebClientPrint::ClientGetWcppVersion)
            {
                //return the WCPP version for the specified Session ID (sid) if any
                ob_start();
                ob_clean();
                header('Content-type: text/plain');
                echo WebClientPrint::cacheGet($sid, WebClientPrint::WCP_CACHE_WCPP_VER);
                return;
            }
            else if ($reqType == WebClientPrint::ClientGetInstalledPrinters)
            {
                //return the installed printers for the specified Session ID (sid) if any
                ob_start();
                ob_clean();
                header('Content-type: text/plain');
                echo base64_decode(WebClientPrint::cacheGet($sid, WebClientPrint::WCP_CACHE_PRINTERS));
                return;
            }
            else if ($reqType == WebClientPrint::ClientGetInstalledPrintersInfo)
            {
                //return the installed printers with detailed info for the specified Session ID (sid) if any
                ob_start();
                ob_clean();
                header('Content-type: text/plain');
                echo base64_decode(WebClientPrint::cacheGet($sid, WebClientPrint::WCP_CACHE_PRINTERSINFO));
                return;
            }
        }
        catch (Exception $ex)
        {
            throw $ex;
        }
      }
    }

    function print(){

      // Process request
      // Generate ClientPrintJob? only if clientPrint param is in the query string
      $urlParts = parse_url($_SERVER['REQUEST_URI']);

      if (isset($urlParts['query'])) {
        $rawQuery = $urlParts['query'];
        parse_str($rawQuery, $qs);
        if (isset($qs[WebClientPrint::CLIENT_PRINT_JOB])) {

          $useDefaultPrinter = ($qs['useDefaultPrinter'] === 'checked');
          $printerName = urldecode($qs['printerName']);

          //Create ESC/POS commands for sample receipt
          $esc = '0x1B'; //ESC byte in hex notation
          $newLine = '0x0A'; //LF byte in hex notation

          $cmds = '';
          $cmds = $esc . "@"; //Initializes the printer (ESC @)
          $cmds .= $esc . '!' . '0x38'; //Emphasized + Double-height + Double-width mode selected (ESC ! (8 + 16 + 32)) 56 dec => 38 hex
          $cmds .= 'BILL'; //text to print
          $cmds .= $newLine . $newLine;
          $cmds .= $esc . '!' . '0x00'; //Character font A selected (ESC ! 0)
          $cmds .= 'COOKIES                   5.00';
          // $cmds .= $newLine;
          // $cmds .= 'MILK 65 Fl oz             3.78';
          // $cmds .= $newLine;
          // $cmds .= 'TOTAL                     8.78';
          // $cmds .= 'TOTAL                     8.78';
          $cmds .= $newLine;
          $cmds .= $esc . "m"; //For auto cut

      		//Create a ClientPrintJob obj that will be processed at the client side by the WCPP
      		$cpj = new ClientPrintJob();
      		//set ESCPOS commands to print...

          $fileName = uniqid() . '.png';
          $filePath = 'uploads/voucher-print/voucher-'.$qs['voucherId'].'.png';

          //For raw printing
      		// $cpj->printerCommands = $cmds;
          // $cpj->formatHexValues = true;

          //For image printing
          $cpj->printFile = new PrintFile($filePath, $fileName, null);


      		if ($useDefaultPrinter || $printerName === 'null') {
      			// $cpj->clientPrinter = new DefaultPrinter();
            $cpj->clientPrinter = new InstalledPrinter('EPSON TM-H6000IV Receipt - USB');
      		} else {
      			$cpj->clientPrinter = new InstalledPrinter($printerName);
      		}

      		//Send ClientPrintJob back to the client
      		// ob_start();
      		// ob_clean();
      		// header('Content-type: application/octet-stream');
          // $status = $this->voucherLayout_Model->get_status_pending($qs['voucherId']);
          // if ($status->PrintedStatus == 2) {

            echo $cpj->sendToClient();
            $this->voucherLayout_Model->updateStatus($qs['voucherId']);
            //Remove image from folder
            unlink('uploads/voucher-print/voucher-'.$qs['voucherId'].'.png');
            exit();
          // }

      		// ob_end_flush();
      		exit();

        }
      }
    }

    function upload(){
      $data = array_merge($this->global_data);
      $get = $this->input->post();

      $getstatus = $this->voucherLayout_Model->get_status_pending($get['id']);

      if (isset($getstatus)) {
        if (date('Y-m-d H:i:s')>= date('Y-m-d H:i:s', strtotime($getstatus->PrintedDate.' + 10 minutes'))) {
          $array = [
             'VoucherId'       => $get['id'],
             'PrintedDate'     => date('Y-m-d H:i:s'),
             'PrintedBy'       => $data['UserId'],
         ];

         $this->voucherLayout_Model->insertlog($array);
        }

        $img = str_replace('data:image/png;base64,', '', $get['baseimage']);
      	$img = str_replace(' ', '+', $img);
      	$img = base64_decode($img);

        file_put_contents("uploads/voucher-print/voucher-".$get['id'].".png", $img);
      }else {
        $array = [
           'VoucherId'       => $get['id'],
           'PrintedDate'     => date('Y-m-d H:i:s'),
           'PrintedBy'       => $data['UserId'],
       ];

       $this->voucherLayout_Model->insertlog($array);

        $img = str_replace('data:image/png;base64,', '', $get['baseimage']);
      	$img = str_replace(' ', '+', $img);
      	$img = base64_decode($img);

        file_put_contents("uploads/voucher-print/voucher-".$get['id'].".png", $img);
      }

      $result['token']   = $data['csrf']['hash'];
      echo json_encode($result);
    }

    function get_voucher(){
      $data = array_merge($this->global_data);
      $get = $this->input->post();

      $voucher = $this->voucherLayout_Model->get_reprintSuccess($get['id']);
      $result['voucher'] = [];
      foreach ($voucher as $row) {
        $status = $this->voucherLayout_Model->get_status_pending($row->VoucherId);
        if (isset($status)) {
          $printstatus = $status->PrintedStatus;
        }else {
          $printstatus = '2';
        }
        $result['voucher'][] = [
          'VoucherId' => $row->VoucherId,
          'PrintStatus' => $printstatus
        ];
      }

      $result['token']   = $data['csrf']['hash'];
      echo json_encode($result);
    }

    function printing(){
      $data['voucherId'] = $this->uri->segment(4);
      $this->load->view('print',$data);
    }

}
// ?>
