<?php
namespace app\admin\model;
use app\admin\model\BaseModel;

//城市管理表
class City extends BaseModel
{
    // protected $hidden = ['id','uid','articleid','create_time','update_time'];
	protected $pk = 'id';//默认主键
	protected $table = 'market_city';//选择数据表
				
}

