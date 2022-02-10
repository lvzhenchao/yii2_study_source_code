
# AR活动记录
## 数据表到面向对象类的映射；数据表映射成一个类，数据表的每一条记录就是数据表实例化后的对象
- 解决编写SQL语句的问题
- 提高编码速度
- 数据表更加场景化

# AR类
- 继承ActiveRecord类
- 至少要有三个方法：function tableName(数据表)、function rules(字段规则)、function attributeLabels(字段标签)

# AR是模型：AR最为数据库的管理工具
- Model是代表业务数据，规则【rules(字段规则)】和逻辑【方法】的对象

# AR连接数据库
- 公共方法，组件获取数据库信息
`
    //DB组件
    'components' => [
            'db' => [
                'class' => 'yii\db\Connection',
                'dsn' => 'mysql:host=localhost;dbname=yii2advanced',
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8',
            ],
    ]
    //公共底层方法
    public static function getDb()
    {
        return Yii::$app->getDb();
    }
    public function getDb()
    {
        return $this->get('db');
    }
    //如果有多个数据库，可以对这个方法重写，从而读取相应的数据信息
    public static function getDb()
    {
        return Yii::$app->db1;
        return Yii::$app->db2;
    } 
`