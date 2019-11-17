<?php 
namespace app\index\validate;
use think\facade\Request;  //请求
use think\Validate;
use app\lib\exception\VerificationException;

class AddressValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|number',
        'uid' => 'require|number',
        'phone'=>'require|mobile',
        'name'=>'require|length:1,25',
        'country'=>'require',
        'province'=>'require',
        'city'=>'require',
        'region'=>'require',
        'details_addr'=>'require|length:1,100',
        'is_default'=>'require|number',
    ];
	
    protected $message=[
        'id.require' => 'id必须填写！',
        'id.number' => 'id必须是数字！',
        'uid.require' => 'uid必须填写！',
        'uid.number' => 'uid必须是数字！',
        'name.require' => '账号必填！',
        'name.length' => '账号长度为5-25！',
        'phone.require'=>'手机必须填写！',
        'phone.mobile'=>'手机格式不正确！',
        'country.require' => '所属国家必须填写！',
        'province.require' => '所属省份必须填写！',
        'city.require' => '所属城市必须填写！',
        'region.require' => '所属地区必须填写！',
        'details_addr.require' => '详情地址必须填写！',
        'is_default.require' => '默认字段必须填写！',
        'is_default.number' => '默认字段必须是数字！',
    ];

    protected $scene = [
        'add'=>['phone','name','province','city','region','details_addr'],
        'id'=>['id'],
        'edit'=>['id','phone','name','province','city','region','details_addr'],
    ];

}

?>