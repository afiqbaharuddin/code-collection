<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Product;
use App\Models\InvoiceItem;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index(){
      $invoices = Invoice::with('customer')->get();
      return view('invoices.index', compact('invoices'));
    }

    public function create(){
      $customers = Customer::all();
      $products  = Product::all();

      return view('invoices.create',compact('customers', 'products'));
    }

    public function store(Request $request){

      $invoice = new Invoice();
      $invoice->customer_id  = $request->customer_id;
      $invoice->invoice_date = $request->invoice_date;
      $invoice->total        = $request->total;
      $invoice->save();

      foreach ($request->items as $item) {

        $invoiceItem = new InvoiceItem();
        $invoiceItem->invoice_id = $invoice->id;
        $invoiceItem->product_id = $item['product_id'];
        $invoiceItem->quantity   = $item['quantity'];
        $invoiceItem->price      = $item['price'];
        $invoiceItem->save();
      }

      return redirect()->route('invoices.index');
    }

    public function show($id){
      $invoice = Invoice::with('items.product', 'customer')->findOrFail($id);
      return view('invoices.show', compact('invoice'));
    }

    public function edit($id){

      $invoice   = Invoice::findOrFail($id);
      $customers = Customer::all();
      $products  = Product::all();

      return view('invoices.edit', compact('invoice','customers','products'));
    }

    public function update(Request $request, $id){

      $invoice = Invoice::findOrFail($id);
      $invoice->customer_id  = $request->customer_id;
      $invoice->invoice_date = $request->invoice_date;
      $invoice->total        = $request->total;
      $invoice->save();

      InvoiceItem::where('invoice_id',$id)->delete();
      foreach ($request->items as $item) {

        $invoiceItem = new InvoiceItem();
        $invoiceItem->invoice_id = $invoice->id;
        $invoiceItem->product_id = $item['product_id'];
        $invoiceItem->quantity   = $item['quantity'];
        $invoiceItem->price      = $item['price'];
        $invoiceItem->save();
      }

      return redirect()->route('invoices.index');
    }

    public function destroy($id){
      $invoice = Invoice::findOrFail($id);
      $invoice->delete();

      return redirect()->route('invoices.index');
    }

    public function downloadPdf($id){
      $invoice = Invoice::with('items.product', 'customer')->findOrFail($id);
      $pdf     = Pdf::loadView('invoices.pdf', compact('invoice'));

      return $pdf->download('invoice_' .$invoice->id. '.pdf');
    }
}
