<?php
include('_functions.php');

if(!isset($TPAUTH['entitlements']) || empty($TPAUTH['entitlements']))
{
    http_response_code(307);
header("Location: index.php");
exit;
}
?>
<!doctype html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Profile | TataPlay Online</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="shortcut icon" href="favicon.ico">
<style>
body {
    background-color: #6b00dd;
    }
.card {
    background-color: #220046;
}

.tata-play-head {
    border-bottom: 1px solid #C0C0C0;
    padding-bottom: 10px;
}
#tplabel {
    color: #FFFFFF;
    font-weight: bold;
    margin-bottom: 3px;
}
#tpy_alertbox, #tsk_subsid, #tsk_mobile, #tsk_password {
    display: none;
}
.white-box {
    background-color: #FFFFFF;
    padding: 10px;
    margin: 10px 0;
    border-radius: 8px;
    display: flex;
    align-items: center;
}

.link {
    flex: 1;
    color: #000000;
    text-decoration: none;
}
.center-button {
            text-align: right;
            margin-top: 0px;
        }
    @media (max-width: 767px) {
    .btn-block-on-small {
        width: 100%;
    }
}
    @media (min-width: 768px) {
    .btn-block-on-small {
        width: auto;
    }
}
</style>
</head>
<body>
    
<div class="card mt-4 ms-3 me-3">
  <div class="card-body">
      <p class="tata-play-head">
          <img src="img/tata-sky-logo.png" alt="TataPlay Online" />
      </p>
      <div class="white-box">
            <?php
                $base_path = rtrim(dirname($_SERVER['PHP_SELF']), '/');
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$playlistLink = "{$protocol}://{$_SERVER['HTTP_HOST']}" . ($base_path ? $base_path : '') . "/playlist.php";
            ?>
            <a href="<?php echo $playlistLink; ?>" class="link" id="playlistLink" target="_blank"><?php echo $playlistLink; ?></a>            
        </div>              
        <div class="row">
    <div class="col-12 col-md-6 mb-2 mb-md-0">
        <button onclick="copyToClipboard()" class="btn btn-warning btn-block-on-small" id="tpy_process">Copy</button>
    </div>
    <div class="col-12 col-md-6">
        <div class="center-button">
            <a href="logout.php" class="btn btn-danger btn-block-on-small" id="tpy_process">Logout</a>
        </div>
    </div>
</div>
</div>
</div>
<script>
function copyToClipboard() {
    var linkElement = document.getElementById("playlistLink");
    var tempInput = document.createElement("input");
    tempInput.value = linkElement.textContent;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);
    alert("Link copied to clipboard!");
}
</script>
</body>
</html>
