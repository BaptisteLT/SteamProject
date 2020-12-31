<?php
namespace App\Service;

class ApiItems
{
    public function getItems()
    {
        
        $curl = curl_init();

        $opts = [
            CURLOPT_URL => 'http://csgobackpack.net/api/GetItemsList/v2/',
            CURLOPT_RETURNTRANSFER => true,
        ];

        curl_setopt_array($curl, $opts);

        
        $response = json_decode(curl_exec($curl), true);
        
        curl_close($curl);

        return $response;
    }
}