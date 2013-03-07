<?php

                                
                $address='127.0.0.1';
				
                $port=8079;
				
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
                        socket_listen($create,$backlog=10);  
						//listen for connection on socket
						
   do{
		if(($accept=socket_accept($create))===false) break;
		//accept connection
      
         if (($input = socket_read($accept, 2048, PHP_NORMAL_READ))===false) 
		        {
                             break ;
                } 
	    //reading request headers
						   
        if (!$input === trim($input)) {
            continue;
        }
		
		$request=substr($input, 5, strpos($input, 'HTTP')-5);
		//extracting filename from header
		
		$request=urldecode($request);
		//decoding if url contains any special characters
		
		//checking if page exists on server
		if(file_exists($request))
		{
		
	    echo "error:".socket_strerror(socket_last_error())."\n";
		
		//header("HTTP/1.0 404 Not Found");
		
		$response="HTTP/1.1 200 OK\r\nDate: ".date('D, d M y H:i:s O')."\r\nServer: samurai/1.0.0.2\r\nCache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0\r\nPragma: no-cache\r\nContent-Length: 53\r\nKeep-Alive: timeout=5, max=100\r\nConnection: Keep-Alive\r\nContent-Type: text/html\r\n\n";
		//standard http headers
		
		var_dump(headers_list());
		//dumps sent headers
		
        $file = file_get_contents($request, true);
		//get contents of requested page
		
		$message=$response.$file;
		
        socket_write($accept, $message, strlen($message));
		}

       //if file not exist		
		else {
		
		$response="HTTP/1.1 404 Not Found\r\nDate: ".date('D, d M y H:i:s O')."\r\nServer: samurai/1.0.0.2\r\nCache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0\r\nPragma: no-cache\r\nContent-Length: 53\r\nKeep-Alive: timeout=5, max=100\r\nContent-Type: text/html\r\n\n";
		
		$message="<h1>404 Not Found</h1><br><h4>Samurai/1.0.0.1<br>".date('D, d M y H:i:s O')."</h4>";
		
		$message=$response.$message;
		
		socket_write($accept, $message, strlen($message));
		
		}

        while(1);
		
	    socket_close($accept);

  
socket_close($create);

?>