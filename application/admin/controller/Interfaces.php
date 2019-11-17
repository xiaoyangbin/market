<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\facade\Request;//请求

class Interfaces extends Base{

    //QQ登陆
    public function QQlogin(){
        $qc = new \kuange\QC();
        return redirect($qc->qq_login());
    }

    //QQ回调
    public function QQCallback(){
        $data = Request::param();
        halt($data);
    }

    //微信登陆
    public function WeiXinlogin(){
        return '微信登陆';
    }

    //百度地图
    public function BaiDuditu(){
        return $this->fetch();
    }

    //地图回调
    public function ditucallback(){
        $indirizzo = Request::post("dizhi");
        $soloadds = \Map::getzhengadds($indirizzo); //（地址解析\正地理编码）
        $aa = json_decode($soloadds);//把字符串转成对象
        $date['name'] = $indirizzo;
        $date['lng'] = $aa->result->location->lng;
        $date['lat'] = $aa->result->location->lat;
        $date['precise'] = $aa->result->precise;//是否精确查找。1为精确查找，即准确打点；0为不精确，即模糊打点。
        $date['confidence'] = $aa->result->confidence; //描述打点绝对精度（即坐标点的误差范围）。
        $date['comprehension'] = $aa->result->comprehension;//描述地址理解程度。分值范围0-100，分值越大，服务对地址理解程度越高
        $date['level'] = $aa->result->level;//level
        // $indietroadds = \Map::getniadds($date['lng'],$date['lat']); //逆地理编码
        // $data2 = \Map::adds($date['lng'],$date['lat']); //返回静态图片
        return json($date,200);
    }

    //github登陆
    public function GuiHublogin(){
        return 'github登陆';
    }

    //天气预报
    public function weather(){
        return $this->fetch();
    }
    

    
    //get请求
    function CurlGet($url)
    {
        return $this->CurlPost($url, "");
    }

    //curl 的post请求
    function CurlPost($url, $data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        curl_setopt($curl, CURLOPT_URL, $url);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}
