	<?php
 
 
	  if(isset($_POST['upload']))
	  {
 $folder = '/var/www/sergey/data/www/plastom.ujob.su/download/';

  	 
	    $newDoc = new DJEMDocument($djem);
$newDoc->_parent_id = 26064;
$newDoc->_name ='Пользователь-'.$userId.'-'. $_FILES['uploadFile']['name'];
$name=pathinfo($_FILES['uploadFile']['name']);
  $uploadedFile = $folder.$newDoc->Store().'.'.$name['extension'];
$newDoc->user_file='/download/'.$newDoc->Store().'.'.$name['extension'];
$newDoc->id_user=$userId;
$newDocId = $newDoc->Store();	 

	  


if(move_uploaded_file($_FILES['uploadFile']['tmp_name'],
   $uploadedFile))
{
  echo 'Файл загружен';
  require_once('/var/www/sergey/data/www/plastom.ujob.su/system/php/modules/sms-dispatch.php'); 
$api = new MainSMS ( 'alert' , 'fa401c791c3a3', false, false );
echo 'Текущий баланс: ' . $api->getBalance (), PHP_EOL ;           
echo 'отправка СМС/а смс: ' . $api->sendSMS ( '89268374593' , 'Вы успешно добавили прайс ' , 'metal100.ru' ), PHP_EOL ;
$response = $api->getResponse ();
$result = $api->checkStatus ( $response [ 'messages_id' ]);
foreach ( $result as $message_id => $status ) {
echo sprintf ( 'Статус сообщения %s: %s' , $message_id , $status ), PHP_EOL ;
}
echo 'запрос стоимости смс: ' . $api->messagePrice ( '89121231234,9121231235', 'api test' ), PHP_EOL ;
echo 'запрос информации о номерах: ' . $api->phoneInfo ( '89121231234,9121231235' ), PHP_EOL ;
}
else
{
   echo 'Во  время загрузки файла произошла ошибка';
}
 

  

  }

	?>