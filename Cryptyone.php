<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/api/finance/cryptyone/credentials.php";

class Cryptyone
{
    private static $instances = [];
    private $credentials = [];
    private $parameters = [];

    private function __construct()
    {
        $this->credentials['CREATE_TOKEN_URL'] = API_URL . CREATE_TOKEN_PATH;
        $this->env();
    }

    public static function getInstance(): Cryptyone
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }
        return self::$instances[$cls];
    }

    private function env(): string
    {
        return $this->credentials['API_KEY'] = preg_match("/[a-z0-9]+\.develop888\.com/", $_SERVER['HTTP_HOST']) ? API_KEY_DEV : API_KEY_PROD;
    }

    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }


    public function getParameters(): string
    {
        return json_encode($this->parameters);
    }

    private function create_token_request(): string
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->credentials['CREATE_TOKEN_URL'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($this->parameters),
            CURLOPT_HTTPHEADER => array(
                "Authorization: {$this->credentials['API_KEY']}",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return $code == 200 ? $response : json_encode(["code" => $code, "message" => $response]);
    }

    public function create_token(): string
    {
        return $this->create_token_request();
    }
}
