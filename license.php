<?php

$id = $_REQUEST['id'];

function secure_values($action, $data)
{
    $protec = "";
    $method = 'AES-128-CBC';
    $ky = 'joincodecrafters';
    $iv = substr(sha1($ky.'coolapps'."24662b4f995b7b3d348211c94fdaa080"), 0, 16);
    
    if($action == "encrypt")
    {
        $encrypted = openssl_encrypt($data, $method, $ky, OPENSSL_RAW_DATA, $iv);
        if(!empty($encrypted))
        {
            $protec = bin2hex($encrypted);
        }
    }
    else
    {
        $decrypted = openssl_decrypt(hex2bin($data), $method, $ky, OPENSSL_RAW_DATA, $iv);
        if(!empty($decrypted))
        {
            $protec = $decrypted;
        }
    }
    return $protec;
}
{
$getUData = @file_get_contents('secure/_sessionData');
    $decUData = secure_values('decrypt', $getUData);
    $TATA_DATA = @json_decode($decUData, true);
    $TPAUTH = array('access_token' => $TATA_DATA['data']['accessToken'],
                    'refresh_token' => $TATA_DATA['data']['refreshToken'],
                    'subscriberID' => $TATA_DATA['data']['userDetails']['sid'],
                    'subscriberRMN' => $TATA_DATA['data']['userDetails']['rmn'],
                    'subscriberNAME' => $TATA_DATA['data']['userDetails']['sName'],
                    'profileID' => $TATA_DATA['data']['userProfile']['id'],
                    'deviceName' => $TATA_DATA['data']['deviceDetails']['deviceName'],
                    'entitlements' => $TATA_DATA['data']['userDetails']['entitlements']);
    }
//-----------------------------------------------------------------------//
{
$chnDetailsAPI = 'https://kong-tatasky.videoready.tv/content-detail/pub/api/v1/channels/'.$id;
$chnDlHeads = array('Accept-Language: en-US,en;q=0.9',
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
                    'device_details: {"pl":"web","os":"WINDOWS","lo":"en-us","app":"1.36.63","dn":"PC","bv":104,"bn":"CHROME","device_id":"24662b4f995b7b3d348211c94fdaa080","device_type":"WEB","device_platform":"PC","device_category":"open","manufacturer":"WINDOWS_CHROME_104","model":"PC","sname":"'.$TPAUTH['subscriberNAME'].'"}',
                    'Referer: https://watch.tataplay.com/',
                    'Origin: https://watch.tataplay.com',
                    'Authorization: bearer '.$TPAUTH['access_token'],
                    'profileId: '.$TPAUTH['profileID'],
                    'platform: web',
                    'locale: ENG',
                    'kp: false');
$process = curl_init($chnDetailsAPI);
curl_setopt($process, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($process, CURLOPT_HTTPHEADER, $chnDlHeads);
curl_setopt($process, CURLOPT_HEADER, 0);
curl_setopt($process, CURLOPT_TIMEOUT, 10);
curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
$chnOut = curl_exec($process);
curl_close($process);
}
    $vUData = @json_decode($chnOut, true);

    
        $widevine = $vUData['data']['detail']['dashWidewineLicenseUrl'];
              $mpd = $vUData['data']['detail']['dashWidewinePlayUrl'];
         $sub_epid = $vUData['data']['detail']['entitlements']['0'];
    
{
        
        $jwtpay = ("{\"action\":\"stream\",\"epids\":[{\"epid\":\"Subscription\",\"bid\":\"$sub_epid\"}]}");
               
        $sherlocation = 'https://tm.tapi.videoready.tv/auth-service/v1/oauth/token-service/token';
        $sherheads = array('Accept-Language: en-US,en;q=0.9',
                           'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
                           'content-type: application/json',
                           'device_details: {"pl":"web","os":"WINDOWS","lo":"en-us","app":"1.36.63","dn":"PC","bv":104,"bn":"CHROME","device_id":"24662b4f995b7b3d348211c94fdaa080","device_type":"WEB","device_platform":"PC","device_category":"open","manufacturer":"WINDOWS_CHROME_104","model":"PC","sname":"'.$TPAUTH['subscriberNAME'].'"}',
                           'kp: false',
                           'locale: ENG',
                           'platform: web',
                           'profileId: '.$TPAUTH['profileID'],
                           'Referer: https://watch.tataplay.com/',
                           'x-device-id: '."24662b4f995b7b3d348211c94fdaa080",
                           'x-device-platform: PC',
                           'x-device-type: WEB',
                           'x-subscriber-id: '.$TPAUTH['subscriberID'],
                           'x-subscriber-name: '.$TPAUTH['subscriberNAME'],
                           'Authorization: bearer '.$TPAUTH['access_token'],
                           'Origin: https://watch.tataplay.com');
        $sherposts = $jwtpay;
        $process = curl_init($sherlocation);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_POSTFIELDS, $sherposts);
        curl_setopt($process, CURLOPT_HTTPHEADER, $sherheads);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_ENCODING, '');
        curl_setopt($process, CURLOPT_TIMEOUT, 10);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        $vrswvx = curl_exec($process);
        curl_close($process);
        $mksaz= @json_decode($vrswvx, true);        
        $ls_session = 'ls_session='.$mksaz['data']['token'];
        $licurl = $widevine.'&'.$ls_session;
        http_response_code(307);
        header("Location: $licurl");
        exit();
}
?>