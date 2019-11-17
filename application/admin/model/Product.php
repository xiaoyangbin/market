<?php
namespace app\admin\model;
use think\model\concern\SoftDelete;//软删除
use app\admin\model\BaseModel;
use app\admin\validate\Productvalidate;//商品验证器
use app\admin\model\Productimg;//商品图片模型
use think\facade\Request; //请求

//商品管理表
class Product extends BaseModel
{
    protected $hidden = ['update_time','delete_time','production_date','descript','delete_time'];
	protected $pk = 'id';//默认主键
    protected $table = 'market_product';//选择数据表
    //软删除
    use SoftDelete;
	//开启自动时间戳
    protected $autoWriteTimestamp = true;
    protected $deleteTime = 'delete_time';
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
	protected $dateFormat = 'Y-m-d H:i:s';
	//自动完成设置
	protected $auto = [];
	// 仅新增时设置  
	protected $insert = ['create_time','update_time'];
    
    //关联商品图片表
    public function pimages()
	{
	    return $this->hasMany('Productimg', 'pimg', 'id');
    }

	public static function list(){
		$where = Request::post();
		// halt($where);
        if($where['price']){
			$a1 =  substr($where['price'],0,strpos($where['price'], '-'));
			$a2 =  trim(strrchr($where['price'], '-'),'-');
			$where['price'] = null;
		}
		if($where['name']){
			$pname = $where['name'];
			$where['name'] = null;
		}
        foreach ($where as $key=>$value)
        {
            if(empty($value) && !is_numeric($value)){
                unset($where[$key]);
            }
		}
		if(!empty($a1) && !empty($pname)){
			$datalist = self::with('pimages', ['status'=>1,'type'=>1])->where($where)->where('price','between',"$a1,$a2")->where('name','like','%'.$pname.'%')->paginate(5);
		}else if(!empty($a1)){
			$datalist = self::with('pimages', ['status'=>1,'type'=>1])->where($where)->where('price','between',"$a1,$a2")->paginate(5);
		}else if(!empty($pname)){
			$datalist = self::with('pimages', ['status'=>1,'type'=>1])->where($where)->where('name','like','%'.$pname.'%')->paginate(5);
			// halt($datalist);  
		}else{
			$datalist = self::with('pimages', ['status'=>1,'type'=>1])->where($where)->paginate(5); 
		}
		return $datalist;
	}

    public static function saves(){
        //获取POST请求
		$data = Request::post();
		//ID等于空的话代表添加分类
		if(!isset($data['id'])){
            (new Productvalidate())->scene('add')->goCheck();
			$result = self::create($data);
			if($result){
               for($i=0;$i<count($data['url']);$i++){
                   Productimg::proimg($result->id,$data['url'][$i],$i,1);
               }
               return json(201,201);
			}
			//创建失败返回500（内部错误）
			return json(500,200);
		}
		//ID不为空侧为编辑
		else{
			(new Productvalidate())->scene('edit')->goCheck();
			$url = $data['url'];
			unset($data['url']);
			$result = self::where('id', $data['id'])->update($data);
			//全部删除图片
			$row = Productimg::where('pimg',$data['id'])->delete();
			if($row){
				//图片入库
				for($i=0;$i<count($url);$i++){
					Productimg::proimg($data['id'],$url[$i],$i,1);
				}
				return json(201,201);
			}
			//更新失败或者没有更新返回400
			return json(400,200);
		}
	}
	
	//审核
	public static function trialproducts(){
		(new Productvalidate())->scene('trialproducts')->goCheck();
		$productid = Request::param();
		if($productid['audit_status']==0){
			$result = self::where('id',$productid['id'])->update(['audit_status' => 1]);
		}else{
			$result = self::where('id',$productid['id'])->update(['audit_status' => 0]);
		}
		if($result){
			return json(202,202);
		}
		return json(400,200);
	}

	//删除
	public static function deleteproducts(){
		(new Productvalidate())->scene('id')->goCheck();
		$productid = Request::post('id');
		$dbresult = self::destroy($productid);
		if($dbresult){
			 $pimg = Productimg::where('pimg',$productid)->select();
			 for($i=0;$i<count($pimg);$i++){
				if(unlink(substr($pimg[$i]['url'],22)) !=true){
					return json('图片删除失败');
				}
				if(Productimg::destroy($pimg[$i]['id']) !=true){
					return json('商品图片数据删除失败');
				}
			 }
			 return json(202,202);
		}
		return json(500,200);
	}


}

