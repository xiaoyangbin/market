<?php
namespace app\admin\validate;
use app\admin\validate\BaseValidate;//统一验证器

//商品添加分类自定义验证器
class Categoryvalidate extends BaseValidate
{
	protected $rule = [
        'id' => 'require|number',
        'name' => 'require',
        'listirder'=>'require|number',
        'status'=>'require|number',
        'parent_id'=>'require|number'
    ];
	
	//错误信息
    protected $message=[
        'name.require' => '分类名称必填！',
        'listirder.require' => '商品分类排序必填！',
        'listirder.number' => '商品分类排序必须是数字！',
        'status.require' => '商品分状态序必填！',
        'status.number' => '商品分类状态必须是数字！',
        'parent_id.require'=>'所属分类必填！',
        'parent_id.number'=>'所属分类必须是数字'
    ];

    //验证场景
    protected $scene = [
        'id'=>['id'],//编辑ID验证
        'add'=>['name','listirder'],//父类添加
        'saves'=>['id','name','listirder','status'],//执行父类编辑
    ];

}


?>