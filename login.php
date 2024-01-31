<?php

include('_functions.php');

//----------------------------------------------------------------------------//

if(isset($_GET['status']) && $_GET['status'] == 1 || $_GET['status'] == "1")
{
    if(file_exists('secure/_sessionData'))
    {
        $getTPLK = @file_get_contents('secure/_sessionData');
        if(!empty($getTPLK))
        {
            api_res('success', 200, 'Logged In', array('is_logged_in' => true));
        }
    }
    api_res('error', 400, 'Not Logged In', array('is_logged_in' => false));
}

//----------------------------------------------------------------------------//

if($_SERVER['REQUEST_METHOD'] !== "POST")
{
    http_response_code(405);
    api_res('error', 405, 'Method Not Allowed', '');
}

$sbid = ""; $rmn = "";
$method = ""; $password = "";

if(isset($_REQUEST['method']))
{
    $method = trim($_REQUEST['method']);
}
if(isset($_REQUEST['rmn']))
{
    $rmn = $_REQUEST['rmn'];
}
if(isset($_REQUEST['sbid']))
{
    $sbid = trim($_REQUEST['sbid']);
}

if(isset($_REQUEST['password']))
{
    $password = trim($_REQUEST['password']);
}

if($method == "subid_otp_gen")
{
    if(empty($sbid))
    {
        http_response_code(400);
        api_res('error', 400, 'Please Enter Subscriber ID', '');
    }
    
    $failed_login_msg = 'Failed To Initiate Login';
    $tmapi = 'https://tm.tapi.videoready.tv/rest-api/pub/api/v2/generate/otp';
    $tmpost = '{"sid":"'.$sbid.'","rmn":""}';
    $tmhead = array('Accept-Language: en-US,en;q=0.9',
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
                    'content-type: application/json',
                    'device_details: {"pl":"web","os":"WINDOWS","lo":"en-us","app":"1.36.63","dn":"PC","bv":104,"bn":"CHROME","device_id":"'.$DEVICE_ID.'","device_type":"WEB","device_platform":"PC","device_category":"open","manufacturer":"WINDOWS_CHROME_104","model":"PC","sname":""}',
                    'locale: ENG',
                    'platform: web',
                    'Referer: https://watch.tataplay.com/',
                    'Origin: https://watch.tataplay.com');
    $process = curl_init($tmapi); 
    curl_setopt($process, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($process, CURLOPT_POSTFIELDS, $tmpost);
    curl_setopt($process, CURLOPT_HTTPHEADER, $tmhead); 
    curl_setopt($process, CURLOPT_HEADER, 0);
    curl_setopt($process, CURLOPT_TIMEOUT, 10); 
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);  
    $tmout = curl_exec($process); 
    curl_close($process);
    if(!empty($tmout))
    {
        $tmdata = @json_decode($tmout, true);
        if(isset($tmdata['data']['rmn']))
        {
            if(stripos($tmdata['message'], 'otp') !== false && stripos($tmdata['message'], 'success') !== false)
            {
                api_res('success', 200, 'OTP Generated Successfully', array('rmn' => $tmdata['data']['rmn'] ));
            }
        }
    }
    if(isset($tmdata['message']) && !empty($tmdata['message'])){ $failed_login_msg = 'Failed To Initiate Login - Reason: '.$tmdata['message']; }
    api_res('error', 400, $failed_login_msg, '');
}
elseif($method == "subid_otp_ok")
{
    if(empty($sbid))
    {
        http_response_code(400);
        api_res('error', 400, 'Please Enter Subscriber ID', '');
    }
    
    if(empty($rmn))
    {
        http_response_code(400);
        api_res('error', 400, 'Registered Mobile Number Is Missing', '');
    }
    
    if(empty($password))
    {
        http_response_code(400);
        api_res('error', 400, 'Please Enter OTP', '');
    }
    
    $rmn = str_replace(" ", "+", $rmn);
    
    $tpapi = 'https://tm.tapi.videoready.tv/rest-api/pub/api/v3/login/ott';
    $tppost = '{"rmn":"'.$rmn.'","sid":"'.$sbid.'","authorization":"'.$password.'","loginOption":"OTP"}';
    $tphead = array('Accept-Language: en-US,en;q=0.9',
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
                    'content-type: application/json',
                    'device_details: {"pl":"web","os":"WINDOWS","lo":"en-us","app":"1.36.63","dn":"PC","bv":104,"bn":"CHROME","device_id":"'.$DEVICE_ID.'","device_type":"WEB","device_platform":"PC","device_category":"open","manufacturer":"WINDOWS_CHROME_104","model":"PC","sname":""}',
                    'locale: ENG',
                    'platform: web',
                    'Referer: https://watch.tataplay.com/',
                    'Origin: https://watch.tataplay.com');
    $process = curl_init($tpapi); 
    curl_setopt($process, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($process, CURLOPT_POSTFIELDS, $tppost);
    curl_setopt($process, CURLOPT_HTTPHEADER, $tphead); 
    curl_setopt($process, CURLOPT_HEADER, 0);
    curl_setopt($process, CURLOPT_TIMEOUT, 10); 
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);  
    $tpout = curl_exec($process); 
    curl_close($process);
    if(!empty($tpout))
    {
        $tmdata = @json_decode($tpout, true);
        if(isset($tmdata['data']['accessToken']))
        {
            @file_put_contents('secure/_sessionData', secure_values('encrypt', $tpout));
            api_res('success', 200, 'Logged In Successfully', array());
        }
    }
    if(isset($tmdata['message']) && !empty($tmdata['message'])){ $failed_login_msg = 'Failed To Login - '.$tmdata['message']; }
    api_res('error', 400, $failed_login_msg, '');
}
elseif($method == "subid_pass")
{
/******************************************************************************
 *          S U B S C R I B E R   I D  -  P A S S W O R D
 * ****************************************************************************/
    if(empty($sbid))
    {
        http_response_code(400);
        api_res('error', 400, 'Please Enter Subscriber ID', '');
    }
    if(empty($password))
    {
        http_response_code(400);
        api_res('error', 400, 'Please Enter Password', '');
    }
    $tmapi = 'https://tm.tapi.videoready.tv/rest-api/pub/api/v2/generate/otp';
    $tmpost = '{"sid":"'.$sbid.'","rmn":""}';
    $tmhead = array('Accept-Language: en-US,en;q=0.9',
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
                    'content-type: application/json',
                    'device_details: {"pl":"web","os":"WINDOWS","lo":"en-us","app":"1.36.63","dn":"PC","bv":104,"bn":"CHROME","device_id":"'.$DEVICE_ID.'","device_type":"WEB","device_platform":"PC","device_category":"open","manufacturer":"WINDOWS_CHROME_104","model":"PC","sname":""}',
                    'locale: ENG',
                    'platform: web',
                    'Referer: https://watch.tataplay.com/',
                    'Origin: https://watch.tataplay.com');
    $process = curl_init($tmapi); 
    curl_setopt($process, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($process, CURLOPT_POSTFIELDS, $tmpost);
    curl_setopt($process, CURLOPT_HTTPHEADER, $tmhead); 
    curl_setopt($process, CURLOPT_HEADER, 0);
    curl_setopt($process, CURLOPT_TIMEOUT, 10); 
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);  
    $tmout = curl_exec($process); 
    curl_close($process);
    if(!empty($tmout))
    {
        $tmdata = @json_decode($tmout, true);
        if(isset($tmdata['data']['rmn']) && !empty($tmdata['data']['rmn']))
        {
            $rmobnum = $tmdata['data']['rmn'];
        }
    }
    if(isset($rmobnum) && !empty($rmobnum))
    {
        $dtapi = 'https://tm.tapi.videoready.tv/rest-api/pub/api/v3/login/ott';
        $loginpostload = json_encode(array('loginOption' => 'PWD', 'rmn' => $rmobnum, 'sid' => $sbid, 'authorization' => $password));
        $process = curl_init($dtapi);
        curl_setopt($process, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($process, CURLOPT_POSTFIELDS, $loginpostload);
        curl_setopt($process, CURLOPT_HTTPHEADER, $tmhead); 
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_TIMEOUT, 10); 
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);  
        $tmout = curl_exec($process); 
        curl_close($process);
        if(!empty($tmout))
        {
            $tmdata = @json_decode($tmout, true);
            if(isset($tmdata['data']['accessToken']))
            {
                @file_put_contents('secure/_sessionData', secure_values('encrypt', $tmout));
                api_res('success', 200, 'Logged In Successfully', array());
            }
        }
        api_res('error', 400, 'Failed To Login '.$tmdata['message'], array());
    }
    else
    {
        api_res('error', 400, 'Failed To Lookup Your Account Details. Please Check Subscriber ID.', '');
    }
}
else
{
    api_res('error', 400, 'Please select a valid Login Method.', '');
}

?>