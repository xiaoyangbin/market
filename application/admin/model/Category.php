<?php
namespace app\admin\model;
use app\admin\model\BaseModel;
use think\facade\Request;//请求
use app\admin\validate\Categoryvalidate;//商品分类验证器

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

	//添加编辑
	public static function saves(){
		//获取POST请求
		$data = Request::post();
		//ID等于空的话代表添加分类
		if(!isset($data['id'])){
			(new Categoryvalidate())->scene('add')->goCheck();
			$result = self::create($data);
			if($result){//创建成功返回201
				return json(201,201);
			}
			//创建失败返回500（内部错误）
			return json(500,200);
		}
		//不为空侧为编辑
		else{
			// halt($data);
			(new Categoryvalidate())->scene('saves')->goCheck();
			$result = self::where('id', $data['id'])->update($data);
			if($result){
				return json(202,202);
			}
			//更新失败或者没有更新返回400
			return json(400,200);
		}
	}

	//父类删除连同子类一起删除(根据id获取下级栏目)
	public static function getChildren($data,$cateId){
		static  $arr = [];
        foreach ($data as $key =>$val){
            //如果栏目的parent_id等于传过来的cateId那就是所传的id的子栏目
            if($val['parent_id'] == $cateId){
                $arr[] = $val['id'];
                self::getChildren($data,$val['id']);
            }
		}
        return $arr;
	}

}

