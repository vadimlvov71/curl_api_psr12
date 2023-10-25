<?php

namespace App\Classes;

class Curl
{
    private $dataToString = "";

    public function __construct(array $data, string $hash)
    {
        foreach ($data as $key => $value) {
            $this->dataToString .= $key . "=" . $value . "&";
        }
        $this->dataToString .= "hash=" . $hash;
    }

    public function requestCurl(string $url)
    {
        $result = [];
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->dataToString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         // Required for HTTP error codes
        //curl_setopt($ch, CURLOPT_FAILONERROR, true);
        /*if (curl_errno($ch)) {
            return curl_error($ch);
        }*/
        $info = curl_getinfo($ch);
        if (curl_errno($ch) || substr($info['http_code'], 0, 1) !== '2') {
            //TO DO  $info should be passed to interface
        }
        $output = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        return $output;
    }
}
