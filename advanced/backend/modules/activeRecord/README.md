
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