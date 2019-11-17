<?php
namespace app\admin\model;
use app\admin\model\BaseModel;

//会员管理表
class User extends BaseModel
{
    // protected $hidden = ['id','uid','articleid','create_time','update_time'];
	protected $pk = 'id';//默认主键
	protected $table = 'market_user';//选择数据表
	//开启自动时间戳
	protected $autoWriteTimestamp = true;
	protected $createTime = 'create_time';
	protected $dateFormat = 'Y-m-d H:i:s';
	//自动完成设置
	protected $auto = [];
	// 仅新增时设置  
	protected $insert = ['create_time'];
			
}

