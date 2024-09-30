<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use PDF;

class InvoiceController extends Controller
{
    public function generateInvoice(){
        $data = [
            'invoice_number' => '123',
            'invoice_date'   => 'January 1, 2024',
            'due_date'       => 'February 1, 2024',

            'company' => [
                'name'    => 'Your Company',
                'address' => 'The Ascent, Petaling Jaya',
            ],

            'customer' => [
                'name'  => 'Customer Name',
                'email' => 'cus@gmail.com',
            ],

            'items' => [
                ['name' => 'Website design', 'price' => '$300.00'],
                ['name' => 'Hosting (3 months)', 'price' => '$75.00'],
                ['name' => 'Domain name (1 year)', 'price' => '$10.00']
            ],
            'total' => '$385.00'
        ];

        $pdf = PDF::loadView('invoice', $data);

        return $pdf->download('invoice.pdf');
    }

    public function genInvoice(){
        $data = [
            [
                'quantity'    => 2,
                'description' => 'Gold',
                'price'       => 'RM 50.00',
            ],
            [
                'quantity'    => 1,
                'description' => 'Silver',
                'price'       => 'RM 25.00',
            ],
            [
                'quantity'    => 3,
                'description' => 'Platinum',
                'price'       => 'RM 75.00',
            ],
        ];

        $pdf = PDF::loadView('invoice2', ['data' => $data]);

        return $pdf->download();
    }

//invoice system code

  public function invoiceSystem(){
    $invoices = Invoice::all();
    return view('invoices.invoiceSystem', compact('invoices'));
  }

  public function create(){
    return view('invoices.create');
  }

  public function store(Request $request){
    $invoice = new Invoice();
    $invoice->customer_name = $request->input('customer_name');
    $invoice->date          = $request->input('date');
    $invoice->total_amount  = $request->input('total_amount');
    $invoice->save();

    return redirect()->route('invoices.invoiceSystem');
  }

  public function show($id){
    $invoice = Invoice::find($id);
    return view('invoices.show', compact('invoices'));
  }

  public function edit($id){
    $invoice = Invoice::find($id);
    return view('invoices.edit', compact('invoices'));
  }

  public function update(Request $request, $id){
    $invoice = Invoice::find($id);;
    $invoice->customer_name = $request->input('customer_name');
    $invoice->date          = $request->input('date');
    $invoice->total_amount  = $request->input('total_amount');
    $invoice->save();

    return redirect()->route('invoices.invoiceSystem');
  }

  public function destroy($id){
    $invoice = Invoice::find($id);
    $invoice->delete();

    return redirect()->route('invoices.invoiceSystem');
  }

  public function generatePDF($id){
    $invoice = Invoice::find($id);
    $pdf     = \PDF::loadView('invoices.pdf', compact('invoice'));

    return $pdf->download('invoice.pdf');
  }
}
