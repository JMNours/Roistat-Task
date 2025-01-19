<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Project\AmoCRM\AmoClient;

define('AMO_DOMAIN', 'https://jenainours2.amocrm.ru');
define('AMO_ACCESS_TOKEN', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImEyZDg0YzU2ODVmMjM1MWE3OWYxY2VmYjc3ZWYyODRiM2E0MzgzZGVmMmU0NjY1ODJlMmZjY2Q5NTViMDM4M2E1ZjJlZTdhMDZiYWJmZDRiIn0.eyJhdWQiOiI3YjQ5MGYxOC0yYmU1LTRhNTUtYTc0ZS0xOTYzYzk2NDFiOTgiLCJqdGkiOiJhMmQ4NGM1Njg1ZjIzNTFhNzlmMWNlZmI3N2VmMjg0YjNhNDM4M2RlZjJlNDY2NTgyZTJmY2NkOTU1YjAzODNhNWYyZWU3YTA2YmFiZmQ0YiIsImlhdCI6MTczNzI5MDY2NiwibmJmIjoxNzM3MjkwNjY2LCJleHAiOjE3MzcyOTMwNjYsInN1YiI6IjEyMDAxMjU4IiwiZ3JhbnRfdHlwZSI6IiIsImFjY291bnRfaWQiOjAsImJhc2VfZG9tYWluIjpudWxsLCJ2ZXJzaW9uIjoxLCJzY29wZXMiOlsiY2hhdHMiLCJjcm0iLCJub3RpZmljYXRpb25zIiwidW5zb3J0ZWQiLCJtYWlsIl0sImhhc2hfdXVpZCI6ImFkNGRjZmVmLWU5NWQtNDQxYS1hMmIwLTUyNjg0ZWU1YjAxYiJ9.CV4hbXJP41rg3jkYgIGl-LnLQibH8p6gBKjETt977MgeVJlUJMnQR_OCM8QtId8jwLr4U1ssRi0bAcCSahqrm0B-fjl223fcPimijNTzAy3yPvPEdtdKq94YwaohsNS-qOWsQIUxg0N630t84fWHnluuzkO7OwfCXhtvI3wZS2VXksfxeV7pa97GBEuKqV9FhzlkPBE1JzMj8pNR0j7pF-VWL9LvqOCnuHTpAsIGGxsDhONOYqcOoJbZVNWbojegmfQ8R7cx8Wx4YkPmTXRcIc2aHW-wnA_lJOIAJOiTGRIvDekePlRjQTegjO46rK-tcnrsw1piipi2tNtHI3iC4A');
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
