<?php

// Put your device token here (without spaces):
$deviceToken = '3d991d2f986f1156aea5d6203db3f4791a3400d2ac96af002349e5cc2b827f1c';

// Put your private key's passphrase here:
$passphrase = "123321";//'pushchat';

// Put your alert message here:
$message = '测试推送!';

////////////////////////////////////////////////////////////////////////////////

$ctx = stream_context_create();
stream_context_set_option($ctx, 'ssl', 'local_cert', 'outck.pem');
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

// Open a connection to the APNS server
$fp = stream_socket_client(
	'ssl://gateway.sandbox.push.apple.com:2195', $err,
	$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

if (!$fp)
	exit("Failed to connect: $err $errstr" . PHP_EOL);

echo 'Connected to APNS<br/>' . PHP_EOL;



// Create the payload body
$body['aps'] = array(
	// 'alert' => $message,//推动显示内容
	// 'alert' => array(
	// 	'loc-key' => 'DEMO_FORMAT',
 //        'loc-args' => array('a','b'),
	// ),
	'alert' => array(
		'title' => 'FGDDEF',
        'body' => 'dfasdfsf',
        'action-loc-key' => 'PLAY'//需要将设置通知的提醒模式
	),
	'sound' => 'sub.caf'//自动以推送声音，声音需要放到沙盒的Library/Sounds目录下。
	// 'category' => 'alert'//选择按钮推送后左滑出选择按钮。
	);

// Encode the payload as JSON
$payload = json_encode($body);

var_dump($payload);
echo '<br/>';
// Build the binary notification
$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

// Send it to the server
$result = fwrite($fp, $msg, strlen($msg));

if (!$result)
	echo 'Message not delivered' . PHP_EOL;
else
	echo 'Message successfully delivered' . PHP_EOL;

// Close the connection to the server
fclose($fp);
