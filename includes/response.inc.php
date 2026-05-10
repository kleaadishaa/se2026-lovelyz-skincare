<?php

function sendResponse($success, $message, $statusCode = 200, $data = null)
{
    http_response_code($statusCode);

    header('Content-Type: application/json');

    $response = [
        "success" => $success,
        "message" => $message
    ];

    if ($data !== null) {
        $response = array_merge($response, $data);
    }

    echo json_encode($response);
    exit;
}