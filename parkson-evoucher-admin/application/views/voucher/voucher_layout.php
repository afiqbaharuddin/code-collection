<?php
    require_once('webprint/WebClientPrint.php');
    use Neodynamic\SDK\Web\WebClientPrint;
?>

<head>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&family=Roboto+Mono:wght@300&family=Roboto:wght@300&display=swap" rel="stylesheet">

    <style media="screen">
      #invoice-POS {
      box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
      /* padding:2mm; */
      margin: auto;
      /* font-family:  'Helvetica', 'Arial', sans-serif; */
      font-family: 'Roboto Mono', monospace;
      width: 78mm;
      background: #FFF;

      ::selection {background: #f31544; color: #FFF;}
      ::moz-selection {background: #f31544; color: #FFF;}
      h1{
        font-size: 1.5em;
        color: #222;
      }
      h2{font-size: .9em;}
      h3{
        font-size: 1.2em;
        font-weight: 300;
        line-height: 2em;
      }
      p{
        font-size: .7em;
        color: #666;
        line-height: 1.2em;
      }

      #top, #mid,#bot{ /* Targets all id with 'col-' */
        border-bottom: 1px solid #EEE;
      }

      #top{min-height: 100px;}
      #mid{min-height: 80px;}
      #bot{ min-height: 50px;}

      #top .logo{
        //float: left;
        height: 60px;
        width: 60px;
        background: url() no-repeat;
        background-size: 60px 60px;
      }
      .clientlogo{
        float: left;
        height: 60px;
        width: 60px;
        background: url() no-repeat;
        background-size: 60px 60px;
        border-radius: 50px;
      }
      .info{
        display: block;
        //float:left;
        margin-left: 0;
      }
      .title{
        float: right;
      }
      .title p{text-align: right;}
      table{
        width: 100%;
        border-collapse: collapse;
      }
      td{
        //padding: 5px 0 5px 15px;
        //border: 1px solid #EEE
      }
      .tabletitle{
        padding: 5px;
        font-size: .5em;
        background: #EEE;
      }
      .service{border-bottom: 1px solid #EEE;}
      .item{width: 24mm;}
      .itemtext{font-size: .5em;}

      #legalcopy{
        margin-top: 5mm;
      }
    }
    .text-center{
      text-align: center;
    }
    .mtb{
      margin-top: 20px;
      margin-bottom: 20px;
    }
    .mtbsmall{
      margin-top: 5px;
      margin-bottom: 5px;
    }
    .t-title{
      font-size: 13px;
      font-weight: bold;
    }
    .s-info{
      font-size: 13px;
    }

    @media print {
      .text-center{
        text-align: center;
      }
      .mtb{
        margin-top: 20px;
        margin-bottom: 20px;
      }
      .mtbsmall{
        margin-top: 5px;
        margin-bottom: 5px;
      }
      .t-title{
        font-size: 13px;
        font-weight: bold;
      }
      .s-info{
        font-size: 13px;
      }
    }
    .number {
      font-family: 'Roboto', sans-serif;
      font-weight: bolder;
      font-size:11px;
    }
    .detail {
      font-family: 'Roboto Mono', monospace;
      font-weight: bolder;
      font-size:11px;
    }
    .table-detail {
      margin-top: -5px;
      width: 100%;
    }
    .table-detail-2 {
      margin-top: 10px;
      width: 100%;
    }
    .table-detail-3 {
      margin-top: 60px;
      width: 100%;
    }
    .voucher-header-1 {
      font-family: 'Roboto Mono', monospace;
      font-size: 11px;
      text-align: center;
      text-transform: uppercase;
      margin-top: 5px;
    }
    .voucher-header-2 {
      font-family: 'Roboto Mono', monospace;
      font-size: 11px;
      text-align: center;
      text-transform: uppercase;
      margin-top: -28px;
    }
    .voucher-header-3 {
      font-family: 'Roboto', sans-serif;
      font-size: 11px;
      text-align: center;
      text-transform: uppercase;
      margin-top: -27px;
    }
    .voucher-header-4 {
      font-size: 11px;
      text-align: center;
      margin-top: -30px;
    }
    .voucher-name {
      font-family: 'Roboto Mono', monospace;
      font-size:20px;
      text-align:center;
      margin-top:-20px;
      text-transform:uppercase;
    }
    .voucher-value {
      font-family: 'Roboto', sans-serif;
      font-size:30px;
      text-align:center;
      margin-top:-25px;
    }
    .valid-store {
      font-size:15px;
      text-align:center;
      margin-top:-30px;
      text-transform:uppercase;
    }
    .valid-date {
      margin-top:-14px;
      text-align:center;
      color:#505203;
      font-size:12px;
      margin-bottom:-2px;
      text-transform:uppercase
    }
    .voucher-no {
      margin-top:-1px;
      text-align:center;
      color:#505203;
      font-size:14px;
      letter-spacing:5px;
    }
    .align {
      text-align:right;
    }
    .dot-line {
      margin-top:-35px;
      text-align:center;
    }
  </style>
