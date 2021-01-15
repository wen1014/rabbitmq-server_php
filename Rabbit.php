<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/Rbit.php';


use PhpAmqpLib\Connection\AMQPStreamConnection;
use \PhpAmqpLib\Message\AMQPMessage;

/**
 * 描述：rabbit 服务类
 * 类名称： Rabbit
 * 创建人： wenhao
 * 创建时间：2021/1/14 下午3:32
 */
class Rabbit implements Rbit
{

    //保存类实例的静态成员变量
    private static $instance = [];
    protected $connection;
    protected $channel;
    protected $queueName;
    protected $exchangeName;
    protected $queueConfig;
    protected $exchangeConfig;
    protected $exchange;
    protected $route;
    protected $config;

    private function __construct($msmq)
    {
        //所有配置
        $this->config = require_once __DIR__ . '/config.php';
        //交换机名字:
        $this->exchangeName = $this->config['msmq'][$msmq]['exchange_name'];
        //队列名字:
        $this->queueName = $this->config['msmq'][$msmq]['queue_name'];
        //路由键:
        $this->route = $this->config['msmq'][$msmq]['routing_key'];
        //交换机配置:
        $this->exchangeConfig = $this->config['exchange'][$this->exchangeName];
        //队列配置:
        $this->queueConfig = $this->config['queue'][$this->queueName];


        //其它配置信息
        $insist              = false;
        $login_method        = 'AMQPLAIN';
        $login_response      = null;
        $locale              = 'en_US';
        $connection_timeout  = 3.0;
        $read_write_timeout  = 3.0;
        $context             = null;
        $keepalive           = false;
        $heartbeat           = 60;
        $channel_rpc_timeout = 0.0;
        $ssl_protocol        = null;

        //创建连接
        $this->connection = new AMQPStreamConnection(
            $this->config['host'],
            $this->config['port'],
            $this->config['username'],
            $this->config['password'],
            $this->config['vhost'],
            $insist,
            $login_method,
            $login_response,
            $locale,
            $connection_timeout,
            $read_write_timeout,
            $context,
            $keepalive,
            $heartbeat,
            $channel_rpc_timeout,
            $ssl_protocol
        );

        //获取信道
        $this->channel  = $this->connection->channel();
        $exchangeConfig = $this->exchangeConfig;
        //在信道里创建交换器
        $this->channel->exchange_declare(
            $this->exchangeName,
            $exchangeConfig['type'],
            $exchangeConfig['passive'],
            $exchangeConfig['durable'],
            $exchangeConfig['auto_delete']
        );
    }

    public static function getInstance($msmq)
    {
        if (!isset(self::$instance[$msmq])) {
            self::$instance[$msmq] = new self($msmq);
        }
        return self::$instance[$msmq];
    }

    /**
     * 描述：生产者.
     * 函数名： send
     * 创建人：wenhao
     * 创建时间：2021/1/14 下午4:29
     * @param array $data
     * @param array $properties
     */
    public function send(array $data, string $routeName = '', $properties = [])
    {
        $queueConfig = $this->queueConfig;
        if ($queueConfig['durable']) {
            //开启消息持久化
            $properties['delivery_mode'] = AMQPMessage::DELIVERY_MODE_PERSISTENT;
        }
        if (is_array($data)) {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        $msg = new AMQPMessage($data, $properties);
        if ($this->exchangeConfig['type'] != 'topic') {
            //如果是不是主题交换机.
            $routeName = $this->route;
        }
        $this->channel->basic_publish(
            $msg,
            $this->exchangeName,
            $routeName
        );
    }

    /**
     * 描述：消费者
     * 函数名： receive
     * 创建人：wenhao
     * 创建时间：2021/1/14 下午4:29
     * @param Closure $closure
     * @param int $prefetchCount
     * @throws ErrorException
     */
    public function receive(\Closure $closure, $prefetchCount = 1)
    {
        //声明消费队列
        $queueConfig = $this->queueConfig;
        [$queue, ,] = $this->channel->queue_declare(
            $this->queueName,
            $queueConfig['passive'],
            $queueConfig['durable'],
            $queueConfig['exclusive'],
            $queueConfig['auto_delete']
        );
        if ($this->exchangeConfig['type'] == 'topic') {
            $routeNameArr = explode(',',$this->route);
            foreach ($routeNameArr as $routeName) {
                //绑定队列到交换机
                $this->channel->queue_bind(
                    $queue,
                    $this->exchangeName,
                    $routeName
                );
            }
        }else{
            //绑定队列到交换机
            $this->channel->queue_bind(
                $queue,
                $this->exchangeName,
                $this->route
            );
        }

        //消费消息
        $this->channel->basic_qos(null, $prefetchCount, null);

        //回调函数处理消息
        $this->channel->basic_consume($queue, '', false, false, false, false, function ($message) use ($closure) {
            if ($closure($message->body)) {
                $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
            }
        });

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    //__clone方法防止对象被复制克隆
    public function __clone()
    {
        trigger_error('Clone is not allow!', E_USER_ERROR);
    }
}