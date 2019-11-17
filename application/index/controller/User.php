<?php
namespace app\index\controller;
use app\index\controller\Base;
use think\facade\Request;
use app\index\model\UserModel;
use think\facade\Session;
use app\index\model\City;
use app\index\model\UserAddress;
use app\index\validate\AddressValidate;

class User extends Base{
    // protected function initialize(){
    //     if(!Session::get('username')){
    //         $this->redirect('http://www.market.com/index/index/login.html');
    //     }
    // }

    //个人中心
    public function index(){
        return $this->fetch();
    }

    //个人信息
    public function information(){
        $userdata = UserModel::where('username',Session::get('username'))->find();
        $this->assign('userdata',$userdata);
        return $this->fetch();
    }

    //修改头像
    public function urlimg(){
        if(Request::isAjax()){
            $result = UserModel::Headportrait();
            return $result;
        }else{
            return json('访问类型错误。', 400);
        }
    }

    //收货地址
    public function address(){
        $addresslist = UserAddress::where('uid', Session::get('uid'))->order('create_time', 'asc')->select();
        $province = City::where('parent_id',1)->select();
        $this->assign('province',$province);
        $this->assign('addresslist',$addresslist);
        return $this->fetch();
    }

    public function getRegion()
	{
		$map['parent_id']=input("pid");
        $map['type']=input("type");
        $list=City::where($map)->select();
        return $list;
	}

    //添加编辑
    public function addaddress(){
        if(Request::isAjax()){
            $result = UserAddress::saveaddress();
            return $result;
        }else{
            return json('访问类型错误。', 400);
        }
    }

    public function defaultaddress(){
       if(Request::isAjax()){
            $result = UserAddress::default();
            return $result;
        }else{
            return json('访问类型错误。', 400);
        }
    }

    public function editaddress(){
        (new AddressValidate())->scene('id')->goCheck();
        $addressid = Request::param('id');
        $userddress = UserAddress::where('id',$addressid)->find();
        $province = City::where('parent_id',1)->select();
        $this->assign('userddress',$userddress);
        $this->assign('province',$province);
        return $this->fetch();
    }


    public function deleteaddress(){
        (new AddressValidate())->scene('id')->goCheck();
        $addressid = Request::param('id');
        $result = UserAddress::destroy($addressid);
        if( $result){
            $this->redirect('http://www.market.com/index/user/address.html');
        }
        return json('内部错误！',500);
    }
    public function order(){
        return $this->fetch();   
    }

    public function orderinfo(){
        return $this->fetch();   
    }

    public function collection(){
        return $this->fetch(); 
    }

    public function foot(){
        return $this->fetch(); 
    }

    public function comment(){
        return $this->fetch(); 
    }


    //用户新增和编辑
    public function saveuser(){
        if(Request::isAjax()){
            $result = UserModel::saves();
            return $result;
        }else{
            return json('访问类型错误。', 400);
        }
    }

    //用户登陆
    public function login(){
        if(Request::isAjax()){
            $result = UserModel::LoginValidate();
            return $result;
        }else{
            return json('访问类型错误。', 400);
        }
    }

    public function forgetpassword(){
        if(Request::isAjax()){
            $result = UserModel::editpassword();
            return $result;
        }else{
            return json('访问类型错误。', 400);
        }
    }
}
