### rabbitmq 服务类


#### 直连交换机类型. note:同一交换机、不同队列 不同路由键
1.文件`创建`生产者:`php producer01.php`
2.文件`创建`消费者:`php consumer01.php`


1.文件`日志`生产者:`php producer02.php`
2.文件`日志`消费者:`php consumer02.php`


#### 扇形交换机类型. note:同一交换机、不同队列 扇形交换机 不需要路由键   扇型交换机投递消息的拷贝到所有绑定到它的队列
1.用户`1列表`生产者:`php producer03.php`
2.用户`1列表`消费者:`php consumer03.php`


1.用户`2列表`生产者:`php producer04.php`
2.用户`2列表`消费者:`php consumer04.php`


#### 主题交换机类型
1.系统日志`用户模块`生产者:`php producer05.php`
1.系统日志`支付模块`生产者:`php producer06.php`
2.系统日志消费者:`php consumer05.php`
