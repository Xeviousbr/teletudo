<?php  
return array(  
  'driver' => 'smtp',  
  'host' => 'gator2024.hostgator.com', //smtp.gmail.com
  'port' => 465, // 25,  
  'from' => array('address' => 'huahua@intonses.com.br', 'name' => 'Tele-Tudo'),  
  'encryption' => 'ssl',  
  'username' => 'inton634', // 'inton634_tele', //Utilize o usuário informado na criação da conta do HostGator  
  'password' => '4zk3xkV3K5', // Utilize a senha recebida por e-mail pelo HostGator  
  'sendmail' => '/usr/sbin/sendmail -bs',  
  'pretend' => false,  
); 