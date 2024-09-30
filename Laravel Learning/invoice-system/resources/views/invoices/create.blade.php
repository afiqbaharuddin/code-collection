@extends('invoices.app')

@section('content')
    <div class="container">
        <h1>Create Invoice</h1>
        <form action="{{ route('invoices.store') }}" method="POST">
            @csrf
            <div class="form-group mb-2">
                <label for="customer">Customer</label>
                <select name="customer_id" id="customer" class="form-control">
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-2">
                <label for="invoice_date">Invoice Date</label>
                <input type="date" name="invoice_date" id="invoice_date" class="form-control">
            </div>
            <div class="form-group mb-2">
                <label for="total">Total</label>
                <input type="number" step="0.01" name="total" id="total" class="form-control">
            </div>
            <div id="items" class="mb-4">
                <div class="form-group mb-2">
                    <label for="product">Product</label>
                    <select name="items[0][product_id]" id="product" class="form-control">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label for="quantity">Quantity</label>
                    <input type="number" name="items[0][quantity]" id="quantity" class="form-control">
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" step="0.01" name="items[0][price]" id="price" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Create Invoice</button>
        </form>
    </div>
@endsection
