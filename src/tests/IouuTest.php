<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use Illuminate\Foundation\Testing\RefreshDatabase;

class IoouTest extends TestCase
{
    
    public function testCreateMember(){
        $member = factory(\App\Models\Member::class)->make()->toArray();

        $this->json('POST', '/api/member/create', $member, ['Accept' => 'application/json'])
            ->seeStatusCode(200)
            ->seeJsonStructure([
                "data" => [
                    'member_id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
                
             ]);
           
    }

    public function testCreateContract(){
        $membe = factory(\App\Models\Member::class)->make()->toArray();
        $member = App\Models\Member::find(1);
  
       
        $data = ["member_id"=>$member->member_id,"amount"=>15000, "periods"=>6, "rate"=>3];
       
        $this->json('POST', '/api/contract/create', $data,['Accept' => 'application/json'])
            ->seeStatusCode(200);
            
           
    }
}
