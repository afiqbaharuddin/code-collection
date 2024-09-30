<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->id }}</title>
  </head>

  <style media="screen">
    body{
      font-family: Arial, sans-serif;
    }
    .invoice-box{
      width: 100%;
      padding:20px;
      border: 1px solid #eee;
      box-shadow: 0 0 10px rgba(0,0,0,0.15);
      font-size: 16px;
      line-height: 24px;
      color: #555;
    }
    .invoice-box table{
      width:100%;
      line-height: inherit;
      text-align: left;
    }
    .invoice-box table td{
      padding: 5px;
      vertical-align: top;
    }
    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }
    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }
    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    .invoice-box table tr.item td {
        border-bottom: 1px solid #eee;
    }
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
  </style>

  <body>
    <div class="invoice-box">
      <table cellpadding="0" cellspacing="0">
        <tr class="top">
          <td colspan="2">
            <table>
              <tr>
                <td class="title">
                  <h2>Invoice #{{ $invoice->id }}</h2>
                </td>
                <td>
                  {{ $invoice->invoice_date }}<br>
                  <strong>Total:</strong>${{ number_format($invoice->total, 2) }}
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <tr class="heading">
          <td>Item</td>
          <td>Price</td>

          @foreach($invoice->items as $item)
            <tr class="item">
              <td>{{ $item->product->name }} (x{{ $item->quantity }})</td>
              <td>${{ number_format($item->price, 2) }}</td>
            </tr>
          @endforeach

          <tr class="total">
            <td></td>
            <td>Total: ${{ number_format($invoice->total, 2) }}</td>
          </tr>
        </tr>
      </table>
    </div>

  </body>
</html>
