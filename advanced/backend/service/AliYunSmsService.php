<?php
namespace backend\service;

use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendBatchSmsRequest;
use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;
use Yii;

class AliYunSmsService {

    public static function createClient($accessKeyId, $accessKeySecret){
        $config = new Config([
            "accessKeyId" => $accessKeyId,
            "accessKeySecret" => $accessKeySecret
        ]);
        $config->endpoint = Yii::$app->params['alibabaCloudSms']['endPoint'];
        return new Dysmsapi($config);
    }


    public static function sendSms($args=''){
        $accessKeyId = Yii::$app->params['alibabaCloudSms']['accessKeyId'];
        $accessKeySecret = Yii::$app->params['alibabaCloudSms']['accessKeySecret'];
        $client = self::createClient($accessKeyId, $accessKeySecret);
        $sendSmsRequest = new SendSmsRequest([
            "signName" => "阿里云短信测试",
            "templateCode" => "SMS_154950909",
            "phoneNumbers" => "15910371690",
            "templateParam" => "{'code':'150387'}"
        ]);
        $res = $client->sendSms($sendSmsRequest);
        prd($res);
    }


    public static function sendBatchSms($args){
        $accessKeyId = Yii::$app->params['alibabaCloudSms']['accessKeyId'];
        $accessKeySecret = Yii::$app->params['alibabaCloudSms']['accessKeySecret'];
        $client = self::createClient($accessKeyId, $accessKeySecret);
        $sendBatchSmsRequest = new SendBatchSmsRequest([
            "phoneNumberJson" => ['15910371690','15910371690'],
            "signNameJson" => "批量发送阿里云短信测试",
            "templateCode" => "SMS_154950909",
            "templateParamJson" => "{'code':'150387'}"
        ]);
        $res = $client->sendBatchSms($sendBatchSmsRequest);
        prd($res);
    }
}
