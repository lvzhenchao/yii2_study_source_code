<?php

namespace backend\controllers;

use backend\service\AliYunSmsService;
use backend\service\DaHanSmsService;
use backend\service\MontnetsSmsService;
use Yii;
use yii\web\Controller;

class SmsController extends Controller
{

    //阿里获取短信模板
    public function actionAliTemplate()
    {
        $service = new AliYunSmsService();

        $res = $service->getSmsTemplateList();
        if ($res->body->code == 'OK') {
            prd($res->body->smsTemplateList);
        }

        //[templateCode] => SMS_224135731
        //[templateContent] => 验证码：${code}
        //[templateName] => 验证码
        //[templateType] => 0

        prd($res);

    }

    //阿里获取短信签名
    public function actionAliSign()
    {
        $service = new AliYunSmsService();

        $res = $service->getSmsSignList();
        if ($res->body->code == 'OK') {
            prd($res->body->smsSignList);
        }

        prd($res);

    }

    //阿里发送短信
    public function actionAliSendSms()
    {
        $service = new AliYunSmsService();

        $data['signName']      = "展曲";
        $data['templateCode']  = "SMS_183770238";
        $data['phoneNumbers']  = "15910371690";
        $data['templateParam'] = '{"code":"8888"}';
        $res = $service->sendSms($data);
        if ($res->body->message == 'OK' && $res->body->code == 'OK') {
            prd($res->body);
        }

        prd($res);

    }

    //阿里群发送短信
    public function actionAliSendSmsBatch()
    {
        $service = new AliYunSmsService();

        $data['signNameJson']      = ["展曲","展曲"];
        $data['templateCode']      = "SMS_183770238";
        $data['phoneNumberJson']   = ["15910371690","15313975639"];
        $data['templateParamJson'] = ['{"code":"666"}','{"code":"666"}'];
        $res = $service->sendBatchSms($data);
        if ($res->body->message == 'OK' && $res->body->code == 'OK') {
            prd($res->body);
        }

        prd($res);

    }

    //阿里发送短信查看发送详情
    public function actionAliSendSmsDetail()
    {
        $service = new AliYunSmsService();
        $data['phoneNumber'] = '15910371690';
        $data['bizId']       = '161307344998121201^0';
        $data['sendDate']    = '20220216';
        $res = $service->getSendSmsDetails($data);
        if ($res->body->message == 'OK' && $res->body->code == 'OK') {
            prd($res->body);
        }
        prd($res);


    }

    //阿里发送短信发送统计信息
    public function actionAliSendSmsStatistics()
    {
        $service = new AliYunSmsService();
        $data['isGlobe']    = 1;
        $data['startDate']  = '20220213';
        $data['endDate']    = '20220216';
        $res = $service->getRpt($data);
        if ($res->body->code == 'OK') {
            prd($res->body);
        }
        prd($res);


    }

    //梦网发送短信
    public function actionMontnetsSendSms()
    {

        $data['userid']  = '';
        $data['pwd']     = '';
        $data['mobile']  = '15910371690';
        $data['content'] = '验证码：666，打死都不要告诉别人哦！';

        $service = new MontnetsSmsService($data['userid']);
        $res = $service->sendSms($data);

        if ($res['result'] != 0 && isset($res['desc'])) {
            $res['desc'] = iconv('GBK', 'UTF-8', urldecode($res['desc']));
        }
        prd($res);
    }

    //梦网批量发送短信
    public function actionMontnetsSendSmsBatch()
    {

        $data['userid']  = '';
        $data['pwd']     = '';

        //手机号码不能超过最大支持数量（1000）
        $data['mobile']  = '15910371690,15313975639';
        $data['content'] = '验证码：6666，打死都不要告诉别人哦！';

        $service = new MontnetsSmsService($data['userid']);
        $res = $service->sendBatchSms($data);

        if ($res['result'] != 0 && isset($res['desc'])) {
            $res['desc'] = iconv('GBK', 'UTF-8', urldecode($res['desc']));
        }
        prd($res);

    }

    //梦网发送短信查看发送日志
    public function actionMontnetsSendSmsDetail()
    {

        $data['userid']  = '';
        $data['pwd']     = '';

        $service = new MontnetsSmsService($data['userid']);
        $res = $service->getRpt($data);

        if ($res['result'] != 0 && isset($res['desc'])) {
            $res['desc'] = iconv('GBK', 'UTF-8', urldecode($res['desc']));
        }

        prd($res);
    }

    //梦网发送短信查看余额
    public function actionMontnetsBalance()
    {

        $data['userid']  = '';
        $data['pwd']     = '';

        $service = new MontnetsSmsService($data['userid']);
        $res = $service->getBalance($data);
        pr($res);
        if ($res['result'] === 0) {
            if ($res['chargetype'] === 0) {
                prd("查询成功，当前计费模式为条数计费,剩余条数为：" . $res['balance']);
            } else if ($res['chargetype'] === 1) {
                prd("查询成功，当前计费模式为金额计费,剩余金额为：" . $res['money']."元");
            } else {
                prd("未知的计费类型");
            }
        } else {
            prd("查询余额失败，错误码：" . $res['result']);
        }

    }

    //大汉三通发送短信
    public function actionDaHanSendSms()
    {

        $service = new DaHanSmsService();

        $data['phones']  = '15910371690';
        $data['content'] = '验证码：3333，打死都不要告诉别人哦！';
        $res = $service->sendSms($data);
        $res = json_decode($res, true);
        prd($res);
    }

    //大汉三通发送短信查看发送日志
    public function actionDaHanSendSmsDetail()
    {

        $service = new DaHanSmsService();

        $res = $service->getRpt();
        $res = json_decode($res, true);
        prd($res);
    }

    //大汉三通查看余额
    public function actionDaHanBalance()
    {

        $service = new DaHanSmsService();

        $res = $service->getBalance();
        $res = json_decode($res, true);
        pr($res);
        if ($res['result'] == 0) {
            if ($res['smsBalance']) {
                echo "短信产品：";
                prd($res['smsBalance']);
            } else if ($res['mmsBalance']) {
                echo "彩信产品：";
                prd($res['mmsBalance']);
            } else if ($res['walletBalance']) {
                echo "钱包：";
                prd($res['walletBalance']);
            }
        } else {
            prd("查询余额失败，错误码：" . $res['result']);
        }
    }
}
