<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
</head>

<style>
    body{
        font-family: Arial, sans-serif;
    }

    .invoice-box{
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0,0,0,0.15);
        font-size: 16px;
        line-height: 24px;
        color:#555;
    }

    .invoice-box table{
        width: 100%;
        line-height: inherit;
        text-align: left;
    }

    .invoice-box table td{
        padding: 5px;
        vertical-align: top;
    }

    .invoice-box table tr td:nth-child(2){
        text-align: right;
    }

    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }
    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }
    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }
    .invoice-box table tr.item td{
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
        <table cellpadding='0' cellaspacing='0'> 
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <h1>Invoice</h1>
                            </td>
                            <td>
                                Invoice #: 123<br>
                                Created: January 1, 2024<br>
                                Due: February 1, 2024
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td>
                    Payment Method
                </td>
                <td>
                    Check #
                </td>
            </tr>
            <tr class="details"> 
                <td>
                    Check
                </td>
                <td>
                    1000
                </td>
            </tr>
            <tr class="heading">
                <td>
                    Item
                </td>
                <td>
                    Price
                </td>
            </tr>
            <tr class="item">
                <td>
                    Website design
                </td>
                <td>
                    $300.00
                </td>
            </tr>
            <tr class="item">
                <td>
                    Hosting (3 months)
                </td>
                <td>
                    $75.00
                </td>
            </tr>
            <tr class="item last">
                <td>
                    Domain name (1 year)
                </td>
                <td>
                    $10.00
                </td>
            </tr>
            <tr class="total">
                <td></td>
                <td>
                   Total: $385.00
                </td>
            </tr>
        </table>
    </div>
</body>
</html>