</head>

<body>
    <div id="invoice-POS">

    <?php foreach ($sucessReprint as $row): ?>
      <div id="bot-<?php echo $row->VoucherId; ?>">
        <div class="row">
          <div class="col-md-12" id="printTable">
            <div class="col-md-12 mtb text-center">
            </div>
            <div class="col-md-12 text-center">
              <div class="topbox">
                <table width="100%">
                  <input type="hidden" name="detailsid" value="<?php echo $row->VoucherIssuanceId; ?>">
                  <tr>
                    <img style="width:80%" src="<?php echo base_url(); ?>assets/img/branding/parksonlogo.bmp" />
                    <h6 class="voucher-header-1"><?php echo $row->StoreName; ?></h6>
                    </tr>
                    <tr>
                      <h6 class="voucher-header-2">PARKSON CORPORATION SDN BHD</h6>
                    </tr>
                    <tr>
                      <h6 class="number voucher-header-3">198601007838 (157029-X)</h6>
                    </tr>
                    <tr>
                      <h6 class="voucher-header-4">www.parkson.com.my</h6>
                    </tr>
                  <tr>
                    <h2 class="voucher-name"
                      style="font-family: 'Roboto Mono',monospace;
                      font-size:20px;
                      text-align:center;
                      margin-top:-20px;
                      text-transform:uppercase;"><?php echo $row->VoucherName;?>
                    </h2>
                    </tr>
                    <tr>
                      <h2 class="voucher-value"
                        style="font-family: 'Roboto', sans-serif;
                        font-size:30px;
                        text-align:center;
                        margin-top:-25px;
                        font-weight:bolder;">RM <?php echo $row->VouchersValue;?>
                      </h2>
                    </tr>
                    <tr>
                      <?php if ($row->RedeemTypeId == 2 ){ ?>
                        <h2 class="valid-store" style="font-size:15px; text-align:center; margin-top:-30px; text-transform:uppercase;">VALID AT ALL PARKSON STORES</h2>
                      <?php }else{ ?>
                        <h2 class="valid-store" style="font-size:15px; text-align:center; margin-top:-30px; text-transform:uppercase;">VALID AT <?php echo $row->StoreName ?></h2>
                      <?php  } ?>
                    </tr>
                    <tr>
                      <h6 class="number valid-date">Valid from <?php echo date('d/m/Y') ?> to
                        <?php if ($row->VoucherStatusId == 3){ ?>
                          <?php  echo date('d/m/Y', strtotime($extendDate->ExtendDate)); ?>
                        <?php }else { ?>
                          <?php  echo date('d/m/Y', strtotime($row->ExpDate)); ?>
                      <?php  } ?>
                      </h6>
                    </tr>
                    <tr>
                      <svg id="barcode<?php echo $row->VoucherId; ?>"></svg>
                    </tr>
                    <tr>
                      <h6 class="number voucher-no"><?php echo substr($row->VouchersNumber, 0, 20); ?></h6>
                    </tr>
                    <tr>
                      <h3 class="dot-line">- - - - - - - - - - - - -</h3>
                    </tr>
                </table>

                <table class="table-detail">
                  <tr>
                    <td>
                      <b class="detail">Terms & Conditions
                          <br>1. This voucher is not redeemable for cash in whole or in part.
                          <br>2. This voucher is not entitled for Parkson Card Points or BonusLink Points and
                          is not valid for further redemption or any other vouchers/coupons.
                          <br>3. Please visit www.parkson.com.my for more information.
                      </b>
                    </td>
                  </tr>
                </table>

                <?php if ($row->VoucherTypeId != 2): ?>
                  <table class="table-detail-2" >
                    <tbody>
                      <tr>
                        <td class="number"> <?php echo $printStaffId->StaffId; ?> ECS<?php echo $row->StoreCode; ?></td>
                        <td class="number align"><?php echo substr($row->ReceiptNumber, 3,7); ?>-<?php echo substr($row->ReceiptNumber, 11,22); ?></td>
                      </tr>
                      <tr>
                        <td class="number"><?php echo date('d/m/Y H:i') ?> </td>
                        <td class="number align"><?php  echo date('d/m/Y H:i', strtotime($row->ReceiptDateTime)); ?></td>
                      </tr>
                      <tr>
                        <?php if (isset($duplicateVoucher->ReceiptNumber)) { ?>
                          <td  class="detail" style="font-size:17px">**Duplicate</td>
                        <?php } else { ?>
                          <td></td>
                        <?php } ?>
                        <td class="number align"><?php echo $row->VouchersNumber; ?></td>
                      </tr>
                    </tbody>
                  </table>
                  <?php endif; ?>

                <?php if ($row->VoucherTypeId== 2): ?>
                  <table class="table-detail-3" >
                    <tbody>
                      <tr>
                        <td class="number"> <?php echo $printStaffId->StaffId; ?> ECS<?php echo $row->StoreCode; ?></td>
                        <td class="number align"><?php echo substr($row->ReceiptNumber, 3,7); ?>-<?php echo substr($row->ReceiptNumber, 11,22); ?></td>
                      </tr>
                      <tr>
                        <td class="number"><?php echo date('d/m/Y H:i') ?> </td>
                        <td class="number align"><?php  echo date('d/m/Y H:i', strtotime($row->ReceiptDateTime)); ?></td>
                      </tr>
                      <tr>
                        <?php if (isset($duplicateVoucher->ReceiptNumber)) { ?>
                          <td  class="detail" style="font-size:17px">**Duplicate</td>
                        <?php } else { ?>
                          <td></td>
                        <?php } ?>
                        <td class="number align"><?php echo $row->VouchersNumber; ?></td>
                      </tr>
                    </tbody>
                  </table>
                <?php endif; ?>

              </div>
            </div>
          </div>
        </div>
      </div><!--End InvoiceBot-->
    <?php endforeach; ?>

