<?php
namespace backend\service;

use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySendDetailsRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySendStatisticsRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySmsSignListRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySmsTemplateListRequest;
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
     * 发送短信
     *
     * 必填项参数
     * @param string $data['signName']      "阿里云短信测试" 短信签名名称【】
     * @param string $data['templateCode']  "SMS_154950909" 短信模板
     * @param string $data['phoneNumbers']  "15910371690" 接收短信的手机号码
     *
     * 可选项参数
     * @param string $data['templateParam']    {"code":"1111"} 短信模板变量对应的实际值 json格式
     * @param string $data['smsUpExtendCode']  上行短信扩展码
     * @param string $data['OutId'] 外部流水扩展字段
     *
     * return object
     * 错误码返回中心
     * https://error-center.aliyun.com/status/product/Dysmsapi?spm=a2c4g.11186623.0.0.633f309b7CAU2o
     *
     * 发送成功返回信息
     *  {
     *       "RequestId": "10A2CE55-81B0-50EA-958D-1F197AFE2B4F",
     *       "Message": "OK",
     *       "BizId": "161307344998121201^0",
     *       "Code": "OK"
     *   }
     *
     *  Code：     请求状态码，返回OK代表请求成功
     *  Message：  状态码的描述
     *  BizId：    发送回执ID,可根据发送回执ID在接口QuerySendDetails中查询具体的发送状态；【这个需要存库】
     *  RequestId：请求ID
     */
    public function sendSms($data){
        if (!isset($data['signName']) || !isset($data['templateCode']) || !isset($data['phoneNumbers'])) {
            return "参数缺失";
        }
        if (empty($data['signName']) || empty($data['templateCode']) || empty($data['phoneNumbers'])) {
            return "参数不能为空";
        }

        if (isset($data['templateParam']) && is_array($data['templateParam'])) {
            $data['templateParam'] = json_encode($data['templateParam']);
        } else if (!isset($data['templateParam'])) {
            $data['templateParam'] = '';
        }

        $sendSmsRequest = new SendSmsRequest([
            "signName"      => $data['signName'],
            "templateCode"  => $data['templateCode'],
            "phoneNumbers"  => $data['phoneNumbers'],
            "templateParam" => $data['templateParam'],
        ]);

        return $this->client->sendSms($sendSmsRequest);
    }


    /**
     * 查看短信发送详情
     *
     * 必填项参数
     * @param string $data['phoneNumber']  接收短信的手机号码；
     * @param string $data['sendDate']     短信发送日期；短信发送日期，支持查询最近30天的记录。格式为【yyyyMMdd】，例如20181225
     * @param int $pageSize                分页查看发送记录；分页查看发送记录，指定每页显示的短信记录数量。取值范围为1~50
     * @param int $currentPage             当前页码
     *
     * 可选项参数
     * @param string $data['bizId']        发送回执ID；即发送流水号。调用发送接口SendSms或SendBatchSms发送短信时，返回值中的BizId字段
     *
     * 发送成功返回信息
     *  {
     *       "TotalCount": 1,                                                  短信发送总条数
     *       "Message": "OK",                                                  状态码的描述。
     *       "RequestId": "08440472-E581-5726-9CC8-B85E1828AABB",              请求ID。
     *       "Code": "OK",                                                     请求状态码。
     *       "SmsSendDetailDTOs": {
     *           "SmsSendDetailDTO": [
     *              {
     *                   "ReceiveDate": "2022-02-16 15:55:28",                 短信接收日期和时间
     *                   "PhoneNum": "15910371690",                            接收短信的手机号码
     *                   "Content": "【阿里云短信测试】您正在...",                短信内容
     *                   "SendStatus": 3,                                      短信发送状态，包括：1：等待回执2：发送失败。3：发送成功
     *                   "SendDate": "2022-02-16 15:55:21",                    短信发送日期和时间
     *                   "ErrCode": "DELIVERED"                                运营商短信状态码。短信发送成功：DELIVERED
     *               }
     *           ]
     *       }
     *   }
     *
     *  code：     请求状态码，返回OK代表请求成功
     *  message：  状态码的描述
     *  requestId：请求ID
     *  TotalCount：短信发送总条数
     *  SmsSendDetailDTOs：短信发送明细
     */
    public function getSendSmsDetails($data, $pageSize = 10, $currentPage = 1){

        if (!isset($data['phoneNumber']) || !isset($data['sendDate'])) {
            return "参数缺失";
        }

        if (empty($data['phoneNumber']) || empty($data['sendDate'])) {
            return "参数不能为空";
        }


        if ($pageSize > 50) {
            return "数量不能超过50";
        }

        $querySendDetailsRequest = new QuerySendDetailsRequest([
            "phoneNumber" => $data['phoneNumber'],
            "bizId"       => isset($data['bizId']) ? $data['bizId'] : '',
            "sendDate"    => $data['sendDate'],
            "pageSize"    => $pageSize,
            "currentPage" => $currentPage
        ]);

        return $this->client->querySendDetails($querySendDetailsRequest);
    }

    /**
     * 查询短信发送量详情
     * 
     * 必填项参数
     * @param int    $data['IsGlobe']    短信发送范围。取值1：国内短信发送记录2：国际/港澳台短信发送记录
     * @param string $data['StartDate']  开始日期，格式为yyyyMMdd，例如20181225
     * @param string $data['EndDate']    结束日期，格式为yyyyMMdd，例如20181225
     * @param int $pageIndex             页码。默认取值为1。
     * @param int $pageSize              页数。取值范围：1~50
     *
     *
     * 发送成功返回信息
     *  {
     *       "RequestId": "825FCE92-F22B-5BD4-9709-29C8F4A55301",   请求ID
     *       "Data": {                                              返回数据:
     *          "TargetList": [                                     返回数据列表
     *                   {
     *                   "TotalCount": 7,                           发送成功的短信条数
     *                   "NoRespondedCount": 0,                     未收到回执的短信条数
     *                   "SendDate": "20220214",                    短信发送日期，格式为yyyyMMdd，例如20181225
     *                   "RespondedFailCount": 0,                   接收到回执失败的短信条数
     *                   "RespondedSuccessCount": 7                 接收到回执成功的短信条数
     *               }
     *           ],
     *           "TotalSize": 1                                     返回数据的总条数
     *       },
     *       "Code": "OK"
     *   }
     *
     */
    public function getRpt($data, $pageIndex = 1, $pageSize = 10)
    {
        if (!isset($data['isGlobe']) || !isset($data['startDate']) || !isset($data['endDate'])) {
            return "参数缺失";
        }
        if (empty($data['isGlobe']) || empty($data['startDate']) || empty($data['endDate'])) {
            return "参数不能为空";
        }

        if ($pageSize > 50) {
            return "数量不能超过50";
        }

        $querySendStatisticsRequest = new QuerySendStatisticsRequest([
            "isGlobe"    => $data['isGlobe'],
            "startDate"  => $data['startDate'],
            "endDate"    => $data['endDate'],
            "pageIndex"  => $pageIndex,
            "pageSize"   => $pageSize
        ]);

        return $this->client->querySendStatistics($querySendStatisticsRequest);
    }

    /**
     * 获取短信模板列表
     * @param int $pageIndex
     * @param int $pageSize
     *
     * 发送成功返回信息
     * {
     *   "RequestId": "7A14C181-3BF9-5152-B77B-51EF977757F7",                             请求ID
     *   "SmsTemplateList": [                                                             请求状态码 返回OK代表请求成功
     *           {
     *               "TemplateCode": "SMS_224135731",                                     短信模板CODE
     *               "AuditStatus": "AUDIT_STATE_NOT_PASS",                               模板审批状态 AUDIT_STATE_INIT：审核中 AUDIT_STATE_PASS：审核通过。AUDIT_STATE_NOT_PASS：审核未通过 AUDIT_STATE_CANCEL：取消审核
     *               "TemplateContent": "验证码：${code}",                                 模板内容
     *               "TemplateName": "验证码",                                             模板名称
     *               "TemplateType": 0,                                                   模板类型。取值：0：短信通知。1：推广短信。2：验证码短信。6：国际/港澳台短信。7：数字短信
     *               "OrderId": "147659047",                                              工单ID
     *               "CreateDate": "2021-09-13 22:12:12",                                 短信模板的创建时间，格式为yyyy-MM-dd HH:mm:ss
     *               "Reason":                                                            审核备注
     *                   {
     *                      "RejectInfo": "建议先申请相关签名再申请模板，申请时请提供业务指引，
     *                             如APP、链接等；\n模板类型选择错误，请申请“验证码模板”；",
     *                      "RejectSubInfo": "",
     *                      "RejectDate": "2021-09-13 22:32:00"
     *                   }
     *       }
     *   ],
     *   "Code": "OK"
     *   "Message": "OK"                                                                  状态码的描述
     * }
     *
     */
    public function getSmsTemplateList($pageIndex = 1, $pageSize = 50){
        if ($pageSize > 50) {
            return "数量不能超过50";
        }
        $querySmsTemplateListRequest = new QuerySmsTemplateListRequest([
            "pageIndex" => $pageIndex,
            "pageSize"  => $pageSize
        ]);

        return $this->client->querySmsTemplateList($querySmsTemplateListRequest);
    }

    /**
     * 获取短信签名列表
     * @param int $pageIndex
     * @param int $pageSize
     *
     * 发送成功返回信息
     * {
     *   "RequestId": "7A14C181-3BF9-5152-B77B-51EF977757F7",                             请求ID
     *   "SmsSignList": [                                                                 请求状态码 返回OK代表请求成功
     *           {
     *               "SignName": "SMS_224135731",                                         签名名称
     *               "AuditStatus": "AUDIT_STATE_NOT_PASS",                               签名审批状态 AUDIT_STATE_INIT：审核中 AUDIT_STATE_PASS：审核通过。AUDIT_STATE_NOT_PASS：审核未通过 AUDIT_STATE_CANCEL：取消审核
     *               "CreateDate": "2020-01-08 16:44:13",                                 短信签名的创建日期和时间，格式为yyyy-MM-dd HH:mm:ss
     *               "BusinessType": "验证码类型",                                         签名场景类型
     *               "OrderId": "147659047",                                              工单ID
     *               "Reason":                                                            审核备注
     *                   {
     *                      "RejectInfo": "建议先申请相关签名再申请模板，申请时请提供业务指引， 审批未通过的备注信息
     *                             如APP、链接等；\n模板类型选择错误，请申请“验证码模板”；",
     *                      "RejectSubInfo": "",                                          审批未通过的备注信息。
     *                      "RejectDate": "2021-09-13 22:32:00"                           审批未通过的时间，格式为yyyy-MM-dd HH:mm:ss
     *                   }
     *       }
     *   ],
     *   "Code": "OK"
     *   "Message": "OK"                                                                  状态码的描述
     * }
     *
     */
    public function getSmsSignList($pageIndex = 1, $pageSize = 50){
        if ($pageSize > 50) {
            return "数量不能超过50";
        }
        $querySmsSignListRequest = new QuerySmsSignListRequest([
            "pageIndex" => $pageIndex,
            "pageSize"  => $pageSize
        ]);

        return $this->client->querySmsSignList($querySmsSignListRequest);
    }


    /**
     * 批量发送短信 : 未测
     *
     * 必填项参数
     * @param string $data['SignNameJson']      短信签名名称，JSON数组格式
     * @param string $data['templateCode']      "SMS_154950909" 短信模板
     * @param string $data['PhoneNumberJson']   接收短信的手机号码，JSON数组格式
     *
     * 可选项参数
     * @param string $data['TemplateParamJson']   短信模板变量对应的实际值，JSON格式 [{"name":"TemplateParamJson"},{"name":"TemplateParamJson"}]
     *
     */
    public function sendBatchSms($data){
        if (empty($data['signNameJson']) || empty($data['templateCode']) || empty($data['phoneNumberJson'])) {
            return "参数缺失";
        }

        if (is_array($data['phoneNumberJson'])) {
            $data['phoneNumberJson'] = json_encode($data['phoneNumberJson']);
        }

        if (isset($data['templateParamJson']) && is_array($data['templateParamJson'])) {
            $data['templateParamJson'] = json_encode($data['templateParamJson']);
        }


        $sendBatchSmsRequest = new SendBatchSmsRequest([
            "signName"      => $data['signNameJson'],
            "templateCode"  => $data['templateCode'],
            "phoneNumbers"  => $data['phoneNumberJson'],
            "templateParam" => isset($data['templateParamJson']) ? $data['templateParamJson'] : ''
        ]);
        return $this->client->sendBatchSms($sendBatchSmsRequest);
    }
}
