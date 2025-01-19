<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Project\AmoCRM\AmoClient;

define('AMO_DOMAIN', 'https://jenainours2.amocrm.ru');
define('AMO_ACCESS_TOKEN', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImE1ODg0ZTI3NDA1MTM1Mzc2MmFjZDQzMmM1NGQzMzY2ZTk5MTNiMWIzY2NhMjZjNzI5MjAyMmJjMDg5MzNlZjhmMGYzOGIyZjFjNDk4ZmY1In0.eyJhdWQiOiI3YjQ5MGYxOC0yYmU1LTRhNTUtYTc0ZS0xOTYzYzk2NDFiOTgiLCJqdGkiOiJhNTg4NGUyNzQwNTEzNTM3NjJhY2Q0MzJjNTRkMzM2NmU5OTEzYjFiM2NjYTI2YzcyOTIwMjJiYzA4OTMzZWY4ZjBmMzhiMmYxYzQ5OGZmNSIsImlhdCI6MTczNzI5NDI3MSwibmJmIjoxNzM3Mjk0MjcxLCJleHAiOjE3MzcyOTY2NzEsInN1YiI6IjEyMDAxMjU4IiwiZ3JhbnRfdHlwZSI6IiIsImFjY291bnRfaWQiOjAsImJhc2VfZG9tYWluIjpudWxsLCJ2ZXJzaW9uIjoxLCJzY29wZXMiOlsiY2hhdHMiLCJjcm0iLCJub3RpZmljYXRpb25zIiwidW5zb3J0ZWQiLCJtYWlsIl0sImhhc2hfdXVpZCI6ImJlYzc2Mzk2LWQyNzItNDVhMS04OTJhLWExNDVlYTE5ZGI4YSJ9.cgRefYELIDtV2bnpblLVMxrCSsvq5UaXL0fp99wLHG8X_Z28qc48oxlqZ8lFvGDt0_xzeQVG2jncLtDrJ_KTbKTW9ynbfxFhqQznTUhBkl13Y4Oz6fyeylwtgkMqu4xhdLtbaBoLLJbF5h8diTae3Tl-PYTjZQr_Uek2nivqCuRGcaa74IxdtkSJ0r2QuQ-CCpuvcVsRAasY8cbvna0kQvWTLikVH0NBDDnlnaz318foF0dXHLhp6GgNfpdoKj4kzGm6o1GVKegzH5IMNT1TbA0HPUDz-HbxnoSKr6b1P8vb6uQuYyQvFgXLD6A9XtS6gRfCqRZGqqqtfXb0-dMZyw');
define('AMO_PIPELINE_STATUS_ID', 73305226);
define('AMO_30_SEC_CUSTOM_FIELD_ID', 1013655);

define('REQUIRED_KEYS', ['price', 'name', 'phone', 'email']);

if (empty($_POST)) {
    http_response_code(400);
    echo json_encode(['message' => 'Body is empty']);

    die();
}

$amoClient = new AmoClient(AMO_DOMAIN, AMO_ACCESS_TOKEN);
try {

    $body = prepareBodyForLeadComplexRequest($_POST);
    $response = $amoClient->addLeadsComplex($body);
} catch (Throwable $e) {
    http_response_code($e->getCode());
    echo json_encode(['message' => $e->getMessage()]);

    die();
}

echo $response;

die();

function prepareBodyForLeadComplexRequest(array $payload)
{
    vaidatePayload($payload);
    
    $body = [
        'price' => intval($payload['price']),
        'status_id' => AMO_PIPELINE_STATUS_ID,
        '_embedded' => [
            'contacts' => [[
                'name' => $payload['name'],
                'custom_fields_values' => [
                    [
                        'field_code' => 'EMAIL',
                        'values' => [
                            [
                                'value' => $payload['email']
                            ]
                        ]
                    ],
                    [
                        'field_code' => 'PHONE',
                        'values' => [
                            [
                                'value' => $payload['phone']
                            ]
                        ]
                    ]
                ]
            ]]
        ]
    ];

    if (isset($payload['additional'])) {
        $body['custom_fields_values'] = [
            [
                'field_id' => AMO_30_SEC_CUSTOM_FIELD_ID,
                'values' => [
                    [
                        'value' => $payload['additional'] == 'true' ? true : false
                    ]
                ]
            ]
        ];
    }
    return [$body];
}

function vaidatePayload(array $payload)
{
    $missingKeys = array_diff_key(array_flip(REQUIRED_KEYS), $payload);
    if(!empty($missingKeys))
        throw new Exception('Invalid form body', 400);
}
