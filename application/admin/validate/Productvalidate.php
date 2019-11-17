<?php
namespace app\admin\validate;
use app\admin\validate\BaseValidate;//统一验证器
use app\admin\validate\Categoryvalidate;//商品分类验证器

//商品添加分类自定义验证器
class Productvalidate extends BaseValidate
{
	protected $rule = [
        'id' => 'require|number',
        'name' => 'require',//名字
        'brand'=>'require',//品牌
        'price'=>'require|float',//价格
        'number'=>'require|number',//数量
        'stock'=>'require|number',//库存
        'production_date'=>'require',//生产日期
        'shelf_life'=>'require',//商品有效期
        'descript'=>'require',//商品描述
        'url'=>'require|array',//图片
        'audit_status'=>'require|number',//审核
    ];
	
	//错误信息
    protected $message=[
        'id.require' => 'id必须填写！',
        'id.number' => 'id必须是数字！',
        'name.require' => '商品名字必须填！',
        'brand.require' => '商品品牌必须填！',
        'price.require' => '商品价格必须填写！',
        'price.float' => '商品价格如18.88！',
        'number.require' => '商品数量必须填！',
        'number.number' => '商品数量必须是数字！',
        'stock.require' => '商品库存必须填！',
        'stock.number' => '商品库存必须是数字！',
        'production_date.require' => '商品生产日期须填！',
        'shelf_life.require'=>'商品有效期必须填！',
        'descript.require'=>'商品描述必须填！',
        'url.require'=>'商品图片必须填！',
        'url.array'=>'商品图片必须是数组！',
        'audit_status.require' => '商品审核ID必须填写！',
        'audit_status.number' => '商品审核ID必须是数字！',
    ];

    //验证场景
    protected $scene = [
        'id'=>['id'],//删除
        'trialproducts'=>['id','audit_status'],//审核
        'add'=>['name','price','brand','number','stock','production_date','shelf_life','descript','url'],//添加
        'edit'=>['id','name','brand','price','number','stock','production_date','shelf_life','descript','url'],//修改
    ];

}


?>