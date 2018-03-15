<?php
/**
 * Created by PhpStorm.
 * User: asus-pc
 * Date: 2018/3/11
 * Time: 15:23
 */
namespace frontend\aliyun;

use yii\base\Component;
use frontend\aliyun\SignatureHelper;

class SmsHander extends Component{

    public  $ak;
    public  $sk;
    public  $sign;
    public  $tel;
    public  $templateCode;
    public  $templateParam;



    public function getTel($tel){
       $this->tel = $tel;
       return $this;
    }



    public function getTemplateParam($param){
        $this->templateParam = $param;
        return $this;
    }



    function sendSms() {

        $params = [
            "PhoneNumbers"=>$this->tel,
            "SignName"=>$this->sign,
            "TemplateCode"=>$this->templateCode,
            'TemplateParam'=>$this->templateParam,
        ];

        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }

        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new SignatureHelper();

        // 此处可能会抛出异常，注意catch
        $content = $helper->request(
            $this->ak,
            $this->sk,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendSms",
                "Version" => "2017-05-25",
            ))
        );
        return ($content->Message == 'OK' && $content->Code == 'OK');
    }

}