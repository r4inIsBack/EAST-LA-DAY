<?php

if (!isset($_GET['id'])) {

    die("Missing video ID");

}

$videoId = preg_replace('/[^a-zA-Z0-9]/', '', $_GET['id']);

function getM3U8($videoId) {

    $api = "https://www.dailymotion.com/player/metadata/video/" . $videoId;

    $json = file_get_contents($api);

    if (!$json) return false;

    $data = json_decode($json, true);

    if (!isset($data['qualities'])) return false;

    foreach ($data['qualities'] as $group) {

        foreach ($group as $item) {

            if (

                isset($item['type']) &&

                (

                    $item['type'] === "application/x-mpegURL" ||

                    $item['type'] === "application/vnd.apple.mpegurl"

                )

            ) {

                return $item['url'];

            }

        }

    }

    return false;

}

$m3u8 = getM3U8($videoId);

if (!$m3u8) {

    die("Stream not available");

}

header("Location: " . $m3u8);

exit;