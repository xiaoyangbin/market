<?php
namespace app\index\controller;
use app\index\controller\Base;
use think\facade\Request;
use think\facade\Session;
use app\index\model\Category;

class Index extends Base{

    //商场主页
    public function index()
    {
        $data = Category::all()->toArray();
        $category = Category::getcatezis($data);
        // halt($category);
        $this->assign('username',Session::get('username'));
        $this->assign('category',$category);
        return $this->fetch();
    }

    //用户注册
    public function register()
    {
        return $this->fetch();
    }

    //用户登陆
    public function login(){
        if(Session::get('username')){
            $this->redirect('http://www.market.com/index/index/index.html');
        }
        return $this->fetch();
    }

    public function emails(){
        $email = Request::param('email');
        if(!$email){
            $Reply = ['data'=>'邮箱不能为空','code'=>'2'];
            return json($Reply);
        }
		$rule = ['email|邮箱'=>'require|email'];
        $res=$this->validate(Request::param(),$rule);
        if(true !== $res){
			$Reply = ['data'=>'邮箱格式不正确','code'=>'3'];
            return json($Reply);
		}
		$title = '验证码';
		$randomcode = rand('100000','999999');
        Session::set('randomcode',$randomcode);
        $result =  sendMail($email,$title,"您好！本次操作验证码为：".$randomcode).",请在5分钟内验证，该验证码只能使用一次。";
        if($result){
	        $Reply = ['data'=>'邮箱发送成功！','code'=>'1'];
            return json($Reply);
	    }else{
            $Reply = ['data'=>'邮箱发送失败！','code'=>'-1'];
            return json($Reply);
	    }
    }
    
    //忘记密码
    public function forgetpassword(){
        return $this->fetch();
    }

    public function getCategory(){

    }
}
