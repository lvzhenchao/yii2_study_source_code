# 行为的目的：因为php是单继承 【mixins】

- 继承：感觉更像物种的进化，物种的扩展，生成新的物种，这些新的物种又具有不同的特性。
- 行为：想要实现某种功能的时候需要借助别的工具；想用笔记本画画，然后买一个触控板，接上就可以。
前提是笔记本要有接口，behavior就相当于这个预留的接口

## 区别
- 继承：要实现新的属性功能就必须生成一个新的对象
- 行为：不必对现有类进行修改、yii可以绑定多个行为，从而达到类似多继承的效果

## 补充：
- Yii行为我认为使用的是装饰模式，动态的给一个对象添加一些额外的职责；就增加功能而言，装饰模式比生成子类更为灵活


# 内置的5个行为主要是对AR模型属性的增强

# 行为是一个类，注入到yii的组件并未其增加功能；两种角色：
- 行为类拥有一些功能，可以注入到组件类
- 组件类 增强功能的类

# 组件为何能直接使用行为的属性
## 一个魔术方法__get 当访问不存在或者不能访问的成员变量时对象会自动调用__get()方法
`

    通过这个方法，yii2的Component类访问到了关联行为的属性
    public function __get($name) {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            // read property, e.g. getName()
            return $this->$getter();
        }
    
        // behavior property
        $this->ensureBehaviors();
        foreach ($this->_behaviors as $behavior) {
            if ($behavior->canGetProperty($name)) {
                return $behavior->$name;
            }
        }
        ...
    }
`

# 行为的方法是如何注入到组件类中
## __call 魔术方法  当发现一个类的方法未定义时会触发此函数
- public mixed __call ( string $name , array $arguments )
## call_user_func_array 调用回调函数，并把一个数组参数作为回调函数的参数

# Behavior 行为类有一个叫做 events函数用来返回所有相关事件
`
public function events(){
		return [
			ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
		];
}
`

# Yii2行为和Trait；行为在“注入”属性和方法方面类似于trait，很多方面却不相同，各有利弊。更像互补的而不是相互替代
## 行为的优势
- 行为像普通类支持继承。trait可以视为PHP语言支持的复制粘贴功能，不支持继承
- 行为无须修改组件类就可以动态附加到组件或移除。使用trait，必须修改使用的它的类
- 行为是可配置的而trait不能
- 行为以响应事件来自定义组件的代码执行
- 不同行为附加到同一组件产生命名冲突时，冲突通过先附加行为的优先权自动解决。trait需要手动修改重命名解决
## Trait的优势
- trait更高效，因为行为是对象，消耗时间和内存
- IDE对trait更友好，因为是语言结构
- 行为只能服务于组件类，而trait没有这个限制












































