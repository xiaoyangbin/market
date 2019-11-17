<?php
namespace app\admin\controller;
use app\admin\controller\Base;

class User extends Base{
    public function login()
    {
        return $this->fetch();
    }
 
}
