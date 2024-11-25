<?php
function loadShaData() {
    // Load data from the files into an associative array
    $files = ['sha1_list.txt', 'sha224_list.txt', 'sha256_list.txt'];
    $hashData = [];

    foreach ($files as $file) {
        if (file_exists($file)) {
            $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                list($string, $hash) = explode(':', $line);
                $hashData[trim($hash)] = trim($string);
            }
        }
    }
    return $hashData;
}

// Check if the hash was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hash'])) {
    $hash = trim($_POST['hash']);
    $hashData = loadShaData();

    // Search for the hash in the data
    $result = $hashData[$hash] ?? null;

    // Generate the response page
    echo "<!DOCTYPE html>";
    echo "<html lang='en'>";
    echo "<head><meta charset='UTF-8'><title>SHA Lookup Result</title></head>";
    echo "<body>";
    echo "<h1>SHA Lookup Result</h1>";
    echo "<p><strong>Hash Searched:</strong> $hash</p>";

    if ($result) {
        echo "<p><strong>Original String:</strong> $result</p>";
    } else {
        echo "<p><strong>Message:</strong> The hash value could not be found in the server’s records.</p>";
    }

    // Form to search for another hash
    echo "<form action='sha.php' method='POST'>";
    echo "<label for='hash'>Enter Another SHA Hash:</label><br>";
    echo "<input type='text' id='hash' name='hash' required><br><br>";
    echo "<button type='submit'>Search Again</button>";
    echo "</form>";
    echo "</body>";
    echo "</html>";
} else {
    // Redirect to sha.html if accessed directly
    header('Location: sha.html');
    exit;
}
