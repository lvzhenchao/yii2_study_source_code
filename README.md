# 目录结构
- backend 传统后端应用目录
- frontend 传统前端应用目录
- common 公共目录，前后端的公共部分；config/公共配置目录
- console 控制命令台目录
- environments 环境配置参数管理目录
- vagrant 虚拟化开发环境

# 文件结构
- .bowerrc：类似composer的npm包管理
- codeception.yml：配置文件，测试框架的配置文件
- requirements.php 程序运行时必要的检查,本框架运行时所必须要的文件

`

    backend
        assets/
            AppAsset.php 前端资源的控制管理文件              
        config/          配置文件目录    
        controllers/         
        models/              
        runtime/             
        tests/                  
        views/               
        web/       web服务器访问的静态资源，对外访问的  
            index.php 
            index-test.php 模拟线上环境       
             
`

# 运行流程[https://box.kancloud.cn/2015-10-10_561892e98e9a7.png]
- 入口文件：传统的index.php文件【index-test.php】; 命令行的入口文件【根目录的yii命令：php yii help】

`

    //实例化后会生成一个应用主体
    (new yii\web\Application($config))->run();

    public function __construct($config = [])
    {
        Yii::$app = $this;
        static::setInstance($this);
    
        $this->state = self::STATE_BEGIN;
    
        $this->preInit($config); //配置几个高级别的应用主体属性;例如：basePath
    
        $this->registerErrorHandler($config);// 错误处理方法
    
        Component::__construct($config);
        
    }

`
- 应用组件：加载main.php配置里面的组件；  使用\Yii::$app->组件名，来获取相关组件的相关信息

`

    main.php里面的配置属性基本都在 yii\web\Application 和 yii\base\Application 里面

`

- 过滤器：动作【方法】执行之前或之后，执行的的动作；本质上是一类特殊的 行为

# 应用主体生命周期总结
- 入口文件加载应用主体配置数组
- 入口脚本创建一个应用主体实例
- yii\web\Application的__construct方法会：$this->preInit($config)；$this->registerErrorHandler($config);
- yii\web\Application调用init()方法初始化
- 入口脚本调用run()方法运行应用主体
- 触发const EVENT_BEFORE_REQUEST = 'beforeRequest';事件
- 处理请求，解析路由和参数；创建路由指定的模块、控制器和动作对应的类，并运行动作
- 触发const EVENT_AFTER_REQUEST = 'afterRequest';事件
- 发送响应到终端用户
- 入口脚本接收应用主体传来的退出状态并完成请求的处理

# gii配置['class' => 'yii\gii\Module']
- 添加允许访问的IP
`
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '*.*.*.*'],
    ];
`

# 配置及环境
`

    配置项的形式：数组形式
    [
        'class' => 'ClassName', //指定了将要创建的对象的完全限定类名
        'propertyName' => 'peopertyValue', //对象属性的初始值，键值对
        'on eventName' => $eventHandler, //附加到对象事件上的句柄
        'as behaviorName' => $behaviorConfig, //附加到对象的行为
    ]
    

`
## 配置
- 应用主体配置
`
main.php里面的配置属性基本都在 yii\web\Application 和 yii\base\Application 里面；包括组件
四个配置项，优先级别，由低到高, 下面的覆盖前面的
    main.php读取配置项
    $params = array_merge(
        require __DIR__ . '/../../common/config/params.php',
        require __DIR__ . '/../../common/config/params-local.php',
        require __DIR__ . '/params.php',
        require __DIR__ . '/params-local.php'
    );
`

- 组件配置

## 环境
- prod：生产环境 YII_ENV_PROD 为true
- dev： 开发环境 YII_ENV_DEV  为true
- test：测试环境 YII_ENV_TEST 为true

# 控制器
- 默认控制器：yii\base\Application::defaultRoute属性指定的默认控制器

`

    应用配置中修改这个就行
    [
        'defaultRoute' => 'main',
    ]
    'defaultRoute' => 'test/index1',//如果后面跟着方法，就不会走默认控制器里面的方法
    
    尽可能修改main-local.php的内容
    
    默认的操作方法在\yii\base\Controller：public $defaultAction = 'index';
    在当前控制器里面重写这个属性配置就行
`
- 控制器动作【方法】
## 动作分类
- 内联动作：类似这样的actionIndex

- 独立动作：通过继承yii\base\Action或它的子类来定义;需要通过控制器中覆盖yii\base\Controller::actions()方法在action map中申明

`

    独立动作访问优先级高于内联动作

    public function actions()
    {
        两种写法
        return [
            // 用类来申明"error" 操作
            'error' => 'yii\web\ErrorAction',
    
            // 用配置数组申明 "view" 操作
            'view' => [
                'class' => 'yii\web\ViewAction',
                'viewPrefix' => '',
            ],
        ];
    }

`
# 视图
- 渲染：控制器渲染、视图渲染等；render()、renderPartial()常用；页面视图中的this->render和控制器this->render的this意义不一样
- 强制定义视图渲染目录及文件：'viewPath'=> '@backend/template',
- 获取数据：推送和拉取

`

    推送数据
    echo $this->render('report', [
        'foo' => 1,
        'bar' => 2,
    ]);
    
    拉取：前提是控制器需自己预先先定义好
    <?= $this->context->id ?>
    <?= $this->context->title ?>
    <?= $this->context->title ?>

`

# 视图-主题
- 主题的视图优先级高于本身的主题映射

`
'components' => [
        'view' => [
            'theme' => [
                'basePath' => '@backend/template/duanwu',//主题资源css、js、images
                'baseUrl' => '@backend/template/duanwu',
                'pathMap' => [//映射基准替换规则，必须配置
                    '@backend/template' => '@backend/template/duanwu',
                ],
            ],
        ],
    ],
`
- 主题继承

`

    第一个将被优先使用
    越往上优先级越高，没有才会找下面的
    '@backend/template' => [
        '@backend/template/chunjie',
        '@backend/template/duanwu',
    ],

`
- 静态资源的管理

`

    例如，只想IE9或更高的浏览器包含一个CSS文件，可以使用如下选项：
    public $cssOptions = ['condition' => 'lte IE9'];
    <!--[if lte IE9]>
    <link rel="stylesheet" href="path/to/foo.css">
    <![endif]-->
    
    使JavaScript文件包含在页面head区域（JavaScript文件默认包含在body的结束处）使用以下选项：
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

`

# 模型
- 属性
- 属性标签：通过getAttributeLabel()方法获取标签；或者重写public function attributeLabels()这个方法，自定义标签名
- 验证规则和场景写法




