</div><!--End Invoice-->
</body>

<input type="hidden" id="sid" name="sid" value="<?php echo session_id(); ?>" />
<input type="hidden" id="useDefaultPrinter" />
<input type="hidden" name="installedPrinterName" id="installedPrinterName" value="EPSON TM-H6000IV Receipt"/>

<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js" type="text/javascript"></script> -->
<!-- <script src="https://fonts.googleapis.com/css?family=Bangers|Roboto"></script> -->
<script src="<?php echo base_url(); ?>assets/third-party/barcode/barcode.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/printer/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/printer/html2canvas.js"></script>
<!-- <script src="https://html2canvas.hertzen.com/dist/html2canvas.js" type="text/javascript"></script> -->


<script type="text/javascript">


<?php foreach ($sucessReprint as $row): ?>
  var receiptID = '<?php echo substr($row->VouchersNumber, 0, 20); ?>'
  JsBarcode("#barcode<?php echo $row->VoucherId; ?>", receiptID, {
    format: "code128",
    width: 1.8,
    height: 45,
    marginLeft: 0,
    displayValue: false
  });
  <?php endforeach; ?>

  <?php foreach ($sucessReprint as $row): ?>

    html2canvas(document.querySelector("#bot-<?php echo $row->VoucherId; ?>"),{
      scale: 5,
    }).then(canvas => {
        // document.body.appendChild(canvas);
        var baseimage = canvas.toDataURL("image/png", 1);
        var id = <?php echo $row->VoucherId; ?>;
        upload(id,baseimage);
    });

  <?php endforeach; ?>

  function upload(id,baseimage){
    var csrfName = $('.txt_csrfname').attr('name');
    var csrfHash = $('.txt_csrfname').val();

    $.ajax({
      type: "post",
      url: "<?php echo base_url(); ?>voucher/VoucherLayout/upload",
      data: {id:id,baseimage: baseimage, [csrfName]: csrfHash},
      dataType: 'json',
      success: function(data)
      {
        $('.txt_csrfname').val(data.token);
      }
    });
  }

  setTimeout(function(){
    var id = <?php echo $reprintid; ?>;
    var csrfName = $('.txt_csrfname').attr('name');
    var csrfHash = $('.txt_csrfname').val();

    $.ajax({
      type: "post",
      url: "<?php echo base_url(); ?>voucher/VoucherLayout/get_voucher",
      data: {id:id, [csrfName]: csrfHash},
      dataType: 'json',
      success: function(data)
      {
        $('.txt_csrfname').val(data.token);

        for (var i = 0; i < data.voucher.length; i++) {
          // console.log(data.voucher[i].VoucherId);
          // if (i > 0){
          //   if (data.voucher[i].PrintStatus == 2) {
          //     delayPrint(i,data.voucher[i].VoucherId);
          //   }
          // }else {
          //
          // }
          if (data.voucher[i].PrintStatus == 2) {
            javascript:jsWebClientPrint.print('useDefaultPrinter=' + $('#useDefaultPrinter').attr('checked') + '&printerName=' + $('#installedPrinterName').val() + '&voucherId=' + data.voucher[i].VoucherId);
            setTimeout(function() {
              window.location.reload(); // close once it's done
            }, 3000);
            return false;
          }
        }
      }
    });

  }, 1000);

  function delayPrint(interval,id) {
    var domain = '<?php echo base_url(); ?>voucher/VoucherLayout/printing/'+id;
    setTimeout(function() {
      var wnd = window.open(domain);
      setTimeout(function() {
        wnd.close(); // close once it's done
      }, 1000);
    }, interval * 3000);
  }

</script>

<?php
  //Specify the ABSOLUTE URL to the WebClientPrintController.php and to the file that will create the ClientPrintJob object
  $baseurl = base_url()."voucher/VoucherLayout/";
  $webClientPrintControllerAbsoluteURL = $baseurl.'print_settings';
  $printESCPOSControllerAbsoluteURL    = $baseurl.'print';
  echo WebClientPrint::createScript($webClientPrintControllerAbsoluteURL, $printESCPOSControllerAbsoluteURL, session_id());
?>
