<?php
namespace app\index\model;

use think\Model;

class DayDetail extends Model
{
   
    // 开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 定义自动完成的属性
    protected $insert = ['status' => 1];

    // 定义关联方法
    public function family_user()
    {
        // 定义关联
        return $this->hasMany('FamilyUser','id','id');
    }

}