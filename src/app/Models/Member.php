<?php

namespace App\Models;

use App\Libs\ConnectorNode;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
  protected $table = 'members';

  protected $primaryKey = 'member_id';

  public $timestamps = true;

  protected $dates = [
    'created_at',
    'updated_at',
    'deleted_at',
  ];

  protected $fillable = [
    'email',
    'name',
  ];




  public function hashs()
  {
      return $this->hasMany('App\Models\Contract', 'member_id');
  }

 


 

  



}
