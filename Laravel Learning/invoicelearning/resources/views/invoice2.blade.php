<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invocie 2</title>

    <link rel="stylesheet" href="{{public_path('invoice.css')}}" type="text/css">
</head>

<body>
    <table class="table-no-border">
        <tr>
            <td class="width-70">
                <img src="{{public_path('itsolutionstuff.png')}}" alt="" width="200">
            </td>
            <td class="width-30">
                <h2>Invoice ID: 9828121</h2>
            </td>
        </tr>
    </table>
    
    <div class="margin-top">
        <table class="table-no-border">
            <tr>
                <td class="width-50">
                    <div><strong>To:</strong></div>
                    <div>Mal</div>
                    <div>The Ascent, Petaling Jaya</div>
                    <div><strong>012-3456789</strong></div>
                    <div><strong>Email:</strong>synergy@gmail.com.my</div>
                </td>
                <td class="width-50">
                    <div><strong>From:</strong></div>
                    <div>Hardik Savani</div>
                    <div>201, Styam Hills, Rajkot - 360001</div>
                    <div><strong>Phone:</strong> 84695585225</div>
                    <div><strong>Email:</strong> hardik@gmail.com</div>
                </td>
            </tr>
        </table>
    </div>

    <div>
        <table class="product-table">
            <thead>
                <tr>
                    <th class="width-25">
                        <strong>Quantity</strong>
                    </th>
                    <th class="width-50">
                        <strong>Product</strong>
                    </th>
                    <th class="width-25">
                        <strong>Price</strong>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $value)
                    <tr>
                        <td class="width-25">
                            {{$value['quantity']}}
                        </td>
                        <td class="width-50">
                            {{$value['description']}}
                        </td>
                        <td class="width-25">
                            {{$value['price']}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th class="width-70" colspan="2">
                        <strong>Sub Total:</strong>
                    </th>
                    <th class="width-25">
                        <strong>RM 1000.00</strong>
                    </th>
                </tr>
                <tr>
                    <th class="width-70" colspan="2">
                        <strong>Total Amount:</strong>
                    </th>
                    <th class="width-25">
                        <strong>RM 2000.00</strong>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="footer-div">
        <p>Thank you, </br>synergy@gmail.com</p>
    </div>
</body>
</html>