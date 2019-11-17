<?php
namespace app\index\model;
use app\index\model\BaseModel;
use think\facade\Request;//请求

//商品分类表
class Category extends BaseModel
{
    protected $hidden = ['update_time'];
	protected $pk = 'id';//默认主键
	protected $table = 'market_category';//选择数据表
	//开启自动时间戳
	protected $autoWriteTimestamp = true;
	protected $createTime = 'create_time';
	protected $dateFormat = 'Y-m-d H:i:s';
	//自动完成设置
	protected $auto = [];
	// 仅新增时设置  
	protected $insert = ['create_time'];

	//获取分类
	public static function getcatezis($list,$path=0,$flag=1){
		static $catezi = array();
		foreach($list as $k => $v){
			//先获取顶级ID
			if($v['parent_id'] == $path){
				//获取到得存进数组
				$catezi[] = $v;
				self::getcatezis($list,$v['id'],$flag+1);
			}
		}
		return $catezi;
	}

}

