<?php
/**
 * The template for displaying all pages, single posts and attachments
 *
 * This is a new template file that WordPress introduced in
 * version 4.3.
 *
 * @package OceanWP WordPress theme
 */
 
  echo 'entro al php del server';
 
  $x = 10;
  
  echo $x;
  
  //$variable = 100;
  
  
  
  $result = array('variable' => '100');
  echo json_encode($result);
  
 /* 
  if($_GET['x']){
	  
    echo "entro a leer la palabra $_POST['x']";	
	
  }
  */
  
  $y = 20;
  
  echo $y;

 function all_columnist() {
 
  echo 'estas dentro de la funcion en el servidor';
  
  $x = "ESTO ES UN TEXTO";
  
  echo $x;
  
  $y = 20;
  
  echo $y;
 
 }


?>
