<?php
                                
                $address='127.0.0.1';
                $port=8079;
				
                $create=socket_create(AF_INET,SOCK_STREAM,SOL_TCP); 
				    //create socket
                  
               $bind=socket_bind($create,$address,$port);
			       //bind a name to created socket
                        socket_listen($create,$backlog=0);  
						//listen for connection on socket
   do{
		if(($accept=socket_accept($create))===false) break;
		//header("HTTP/1.0 404 Not Found");
		//accept connection
		$response="HTTP/1.1 200 OK \r\nDate: ".date('D, d M y H:i:s O')."\r\nServer: Samurai/1.0.0.1 \r\nAccept-Ranges: bytes \r\nConnection: close \r\nContent-Type: text/html; charset=UTF-8\r\n\n";
		//standard http headers
        socket_write($accept, $response, strlen($response));
		var_dump(headers_list());
		if(headers_sent()===true) echo "true";
        $message="\n<h2><center>Welcome on Samurai 1.0.0.1</center></h2>";
        socket_write($accept, $message, strlen($message));
	    socket_close($accept);
  
 }

  while(1);
socket_close($create);
?>