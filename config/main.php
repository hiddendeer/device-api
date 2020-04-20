<?php

return [
    'response' => [
        'class' => 'yii\web\Response',
        'on beforeSend' => function ($event) {
            $response = $event->sender;
            $response->data = [
                'code' => isset($response->data['resp_code']) ? $response->data['resp_code'] : $response->getStatusCode(),
                'data' => $response->data,
                'message' => isset($response->data['message']) ? $response->data['message'] : $response->statusText
            ];
            $response->format = yii\web\Response::FORMAT_JSON;
        },
    ],
];