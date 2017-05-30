<?php

  include_once '../includes/db_connect.php';
	$filetype = array('jpeg','jpg','png','gif','PNG','JPEG','JPG');
   	foreach ($_FILES as $key )
    {

          $name =time().$key['name'];

          $path='../img/'.$name;
          $file_ext =  pathinfo($name, PATHINFO_EXTENSION);
          if(in_array(strtolower($file_ext), $filetype))
          {
            if($key['name']<1000000)
            {

             @move_uploaded_file($key['tmp_name'],$path);
             header('Content-Type: application/json');
        	 print json_encode($path);

            }
           else
           {
               
	        header('HTTP/1.1 500 Internal Server Booboo');
	        header('Content-Type: application/json; charset=UTF-8');
	        die(json_encode(array('message' => 'FILE_SIZE_ERROR', 'code' => 1337)));
           }
        }
        else
        {
           
	        header('HTTP/1.1 500 Internal Server Booboo');
	        header('Content-Type: application/json; charset=UTF-8');
	        die(json_encode(array('message' => 'FILE_TYPE_ERROR', 'code' => 1337)));
        }// Its simple code.Its not with proper validation.
    }

?>