
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
# 模型名::find()&模型名::findBySql()的意义
- 返回yii\db\ActiveQuery 实例，该类继承自yii\db\Query

# yii\db\Query::batch() 和 yii\db\Query::each()
- 返回一个实现了`Iterator` 接口 yii\db\BatchQueryResult 的对象

# 查询需注意的地方
- one()查询也可以加上limit，针对可能不是一条结果的时候，框架不会加limit;查询结果只会显性的返回一条;
- 组装原生sql时，

# AR属性的意义
- model的属性：业务数据
- AR属性的含义：表中的所有列
- 访问属性

`
    
    //访问单个属性
    $model->username;//建议
    $model['username'];
    //访问所有属性
    $model->attributes;//获取属性 和 其值
    $model->attributes();//获取的是属性的集合不包括值
    //访问属性修改前但未保存提交【save】的数据;
    //就是直接从数据读出的原始数据；
    //如果是save完之后的，那就是新数据了
    pr($model->attributes());
    pr($model->attributes);
    pr($model->getOldAttribute('admin'));

`
- 属性标签认识
`
    pr($model->attributeLabels());//获取所有属性的标签
    pr($model->getAttributeLabel('address'));//获取某个属性的的标签
    pr($model->generateAttributeLabel('address11'));//生成某个属性的标签
`

