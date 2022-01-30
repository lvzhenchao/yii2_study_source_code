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





















