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

