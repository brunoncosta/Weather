<?php

Class Curl {

   public static function get( $data ){

      $ch = curl_init();

      if( !empty( $data['headers'] ) ) {
         curl_setopt($ch, CURLOPT_HTTPHEADER, $data['headers']);
      }
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_URL, $data['url']);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

      if( !empty( $data['post'] ) ) {
         curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $data['post']);
      }

      $result = curl_exec($ch);

      if (curl_errno($ch)) {
         print_r( curl_error($ch) );
      }

      curl_close($ch);

      return json_decode($result);

   }

}
