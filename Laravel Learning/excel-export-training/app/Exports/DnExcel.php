<?php

namespace App\Exports;

use App\Models\DeliveryNote;
use App\Models\DnProduct;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class DnExcel implements FromCollection, WithHeadings, WithEvents, WithHeadingRow, WithDrawings, WithStartRow
{
    protected $dnId;

    function _construct($dnId){
      $this->id = $dnId;
    }

    public function collection(){

      $deliveryNote = DeliveryNote::find($this->id);
      $productList  = DnProduct::where('dn_id',$this->id)->get();

      $data = [];
      foreach ($productList as $i => $product) {
        $data = [
          $i + 1,
          $product->product_name,
          '',
          '',
          '',
          '',
          'Packing List No',
          'From Carton No',
          'To Carton No',
          'Remark',
          $product->quantity,
          'Qty Per Carton',
          'Total Qty',
        ];
      }

      return $collect($data);
    }


    public function headings():array{

      $deliveryNote    = DeliveryNote::find($this->id);
      $LogisticCompany = LogisticCompany::where('logistic_id',$deliveryNote->logistic_id)->first();

      return [
        [''],[''],[''],[''],[''],[''],[''],[''],
        ['From: ','Synergy ESCO (Malaysia) Sdn. Bhd.','','','','','','Job ID:','','Job ID Value'],
        ['Address Line 1','','','','','','','Delivery Date/Time','',$deliveryNote->delivery_date],
        ['Address Line 2','','','','','','','Delivery Note No.:','',$deliveryNote->dn_no],
        ['City,State,ZIP Code','','','','','','','Logistic:','',$LogisticCompany->company_name],
        [''],
        ['Contact Person:','Contact Name','','','','','','Contact No.:','',"Contact Number"]
      ]
    }


}
