<?php

namespace app\admin\controller;

use app\admin\controller\Base;
use think\facade\Request; //请求
use app\admin\model\Category; //商品分类表
use app\admin\model\Product; //商品类
use app\admin\model\Productimg;
use app\admin\validate\Categoryvalidate; //分类验证器
use app\admin\validate\Productvalidate;//商品验证器 

use function PHPSTORM_META\type;

//商品控制器
class Commodity extends Base
{

    //==================================================================================================================================================
    // 商品分类控制器操作

    protected $beforeActionList = [
        //如果你执行的是delete这个方法 那就先执行查询他下级的方法
        'del_son' => ['only' => 'deletecate']
    ];

    //商品分类表
    public function cate()
    {
        $data = Category::all()->toArray();
        $category = Category::getcatezis($data);
        $this->assign('category', $category);
        return $this->fetch();
    }

    //删除商品分类
    public function deletecate()
    {
        if (Request::isAjax()) {
            //独立验证器
            (new Categoryvalidate())->scene('id')->goCheck();
            $cateid = Request::param('id');
            $result = Category::destroy($cateid);
            if ($result) {
                return json(201, 201);
            }
            return json(500, 500);
        } else {
            //禁止AJAX反问 返回403
            return json(403, 400);
        }
    }

    //删除父类则连同子类一起删除
    public function del_son()
    {
        //接收要删除的id
        (new Categoryvalidate())->scene('id')->goCheck();
        $cateId = Request::param('id');
        //获取所有数据
        $data = Category::all()->toArray();
        //获取顶级栏目下的所有子级的id
        $sonId = Category::getChildren($data, $cateId);
        if ($sonId) { //如果存在子栏目
            $res = Category::destroy($sonId);
            if ($res) {
                return true;
            } else {
                return false;
            }
        }
    }

    //分类修改页面
    public function editcate()
    {
        (new Categoryvalidate())->scene('id')->goCheck();
        $cateid = Request::param('id');
        $oulcate = Category::get($cateid);
        $this->assign('oulcate', $oulcate);
        return $this->fetch();
    }

    //执行添加或者分类修改
    public function catesave()
    {
        if (Request::isAjax()) {
            $result = Category::saves();
            return $result;
        } else {
            //禁止AJAX反问 返回403
            return json(403, 400);
        }
    }

    //添加子类
    public function addsubclass()
    {
        (new Categoryvalidate())->scene('id')->goCheck();
        $cateid = Request::param('id');
        $cate = Category::get($cateid);
        $this->assign('cate', $cate);
        return $this->fetch();
    }
    //商品分类结束

//==================================================================================================================================================
    //商品列表
    public function product()
    {
        if (Request::post()) {
            $productlist = Product::list();
            // halt($productlist);
        }else{
            $productlist = product::with('pimages', ['status'=>1,'type'=>1])->paginate(5);
        }
        $data = Category::all()->toArray();
        $category = Category::getcatezis($data);
        $this->assign('category', $category);
        $this->assign('productlist',$productlist);
        return $this->fetch();
    }

    //添加页面
    public function addproduct()
    {
        $data = Category::all()->toArray();
        $category = Category::getcatezis($data);
        $this->assign('category', $category);
        return $this->fetch();
    }

    // 上传图片接口
    public function img()
    {
        $file = Request::file('file'); //获取file对象
        if ($file) {
            //存入服务器
            $info = $file->validate([
                'size' => 5000000000,  //文件大小
                'ext' => 'jpeg,jpg,png,gif'  //文件扩展名
            ])->move('upload/');  //移动到public/upload/
            if ($info) {
                $res['status']=1;
                $res['info'] =  $info->getSaveName(); //图片的路径\
                $res['message']='上传成功！';
                return json($res);
            } else {
                return json(400, 200);
            }
        } else {
            return json(404, 200);
        }
    }

    //删除图片接口
    public function deletepimg(){
        if (Request::isAjax()) {
            $result = Productimg::deletepimage();
            return $result;
        } else {
            //禁止AJAX反问 返回403
            return json(403, 400);
        }
    }

    //执行添加修改
    public function seaveproduct(){
        if (Request::isAjax()) {
            $result = Product::saves();
            return $result;
        } else {
            //禁止AJAX反问 返回403
            return json(403, 400);
        }
    }

    //通过审核
    public function trialproduct(){
        if (Request::isAjax()) {
            $result = Product::trialproducts();
            return $result;
        } else {
            //禁止AJAX反问 返回403
            return json(403, 400);
        }
    }

    //删除商品
    public function deleteproduct(){
        if (Request::isAjax()) {
            $result = Product::deleteproducts();
            return $result;
        } else {
            //禁止AJAX反问 返回403
            return json(403, 400);
        }
    }

    //编辑页面
    public function editproduct(){
        (new Productvalidate())->scene('id')->goCheck();
        $productid = Request::param('id');
        $oulproduct = Product::with('pimages', ['status'=>1,'type'=>1])->where('id',$productid)->find();
        $data = Category::all()->toArray();
        $category = Category::getcatezis($data);
        $this->assign('category', $category);
        $this->assign('oulproduct', $oulproduct);
        return $this->fetch();
    }

    //商品详情
	public function detailsproduct(){
        (new Productvalidate())->scene('id')->goCheck();
        $productid = Request::param('id');
        $product = Product::with('pimages',['status'=>1,'type'=>1])->where('id',$productid)->find();
        $this->assign('product',$product);
        return $this->fetch();
    }

//==================================================================================================================================================
    


}
