<?php
/**
 * Sends JSON response with specified status code
 *
 * @param mixed $data Data to be JSON encoded and sent
 * @param int $status HTTP status code
 * @return never
 */
function sendJsonResponse(mixed $data, int $status = 200): never {
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    exit;
}