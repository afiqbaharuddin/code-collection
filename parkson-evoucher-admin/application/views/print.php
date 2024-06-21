<?php
    require_once('webprint/WebClientPrint.php');
    use Neodynamic\SDK\Web\WebClientPrint;
?>

<p>Printing in progress.. Do not close. It will close automatically.</p>

<input type="hidden" id="sid" name="sid" value="<?php echo session_id(); ?>" />
<input type="hidden" id="useDefaultPrinter" />
<input type="hidden" name="installedPrinterName" id="installedPrinterName" value="EPSON TM-H6000IV Receipt"/>

<script type="text/javascript">
  setTimeout(function(){
    javascript:jsWebClientPrint.print('useDefaultPrinter=' + $('#useDefaultPrinter').attr('checked') + '&printerName=' + $('#installedPrinterName').val() + '&voucherId=<?php echo $voucherId; ?>');
  }, 500);
</script>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js" type="text/javascript"></script>

<?php
  //Specify the ABSOLUTE URL to the WebClientPrintController.php and to the file that will create the ClientPrintJob object
  $baseurl = base_url()."voucher/VoucherLayout/";
  $webClientPrintControllerAbsoluteURL = $baseurl.'print_settings';
  $printESCPOSControllerAbsoluteURL    = $baseurl.'print';
  echo WebClientPrint::createScript($webClientPrintControllerAbsoluteURL, $printESCPOSControllerAbsoluteURL, session_id());
?>
