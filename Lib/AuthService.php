<?php

namespace Weblab\Lib;

use Firebase\JWT\JWT;

class AuthService
{    
    
    /**
    * generate JWT token
    * 
    */
    public function generateToken($params)
    {                
        $role = !empty($params['role']) ? $params['role'] : '';

        $userName = !empty($params['email']) ? $params['email']:"";

        if ($userName) {
            $tokenId    = base64_encode(mcrypt_create_iv(32));
            $issuedAt   = time();
            $notBefore  = $issuedAt; //Adding 10 seconds
            $expire     = $notBefore + TOKEN_LIFE_TIME;
            $serverName = "weblab"; // Retrieve the server name from config file
            $data = [
                'iat'  => $issuedAt,         // Issued at: time when the token was generated
                'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
                'iss'  => $serverName,       // Issuer
                'nbf'  => $notBefore,        // Not before
                'exp'  => $expire,           // Expire
                'data' => [                  // Data related to the signer user
                    'userid'  => $userName, // userid from the users table
                    'role'    => $role // User name
                 ]
            ];

            // Get the secret key for signing the JWT from an environment variable
            $secretKey = base64_decode(APP_SECRET);
            $algorithm = JWT_ALGORITHM;

            // Sign the JWT with the secret key                               
            $jwt = JWT::encode(
                $data,
                $secretKey,
                $algorithm
            );                                       
            return array("jwt" => $jwt, "data" => $data);           
        } else {
            return false;
        }
    }
    

    /**
    * validate JWT token
    */
    public function validateToken($jwt)
    {                
        $secretKey = base64_decode(APP_SECRET);
        $algorithm = JWT_ALGORITHM;

        // Sign the JWT with the secret key                              
        try {
            $decoded = JWT::decode($jwt, $secretKey, array($algorithm));
            return $decoded;             
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            return false;
        } catch (\DomainException $e) {
            return false;
        } catch (\Firebase\JWT\ExpiredException $e) {
            return false;
        } catch (Exception $ex) {
            return false;
        }
    }
    

    /**
    * encrypt password
    */
    private function encodePassword($raw, $salt)
    {
        return hash('sha256', $salt . $raw); 
    }

    /**
    * validate incoming passwords
    */
    public function isPasswordValid($encoded, $raw, $salt)
    {
        return $encoded === $this->encodePassword($raw, $salt);
    }

        
    /**
    * generate random login password
    */
    public function generatePassword()
    {
       $string = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
       $string_shuffled = str_shuffle($string.time());
       $password = substr($string_shuffled, 1, 8);
       return $password;
    }


    /**
     * get JWT access token from Authirization header
     */
    public function getBearerToken($authHeader) {
        $delimiter = 'Bearer ';
        $authHeader  = trim($authHeader);

        if ( strpos($authHeader, $delimiter) !== false ) {
            $jwtToken = explode($delimiter, $authHeader);
            if ( !empty($jwtToken[1]) ) {
                return $jwtToken[1];
            }
            
        }

        return false;
    }


    /**
     * generate salt
     */
    public function generateSalt($length=64) {
        $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        $randString = "";
        for ($i = 0; $i < $length; $i++) {
            $randString .= $charset[mt_rand(0, strlen($charset) - 1)];
        }

        return $randString;
    }


}
