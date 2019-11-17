<?php
namespace app\admin\controller;
use think\Controller;
use think\facade\Session;//静态Session

class Base extends Controller{

    //初始化方法
	protected function initialize(){
        if(!Session::get('adminuser')){
            $this->redirect('http://www.market.com/admin/admin/login.html');
        }
    }
 
}

?>