<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\facade\Request;//请求
use app\admin\model\Productimg;
use app\admin\validate\BannerValidate;

//推荐位控制器
class Recommend extends Base{
//==================================================================================================================================================  
    //添加轮播图
    public function addbanner(){
        return $this->fetch();
    }

    //获取全部轮播图
    public function getbaner(){
        $bannerlist = Productimg::where('pimg',0)->where('type',2)->order('order', 'asc')->select();
        $this->assign('bannerlist',$bannerlist);
        return $this->fetch();
    }

    //状态修改
    public function setbanstatus(){
        if (Request::isAjax()) {
            (new BannerValidate())->scene('id')->goCheck();
            $banid = Request::post('id');
            $banstatus = Request::post('status');
            if( $banstatus == 0){
                $result = Productimg::where('id',$banid)->update(['status' => 1]);
            }else{
                $result = Productimg::where('id',$banid)->update(['status' => 0]);
            }
           if($result){
                return json(1,200);
           }
           return json(500,200);
        }else{
            return  json('请求类型错误！',200);
        }
    }

    //编辑轮播图
    public function editbanner(){
        (new BannerValidate())->scene('id')->goCheck();
        $banid = Request::param('id');
        $ban = Productimg::where('id',$banid)->find();
        $this->assign('ban',$ban);
        return $this->fetch();
    }

    //删除轮播图
    public function deletebanner(){
        if (Request::isAjax()) {
            $result = Productimg::deleteban();
            return $result;
        } else {
            //禁止AJAX反问 返回403
            return json(403, 400);
        }
    }

    public function sevasbanner(){
        if (Request::isAjax()) {
            $result = Productimg::sevasban();
            return $result;
        }else{
            return  json('请求类型错误！',200);
        }
    }
}

?>