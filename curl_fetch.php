<?php 

function curl_fetch(string $url, string $http_method = 'GET', array $headers = [], array $data = [], int $timeout = 30): array
{
    // Initialize cURL
    $ch = curl_init($url);

    // Set HTTP method (GET, POST, PUT, DELETE)
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($http_method));

    // Set headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Determine Content-Type and prepare data accordingly
    $content_type = 'application/json'; // Default value
    foreach ($headers as $header) {
        if (stripos($header, 'Content-Type:') === 0) {
            $content_type = trim(substr($header, 13)); // Extract Content-Type value
            break;
        }
    }

    // For POST, PUT, DELETE methods, set the request body
    if (in_array(strtoupper($http_method), ['POST', 'PUT', 'DELETE']) && !empty($data)) {
        if ($content_type === 'application/json') {
            // Send data as JSON
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } elseif ($content_type === 'application/x-www-form-urlencoded') {
            // Send data as x-www-form-urlencoded
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
    }

    // General cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

    // Execute the request
    $response = curl_exec($ch);

    // Handle cURL errors
    if ($response === false) {
        return [
            'curl_status' => 'error',
            'error' => [
                'code' => curl_errno($ch),
                'message' => curl_error($ch)
            ]
        ];
    }

    // Get HTTP response code
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Close the cURL session
    curl_close($ch);

    // Decode JSON response if possible
    $response_data = json_decode($response, true);

    // Check if JSON decoding was successful
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [
            'curl_status' => 'error',
            'error' => [
                'code' => json_last_error(),
                'message' => json_last_error_msg(),
                'response' => $response
            ]
        ];
    }

    // Check if response contains error details for 500 or other errors
    if ($http_code !== 200) {
        // Provide more detailed error message if available
        $error_details = isset($response_data['data']) ? $response_data['data'] : $response_data;
        
        return [
            'curl_status' => 'error',
            'http_code' => $http_code,
            'error_message' => $error_details['message'] ?? 'Unknown error occurred.',
            'error_details' => $error_details
        ];
    }

    // Return the response based on HTTP status code
    if ($http_code === 200) {
        // Check if the response contains 'data' and merge if necessary
        if (isset($response_data['data'])) {
            return array_merge([
                'curl_status' => 'success'
            ], $response_data); // Merges response data
        }

        // If no 'data' exists, return the response with the appropriate status
        return [
            'curl_status' => 'success',
            'data' => $response_data ?: $response // Use JSON if valid, otherwise raw response
        ];
    }

    // Default return if no conditions were met
    return [
        'curl_status' => 'error',
        'http_code' => $http_code,
        'error_message' => 'Unexpected error occurred.'
    ];
}
