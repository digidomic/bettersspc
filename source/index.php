<?php 
include('config.php'); 
?>

<html>
<head>
<title> <?php echo $title; ?> </title>

<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- link stylesheets -->
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">

   

</head>

<body>
	<div class="limiter">
		<div class="container-login100" style="  background:#212531; background: linear-gradient(to left bottom, #1686D6 50%, #1F9AE5 50%);">
			<div class="wrap-login100 p-l-55 p-r-55 p-t-15 p-b-14">
            <div class="wrap-login100 p-l-0">
                <!--Company Logo-->
				<img src="./images/logo.png"></img>
            </div>
                <!--Beginning of form-->
				<form class="login100-form validate-form" id="form01" method="POST">
                
					<span class="login100-form-title p-b-49 p-t-25">
					<?php echo $header; ?>
					</span>
                    <!--Field for email address (username+domain) -->
					<div class="wrap-input100 validate-input m-b-5" data-validate = "Eingabe erforderlich">
						<span class="label-input100">Email-Adresse:</span>
						<input class="input100" type="text" name="username" id="username" placeholder="Tippen Sie Ihre Email-Adresse ein">
						<span class="focus-input100" data-symbol="&#xf206;"></span>
					</div>
					</br>

                    <!--Field for actual password-->
					<div class="wrap-input100 validate-input m-b-5" data-validate="altes Passwort erforderlich">
						<span class="label-input100">aktuelles Passwort:</span>
						<input class="input100" type="password" name="passold" id="passold" placeholder="Tippen Sie Ihr altes Passwort ein">
						<span class="focus-input100" data-symbol="&#xf190;"></span>
					</div>
                    </br>

                    <!--Field for new password-->
                    <div class="wrap-input100 validate-input" data-validate="Eingabe erforderlich">
						<span class="label-input100">neues Passwort:</span>
						<input class="input100" type="password" name="passnew" id="passnew" placeholder="Tippen Sie Ihr neues Passwort ein">
						<span class="focus-input100" data-symbol="&#xf190;"></span>
					</div>
                    </br>

                    <!--Field for new password confirmation -->
                    <div class="wrap-input100 validate-input" data-validate="Eingabe erforderlich">
						<span class="label-input100">neues Passwort wiederholen:</span>
						<input class="input100" type="password" name="passnew2" id="passnew2" placeholder="Tippen Sie Ihr neues Passwort erneut ein">
						<span class="focus-input100" data-symbol="&#xf190;"></span>
					</div>

                    <!-- JavaScript checks if values for new passwords are equal-->
                    <script>
                        var password = document.getElementById("passnew");
                        var confirm_password = document.getElementById("passnew2");
 
                        function validatePassword(){
                        if(password.value != confirm_password.value) {
							confirm_password.setCustomValidity("Eingaben nicht identisch!");
                            } else {
                        confirm_password.setCustomValidity('');
                            }
                        }
                        password.onchange = validatePassword;
                        confirm_password.onkeyup = validatePassword;
                    </script>
			          
                      <!-- DIV generates space to button -->
					<div class="text-right p-t-8 p-b-5">
					</div>

						<?php
						if ($_SERVER['REQUEST_METHOD'] === 'POST') 
						{
							// Tasks after button pressed
						if (isset($_POST['btnSubmit'])) 
							{
								$notallowed = false;
								$errorcount = 0;

								if (strpos($_POST['username'], '@') === false) {
									$msgonsubmit = '<p class="p-b-14" style="color: red; font-weight: bold;">'.$msg_emailnotvalid.'</p>';
								} else {

							      foreach ($forbiddenusers as $notalloweduser)
								  {
									  if ($_POST['username'] == $notalloweduser)
									  {
										  $notallowed = true;
									  }
								  }

								  if ($notallowed == true)
								  {
									$msgonsubmit = '<p class="p-b-14" style="color: red; font-weight: bold;">'.$msg_notallowed.'</p>';
								  } else {

								// convert new password variable
								$passnew = $_POST['passnew']; 

								//check if new password meets requirements
								if (!preg_match('^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$^', $passnew))
								{
								echo '<script language="javascript">';
								echo 'alert("Passwort erfüllt nicht die Anforderungen (mind. 8 Zeichen, Groß-/Kleinschreibung, mind. 1 Sonderzeichen od. Ziffer).")';
								echo '</script>';
								}
									else
									{
										//connect to AD
										$ldap_conn = ldap_connect($server, $port);
										ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
										$bind = ldap_bind($ldap_conn, $adminuser, $adminpassword);
										
										//convert entered email address to username
										$samaccountname = substr($_POST['username'], 0, strpos($_POST['username'], "@"));
										
										//Building distinguished name
										$filter="(samaccountname=$samaccountname)";
										// searching in OU array
										foreach ($dn as $ou)
										{
											//check DN
											@$res = ldap_search($ldap_conn, $ou, $filter);
											@$first = ldap_first_entry($ldap_conn, $res);
											
											if (is_bool($first) === true)
											{
												/* $errorcount = $errorcount + 1;
												if ($errorcount == 1)
												{
													$msgonsubmit = '<b style="color: red;">'.$msg_error.'</p>';
												} */
												
											} else {

												if (@$bind=ldap_bind($ldap_conn, $_POST['username'], $_POST['passold'])) {
													
													$newuser_dn = ldap_get_dn($ldap_conn, $first); 

													//Set new Password
													$ADSI = new COM("LDAP:");
													$user = $ADSI->OpenDSObject("LDAP://".$server."/".$newuser_dn, $adminuser, $adminpassword, 1);
													$user->SetPassword($passnew);
													$user->SetInfo();
													
													// set output message 
													echo '<div style="text-align: center;">';
													$msgonsubmit = '<b style="color: green;">'.$msg_success.'</p>';
													echo '</div>';

												} else { 
													$msgonsubmit = '<p class="p-b-14" style="color: red; font-weight: bold;">'.$msg_failed.'</p>';
													}
												} 
											}
										}
									}
								}
							}
						}
					
						?>

					<div class="container-login100-form-btn" >
					
					<!-- Show output message -->
					
					<div id="statusmessage" >
						<?php echo @$msgonsubmit; ?>
					</div>

						<!--Submit Button-->
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
                            <button type="submit" class="wrap-login100-form-btn" id="btnSubmit" name="btnSubmit" value="Passwort ändern">Passwort ändern</button>
						</div>	
						
					</div>
					</form>
					<!-- Footer -->
                    <p class="footer">
                    </br></br>
                    BetterSSPC v1.1 <?php echo $footermsg; ?>
                    </p>
				
			</div>
		</div>
	</div>

	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="vendor/animsition/js/animsition.min.js"></script>
	<script src="js/main.js"></script>

	
	<script>

$(document).ready(function() {
    $("body").prepend('<div id="lock-modal" class="ui-widget-overlay" style="z-index: 1001; display: none;"></div>');
    $("body").prepend("<div id='loading-circle' style='display: none;'></div>");
});

$('#form01').submit(function() {
    var pass = true;
    //some validations

    if(pass == false){
        return false;
    }
    $("#lock-modal, #loading-circle").show();

    return true;
});

	</script>


</body>

</html>