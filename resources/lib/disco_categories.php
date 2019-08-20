<?php

$client = new \GuzzleHttp\Client();


$res = $client->request(
    'GET',
    'https://addnow-backend-staging.i-ways-network.org/category-api/categories',
    [
        'headers' => [
            'APP-ID' => 'trnE6CjVkjQuUQOyujzrkjkTiMrWxSOaUhSqhaGQj6ikDk5XiNd03XPYZ7q5OqiUEbNWHAHx2kQ8djnKRqZoa0vdYGei2',
            'API-AUTH-TOKEN' => SdkRestApi::getParam('token')
        ]
    ]

);

/** @return array */
return json_decode($res->getBody(), true);