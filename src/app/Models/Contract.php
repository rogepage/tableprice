<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
  protected $table = 'contracts';

  protected $primaryKey = 'contract_id';

  public $timestamps = true;

  protected $dates = [
    'created_at',
    'updated_at',
    'deleted_at',
  ];

  protected $fillable = [
    'member_id',
    'amount',
    'parcel_value',
    'contract_ref',
    'periods',
    'rate',
    'status'
  ];

  public function prices()
  {
      return $this->hasMany('App\Models\TablePrice', 'contract_id');
  }




   public function getLastPrice(){
     return TablePrice::where('contract_id',$this->contract_id)->orderBy('created_at','desc')->limit(1)->get();
  }


  public function getLastPricePayment(){
    return TablePrice::where('contract_id',$this->contract_id)->orderBy('created_at','desc')->where('payment_status',1)->limit(1)->get()[0];
 }
   


  static function generateValueParcel($tax,$amount,$periods,$contract=false){

   
    $tax = $tax/100; // fracionando a taxa

    $xsub =  1/pow((1+$tax),$periods); // calculando o divisor

    $pmt = ($tax/(1-$xsub))*$amount; // realizando o calculo

    return round($pmt,2);
    
  } 


  
}
