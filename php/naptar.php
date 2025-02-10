<?php
header("Content-Type: application/json; charset=UTF-8");

// Példa események
$events = [
    [
        "title" => "Meeting",
        "start" => "2023-10-10T10:00:00"
    ],
    [
        "title" => "Birthday Party",
        "start" => "2023-10-15T18:00:00"
    ]
];

echo json_encode($events);
?>