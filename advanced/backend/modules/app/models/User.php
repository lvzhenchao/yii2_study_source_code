<?php

namespace backend\modules\app\models;

use common\models\Address;
use Yii;
use yii\filters\RateLimitInterface;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id
 * @property string $admin
 * @property string $password
 * @property string $access_token
 * @property string $allowance
 * @property string $allowance_updated_at
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface,RateLimitInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['admin', 'password'], 'required'],
            [['admin'], 'string', 'max' => 20],
            [['password','access_token'], 'string', 'max' => 255],
            [['allowance', 'allowance_updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'admin' => 'Admin',
            'password' => 'Password',
            'access_token' => 'AccessToken',
            'allowance' => 'Allowance',
            'allowance_updated_at' => 'Allowance Updated At',
        ];
    }

    /**
     * 根据给到的ID查询身份。
     *
     * @param string|integer $id 被查询的ID
     * @return IdentityInterface|null 通过ID匹配到的身份对象
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * 根据 token 查询身份。
     *
     * @param string $token 被查询的 token
     * @return IdentityInterface|null 通过 token 得到的身份对象
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string 当前用户ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string 当前用户的（cookie）认证密钥
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }


    public $rateLimit = 10;
    // 返回在单位时间内允许的请求的最大数目，例如，[10, 60] 表示在60秒内最多请求10次。
    public function getratelimit($request, $action)
    {
        return [$this->rateLimit, 60];
    }

    // 返回剩余的允许的请求数。
    public function loadallowance($request, $action)
    {
        return [$this->allowance, $this->allowance_updated_at];
    }

    // 保存请求时的unix时间戳。
    public function saveallowance($request, $action, $allowance, $timestamp)
    {
        $this->allowance = (int)$allowance;
        $this->allowance_updated_at = (int)$timestamp;
        $this->save();
    }

    public function getAddress()
    {
        return $this->hasOne(Address::className(),['user_id' => 'id']);
    }

    //下面不是很推荐，因为fields里面存的是一些比较单纯的数据类型
//    public function fields()
//    {
//        $fields = parent::fields();
//
//        $fields['addr'] = 'address';//没有这个字段，所以会去找getAddress()
//
//        return $fields;
//    }


    //http://yii2_study.com/app/user/10?fields=id,admin&expand=address
    public function extraFields()
    {
        return [
            //第一种直接写
//            'address',

            //第二种
            'address' => function($model){
                return $model->address;
            }
        ];
    }






}
