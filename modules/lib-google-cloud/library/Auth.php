<?php
/**
 * Auth
 * @package lib-google-cloud
 * @version 0.0.1
 */

namespace LibGoogleCloud\Library;
use LibCurl\Library\Curl;
use LibJwt\Library\Jwt;

class Auth
{
    private static $last_error;

    private static function _getToken(object $cert, string $scope): ?object{
        $jwt = Jwt::encode([
            'scope' => $scope,
            'exp'   => strtotime('+1 hour'),
            'iss'   => $cert->client_email,
            'aud'   => 'https://oauth2.googleapis.com/token',
            'iat'   => time()
        ]);

        $result = Curl::fetch([
            'url'       => 'https://oauth2.googleapis.com/token',
            'method'    => 'POST',
            'headers'   => [],
            'body'      => [
                'grant_type'    => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'     => $jwt
            ]
        ]);

        if(isset($result->error)){
            self::$last_error = $result->error_description;
            return null;
        }

        return $result;
    }

    static function get(string $scopes=''): ?string{
        // find it on cache first
        $cache_name = 'g-cloud-' . md5($scopes);
        $cache_value= \Mim::$app->cache->get($cache_name);
        if($cache_value)
            return $cache_value;

        $cert_file = BASEPATH . '/etc/cert/lib-google-cloud.json';
        if(!is_file($cert_file))
            throw new \Exception('Service account key file not found on etc/cert');

        $cert = file_get_contents($cert_file);
        $cert = json_decode($cert);

        $token = self::_getToken($cert, $scopes);
        if(!$token)
            return null;

        $a_token = $token->access_token;
        $a_expr  = $token->expires_in;

        \Mim::$app->cache->add($cache_name, $a_token, $token->expires_in);

        return $a_token;
    }

    static function lastError(): ?string{
        return self::$last_error;
    }
}