<?php
namespace backend\service;

use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySendDetailsRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendBatchSmsRequest;
use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;
use Yii;

class AliYunSmsService {

    public $client;

    public function __construct() {
        $config = new Config([
            "accessKeyId"     => Yii::$app->params['alibabaCloudSms']['accessKeyId'],
            "accessKeySecret" => Yii::$app->params['alibabaCloudSms']['accessKeySecret']
        ]);
        $config->endpoint = Yii::$app->params['alibabaCloudSms']['endPoint'];
        $this->client = new Dysmsapi($config);
    }

    /**
     * @param string $signName      "阿里云短信测试" 短信签名名称【】
     * @param string $templateCode  "SMS_154950909" 短信模板
     * @param string $phoneNumbers  "15910371690" 接收短信的手机号码
     * @param string $templateParam "{'code':'150387'}" 短信模板变量对应的实际值 json格式
     *
     * return object
     * 错误码返回中心
     * https://error-center.aliyun.com/status/product/Dysmsapi?spm=a2c4g.11186623.0.0.633f309b7CAU2o
     *
     * 发送成功返回信息
     *  [bizId]   => 615323044889168223^0
     *  [code]    => OK
     *  [message] => OK
     *  [requestId] => F2DD642E-CC8A-5AB6-AB20-D838C4246E58
     *
     *  code：     请求状态码，返回OK代表请求成功
     *  message：  状态码的描述
     *  bizId：    发送回执ID,可根据发送回执ID在接口QuerySendDetails中查询具体的发送状态；【这个需要存库】
     *  requestId：请求ID
     */
    public function sendSms($signName ='', $templateCode ='', $phoneNumbers ='', $templateParam =''){
        if (empty($signName) || empty($templateCode) || empty($phoneNumbers) || empty($templateParam)) {
            return "参数缺失";
        }

        if (is_array($templateParam)) {
            $templateParam = json_encode($templateParam);
        }

        $sendSmsRequest = new SendSmsRequest([
            "signName"      => $signName,
            "templateCode"  => $templateCode,
            "phoneNumbers"  => $phoneNumbers,
            "templateParam" => $templateParam
        ]);

        return $this->client->sendSms($sendSmsRequest);
    }


    /**
     * @param string $phoneNumber  接收短信的手机号码；
     * @param string $bizId        发送回执ID；
     * @param string $sendDate     短信发送日期；短信发送日期，支持查询最近30天的记录。格式为【yyyyMMdd】，例如20181225
     * @param int $pageSize        分页查看发送记录；分页查看发送记录，指定每页显示的短信记录数量。取值范围为1~50
     * @param int $currentPage     当前页码
     * @return object \AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySendDetailsResponse|string
     *
     * 发送成功返回信息
     *  [code]    => OK
     *  [message] => OK
     *  [requestId] => F2DD642E-CC8A-5AB6-AB20-D838C4246E58
     *  [TotalCount] => 1
     *  [SmsSendDetailDTOs] => object
     *
     *  code：     请求状态码，返回OK代表请求成功
     *  message：  状态码的描述
     *  requestId：请求ID
     *  TotalCount：短信发送总条数
     *  SmsSendDetailDTOs：短信发送明细
     */
    public function getSendSmsDetails($phoneNumber ='', $bizId = '', $sendDate = '', $pageSize = 10, $currentPage = 1){
        if (empty($phoneNumber) || empty($bizId)) {
            return "参数缺失";
        }

        if (empty($sendDate)) {
            $sendDate = date("Ymd");
        }

        if ($pageSize > 50) {
            return "分页最大不能超过50";
        }

        $querySendDetailsRequest = new QuerySendDetailsRequest([
            "phoneNumber" => $phoneNumber,
            "bizId"       => $bizId,
            "sendDate"    => $sendDate,
            "pageSize"    => $pageSize,
            "currentPage" => $currentPage
        ]);

        return $this->client->querySendDetails($querySendDetailsRequest);
    }



    public function sendBatchSms($signName='', $templateCode='', $phoneNumbers='', $templateParam=''){
        if (empty($signName) || empty($templateCode) || empty($phoneNumbers) || empty($templateParam)) {
            return "参数缺失";
        }

        if (is_array($templateParam)) {
            $templateParam = json_encode($templateParam);
        }

        if (is_array($phoneNumbers)) {
            $templateParam = json_encode($phoneNumbers);
        }

        $sendBatchSmsRequest = new SendBatchSmsRequest([
            "signName"      => $signName,
            "templateCode"  => $templateCode,
            "phoneNumbers"  => $phoneNumbers,
            "templateParam" => $templateParam
        ]);
        return $this->client->sendBatchSms($sendBatchSmsRequest);
    }
}
