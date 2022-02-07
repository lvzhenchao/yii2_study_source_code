# 参考网址
- YII2，FECSHOP 教程[http://www.fancyecommerce.com/]

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

`

    //临时验证：需要对某些没有绑定任何模型类的值进行 临时验证
    $email = 'test@example.com';
    $validator = new yii\validators\EmailValidator();
    
    if ($validator->validate($email, $error)) {
        echo '有效的 Email 地址。';
    } else {
        echo $error;
    }

`
# 翻译

`
    第一个参数指储存消息来源的类别名称，第二个参数指需要被翻译的消息
    \Yii::t('app', 'lzc');
    'i18n' => [
        'translations' => [
            //类别名称  //是yii::t里面的第一个参数名
            'app*' => [
                'class' => 'yii\i18n\PhpMessageSource',
                //翻译文件的存放位置
                'basePath' => '@backend/messages',
                //'sourceLanguage' => 'en-US',
                'fileMap' => [
                    'app' => 'app.php',
                    'app/error' => 'error.php',
                ],
            ],
        ],
    ],
`
# 新建模块示例gii
- backend\modules\shop\Module
- shop

# 路由
- 全拦截路由

`
    临时调整到维护模式，所有的请求下都会显示相同的信息页
    'catchAll' => ['site/offline'],    
`
- 美化URL

`

    nignx添加：
    location / {
        # Redirect everything that isnt a real file to index.php
        try_files $uri $uri/ /index.php?$args;
        
        或者下面的
        if (!-e $request_filename) {
            rewrite ^/backend/web/(.*) /backend/web/index.php last
        }
    }
    
    组件：
    'urlManager' => [
        'enablePrettyUrl' => true,
        'enableStrictParsing' => false,
        'showScriptName' => false,
        'rules' => [
            "<controller:\w+>/<action:\w+>/<id:\d+>" => "<controller>/<action>",
            "<controller:\w+>/<action:\w+>"          => "<controller>/<action>",
            
            单个这个也行
            '/'=>'/',
        ],
    ],

`
# session
- 自定义session
`
    //redis版
    'session' => [
        'class' => 'yii\redis\Session',
        'name' => $session_name,   //配置一个唯一的ID，防止和别的系统的session互串
        'keyPrefix'=>'user_session_',
        'cookieParams' => [
            'domain' => $domain,
            'lifetime' => 0,
            'httpOnly' => true,
            'path' => '/',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'r-2zek58zgsnfticd8g4pd.redis.rds.aliyuncs.com',
            'password' => 'Medcon8899',
            'port' => 6379,
            'database' => YII_ENV != 'prod' ? 16 : 1,
        ],
    ],
`

# cookie
- 发送cookie
`
    //发送 Cookies
    $cookies = Yii::$app->response->cookies;
    $cookies->add(new \yii\web\Cookie([
        'name' => 'language',
        'value' => 'zh-CN',
        'expire' => time()+7*24*3600//7天免登录
    ]));
`
- $request->cookies和$response->cookies的区别
`
前者是客户端的，后者是服务端的
$request->cookies负责读取
$response->cookies负责创建
`
# 组件：基本上都在yii\base\Application 里面定义了或者引用了
- 常用的组件

`

    yii\web\AssetManager: 管理资源包和资源发布，详情请参考 管理资源 一节。
    yii\db\Connection: 代表一个可以执行数据库操作的数据库连接， 注意配置该组件时必须指定组件类名和其他相关组件属性，如yii\db\Connection::dsn。 详情请参考 数据访问对象 一节。
    yii\base\Application::errorHandler: 处理 PHP 错误和异常， 详情请参考 错误处理 一节。
    yii\i18n\Formatter: 格式化输出显示给终端用户的数据，例如数字可能要带分隔符， 日期使用长格式。详情请参考 格式化输出数据一节。
    yii\i18n\I18N: 支持信息翻译和格式化。详情请参考 国际化 一节。
    yii\log\Dispatcher: 管理日志对象。详情请参考 日志 一节。
    yii\swiftmailer\Mailer: 支持生成邮件结构并发送，详情请参考 邮件 一节。
    yii\base\Application::response: 代表发送给用户的响应， 详情请参考 响应 一节。
    yii\base\Application::request: 代表从终端用户处接收到的请求， 详情请参考 请求 一节。
    yii\web\Session: 代表会话信息，仅在yii\web\Application 网页应用中可用， 详情请参考 Sessions (会话) and Cookies 一节。
    yii\web\UrlManager: 支持URL地址解析和创建， 详情请参考 URL 解析和生成 一节。
    yii\web\User: 代表认证登录用户信息，仅在yii\web\Application 网页应用中可用， 详情请参考 认证 一节。
    yii\web\View: 支持渲染视图，详情请参考 Views 一节。
    
    //应用组件的方式
    [
        'components' => [
            // 使用类名注册 "cache" 组件
            'cache' => 'yii\caching\ApcCache',
    
            // 使用配置数组注册 "db" 组件
            'db' => [
                'class' => 'yii\db\Connection',
                'dsn' => 'mysql:host=localhost;dbname=demo',
                'username' => 'root',
                'password' => '',
            ],
    
            // 使用函数注册"search" 组件
            'search' => function () {
                return new app\components\SolrService;
            },
        ],
    ]
`
- 更改错误处理组件

`
    'errorHandler' => [
        'errorAction' => 'test/index17'
    ],
    访问不存在方法试一下
`
- 自定义组件
`
1、自定义一个组件：需继承yii\base\Component;
2、main中引入组件
    'components' => [
        'sms' => [
            'class' => 'backend\components\sms',
        ],
    ]
3、调用
    //sms是在组件中的key，也叫\Yii::$app->componentID
    Yii::$app->sms->send('13800000000');

`
# 类自动加载
- 类映射表（Class Map）

`

    //配置中添加
    Yii::$classMap['Util'] = '@backend/third/Util.php';

`
# restful
- 控制器继承yii\rest\ActiveController;
- 配置url

`

    'urlManager' => [
        'enablePrettyUrl' => true,
        'enableStrictParsing' => false,
        'showScriptName' => false,
        'rules' => [

            '/'=>'/',
            //或
           ['class' => 'yii\rest\UrlRule', 'controller' => 'app\admin'],
        ],
    ],
`
- 注意默认存在的独立方法：可以去掉

`

    //1、rule里面取出
    rules = [
        'except' => ['index'],    
    ];
    
    //2、默认的方法都在独立方法里面
    public function actions()
    {
      $actions =  parent::actions(); 
      unset($actions['index']);
      return $actions;
    }
    
    //3、或者重构那个方法

`


`
yii\rest\ActiveController
    public function actions(){6个}
`
- yii的restful不能解析json格式的数据

`

    组件内添加：json解析器
    'request' => [
            'parsers' => [
                'application/josn' => 'yii\web\JsonParser',
            ]
        ],

`
- 返回格式问题 response;修改行为类底层，响应类

`

    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
//                    'application/xml' => Response::FORMAT_XML,
                ],
            ],

        ];
    }
`
# restful的查询方式
- 查询特定字段：fields; http://yii2_study.com/app/user?fields=id,admin
- 分页：page; http://yii2_study.com/app/user?fields=id,admin&page=1
- 排序：sort;

# restful的认证方式
`
    user组件内
    基本配置
    enableSession = false 或者在模块内的Module.php内设置 Yii::$app->user->enableSession = false;
    loginUrl = null 显示一个HTTP 403 错误而不是跳转到登录界
`
- HTTP Basic Auth[https://www.php.net/manual/zh/features.http-auth.php]

`
    
    可以用 header() 函数来向客户端浏览器发送“Authentication Required”信息，
    使其弹出一个用户名／密码输入窗口
    
    4个参数
    only
    except
    auth
    realm
`
- Query parameter
- OAuth 2

























