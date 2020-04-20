<?php

class Weather
{

   protected $configs;
   protected $url;
   protected $ip;

   protected $data = [
      "location" => "Lisbon",
      "units"    => "metric",
      "ip"       => ""
   ];

   protected $error = [
      "status" => false
   ];

   protected $result;

   public function __construct($configs)
   {
      $this->configs = $configs;
   }

   protected function error($errorMessage)
   {
      $this->error = [
         "status"  => true,
         "message" => $errorMessage
      ];

      return $this;
   }

   protected function check()
   {
      if( isset( $_GET ) )
      {
         foreach ( $_GET as $key => $value)
         {
            if( array_key_exists( $key, $this->data ) )
            {
               $this->data[$key] = $value;
            }
            if( empty( $this->data[$key] ) )
            {
               return $this->error( "API {$key} not set." );
            }
         }
      }
      return $this;
   }

   protected function kmph_to_mps($kmph)
   {
      return floor(0.277778 * $kmph);
   }

   protected function mps_to_kmph($mps)
   {
      return (3.6 * $mps);
   }

   protected function ip()
   {
      // if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
      //    $this->ip = $_SERVER['HTTP_CLIENT_IP'];
      // } else if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
      //    $this->ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      // } else {
      //    $this->ip = $_SERVER['REMOTE_ADDR'];
      // }

      //$this->ip = $_GET['ip'];

      if( empty( $_GET['ip'] ) )
      {
         return false;
      }

      $data = Curl::get([
         "url" => "https://www.brunoncosta.com/API/ip?ip={$this->ip}",
         "headers" => []
      ]);

      $this->data['location'] = $data->city;

      return $this;
   }

   public function set()
   {
      $this->check();
      $this->ip();
      return $this;
   }

   public function cardinal()
   {
      if( empty( $_GET['degree'] ) )
      {
         return $this->error("API degree not found.");
      }

      $cardinal = $this->configs['weather']['directions'][round($_GET['degree'] / 22.5)];
      $cardinal = $cardinal == 'N2' ? 'N' : $cardinal;

      if( empty( $cardinal ) )
      {
         $cardinal = "-";
      }

      $this->result = [
         "cardinal" => $cardinal
      ];

      return $this;
   }

   public function current($type = 'all')
   {
      $result = Curl::get([
         "url" => "{$this->configs['weather']['url']['current']}?appid={$this->configs['weather']['key']}&q={$this->data['location']}&units={$this->data['units']}",
         "headers" => []
      ]);

      $this->result["location"] = isset( $result->name ) ? $result->name : '';

      if($type == 'all' || $type == 'temperature')
      {
         $this->result["temperature"]     = isset( $result->main->temp ) ? $result->main->temp : '';
         $this->result["fells_like"]      = isset( $result->main->feels_like ) ? $result->main->feels_like : '';
         $this->result["min_temperature"] = isset( $result->main->temp_min ) ? $result->main->temp_min : '';
         $this->result["max_temperature"] = isset( $result->main->temp_max ) ? $result->main->temp_max : '';
      }

      if($type == 'all' || $type == 'humidity')
      {
         $this->result["humidity"] = isset( $result->main->humidity ) ? $result->main->humidity : '';
      }

      if($type == 'all' || $type == 'wind')
      {
         $this->result["wind_speed"]     = isset( $result->wind->speed ) ? $result->wind->speed : '';
         $this->result["wind_speed_km"]  = isset( $result->wind->speed ) ? $this->mps_to_kmph( $result->wind->speed ) : '';
         $this->result["wind_direction"] = isset( $result->wind->deg ) ? $result->wind->deg : '';
      }

      if($type == 'all' || $type == 'sky')
      {
         $this->result["sky"]             = isset( $result->weather[0]->main ) ? $result->weather[0]->main : '';
         $this->result["sky_description"] = isset( $result->weather[0]->description ) ? ucwords( $result->weather[0]->description ) : '';
         $this->result["clouds"]          = isset( $result->clouds->all ) ? $result->clouds->all : '';
      }

      return $this;
   }

   public function forecast()
   {
      $this->result = Curl::get([
         "url" => "{$this->configs['weather']['url']['forecast']}?appid={$this->configs['weather']['key']}&q={$this->data['location']}&units={$this->data['units']}",
         "headers" => []
      ]);

      return $this;
   }

   public function get()
   {
      if( empty( $this->result ) )
      {
         return $this->error( "API data not found." );
      }

      if( $this->error['status'] == true )
      {
         return $this->error;
      }

      return $this->result;
   }

}
