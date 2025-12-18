<?php
$url = "http://172.30.103.68/web/etat_salle.php"; // adapter l'URL si nécessaire

$data = [
    "etat" => 0,   // 0 = libre, 1 = occupée
    "nom"  => "C207"
];

$options = [
    "http" => [
        "header"  => "Content-Type: application/json\r\n",
        "method"  => "POST",
        "content" => json_encode($data),
    ]
];

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
echo $result;
