<?php

$client = new \GuzzleHttp\Client();


$res = $client->request(
    'POST',
    'https://addnow-backend-staging.i-ways-network.org/api/oauth2/token',
    [
        'headers' => ['APP-ID' => 'trnE6CjVkjQuUQOyujzrkjkTiMrWxSOaUhSqhaGQj6ikDk5XiNd03XPYZ7q5OqiUEbNWHAHx2kQ8djnKRqZoa0vdYGei2'],
        'form_params' => [
            'grant_type' => 'authorization_code',
            'code' => SdkRestApi::getParam('auth_code')
        ]
    ]

);

/** @return array */
return json_decode($res->getBody(), true);