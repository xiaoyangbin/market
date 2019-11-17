<?php
namespace app\index\model;
use app\index\model\BaseModel;
use think\facade\Request;  //请求
use app\index\validate\AddressValidate;
use think\facade\Session;
use app\index\model\UserModel;
use app\index\model\City;

//用户地址表
class UserAddress extends BaseModel
{
	protected $pk = 'id';//默认主键
	protected $table = 'market_user_addr';//选择数据表
	protected $autoWriteTimestamp = true;
    protected $createTime = 'create_time';
    protected $dateFormat = 'Y-m-d H:i:s';
	protected $auto = [];
    protected $insert = ['create_time'];
    
	public static function saveaddress(){
		$data = Request::post();
		$data['uid'] = Session::get('uid');
		if(is_numeric($data['province']) && is_numeric($data['city']) && is_numeric($data['city'])){
			$data['province'] = City::where('id',$data['province'])->value('name');
			$data['city'] = City::where('id',$data['city'])->value('name');
			$data['region'] = City::where('id',$data['region'])->value('name');
		}
		if(!isset($data['id'])){
			(new AddressValidate())->scene('add')->goCheck();
			$result = self::create($data);
			if($result){
				$Reply = ['data'=>'添加成功','code'=>'201'];
				return json($Reply);
			}
			$Reply = ['data'=>'添加失败','code'=>'403'];
			return json($Reply);
		}else{
			(new AddressValidate())->scene('edit')->goCheck();
			$result = self::update($data);
			if($result){
				$Reply = ['data'=>'编辑成功','code'=>'201'];
				return json($Reply);
			}
			$Reply = ['data'=>'编辑失败','code'=>'403'];
			return json($Reply);
		}
	} 

	public static function default(){
		(new AddressValidate())->scene('id')->goCheck();
		$addresid = Request::post('id');
		self::where('is_default',1)->update(['is_default' => '0']);
		$result = self::where('id',$addresid)->update(['is_default' => '1']);
		if($result){
			$Reply = ['data'=>'默认成为收获地址','code'=>'201'];
			return json($Reply);
		}
		$Reply = ['data'=>'设置失败，请联系管理员','code'=>'404'];
		return json($Reply);
	}

	
}

