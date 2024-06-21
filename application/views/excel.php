<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>
	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
		text-decoration: none;
	}

	a:hover {
		color: #97310e;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
		min-height: 96px;
	}

	p {
		margin: 0 0 10px;
		padding:0;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}
	</style>
	<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"> -->
	<link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
</head>
<body>

	<div id="container">
		<select name="StoreCode" id="StoreCode">
			<!-- <option value="">All Store</option> -->
			<?php foreach ($store as $row): ?>
				<option value="<?php echo $row->StoreId; ?>"><?php echo $row->StoreCode; ?></option>
			<?php endforeach; ?>
		</select>
		<input type="text" name="ReceiptNumber" id="ReceiptNumber" placeholder="Receipt NO">
		<select name="VoucherStatus" id="VoucherStatus">
			<option value="">All Status</option>
			<?php foreach ($status as $row): ?>
				<option value="<?php echo $row->VoucherStatusId; ?>"><?php echo $row->VoucherStatusName; ?></option>
			<?php endforeach; ?>
		</select>
		<!-- <input type="text" name="VoucherNumber" id="VoucherNumber" placeholder="Voucher Number"> -->
		<!-- <input type="text" name="PosNumber" id="PosNumber" placeholder="POS Number"> -->
		<select name="VoucherType" id="VoucherType">
			<option value="">All Voucher</option>
			<?php foreach ($type as $row): ?>
				<option value="<?php echo $row->VoucherTypeId; ?>"><?php echo $row->VoucherName; ?></option>
			<?php endforeach; ?>
		</select>
		<select name="Reason" id="Reason">
			<option value="">All Reason</option>
			<?php foreach ($reason as $row): ?>
				<option value="<?php echo $row->ReasonId; ?>"><?php echo $row->ReasonName; ?></option>
			<?php endforeach; ?>
		</select>
		<select name="TerminalId" id="TerminalId">
			<option value="ALL">ALL</option>
			<option value="SYSTEM">SYSTEM</option>
			<option value="POS">POS</option>
		</select>
		<input type="date" name="StartDate" id="StartDate" placeholder="Start Date">
		<input type="date" name="EndDate" id="EndDate" placeholder="End Date">
		<button type="button" name="button" id="button">Search Now</button>
	</div>


<div id="container">
	<h1>Welcome to CodeIgniter!</h1>

	<div id="body">
		<table id="myTable" class="display">
		    <thead>
		        <tr>
		            <th>Voucher No</th>
		            <th>Store Code</th>
								<th>Type</th>
								<th>Value</th>
								<th>Exp Date</th>
								<th>Created</th>
								<th>Status</th>
		        </tr>
		    </thead>
		    <tbody>

		    </tbody>
		</table>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>

</body>
	<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
	<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script> -->
	<script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

	<script type="text/javascript">
		// let table = new DataTable('#myTable');

		// search();

		function search(){
			var table = $('#myTable');
			table.DataTable({
	      "searching": true,
	      "processing": true,
	      "serverSide": true,
	      "ajax": {
	          "url": "<?php echo base_url(); ?>datatable/list",
	          "type": "POST",
						"data": function(data) {
							var StoreCode     = $('#StoreCode').val();
							var ReceiptNumber = $('#ReceiptNumber').val();
							var VoucherStatus = $('#VoucherStatus').val();
							var VoucherNumber = $('#VoucherNumber').val();
							var PosNumber     = $('#PosNumber').val();
							var VoucherType   = $('#VoucherType').val();
							var StartDate     = $('#StartDate').val();
							var EndDate       = $('#EndDate').val();

							data.StoreCode     = StoreCode;
							data.ReceiptNumber = ReceiptNumber;
							data.VoucherStatus = VoucherStatus;
							data.VoucherNumber = VoucherNumber;
							data.PosNumber     = PosNumber;
							data.VoucherType   = VoucherType;
							data.StartDate     = StartDate;
							data.EndDate       = EndDate;
						}
	      }
	    });
		}

		$('#button').click(function() {
			$('#myTable').DataTable().ajax.reload();
		});

	</script>
</html>
