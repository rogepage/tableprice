<?php

namespace App\Http\Controllers;


use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Member;
use Illuminate\Support\Facades\Log;


use App\Repositories\Contracts\MemberRepositoryInterface;
use Illuminate\Support\Facades\Config;

class MemberController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  protected $member;

 


   /**
     * Cria o cliente
     *
     * @param  string  $name
     * @param  string  $email
     * @return Response
     */  

  public function store(Request $request)
  {

   
    // valida os imputs
    $this->validate($request, [
      'name' => 'required|string|min:6',
      'email' => 'required|string|email|min:10|max:100',
    ]);

    try {
      // verifica se o usuário/email já existe na base
      if(Member::where('email',$request->email)->count() > 0 ){
        return response()->json([
          'success' => false,
          'message' => 'The email has already'
        ], 401);
      }

     // grava o cliente
      $member = Member::create($request->all());
   
     // valida de o cliente foi gravado com sucesso
      if (is_null($member)) {
        return response()->json([
          'status' => 'error',
          'message' => 'Failed to create a member'
        ], 204);
      }

      // retorno sucesso
      return response()->json([
        'success' => true,
        'data' => $member
      ], 200);
    } catch (\Throwable $th) {
      Log::error('An informational message: '.$th->getMessage());
    
      return response()->json([
        'success' => false,
        'message' => 'Failed to create client'
      ], 500);
    }
    
  }

 
}
