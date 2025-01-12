<?php
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secretKey = '1039f7d5b26c76c045e937f01dee0fa8545b2022808daab21ad25dcc17946993';

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

function validateJWT($jwt) {
    global $secretKey;

    try {
        $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
        return $decoded;
    } catch (Exception $e) {
        return "Invalid Token: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['generate'])) {
        $userId = $_POST['userId'];
        $role = $_POST['role'];
        $token = generateJWT($userId, $role);
        $decoded = validateJWT($token);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JWT Generation & Validation</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 100%;
            max-width: 500px;
        }

        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            font-size: 16px;
            color: #555;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .output {
            margin-top: 20px;
            padding: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .output pre {
            margin: 0;
            font-size: 14px;
            color: #333;
        }

    </style>
</head>
<body>

<div class="container">
    <h1>JWT Generation & Validation</h1>
    <form method="POST">
        <label for="userId">User ID</label>
        <input type="text" id="userId" name="userId" required>

        <label for="role">Role</label>
        <input type="text" id="role" name="role" required>

        <button type="submit" name="generate">Generate JWT</button>
    </form>

    <?php if (isset($token)): ?>
        <div class="output">
            <h3>Generated Token:</h3>
            <pre><?php echo $token; ?></pre>
            <h3>Decoded Payload:</h3>
            <pre><?php print_r($decoded); ?></pre>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
