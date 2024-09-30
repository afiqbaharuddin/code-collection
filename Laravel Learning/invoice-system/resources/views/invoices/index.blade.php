@extends('invoices.app')

@section('content')
    <div class="container">
        <h1>Invoices</h1>
        <a href="{{ route('invoices.create') }}" class="btn btn-primary">Create Invoice</a>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->id }}</td>
                        <td>{{ $invoice->customer->name }}</td>
                        <td>{{ $invoice->invoice_date }}</td>
                        <td>{{ $invoice->total }}</td>
                        <td>
                            <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info">View</a>
                            <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning">Edit</a>
                            <a href="{{ route('invoices.downloadPdf', $invoice->id) }}" class="btn btn-success">Download</a>
                            <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
