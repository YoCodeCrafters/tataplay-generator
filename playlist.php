<?php

$json_url = 'https://raw.githubusercontent.com/ForceGT/Tata-Sky-IPTV/master/code_samples/allChannels.json';

$json_content = file_get_contents($json_url);

$data = json_decode($json_content, true);

$m3u_content = '#EXTM3U x-tvg-url="https://www.tsepg.cf/epg.xml.gz"' . "\n\n";

foreach ($data as $channel) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$base_path = rtrim(dirname($_SERVER['PHP_SELF']), '/');
$license_url = "{$protocol}://{$_SERVER['HTTP_HOST']}" . ($base_path ? $base_path : '') . "/wv-license.php?id={$channel['channel_id']}";
    $m3u_content .= "#EXTINF:-1 tvg-id=\"{$channel['channel_id']}\" tvg-logo=\"https://mediaready.videoready.tv/tatasky-epg/image/fetch/f_auto,fl_lossy,q_auto,h_250,w_250/{$channel['channel_logo']}\" group-title=\"{$channel['channel_genre']}\",{$channel['channel_name']}\n";
    $m3u_content .= "#KODIPROP:inputstream.adaptive.license_type=com.widevine.alpha\n";
    $m3u_content .= "#KODIPROP:inputstream.adaptive.license_key={$license_url}\n";
    $m3u_content .= "{$channel['channel_url']}\n\n";
}

$m3u_file_path = __DIR__ . '/playlist.m3u';
file_put_contents($m3u_file_path, $m3u_content);
header("Location: playlist.m3u");
exit;
?>