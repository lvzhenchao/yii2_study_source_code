<?php
namespace backend\actions;

use yii\base\Action;

/**
 * Class ErrorAction
 *
 * 1、'components' => [
        "urlManager" => require (__DIR__ . '/router.php'),
        'errorHandler' => [
            'errorAction' => 'test/error',
        ],
    ]
 *
 * 捕获程序框架错误
 *
 * @package backend\actions
 */
class ErrorAction extends Action
{

    public function run()
    {
        $code = 404;
        $msg = 'Page Not Found';

        $error = \Yii::$app->errorHandler->exception;
        $err_msg = '没有错误';
        if ($error) {
            $code = $error->getCode();
            $msg  = $error->getMessage();
            $file = $error->getFile();
            $line = $error->getLine();

            $err_msg = $msg . " [file: {$file}][line: {$line}][err code:$code.][url:{$_SERVER['REQUEST_URI']}][post:".http_build_query($_POST)."]";
//            ApplogService::add(Yii::$app->id,$err_msg);//我这里存入数据库
        }
        prd($err_msg);
        return $err_msg;
    }
}