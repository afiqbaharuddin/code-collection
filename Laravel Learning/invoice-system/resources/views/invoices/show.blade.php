@extends('invoices.app')

@section('content')
<div class="container">
  <h1>Invoice #{{ $invoice->id }}</h1>
  <p>
    <strong>Customer: </strong>{{ $invoice->customer->name }}
  </p>
  <p>
    <strong>Date: </strong>{{ $invoice->invoice_date }}
  </p>
  <p>
    <strong>Total: </strong>{{ $invoice->total }}
  </p>

  <h4>Items</h4>
  <table class="table">
    <thead>
      <tr>
        <th>Product</th>
        <th>Quantity</th>
        <th>Price</th>
      </tr>
    </thead>
    <tbody>
      @foreach($invoice->items as $item)
        <tr>
          <td>{{ $item->product->name }}</td>
          <td>{{ $item->quantity }}</td>
          <td>{{ $item->total }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
