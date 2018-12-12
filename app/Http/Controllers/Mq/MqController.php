<?php
/*
 * Created on 2018-7-9
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 namespace App\Http\Controllers\Mq;
 
use App\Http\Controllers\Controller;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class MqController extends Controller{
	
	public function index(){
	    $connection = new AMQPStreamConnection('localhost', 5672, 'root', 'root');  // 创建连接
	    $exchange = 'router'; // 交换器，在我理解，如果两个队列使用一个交换器就代表着两个队列是同步的，这个队列里存在的消息，在另一个队列里也会存在
    	$queue = 'php_test';  // 队列名称
    
		$channel = $connection->channel();
		$channel->queue_declare('php_test', false, true, false, false);
		
		$channel->exchange_declare($exchange, 'direct', false, true, false);
    	$channel->queue_bind($queue, $exchange); // 队列和交换器绑定
    
		$msg = new AMQPMessage('Hello World!');    // 要推送的消息
		$msg = new AMQPMessage($messageBody, array('content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
		$channel->basic_publish($msg, '', 'hello');
		echo "执行完成";
		$channel->close();
		$connection->close();
	}
}
?>
