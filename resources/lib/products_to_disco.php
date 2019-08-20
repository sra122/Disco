<?php

$client = new \GuzzleHttp\Client();

$res = $client->request(
    'POST',
    'https://addnow-backend-staging.i-ways-network.org/product-api/products',
    [
        'headers' => [
            'APP-ID' => 'trnE6CjVkjQuUQOyujzrkjkTiMrWxSOaUhSqhaGQj6ikDk5XiNd03XPYZ7q5OqiUEbNWHAHx2kQ8djnKRqZoa0vdYGei2',
            'API-AUTH-TOKEN' => SdkRestApi::getParam('token')
        ],
        'form_params' => [
            'products' => SdkRestApi::getParam('product_details')
        ]
    ]

);

/** @return array */
return json_decode($res->getBody(), true);