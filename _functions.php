<?php

error_reporting(0);
date_default_timezone_set('Asia/Kolkata');

function api_res($status, $code, $message, $data)
{
    header("Content-Type: application/json");
    if($status == "success" || $status == "error")
    {
        if($status == "error"){ $data = array(); }
        $out = array('status' => $status, 'code' => $code, 'message' => $message, 'data' => $data);
        exit(print(json_encode($out)));
    }
    else
    {
        http_response_code(500);
        exit('Fatal Error');
    }
}

function device_id()
{
    $rando = md5(rand(0, 9).rand(0, 9).time().rand(0, 9).rand(0, 9));
    if(!file_exists('secure/_androidID'))
    {
        @file_put_contents('secure/_androidID', $rando);
    }
    else
    {
        $getDDId = @file_get_contents('secure/_androidID');
        if(empty($getDDId))
        {
            @file_put_contents('secure/_androidID', $rando);
        }
    }
}

function secure_values($action, $data)
{
    $protec = "";
    $method = 'AES-128-CBC';
    $ky = 'joincodecrafters';
    if(file_exists('secure/_androidID'))
    {
        $getDevID = @file_get_contents('secure/_androidID');
        if(!empty($getDevID))
        {
            $DEVICE_ID = $getDevID;
        }
    }
    $iv = substr(sha1($ky.'coolapps'.$DEVICE_ID), 0, 16);
    
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


//--------------------------------------------------------------------------//

$DEVICE_ID = '';
device_id();

if(file_exists('secure/_androidID'))
{
    $getDevID = @file_get_contents('secure/_androidID');
    if(!empty($getDevID))
    {
        $DEVICE_ID = $getDevID;
    }
}

if(file_exists('secure/_sessionData'))
{
    $getUData = @file_get_contents('secure/_sessionData');
    $decUData = secure_values('decrypt', $getUData);
    
    //Tata Play Data
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

//---------------------------------------------------------------------------//

function genjwtpayload($epid)
{
  return '{"action":"stream","epids":[{"epid":"Subscription","bid":"'.$epid.'"}]}';
}


?>