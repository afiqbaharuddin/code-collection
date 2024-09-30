@extends('invoices.app')

@section('content')
    <div class="container">
        <h1>Create Invoice</h1>
        <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="customer">Customer</label>
                <select name="customer_id" id="customer" class="form-control">
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{$customer->id == $invoice->customer_id ? 'selected' : ''}}>{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="invoice_date">Invoice Date</label>
                <input type="date" name="invoice_date" id="invoice_date" class="form-control" value="{{$invoice->invoice_date}}">
            </div>
            <div class="form-group">
                <label for="total">Total</label>
                <input type="number" step="0.01" name="total" id="total" class="form-control" value="{{$invoice->total}}">
            </div>
            <div id="items" class="mb-4">
              @foreach($invoice->items as $index => $item)
                <div class="form-group">
                    <label for="product">Product</label>
                    <select name="items[{{ $index }}][product_id]" id="product" class="form-control">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{$product->id == $item->product_id ? 'selected' : ''}}>{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" name="items[{{ $index }}][quantity]" id="quantity" class="form-control" value="{{ $item->quantity }}">
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" step="0.01" name="items[{{ $index }}][price]" id="price" class="form-control" value="{{ $item->price }}">
                </div>
              @endforeach
            </div>
            <button type="submit" class="btn btn-primary">Update Invoice</button>
        </form>
    </div>
@endsection
