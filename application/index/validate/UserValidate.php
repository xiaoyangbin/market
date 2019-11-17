<?php 
namespace app\index\validate;
use think\facade\Request;  //请求
use think\Validate;
use app\lib\exception\VerificationException;

//统一验证器类
class UserValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|number',
        'email' => 'require|email',
        'password'=>'require|length:5,25',
        'username'=>'require|length:1,255',
        'sex'=>'require|number',
        'phone'=>'require|mobile',
        'integral'=>'require|number',
        'stats'=>'require|number',
    ];
	
	//错误信息
    protected $message=[
        'id.require' => 'id必须填写！',
        'id.number' => 'id必须是数字！',
        'username.require' => '账号必填！',
        'username.length' => '账号长度为5-25！',
        'password.require' => '密码必填！',
        'password.length' => '密码长度为5-25！',
        'email.require'=>'邮箱必须填写！',
        'email.email'=>'邮箱格式不对！',
        'sex.require'=>'性别必须填写！',
        'sex.number'=>'性别格式不正确！',
        'phone.require'=>'手机必须填写！',
        'phone.mobile'=>'手机格式不正确！',
        'integral.require' => '积分必填！',
        'integral.number' => '积分格式不正确！',
        'stats.require' => '状态必填！',
        'stats.number' => '状态格式不正确！',
    ];

    //验证场景
    protected $scene = [
        'register'=>['username','email','password'],
        'login' =>['email','password'],
        'edit' =>['username','sex','phone','email'],
    ];

}

?>