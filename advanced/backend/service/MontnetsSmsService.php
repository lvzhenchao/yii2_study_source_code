<?php
namespace backend\service;

use Yii;
use yii\base\Exception;

class MontnetsSmsService {

    private $BaseUrl;
    public $ERROR_310099=-310099;

    public function  __construct($account)
    {

        //JS、H1
        if (strpos($account, 'H1') === 0) {
            $this->BaseUrl = Yii::$app->params['montnetsCloudSms']['h_url'];
        } else if (strpos($account, 'JS') === 0) {
            $this->BaseUrl = Yii::$app->params['montnetsCloudSms']['j_url'];
        }

    }

    /**
     * 单条发送
     * @param $data
     *
     * 必填项参数
     * $data['userid']  账号
     * $data['pwd']     密码
     * $data['mobile']  手机号    短信接收的手机号：只能填一个手机号码
     * $data['content'] 发送内容  短信内容：最大支持350个字，一个字母或一个汉字都视为一个字。编码方法：urlencode（GBK明文）
     *
     * 可选项参数
     * $data['svrtype'] 业务类型
     * $data['exno']    设置扩展号
     * $data['custid']  用户自定义流水编号
     * $data['exdata']  自定义扩展数据
     * $data['apikey']  用户唯一标识：32位长度，由梦网提供，与userid及pwd一样用于鉴权，如提交参数中包含userid及pwd，则可以不用填写该参数
     *
     * 发送成功返回信息
     *  {
     *   "result" : "0",
     *   "msgid"  : "9223372036854775808",
     *   "custid" : "b3d0a2783d31b21b8573"
        }
     *
     *  result： 短信发送请求处理结果：0代表成功；非0代表失败。错误代码详见
     *  msgid：  平台流水号：非0代表64位整型，对应Java和C#的long，不可用int解析。result非0时，msgid为0
     *  custid： 用户自定义流水号：默认与请求报文中的custid保持一致，若请求报文中没有custid参数或值为空，则返回由梦网生成的代表本批短信的唯一编号，result非0时，custid为空
     *
     * @return bool|mixed|string|string[]|null
     * 错误码附录
     * http://developer.sms.monyun.cn:9979/developer/?htmlURL1=API&htmlURL2=APIone#/api/sms/appendix
     */
    public function sendSms($data)
    {
        try {
            if (empty($this->BaseUrl)) {
                $result['result'] = $this->ERROR_310099;
                $result['msg'] = "API请求地址错误";
                return $result;
            }

            if (empty($data['userid']) || empty($data['pwd']) || empty($data['mobile']) || empty($data['content'])) {
                $result['result'] = $this->ERROR_310099;
                $result['msg'] = "参数缺失";
                return $result;
            }

            $data['userid'] = strtoupper($data['userid']);
            $encrypt = $this->encrypt_pwd($data['userid'], $data['pwd']);

            if (!is_array($encrypt) || !isset($encrypt['pwd']) || !isset($encrypt['time'])) {
                $result['result'] = $this->ERROR_310099;
                $result['msg'] = "信息加密错误";
                return $result;
            }

            $data['pwd'] = $encrypt['pwd'];
            $data['timestamp'] = $encrypt['time'];
            $data['content']   = $this->encrypt_content($data['content']);

            $post_data = json_encode($data);
            $result    = $this->connection($this->BaseUrl.'single_send',$post_data);
            return $result;
        }catch (Exception $e) {
            $result['result'] = $this->ERROR_310099;
            $result['msg'] = $e->getMessage();
            return $result;
        }
    }

