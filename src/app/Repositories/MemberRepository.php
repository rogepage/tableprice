<?php

namespace App\Repositories;

use App\Models\Member;
use App\Repositories\Contracts\MemberRepositoryInterface;

class MemberRepository implements MemberRepositoryInterface
{
  public function create(array $request)
  {
    $member = Member::create($request);

    return $member;
  }

  public function find(int $memberId)
  {
    $member = Member::find($memberId);

    return $member;
  }
}
