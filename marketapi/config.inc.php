<?php
function my_autoload_func($class){
   $filename = __DIR__."/$class.class.php";
    if(file_exists($filename)){
       require_once $filename;
    }
}
spl_autoload_register('my_autoload_func');
?>