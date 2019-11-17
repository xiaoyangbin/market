<?php
namespace app\index\model;
use app\index\model\BaseModel;
use think\facade\Request;  //请求
use think\facade\Session;//静态Session
use think\model\concern\SoftDelete;//软删除
use app\index\validate\UserValidate;

//用户表
class UserModel extends BaseModel
{
    use SoftDelete;
	protected $pk = 'id';//默认主键
	protected $table = 'market_user';//选择数据表
	protected $autoWriteTimestamp = true;
    protected $createTime = 'create_time';
    protected $deleteTime = 'delete_time';
    protected $dateFormat = 'Y-m-d H:i:s';
	protected $auto = [];
    protected $insert = ['create_time'];
    
      public function getUrlAttr($value, $data)
      {
         return prefixImgUrl($value, $data);
      }

    //新增或编辑
    public static function saves(){
        $data = Request::post();
        if(!isset($data['id'])){
            if(Session::pull('randomcode') !=  $data['emailyzm']){
                $Reply = ['data'=>'验证码错误','code'=>'403'];
                return json($Reply);
            }
            (new UserValidate())->scene('register')->goCheck();
            $userone = self::where('username',$data['username'])->find();
            if($userone){
                $Reply = ['data'=>'账号已经存在','code'=>'403'];
                return json($Reply);
            }else{
                $usertwo = self::where('email',$data['email'])->find();
                if($usertwo){
                    $Reply = ['data'=>'邮箱已经存在','code'=>'403'];
                    return json($Reply);
                }else{
                    $data['password'] = md5(md5($data['password']).$data['email']);
                    $result = self::create($data);
                    Session::set('username',$data['username']);
                    Session::set('uid',$result->id);
                    if($result){
                        $Reply = ['data'=>'注册成功！','code'=>'201'];
                        return json($Reply);
                    }else{
                        $Reply = ['data'=>'内部错误，注册失败','code'=>'403'];
                         return json($Reply);
                    }
                }
            }
        }else{
            (new UserValidate())->scene('edit')->goCheck();
            $result = self::where('id', $data['id'])->update($data);
            if($result){
                $Reply = ['data'=>'编辑成功！','code'=>'202'];
                return json($Reply);
            }else{
                $Reply = ['data'=>'编辑失败！','code'=>'400'];
                return json($Reply);
            }
        }
    }

    public static function LoginValidate(){
        (new UserValidate())->scene('login')->goCheck();
        $data = Request::post();
        $user = self::where('email',$data['email'])->find();
        if($user){
            if($user['password'] == md5(md5($data['password']).$data['email'])){
                Session::set('username',$user['username']);
                Session::set('uid',$user['id']);
                $Reply = ['data'=>'登陆成功','code'=>'200'];
                return json($Reply);
            }
            $Reply = ['data'=>'密码错误','code'=>'404'];
            return json($Reply);
        }else{
            $Reply = ['data'=>'该用户不存在','code'=>'404'];
            return json($Reply);
        }
    }
    
    public static function editpassword(){
        (new UserValidate())->scene('login')->goCheck();
        $data = Request::post();
        if(Session::pull('randomcode') !=  $data['emailyzm']){
            $Reply = ['data'=>'验证码错误','code'=>'403'];
            return json($Reply);
        }
        $data['password'] = md5(md5($data['password']).$data['email']);
        $result = self::where('email', $data['email'])->update(['password' => $data['password']]);
        if($result){
            $Reply = ['data'=>'验证码错误','code'=>'202'];
            return json($Reply);
        }
        $Reply = ['data'=>'修改失败或跟以前的密码相同','code'=>'403'];
        return json($Reply);
    }

    public static function Headportrait(){
        $url = Request::post('url');
        $user = self::where('username',Session::get('username'))->find();
        unlink(substr($user['url'],22));
        $result = self::where('username',Session::get('username'))->update(['url'=> $url]); 
        if($result){
            $Reply = ['data'=>'头像修改成功！','code'=>'201','url'=> config('img_prefix').$url];
            return json($Reply);
        }
            $Reply = ['data'=>'头像修改失败！','code'=>'400'];
            return json($Reply);
    }

}

