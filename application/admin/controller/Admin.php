<?php
namespace app\admin\controller;
use think\Controller;
use think\captcha\Captcha;
use app\admin\validate\AdminValidate;
use think\facade\Request;  //请求
use app\admin\model\Admin as AdminModer;
use think\facade\Session;//静态Session

class Admin extends Controller
{
    public function login()
    {
        if(Session::get('adminuser')){
            $this->redirect('http://www.market.com/admin/index/index.html');
        }
        return $this->fetch();
    }

    //验证码
    public function verify()
    {
        $config = [
            'fontSize'    =>    20, // 验证码字体大小
            'length'      =>    4, // 验证码位数
            'useNoise'    =>    false, // 关闭验证码杂点
        ];
        $captcha = new Captcha($config);
        // 开启验证码背景图片功能 随机使用扩展包内`think-captcha/assets/bgs`目录下面的图片
        //$captcha->useImgBg = true; 
        $captcha->codeSet = '0123456789'; //指定验证码
        return $captcha->entry();
    }

    public function adminlogin()
    {
        if (Request::isAjax()) {
            (new AdminValidate())->scene('login')->goCheck();
            $result =  AdminModer::loginfind();
            return $result;
        } else { 
            return json(403, 400);
        }
    }

    public function loginout(){
        Session::clear();
        $this->redirect('http://www.market.com/admin/admin/login.html');
    }
}
