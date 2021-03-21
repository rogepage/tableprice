<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;
use App\Models\Member;

class Authenticate
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle(Request $request, Closure $next, $guard = null)
  {
    if (!$request->hasHeader('Authorization')) {
      return response()->json('Unauthorized', 401);
    }
    $authorizationHeader = $request->header('Authorization');
    $token = str_replace('Bearer ', '', $authorizationHeader);

    try {
      $credentials = JWT::decode($token, env('JWT_KEY'), ['HS256']);
    } catch (ExpiredException $e) {
      return response()->json([
        'status' => 'fail',
        'message' => 'Token expired'
      ], 422);
    } catch (Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Token invalid'
      ], 400);
    }

    $member = Member::find($credentials->sub);

    $request->auth = $member;

    return $next($request);
  }
}
