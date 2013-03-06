<?php

                $address='127.0.0.1';
				
                $port=8080;
				
                if(($create=socket_create(AF_INET,SOCK_STREAM,SOL_TCP))===false) 
				    //create socket
                  {
				  
                        echo "error:".socket_strerror(socket_last_error())."\n";
                  
				  }
               if(($bind=socket_bind($create,$address,$port))===false);  
			       //bind a name to created socket
                 {
				 
                        echo "error:".socket_strerror(socket_last_error())."\n";
						
                 }
                        socket_listen($create,$backlog=0);  
						//listen for connection on socket
   do{

		if(($accept=socket_accept($create))===false) break;
		//accept connection
   
		$response="HTTP/1.1 200 OK \n Date: ".date('D, d M y H:i:s O')."\nServer: Samurai/1.0.0.1 \nAccept-Ranges: bytes \nConnection: close \nContent-Type: text/html; charset=UTF-8\n";
		//standard http headers

		socket_write($accept, $response, strlen($response));
		
		$m = "supported commands are QUIT and HELP.\n";
		
		socket_write($accept, $m, strlen($m));
		
		do {
		 

    		 if (($input = socket_read($accept, 2048, PHP_NORMAL_READ))===false) {
                             break ;
                           }
						   
        if (!$input === trim($input)) {
            continue;
        }
		
        if ($input === 'QUIT') {
            break;
        }

		if($input === 'HELP'){
		
		     $help="QUIT to close existing connection.\n HELP to display this help message\n";
			 
			 socket_write($accept, $help, strlen($help));
		}
		
        $back = "You said $input.\n";
		
        socket_write($accept, $back, strlen($back));
		
        echo "$input\n"; //echo on server
		
    } 
	while (true);
   
   socket_close($accept);
  
 }
while(1);

socket_close($create);

?>