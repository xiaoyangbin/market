<?php
namespace app\admin\model;
use app\admin\model\BaseModel;
use think\facade\Request; //请求
use app\admin\validate\BannerValidate;

//商品图片管理表
class Productimg extends BaseModel
{
    protected $hidden = ['is_master','articleid','create_time'];
	protected $pk = 'id';//默认主键
    protected $table = 'market_product_img';//选择数据表
   
	//开启自动时间戳
    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_time';
	protected $dateFormat = 'Y-m-d H:i:s';
	//自动完成设置
	protected $auto = [];
	// 仅新增时设置  
	protected $insert = ['create_time'];
    
    //图片路径拼接
    public function getUrlAttr($value, $data)
    {
       return prefixImgUrl($value, $data);
    }

    //商品图片入库
    public static function proimg($pimg,$url,$order,$type){
        if(mb_strlen($url)==74){
            $url = substr($url,29);
        }
        $img = self::create([
            'pimg'  =>  $pimg,
            'url' =>  $url,
            'order'=>$order,
            'type'=>$type
        ]);
        if(!$img){
            return json('图片入库错误',200);
        }
    }

    //删除图片
    public static function deletepimage(){
        $pimgurl = Request::param('pimg');
        $pimgsum = mb_strlen($pimgurl);//字符串个数
        //长度位74的话截图前面的字段并且删除数据库的图片信息
        if($pimgsum==74){
            $pimgurl = substr($pimgurl,22);
            $url = substr($pimgurl,7);
            $row = self::where('url',$url)->delete();//数据库删除
            if(!$row){
                return json('图片数据库删除失败',200);
            }
        }else{
            //长度位45的话在前面填充路径
            $pimgurl = 'upload/'.$pimgurl;
        }
        $result = unlink($pimgurl);
        if($result){
           return json(200,200); 
        }
        return json(400,200);
    }
    
    //轮播图添加/编辑
    public static function sevasban(){
       $data = Request::post();
        if(!isset($data['id'])){
            (new BannerValidate())->scene('add')->goCheck();
            $result = self::proimg("0",$data['url'],$data['order'],2);
            return json(1,200);
        }else{
            (new BannerValidate())->scene('edit')->goCheck();
            if(mb_strlen($data['url'])==74){
                $data['url'] =  substr($data['url'],29);
                $result = self::update($data);
            }else{
                $result = self::create([
                    'id'=>$data['id'],
                    'pimg'  =>  0,
                    'url' =>$data['url'],
                    'order'=>$data['order'],
                    'type'=>2
                ]);
            }
            if($result){
                return json(1,200);
            }
            return json(500,200);
        }
    }

    //删除轮播图
    public static function deleteban(){
        (new BannerValidate())->scene('id')->goCheck();
        $banid = Request::post('id');
        $ban = self::where('id',$banid)->find();
        $delete = self::destroy($ban['id']);
        if($delete){
            if(unlink(substr($ban['url'],22)) !=true){
                return json('图片删除失败',200);
            }
            return json(201,200);
        }
		return json(500,200);
    }
}

