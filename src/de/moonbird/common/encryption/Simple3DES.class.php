<?php
/**
@class Simple3DES
@author Sascha Meyer, Moonbird IT
@date 2011-09-28

Encode and decode values in a .NET compatible way, 
compatible VB.NET code can be found here: http://pastebin.com/gFmCKXqh
*/
class Simple3DES {
	private $key = '';
	private $iv = 'password';
	private $cipher= FALSE;
	
	public function __construct ($key, $iv= 'password') {
		$this->key= $key;
		$this->cipher= mcrypt_module_open(MCRYPT_3DES, '', 'cbc', '');
		$this->iv= $iv;
	}

	/**
	*
	* Encrypt a string
	* @param String
	* @return String
	*/
	public function encrypt($buffer) {
	  $extra = 8 - (strlen($buffer) % 8);
	  // add required padding with zeroes
	  if($extra > 0) {
		for($i = 0; $i < $extra; $i++) {
		  $buffer .= "\0";
		}
	  }
	  mcrypt_generic_init($this->cipher, $this->key, $this->iv);
	  $result = bin2hex(mcrypt_generic($this->cipher, $buffer));
	  mcrypt_generic_deinit($this->cipher);
	  return $result;
	}

	/**
	*
	* Decrypt a string
	* @param String
	* @return String
	*/
	public function decrypt($buffer) {
	  mcrypt_generic_init($this->cipher, $this->key, $this->iv);
	  $result = rtrim(mdecrypt_generic($this->cipher, $this->hex2bin($buffer)), "\0");
	  mcrypt_generic_deinit($this->cipher);
	  return $result;
	}

	private function hex2bin($data)
	{
	  $len = strlen($data);
	  return pack("H" . $len, $data);
	} 
}

?>