    /**
     * 相同内容群发
     * @param $data
     * $data['mobile'] = '13243757111,13243757112';// 设置手机号码 每个手机号之间用,隔开(必填)
     * 其他参数同上
     *
     *  发送成功返回信息
     *  {
     *   "result" : "0",
     *   "msgid"  : "9223372036854775808",
     *   "custid" : "b3d0a2783d31b21b8573"
     *  }
     *
     *  result： 短信发送请求处理结果：0代表成功；非0代表失败。错误代码详见
     *  msgid：  平台流水号：非0代表64位整型，对应Java和C#的long，不可用int解析。result非0时，msgid为0
     *  custid： 用户自定义流水号：默认与请求报文中的custid保持一致，若请求报文中没有custid参数或值为空，则返回由梦网生成的代表本批短信的唯一编号，result非0时，custid为空
     *
     * @return bool|mixed|string|string[]|null
     *
     */
    public function sendBatchSms($data)
    {
        try{
            if (empty($data['userid']) || empty($data['pwd']) || empty($data['mobile']) || empty($data['content'])) {
                $result['result'] = $this->ERROR_310099;
                $result['msg'] = "参数缺失";
                return $result;
            }

            if (count(explode(',', $data['mobile'])) > 1000) {
                $result['result'] = $this->ERROR_310099;
                $result['msg'] = "号码数量最多不能超过1000";
                return $result;
            }

            $data['userid'] = strtoupper($data['userid']);
            $encrypt = $this->encrypt_pwd($data['userid'],$data['pwd']);

            if (!is_array($encrypt) || !isset($encrypt['pwd']) || !isset($encrypt['time'])) {
                $result['result'] = $this->ERROR_310099;
                $result['msg'] = "信息加密错误";
                return $result;
            }

            $data['pwd'] = $encrypt['pwd'];
            $data['timestamp'] = $encrypt['time'];
            $data['content']   = $this->encrypt_content($data['content']);

            $post_data = json_encode($data);
            $result    = $this->connection($this->BaseUrl.'batch_send',$post_data);

            return $result;
        }catch (Exception $e) {
            $result['result'] = $this->ERROR_310099;
            $result['msg'] = $e->getMessage();
            return $result;
        }
    }

    /**
     * 获取状态报告
     * @param $data
     *
     * 必填项参数
     * $data['userid'] 账号
     * $data['pwd']    密码
     *
     * 可选项参数
     * $data['apikey']    用户唯一标识：32位长度，由梦网提供，与userid及pwd一样用于鉴权，如提交参数中包含userid及pwd，则可以不用填写该参数
     * $data['timestamp'] 时间戳：采用24小时制格式MMDDHHMMSS【date('mdHis')】，即月日时分秒，定长10位，月、日、时、分、秒每段不足2位时左补0，密码选择MD5加密方式时必填该参数，密码选择明文方式时则不用填写
     * $data['retsize']   每次请求想要获取的上行最大条数。最大200，超过200按200返回。小于等于0或不填时，系统返回默认条数，默认100条
     *
     * 发送成功返回
     * {
     *   "result":"0",
     *   "rpts":[{
     *       "msgid":9223372045854775808      平台流水号：对应下行请求返回结果中的msgid
     *       "custid":"b3d0a2783d31b21b8573"  用户自定义流水号：对应下行请求时填写的custid
     *       "pknum":1                        当前条数
     *       "pktotal":2                      总条数
     *       "mobile":"138xxxxxxxx"           手机号：号码规则详见 附录
     *       "spno":"1000457890006"
     *       "exno":"0006"                    下行时填写的exno
     *       "stime":"2016-08-04 17:38:55"    状态报告对应的下行发送时间：YYYY-MM-DD HH:MM:SS
     *       "rtime":"2016-08-04 17:38:59"    状态报告对应的下行发送时间：YYYY-MM-DD HH:MM:SS
     *       "status":0                       接收状态：0代表成功；非0代表失败
     *       "errcode":"DELIVRD"              状态报告错误代码
     *       "errdesc":"success"              状态报告错误代码的描述
     *       "exdata":"exdata0002"            下行时填写的exdata
     *      }]
     *   }
     *
     *  result	int	短信发送请求处理结果：0代表成功；非0代表失败。错误代码详见附录
     *  rpts	string	result非0时rpts为空
     * @return bool|mixed|string|string[]|null
     *
     */
    public function getRpt($data)
    {
        try{
            if (empty($data['userid']) || empty($data['pwd'])) {
                $result['result'] = $this->ERROR_310099;
                $result['msg'] = "参数缺失";
                return $result;
            }

            if (isset($data['retsize']) && $data['retsize'] > 200) {
                $result['result'] = $this->ERROR_310099;
                $result['msg'] = "请求上限不能超过200条";
                return $result;
            }

            $data['userid'] = strtoupper($data['userid']);
            $encrypt = $this->encrypt_pwd($data['userid'],$data['pwd']);

            if (!is_array($encrypt) || !isset($encrypt['pwd']) || !isset($encrypt['time'])) {
                $result['result'] = $this->ERROR_310099;
                $result['msg'] = "信息加密错误";
                return $result;
            }

            $data['pwd']        = $encrypt['pwd'];
            $data['timestamp' ] = $encrypt['time'];
            $post_data = json_encode($data);
            $result = $this->connection($this->BaseUrl.'get_rpt',$post_data);

            return $result;
        }catch (Exception $e) {
            $result['result'] = $this->ERROR_310099;
            $result['msg'] = $e->getMessage();
            return $result;
        }
    }

