<?php
// search_cards.php - proxy search to MTG API and normalize results
header('Content-Type: application/json');
$q = trim($_GET['q'] ?? '');
if ($q === '') { echo json_encode([]); exit(); }

$apiUrl = 'https://api.magicthegathering.io/v1/cards?'.http_build_query([
    'name' => $q,
    'pageSize' => 10
]);
$ctx = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => [
            'Accept: application/json'
        ],
        'timeout' => 8
    ]
]);
$data = @file_get_contents($apiUrl, false, $ctx);
if ($data === false) { echo json_encode([]); exit(); }
$json = json_decode($data, true);
$cards = $json['cards'] ?? [];
$out = [];
foreach ($cards as $c) {
    $img = $c['imageUrl'] ?? null;
    if (!$img && isset($c['multiverseid'])) {
        $img = 'https://gatherer.wizards.com/Handlers/Image.ashx?multiverseid='.(int)$c['multiverseid'].'&type=card';
    }
    $out[] = [
        'id' => $c['id'] ?? null,
        'name' => $c['name'] ?? 'Unknown',
        'setName' => $c['setName'] ?? ($c['set'] ?? ''),
        'multiverseid' => $c['multiverseid'] ?? null,
        'image' => $img
    ];
}
echo json_encode($out);
