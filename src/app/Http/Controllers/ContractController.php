<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Contract;
use App\Models\TablePrice;
use App\Models\Member;
use Illuminate\Support\Facades\Log;

class ContractController extends Controller
{

  /**
     * Retorna todos os contratos do cliente
     *
     * @param  int  $member_id
     * @param  string  $email
     * @return Response
     */  

  #region
  public function getContrats(Request $request){
    $contracts = false;
    
  try {
    if($request->has('email')){
      $client = Member::where('email',$request->email)->get();
     
      if($client->count()>0){
        $contracts = Contract::where('member_id',$client[0]->member_id)->get();
        if($contracts){
            foreach($contracts as $contract){
              if($contract->status==1){ // verifica a condicao do contrato (ativo / renegociado)
                $contract->condition= 'renegotiated';
              }else{
                $contract->condition= 'active';
              }
             $contract->prices; // carrega a tabela price do contrato
             
              
            }
        }
      }
      
    }

    if($request->has('member_id') && is_numeric($request->member_id)){
      $contracts = Contract::where('member_id',$request->member_id)->get();
      if($contracts){
          foreach($contracts as $contract){// verifica a condicao do contrato (ativo / renegociado)
            if($contract->status==1){
              $contract->condition= 'renegotiated';
            }else{
              $contract->condition= 'active';
            }

            $contract->prices; // carrega a tabela price do contrato

          }
      }
    }
  } catch (\Throwable $th) {
   
    Log::debug("Erro: {$th}"); // log de erros
    return response()->json([
      'success' => false,
      'message' => $th->getMessage()
    ], 500);
   
  }
    

  if(!$contracts || $contracts->count()==0){
    return response()->json([
      'success' => false,
      'message' => 'Contract not found'
    ], 400);
  }

  return response()->json([
    'success' => true,
    'contract' => $contracts,
  ], 200);
   

  }
  #endregion

  /**
     * Renegocia o contrado do cliente
     *
     * @param  int  $member_id
     * @param  int  $contract_id
     * @param  int  $periods
     * @return Response
  */
  #region
  public function renegotiateContract(Request $request){
    $this->validate($request, [
      'member_id' => 'required|integer|max:4',
      'contract_id' => 'required|integer|max:4',
      'periods'=> 'required|integer',
    
    ]);
    try {
      DB::beginTransaction();
      $contract = Contract::find($request->contract_id); // localiza o contrato
      // condicao: 
      // 1- contrato deve existir
      // 2 - tem que pertencer ao cliente informado
      // 3 - deve estar ativo (nao negociado)
      if($contract && $contract->member_id == $request->member_id && $contract->status==0){ 

       
    
      //verifica se foi informado uma nova taxa, se nao,  utiliza a do contrato anterior
      if($request->rate !=null){
        $rate = $request->rate/100;
      }else{
        $rate = $contract->rate;
      }
  
      $amount = $contract->getLastPricePayment()->balance_due; // busca o saldo do último pagamento
      $value = Contract::generateValueParcel($rate*100,$amount,$request->periods); // gera a parcela do contrato

   
      $contractNew = new Contract();
      $contractNew->member_id = $request->member_id; // id do cliente
      $contractNew->amount = $amount; // saldo devedor incial
      $contractNew->periods = $request->periods; // numero de parcelas
      $contractNew->rate = $rate; // taxa
      $contractNew->parcel_value = $value; // valor das parcelas
      $contractNew->contract_ref = $contract->contract_id;
      $contractNew->save();

      // muda o status do contrato anterior para renegociado
      if($contractNew){
        $contract->status = 1; // atribui status de renegociado
        $contract->update();
      }
      

      $prices = TablePrice::generateTablePriceByContract($contractNew); // gera a tabela price
      DB::commit();
      return response()->json([
        'success' => true,
        'contract' => $contractNew,
      ], 200);

      

    }else{
  
      
      return response()->json([
        'success' => false,
        'message' => 'Contract not found'
      ], 400);
    }
     
    } catch (\Throwable $th) {
      DB::rollback();
      Log::debug("Erro: {$th}"); // log de erros
      return response()->json([
        'success' => false,
        'message' => $th->getMessage()
      ], 500);
    }
    
    
  }
  #endregion


  /**
     * Marca parcela da tabela price como paga
     *
     * @param  int  $member_id
     * @param  int  $contract_id
     * @param  int  $quota
     * @return Response
  */
  #region
  public function makePaymentPrice(Request $request)
  {
    $this->validate($request, [
      'member_id' => 'required|integer|max:4',
      'contract_id' => 'required|integer|max:4',
      'quota'=> 'required|integer',
    
    ]);

    try {
      $contract = Contract::find($request->contract_id); // persiste o contrato

      // verifica se o membro e dono do contrato
      if($contract->member_id == $request->member_id){
        $price = TablePrice::makePayment($request->contract_id,$request->quota); // registra o pagamento da parcela informada
        return response()->json([
          'success' => true,
          'price' => $price,
          'contract' =>$contract,
        ], 200);
      }else{
        return response()->json([
          'success' => false,
          'message' => 'Contract not find'
        ], 400);
      }
     
    } catch (\Throwable $th) {
    
      Log::debug("Erro: {$th}"); // log de erros
     
      return response()->json([
        'success' => false,
        'message' => $th->getMessage()
      ], 500);
    }
   
  }
  #endregion

  /**
     * Cria o contrado e a tabela price do cliente
     *
     * @param  int  $member_id
     * @param  double  $amount
     * @param  int  $periods
     * @param  double  $rate
     * @return Response
  */
  #region
  public function store(Request $request)
  {

  
    // valida os imputs
    $this->validate($request, [
      'member_id' => 'required|integer',
      'amount' => 'required|numeric',
      'periods'=> 'required|integer',
      'rate'=> 'required|numeric'
    ]);

   
   

    try{

      //  dd(Contract::find(20)->getLastPrice()[0]->quota);
      // calcula o valor da parcela
      $value = Contract::generateValueParcel($request->rate,$request->amount,$request->periods);

      // $a = 4/0;

      DB::beginTransaction();

      $contract = new Contract();
      $contract->member_id = $request->member_id;
      $contract->amount = $request->amount;
      $contract->periods = $request->periods;
      $contract->rate = $request->rate/100;
      $contract->parcel_value = $value;
      $contract->save();

      $prices = TablePrice::generateTablePriceByContract($contract);
     
      DB::commit();
      return response()->json([
            'success' => true,
            'contract' => $contract,
            'prices'=> $prices,
      ], 200);

    } catch (\Throwable $e) {
      Log::debug("Erro: {$e}"); // log de erros
      DB::rollback();
      return response()->json([
        'success' => false,
        'message' => $e->getMessage()
      ], 500);
    }

    
  }

  #endregion


  
}