    /**
     * 查询余额
     *
     * 功能:获取短信
     *
     * 必填项参数
     * $data['userid'] 账号
     * $data['pwd']    密码
     *
     *
     * 发送成功返回
     *  {
     *     "result":"0",
     *     "chargetype":1,
     *     "balance":0,
     *     "money":"10.000000"
     *  }
     *
     *  result	      短信发送请求处理结果：0代表成功；非0代表失败。错误代码详见
     *  chargetype    计费类型：0代表条数计费，money默认为0； 1代表金额计费，balance默认为0
     *  balance       短信余额总条数： result非0时值为0，chargetype为1时值为0
     *  money         短信余额总金额： result非0时值为0，chargetype为0时值为0
     *
     * @return json
     *
     *
     */
    public function getBalance($data)
    {
        try{
            if (empty($data['userid']) || empty($data['pwd'])) {
                $result['result'] = $this->ERROR_310099;
                $result['msg'] = "参数缺失";
                return $result;
            }

            $data['userid'] = strtoupper($data['userid']);
            $encrypt = $this->encrypt_pwd($data['userid'],$data['pwd']);

            if (!is_array($encrypt) || !isset($encrypt['pwd']) || !isset($encrypt['time'])) {
                $result['result'] = $this->ERROR_310099;
                $result['msg'] = "信息加密错误";
                return $result;
            }

            $data['pwd']        = $encrypt['pwd'];
            $data['timestamp' ] = $encrypt['time'];
            $post_data = json_encode($data);
            $result = $this->connection($this->BaseUrl.'get_balance',$post_data);

            return $result;
        }catch (Exception $e) {
            $result['result'] = $this->ERROR_310099;
            $result['msg'] = $e->getMessage();
            return $result;
        }
    }


    /**
     * 密码加密
     * $userid：用户账号
     * $pwd：用户密码
     */
    public function encrypt_pwd($userid, $pwd)
    {
        try {
            $char = '00000000';//固定字符串
            $time = date('mdHis', time());//时间戳
            $pwd  = md5($userid . $char . $pwd . $time);//拼接字符串进行加密
            return ['pwd' => $pwd, 'time' => $time];
        } catch (Exception $e) {
            return $e->getMessage();  //输出捕获的异常消息
        }
    }

    /**
     * 短信内容加密
     * $content：短信内容
     */
    public function encrypt_content($content)
    {
        try {
            return urlencode(iconv('UTF-8', 'GBK', $content));//短信内容转化为GBK格式再进行urlencode格式加密
        }catch (\Exception $e) {
            return $e->getMessage();  //输出捕获的异常消息
        }
    }

    /**
     * 短连接请求方法
     * $url：请求地址
     * $post_data：请求数据
     */
    private function connection($url,$post_data)
    {
        try {
            $attributes = array('Accept:text/plain;charset=utf-8', 'Content-Type:application/json', 'charset=utf-8', 'Expect:', 'Connection: Close');//请求属性
            $ch = curl_init();//初始化一个会话
            /* 设置验证方式 */
            curl_setopt($ch, CURLOPT_HTTPHEADER, $attributes);//设置访问
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//设置返回结果为流
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);//设置请求超时时间
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);//设置响应超时时间
            /* 设置通信方式 */
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);//使用urlencode格式请求

            $result = curl_exec($ch);//获取返回结果集
            $result = preg_replace('/\"msgid":(\d{1,})./', '"msgid":"\\1",', $result);//正则表达式匹配所有msgid转化为字符串
            $result = json_decode($result, true);//将返回结果集json格式解析转化为数组格式
            if (curl_errno($ch) !== 0) //网络问题请求失败
            {
                $result['result'] = $this->ERROR_310099;
                curl_close($ch);//关闭请求会话
                return $result;
            } else {
                $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($statusCode != 200||!isset($result['result']))//域名问题请求失败或不存在返回结果
                {
                    $result='';//清空result集合
                    $result['result'] = $this->ERROR_310099;
                }
                curl_close($ch);//关闭请求会话
                return $result;
            }
        } catch (Exception $e) {
            print_r($e->getMessage());//输出捕获的异常消息
            $result['result'] = $this->ERROR_310099;//返回http请求错误代码
            return $result;
        }
    }




}
