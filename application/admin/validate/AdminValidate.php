<?php 
namespace app\admin\validate;
use think\facade\Request;  //请求
use think\Validate;
use app\lib\exception\VerificationException;

//统一验证器类
class AdminValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|number',
        'adminuser' => 'require|length:5,25',
        'password'=>'require|length:5,25',
        'captcha'=>'require|captcha',
    ];
	
	//错误信息
    protected $message=[
        'id.require' => 'id必须填写！',
        'id.number' => 'id必须是数字！',
        'adminuser.require' => '账号必填！',
        'adminuser.length' => '账号长度为5-25！',
        'password.require' => '密码必填！',
        'password.length' => '密码长度为5-25！',
        'captcha.require' => '验证码必填！',
        'captcha.captcha' => '验证码不正确！',
    ];

    //验证场景
    protected $scene = [
        'login'=>['adminuser','password','captcha'],//父类添加
    ];
}

?>