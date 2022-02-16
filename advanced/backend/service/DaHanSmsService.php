<?php
namespace backend\service;

use Yii;
use yii\base\Exception;

class DaHanSmsService {

    private $BaseUrl;
    private $Account;
    private $Password;
    public $ERROR_310099 = -310099;

    public function  __construct()
    {
        $this->BaseUrl  = Yii::$app->params['daHanCloudSms']['url'];
        $this->Account  = Yii::$app->params['daHanCloudSms']['account'];
        $password = Yii::$app->params['daHanCloudSms']['password'];
        $this->Password = $password ? md5($password) : '';

    }

    /**
     * 短信发送
     * 功能: 发送一条或者多条内容相同的短信
     * @param $data
     *
     * 必填项参数
     * $data['account']   用户账号
     * $data['password']  密码 密码，需采用MD5加密(32位小写)
     * $data['phones']    手机号 接收手机号码，多个手机号码用英文逗号分隔，最多1000个，
     * $data['content']   发送内容 短信内容，最多1000个汉字，内容中不要出现【】[]这两种方括号，该字符为签名专用
     *
     * 可选项参数
     * ....
     *
     * 发送成功返回信息
     *  {
     *   "msgid"  : "f02adaaa99c54ea58d626aac2f4ddfa8",
     *   "result" : "0",
     *   "desc"   : "提交成功",
     *   "failPhones" : "12935353535,110,130123123"
    }
     *
     *  desc	状态描述
     *  msgid	该批短信编号
     *  result	0代表成功 该批短信提交结果；说明请参照：http://help.dahantc.com/docs/oss/1apkg9ncgtaq9.html
     *  failPhones	如果提交的号码中含有错误（格式）号码将在此显示
     *  taskid	有长链接地址替换时返回该参数，短链接的任务编号
     *
     *  @return json
     */
    public function sendSms($data)
    {
        try {

            if (empty($this->BaseUrl) || empty($this->Account) || empty($this->Password)) {
                $result['result'] = $this->ERROR_310099;
                $result['msg'] = "账户信息缺失";
                return $result;
            }

            if (empty($data['phones']) || empty($data['content'])) {
                $result['result'] = $this->ERROR_310099;
                $result['msg'] = "必要参数缺失";
                return $result;
            }

            $data['account']  = $this->Account;
            $data['password'] = $this->Password;

            $post_data = json_encode($data);
            return $this->http_post_json($this->BaseUrl.'/Submit', $post_data,  '');

        }catch (Exception $e) {
            $result['result'] = $this->ERROR_310099;
            $result['msg'] = $e->getMessage();
            return $result;
        }
    }


    /**
     * 获取状态报告
     * 功能: 获取状态报告；建议有数据时无需休眠，当请求无数据返回时，客户端休眠30秒再进行请求。每次最多取200条状态报告
     * @param $data
     *
     * 必填项参数
     * $data['account']   用户账号
     * $data['password']  密码 密码，需采用MD5加密(32位小写)
     *
     *
     * 发送成功返回
     * {
     *   "result":"0",
     *   "desc":"成功",
     *   "reports":[
     *      {
     *       "msgid":"2c92825934837c4d0134837dcba00150",  短信编号
     *       "phone":"13534698345",                       下行手机号码
     *       "status":"0",                                短信发送结果：0——成功；1——接口处理失败；2——运营商网关失败
     *       "desc":"成功",                               状态报告描述
     *       "wgcode":"DELIVRD",                          当status为1时,表示平台返回错误码，参考:状态报告错误码。当status为0或2时，表示运营商网关返回的原始值
     *       "submitTime":"2015-03-17 16:32:16",          状态报告接收时间格式为yyyy-MM-dd HH:mm:ss
     *       "sendTime":"2015-03-17 16:32:17",            客户提交时间格式为yyyy-MM-dd HH:mm:ss
     *       "time":"2015-03-17 16:32:20",                大汉三通发送时间格式为yyyy-MM-dd HH:mm:ss
     *       "smsCount":1,                                长短信条数（接口处理失败只给一条）
     *       "smsIndex":1                                 长短信第几条标示
     *       },
     *      ]
     *   }
     *
     *  result	接口调用结果，说明请参照：提交响应错误码；当result为0时reports字段将出现0到1次，否则reports字段不出现
     *  desc    返回结果描述
     *  reports	信息详情
     *
     * @return json
     *
     */
    public function getRpt()
    {
        try{
            if (empty($this->BaseUrl) || empty($this->Account) || empty($this->Password)) {
                $result['result'] = $this->ERROR_310099;
                $result['msg'] = "账户信息缺失";
                return $result;
            }

            $data['account']  = $this->Account;
            $data['password'] = $this->Password;

            $post_data = json_encode($data);
            return $this->http_post_json($this->BaseUrl.'/Report', $post_data,  '');
        }catch (Exception $e) {
            $result['result'] = $this->ERROR_310099;
            $result['msg'] = $e->getMessage();
            return $result;
        }
    }

