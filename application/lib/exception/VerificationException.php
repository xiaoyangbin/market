<?php
namespace app\lib\exception;
use app\lib\exception\BaseException;

//验证报错类
class VerificationException extends BaseException
{
    public $code = 200;
    public $errorCode = '400';
    public $msg = "参数异常";
}