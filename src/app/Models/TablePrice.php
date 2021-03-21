<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TablePrice extends Model
{
  protected $table = 'table_prices';

  protected $primaryKey = 'price_id';

  public $timestamps = true;

  protected $dates = [
    'created_at',
    'updated_at',
    'deleted_at',
  ];

  protected $fillable = [
    'contract_id',
    'quota',
    'balance_due',
    'fees',
    'amortization',
    'payment_status'

  ];

 

  static function makePayment($contract_id, $quota, $status=1){
   $price =  TablePrice::where('contract_id',$contract_id)->where('quota',$quota)->get()[0];
  
   if($price){
    $price->payment_status = $status;
    $price->update();
   }else{
     throw new \Exception("Price not find", 400);
     
   }
   return $price;
  }

  static function generateTablePriceByContract($contract){
    try {
    
     
     
      $GLOBALS['amount'] = $contract->amount;
      for ($i = 1; $i <=  $contract->periods; $i++) {

          $amount = $GLOBALS['amount'];
         
          $juros = $amount*$contract->rate;
          $totalJuros = $amount+$juros;
          $saldoNovo = $totalJuros-$contract->parcel_value;
          $amortizacao = $contract->parcel_value - $juros;

          $tablePrice = new TablePrice();
          $tablePrice->contract_id = $contract->contract_id;
          $tablePrice->quota = $i;
          $tablePrice->balance_due = $saldoNovo < 0? 0:$saldoNovo;
          $tablePrice->fees = $juros;
          $tablePrice->amortization = $contract->parcel_value-$juros;
          $tablePrice->save();
          $GLOBALS['amount'] = $saldoNovo;
        
      }

      return $contract->prices;
     
    } catch (\Throwable $th) {
      throw $th;
    }

  }
  // static function genereteTablePrice2($contract_id){
  //   try {
  //     $contract = Contract::find($contract_id);
  //     $balance = $contract->amount;
  //     $GLOBALS['balance'] = $contract->amount;
  //     for ($i = 1; $i <=  $contract->periods; $i++) {
  //       if($i===1){
  //           $juros = $contract->amount*$contract->rate;
  //           $totalJuros = $contract->amount+$juros;
  //           $saldoNovo = $totalJuros-$contract->parcel_value;
  //           $amortizacao = $contract->parcel_value - $juros;

  //           $tablePrice = new TablePrice();
  //           $tablePrice->contract_id = $contract->contract_id;
  //           $tablePrice->quota = $i;
  //           $tablePrice->balance_due = $saldoNovo;
  //           $tablePrice->fees = $juros;
  //           $tablePrice->amortization = $contract->parcel_value-$juros;
  //           $tablePrice->save();
  //           $GLOBALS['balance'] = $saldoNovo;

  //       }else{
  //         // $lastPayment = $contract->prices->where('status',1)->sortByDesc('created_at')[0];
  //         // $lastPrice = $contract->prices->sortByDesc('created_at')[0];
  //         // $lastPrice= $contract->getLastPrice()[0];
  //         $amount = $GLOBALS['balance'];
         
  //         $juros = $amount*$contract->rate;
  //         $totalJuros = $amount+$juros;
  //         $saldoNovo = $totalJuros-$contract->parcel_value;
  //         $amortizacao = $contract->parcel_value - $juros;

  //         $tablePrice = new TablePrice();
  //         $tablePrice->contract_id = $contract->contract_id;
  //         $tablePrice->quota = $i;
  //         $tablePrice->balance_due = $saldoNovo;
  //         $tablePrice->fees = $juros;
  //         $tablePrice->amortization = $contract->parcel_value-$juros;
  //         $tablePrice->save();
  //         $GLOBALS['balance'] = $saldoNovo;
  //       }
  //     }
     
  //   } catch (\Throwable $th) {
  //     throw $th;
  //   }
    

  // }
  
}
