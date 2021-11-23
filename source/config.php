<?php

//Domain-Controller
$server = 'COMPANYDC';
// LDAP Port (AD without TLS 389)
$port = '389';
// Domäne
$dn = 'dc=company,dc=com';

// Domain-Admin
$adminuser = 'adadmin';
$adminpassword = '123456';

// Organisation Units
$dn = array(
    "OU=users, dc=company,dc=com",
    "OU=managers, dc=company,dc=com",
    "OU=stuff extern, dc=company,dc=com"
);

// Forbidden Users (with @domain.com)
$forbiddenusers = array(
    "administrator@company.com",
    "sysadmin@company.com"
);


// Localization

// Page title
$title = 'Self-Service Password Change';

// Page header
$header = 'Change Password';

//Field for E-Mail-Addres
$f_email = 'E-Mail-Address:';
$p_email = 'Enter your e-mail address';

//Field for actual password
$f_acpw = 'Current Password:';
$p_acpw = 'Enter your current password';

//Field for new password
$f_newpw = 'new Password:';
$p_newpw = 'Enter your new password';

//Field for new password confirmation
$f_newpwconf = 'Confirm new password:';
$p_newpwconf = 'Confirm your new password';

// Button
$b_submit = 'Change now';

//Field is required
$msg_fieldreq = 'required';


// If password does not match requirement
$msg_pwreq = 'Password does not match requirements.';

// If password confitmation differs from new password
$msg_pwconfnv = 'Confirmation not valid';

// If password change was successfull
$msg_success = 'Successfully changed. </b> </br>
<p>Please note that the change can take up to 10 minutes.';

// If actual password is wrong
$msg_failed = 'Current password is wrong! </br> &nbsp;&nbsp;&nbsp; Maybe user is locked.';

// If user is not allowed to change password
$msg_notallowed = 'User is not allowed to change password.';

// If value for Email is not valid
$msg_emailnotvalid = 'Not a valid e-mail address.';

// Errors
$msg_error = 'Error! E-mail address not found';

?>