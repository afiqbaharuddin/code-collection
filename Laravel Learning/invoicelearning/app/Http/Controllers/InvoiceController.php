<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}