    /**
     * 查询余额
     *
     * 功能:获取短信余额
     *
     * 必填项参数
     * $data['account']   用户账号
     * $data['password']  密码 密码，需采用MD5加密(32位小写)
     *
     *
     * 发送成功返回
     *   {
     *       "result":"0",
     *       "desc":"成功",
     *       "smsBalance":{
     *           "amount":"9999999973",
     *           "number":"9999999973",
     *           "freeze":"16.0"
     *           },
     *       "mmsBalance":{
     *           "amount": "0.0",
     *           "number": "0",
     *           "freeze": "0.0"
     *           },
     *       "walletBalance":{
     *           "amount": "20000.0",
     *           "voiceFreeze": "0.0",
     *           "freeze": "0.0"
     *           }
     *   }
     *
     *  result	接口调用结果，说明请参照:提交响应错误码；如果result为0时，说明请求成功，当某个产品不存在时，返回结果中对应的字段为默认值0；
     *  desc    返回结果描述
     *  smsBalance 短信产品余额{amount：短信剩余金额，保留3位小数，单位：元；number：剩余短信条数；freeze：短信冻结金额，保留3位小数，单位：元。
     *  mmsBalance 彩信产品余额{amount：彩信剩余金额，保留3位小数，单位：元；number：剩余彩信条数；freeze：彩信冻结金额，保留3位小数，单位：元;如果账号配了超级彩信这个参数的值就是超级短信的余额。
     *  walletBalance 钱包余额{amount：钱包剩余金额，保留3位小数，单位：元；voiceFreeze：语音冻结金额，保留3位小数，单位：元；freeze：钱包冻结金额，保留3位小数，单位：元；国际短信和语音余额。
     *
     * @return json
     *
     *
     */
    public function getBalance()
    {
        try{
            if (empty($this->BaseUrl) || empty($this->Account) || empty($this->Password)) {
                $result['result'] = $this->ERROR_310099;
                $result['msg'] = "账户信息缺失";
                return $result;
            }

            $data['account']  = $this->Account;
            $data['password'] = $this->Password;

            $post_data = json_encode($data);
            return $this->http_post_json($this->BaseUrl.'/Balance', $post_data,  '');
        }catch (Exception $e) {
            $result['result'] = $this->ERROR_310099;
            $result['msg'] = $e->getMessage();
            return $result;
        }
    }


    /**
     * PHP发送Json对象数据, 发送HTTP请求
     *
     * @param string $url 请求地址
     * @param array $data 发送数据
     * @return String
     */
    public function http_post_json($url, $data, $functionName = '') {
        $ch = curl_init ( $url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_FRESH_CONNECT, 1 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_FORBID_REUSE, 1 );
        curl_setopt ( $ch, CURLOPT_TIMEOUT, 30 );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, array ('Content-Type: application/json; charset=utf-8', 'Content-Length: ' . strlen ( $data ) ) );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
        $ret = curl_exec ( $ch );

        if ($functionName) {
            echo $functionName . " : Request Info : url: " . $url . " ,send data: " . $data . "  \n";
            echo $functionName . " : Respnse Info : " . $ret . "  \n";
        }

        curl_close ( $ch );
        return $ret;
    }




}
