<?php
namespace app\admin\model;
use app\admin\model\BaseModel;
use think\facade\Request;  //请求
use think\facade\Session;//静态Session
//管理员表
class Admin extends BaseModel
{
	protected $pk = 'id';//默认主键
	protected $table = 'market_admin';//选择数据表

	public static function loginfind(){
		$adminuser = Request::post('adminuser');
		$password = Request::post('password');
		$data = self::where('adminuser',$adminuser)->find();
		if($data){
			if($data['password'] == md5(md5($password)."xiaoyang")){
				Session::set('adminuser',$data['adminuser']);
				return json('1',200);
			}else{
				return json('密码错误',200);
			}
		}else{
			return json('账号不存在',200);
		}
	}
				
}

