<?php
	namespace BurkardBinary\Library\CURL\v1_0_0;

	class CURLFunctions {
				
		public static function MakeCURLCall($url){
			$curl_handle = curl_init();
			curl_setopt($curl_handle,CURLOPT_URL,$url);
			curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
			curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($curl_handle,CURLOPT_SSL_VERIFYPEER,false);
			$buffer = curl_exec($curl_handle);
			$info = curl_getinfo($curl_handle);
			curl_close($curl_handle);
			
			switch(true) {
				case strpos($info['content_type'],'application/json')!==false: {
					return json_decode($buffer,true);
				}
				case strpos($info['content_type'],'text/xml')!==false: {
					return simplexml_load_string($buffer);
				}
				default: {
					return $buffer;
				}
			}
		}
	}
	
?>