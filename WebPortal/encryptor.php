<?php

/**
 * Description of encryptor
 *
 * @author Pieter
 */

declare(strict_types=1);

class encryptor{

   private $secret;
   private $cipherMethod;
   private $separator;
   private $ivLength;
 
   public function __construct(
       string $secret = 'MyApplicationSecretKey',
       string $cipherMethod = 'AES-256-CBC',
       string $separator = '::'
   ) {
       $this->secret = $secret;
       $this->cipherMethod = $cipherMethod;
       $this->separator = $separator;
       $this->ivLength = openssl_cipher_iv_length($cipherMethod);
   }
 
   public function encrypt(string $data): string
   {
       $decodedKey = base64_decode($this->secret);
 
       $iv = base64_encode(openssl_random_pseudo_bytes($this->ivLength));
       $iv = substr($iv, 0, $this->ivLength);
 
       $encryptedData = openssl_encrypt($data, $this->cipherMethod, $decodedKey, 0, $iv);
 
       return base64_encode($encryptedData.$this->separator.$iv);
   }
 
   public function decrypt(string $data): string
   {
       $decodedKey = base64_decode($this->secret);
 
       list($encryptedData, $iv) = explode($this->separator, base64_decode($data), 2);
 
       $iv = substr($iv, 0, $this->ivLength);
 
       return openssl_decrypt($encryptedData, $this->cipherMethod, $decodedKey, 0, $iv);
   }
}
