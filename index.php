<?php
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


// $secureKey = bin2hex(openssl_random_pseudo_bytes(32));
// echo "Generated Secure Key: $secureKey\n";

// Secret key for signing

echo "Started jwt verification";

$secretKey = '1039f7d5b26c76c045e937f01dee0fa8545b2022808daab21ad25dcc17946993';

// Generate JWT
function generateJWT($userId, $role) {
    global $secretKey;

    $payload = [
        'user_id' => $userId,
        'role' => $role,
        'iat' => time(),               
        'exp' => time() + 3600         
    ];

    return JWT::encode($payload, $secretKey, 'HS256');
}

// Validate JWT
function validateJWT($jwt) {
    global $secretKey;

    try {
        print_r(new Key($secretKey, 'HS256'));die;
        $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
        return $decoded;
    } catch (Exception $e) {
        return "Invalid Token: " . $e->getMessage();
    }
}

// Example Usage
$token = generateJWT(123, 'admin');
echo "Generated Token: $token\n";

$decoded = validateJWT($token);
echo "Decoded Payload:\n";
print_r($decoded);

echo "ending jwt validation";

?>

