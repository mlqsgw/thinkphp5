<?php
namespace app\index\model;

use think\Model;

class PlayStatisticsl extends Model
{
   
    // 定义关联方法
    public function familyUser()
    {
        
        // 直播明细 BELONGS TO 关联用户
        return $this->belongsTo('FamilyUser','user_id', 'id');
    }

}