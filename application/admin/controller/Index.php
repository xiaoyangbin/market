<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\App;
use think\Db;
use app\admin\model\Product;
use think\facade\Session;//静态Session

class Index extends Base{
    public function index()
    {  
        $productsh = Product::where('audit_status',0)->count();
        $this->assign('productsh',$productsh);
        $this->assign('time',date('Y-m-d h:i:s',time()));
        $this->assign('adminuser',Session::get('adminuser'));
        return $this->fetch();
    }

    public function welcome()
    {
        //服务器地址 操作系统 运行环境 PHP版本 PHP运行方式 MYSQL版本 ThinkPHP 上传附件限制 执行时间限制 剩余空间 
        $rs = Db::query('select VERSION() as sqlversion');
        $data = array(
            'bb'=>'1.0',
            'ip'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',//服务器地址
            'xitong' =>PHP_OS, //操作系统
            'yxhj'=>$_SERVER["SERVER_SOFTWARE"],//运行环境
            'phpyxfs'=>php_sapi_name(),//PHP运行方式
            'phpbb'=>PHP_VERSION,
            'mysqlbb'=> $rs[0]['sqlversion'],
            'tpbb'=>App::VERSION,//ThinkPHP版本
            'scfjxz'=>ini_get('upload_max_filesize'),//上传附件限制
            'zxsjxz'=>ini_get('max_execution_time').'秒',//执行时间限制
            'sykj'=>round((disk_free_space(".")/(1024*1024)),2).'M',// 剩余空间 
        ); 
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function youxiang(){
        $test = sendMail('940575530@qq.com','你好','你不好');
		halt($test);
    }

    
}
