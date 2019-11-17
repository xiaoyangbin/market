<?php
namespace app\index\model;
use app\index\model\BaseModel;
use think\facade\Request;  //请求

//城市表
class City extends BaseModel
{
	protected $pk = 'id';//默认主键
	protected $table = 'market_city';//选择数据表

    
}

