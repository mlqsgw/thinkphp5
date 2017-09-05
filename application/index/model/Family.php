<?php
namespace app\index\model;

use think\Model;

class Family extends Model
{
     protected $type       = [
        'birthday' => 'timestamp:Y-m-d',
    ];

    // public function family_user(){
    //     return $this->belongTo('FamilyUser');
    // }

    // public function play_statisticsl(){
    //     return $this->belongTo('PlayStatisticsl');
    // }
}