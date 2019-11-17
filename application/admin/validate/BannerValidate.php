<?php 
namespace app\admin\validate;
use think\facade\Request;  //请求
use think\Validate;
use app\lib\exception\VerificationException;

//统一验证器类
class BannerValidate extends BaseValidate
{
    protected $rule = [
        'id' =>'require|number',
        'url' => 'require',
        'order'=>'require|number',
        'status'=>'require|number',
    ];
	
	//错误信息
    protected $message=[
        'id.require' => '图片ID必填！',
        'id.number' => '图片ID必须是数字！',
        'url.require' => '图片路径必填！',
        'order.require' => '图片排序必填！',
        'order.number' => '图片排序必须是数字！',
        'status.require' => '状态必须填写！',
        'status.number' => '状态必须是数字！',
    ];

    //验证场景
    protected $scene = [
        'id' => ['id'],
        'add'=>['url','order'],
        'edit'=>['id','url','order'],
    ];
}

?>