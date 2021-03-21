<?php

namespace App\Repositories\Contracts;

interface MemberRepositoryInterface
{
  public function create(array $request);

  public function find(int $memberId);
}
