<?php

if(! function_exists ( "autoLoader" ))
{
   function autoLoader($className){

      $root = dirname(__FILE__);

      $sources = array(
         "{$root}/Class/{$className}.class.php"
      );

      foreach ($sources as $source) {
         if ( file_exists($source) ) {
            require_once $source;
         }
      }

   }
}

spl_autoload_register('autoLoader');
