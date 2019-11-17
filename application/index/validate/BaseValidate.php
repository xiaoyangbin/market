<?php 
namespace app\index\validate;
use think\facade\Request;  //请求
use think\Validate;
use app\lib\exception\VerificationException;

//统一验证器类
class BaseValidate extends Validate
{
	//接收参数判断通过验证规则
	public function goCheck()
	{
		$params = Request::param();
		//batch分批认证
		$result = $this->check($params);
		
		if(!$result){
            $e = new VerificationException([
                'msg'  => $this->error,
			]);
			throw $e;
		}
		else{
			return true;
		}
	}
	
	
}

?>