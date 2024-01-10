<?php
$tPath = app()->environment('local') ? '' : '/public/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style></style>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disporabudpar - Nganjuk</title>
    <link rel="stylesheet" href="{{ asset($tPath.'css/utama/login.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="{{ asset($tPath.'img/icon/utama/logo.png') }}" rel="icon">
</head>
<body>
    <!-- <img class="wave" src="https://raw.githubusercontent.com/sefyudem/Responsive-Login-Form/master/img/wave.png"> -->
    <div class="container">
        <div class="img">
            <img style="width: 400px;" src="/public/img/icon/utama/login.svg">
        </div>
        <div class="login-content">
            <form action="{{route('users.login')}}" method="post" class="form-login" id="loginForm">
                <h2>Selamat Datang!</h2>
                <div class="input-div one">
                    <div class="i">
                        <i class='bx bx-at'></i>
                    </div>
                    <div>
                        <h5>Email</h5>
                        <input type="email" name="email" id="inpEmail" class="input">
                        <!-- <input class="input" type="text"> -->
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class='bx bxs-lock'></i>
                    </div>
                    <div class="div">
                        <h5>Kata Sandi</h5>
                        <input type="password" name="password" id="inpPassword" class="input">
                        <!-- <input class="input" type="password"> -->
                    </div>
                </div>
                <input type="submit" class="btn" name="login" value="Masuk" id="submit">
                <!-- <input type="submit" class="btn" value="Masuk" name="login"> -->
                </div>
            </form>
        </div>
    </div>
    <div id="preloader" style="display: none;"></div>
    <div id="greenPopup" style="display:none"></div>
    <div id="redPopup" style="display:none"></div>
    <script> 
        const inputs = document.querySelectorAll(".input");
        function addcl(){
        	let parent = this.parentNode.parentNode;
        	parent.classList.add("focus");
        }
        function remcl(){
        	let parent = this.parentNode.parentNode;
        	if(this.value == ""){
        		parent.classList.remove("focus");
        	}
        }
        inputs.forEach(input => {
        	input.addEventListener("focus", addcl);
        	input.addEventListener("blur", remcl);
        });
</script>
</body>

</html>