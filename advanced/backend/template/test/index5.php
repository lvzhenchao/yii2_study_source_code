<?php
//echo "<pre>";
//print_r($this->context);
//echo "index5";
//echo "<br>";

echo $this->render('common',['c' => '我是c']);

\Yii::$app->view->on(\yii\web\View::EVENT_END_BODY, function () {
    echo date('Y-m-d');
});