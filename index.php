<!doctype html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">   
<title>Login | TataPlay Online</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="shortcut icon" href="favicon.ico">
<style>
body {
    background-color: #6b00dd;
    overflow: hidden;
    }
     #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #6b00dd;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            z-index: 9999;
            animation: fadeOut 2s ease-in-out forwards;
        }

        video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #main-content {
            display: none;
        }
.card
{
    background-color: #220046;
}
.tata-play-head
{
    border-bottom: 1px solid #C0C0C0;
    padding-bottom: 10px;
}
#tplabel
{
    color: #FFFFFF;
    font-weight: bold;
    margin-bottom: 3px;
}
#tpy_alertbox, #tsk_subsid, #tsk_mobile, #tsk_password
{
    display: none;
}
</style>
</head>
<body>
    <div id="loading-screen">
        <video autoplay muted loop>
            <source src="img/bg.mp4" type="video/mp4">
        </video>
    </div>
 <div id="main-content">   
<div class="card mt-4 ms-3 me-3">
  <div class="card-body">
      <p class="tata-play-head">
          <img src="img/tata-sky-logo.png" alt="TataPlay Online" />
      </p>
      <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert" id="tpy_alertbox">
        <span id="tpy_alertmsg"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>

      <div class="mt-3">
          <label id="tplabel">Login Methods</label>
          <select class="form-control" id="tpy_method">
              <option value=""> - Click To Select - </option>
              <option value="subid_otp_gen">Subscriber ID and OTP</option>
          </select>
      </div>
      <div class="mt-3" id="tsk_subsid">
          <label id="tplabel">Subscriber ID</label>
          <input type="text" class="form-control" placeholder="Subscriber ID" id="tpy_subs" autocomplete="off"/>
      </div>
      <div class="mt-3" id="tsk_mobile">
          <label id="tplabel">Mobile Number</label>
          <input type="text" class="form-control" placeholder="Mobile Number" id="tpy_mobile" autocomplete="off"/>
      </div>
      <div class="mt-3" id="tsk_password">
          <label id="tplabel" id="tsk_pass_label">Password / OTP</label>
          <input type="text" class="form-control" placeholder="Password / OTP" id="tpy_pass" autocomplete="off"/>
      </div>
      <div class="mt-3">
          <button class="btn btn-warning" id="tpy_process">Process</button>
      </div>
    
  </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
<script>
    checksession();
    sessionStorage.setItem("login_method", "");
    sessionStorage.setItem("rmn", "");
    function checksession()
    {
        $.ajax({
            "url": "login.php",
            "type": "GET",
            "data": "status=1",
            "success": function(data)
            {
                try { data = JSON.parse(data); }catch(err){}
                if(data.status == "success")
                {
                    window.location = "profile.php";
                }
                else
                {
                    
                }
            },
            "error": function(resp, nmc, textStatus)
            {
                
            }
        });
    }
    $("#tpy_method").on("change", function(){
        let login_method = this.value;
        if(login_method == "subid_otp_gen")
        {
            $("#tsk_mobile").fadeOut();
            $("#tsk_password").fadeOut();
            $("#tpy_mobile").val('');
            $("#tpy_pass").val('');
            $("#tpy_subs").val('');
            $("#tsk_subsid").fadeIn();
        }
        if(login_method == "subid_pass")
        {
            $("#tpy_mobile").val('');
            $("#tpy_pass").val('');
            $("#tsk_mobile").fadeOut();
            $("#tsk_password").fadeIn();
        }
        sessionStorage.setItem("login_method", login_method);
    });
    
    $("#tpy_process").on("click", function(){
        $("#tpy_alertbox").fadeOut();
        let login_method = sessionStorage.getItem("login_method");
        let subscriberID = $("#tpy_subs").val();
        let regMobileNo = $("#tpy_mobile").val();
        let password = $("#tpy_pass").val();
        if(sessionStorage.getItem("rmn") !== "" && sessionStorage.getItem("rmn") !== null && sessionStorage.getItem("rmn") !== undefined)
        {
            regMobileNo = sessionStorage.getItem("rmn");
        }
        $.ajax({
            "url": "login.php",
            "type": "POST",
            "data": "method=" + login_method + "&rmn=" + regMobileNo + "&sbid=" + subscriberID + "&password=" + password,
            "success": function(data)
            {
                try { data = JSON.parse(data); }catch(err){}
                if(data.status == "success")
                {
                    
                    if(login_method == "subid_otp_gen")
                    {
                        $("#tpy_alertmsg").html(data.message);
                        $("#tpy_alertbox").fadeIn();
                        $("#tsk_password").fadeIn();
                        sessionStorage.setItem("rmn", data.data.rmn);
                        sessionStorage.setItem("login_method", "subid_otp_ok");
                    }
                    else
                    {
                        if(login_method == "subid_otp_ok")
                        {
                            $("#tpy_alertmsg").html("Logged In Successfully");
                            $("#tpy_alertbox").fadeIn();
                            $("#tpy_process").fadeOut();
                            window.setTimeout(function(){
                                window.location = "profile.php";
                            }, 1500);
                        }
                    }
                    
                }
                else
                {
                    if(data.status == "error")
                    {
                        $("#tpy_alertmsg").html("Error : " + data.message);
                        $("#tpy_alertbox").fadeIn();
                    }
                    else
                    {
                        $("#tpy_alertmsg").html("Unknown Error Occured");
                        $("#tpy_alertbox").fadeIn();
                    }
                }
            },
            "error": function(resp, nmc, textStatus)
            {
                let tada = resp.responseJSON;
                if(tada['message'] !== "" && tada['message'] !== null && tada['message'] !== undefined)
                {
                    $("#tpy_alertmsg").html("Error : " + tada['message']);
                    $("#tpy_alertbox").fadeIn();
                }
                else
                {
                    $("#tpy_alertmsg").html("Please Check Your Internet Connection");
                    $("#tpy_alertbox").fadeIn();
                }
            }
        });
    });
</script>
    <script>
        setTimeout(function() {
            document.getElementById('loading-screen').style.display = 'none';
            document.getElementById('main-content').style.display = 'block';
        }, 3000);
    </script>
</body>
</html>