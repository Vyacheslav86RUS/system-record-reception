<?php
//Открываем сессию
  session_start();
  //Если существует админ
  if(isset($_SESSION['admin'])){
    //Удаляем переменную
    unset($_SESSION);
    //Закрываем сессию
    session_destroy();
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <title>Вход</title>

    <!-- Bootstrap -->
    <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
  </head>
  <div class="contaner" style="padding-top: 160px;">
    <div class="row">
        <div class="result"></div>
        <form class="form-horizontal">
         <div class="form-group">
          <label for="login" class="col-sm-4 control-label">Login</label>
          <div class="col-sm-4">
           <input type="text" class="form-control" id="login" name="login" placeholder="Login">
          </div>
         </div>
         <div class="form-group">
          <label for="pass" class="col-sm-4 control-label">Пароль</label>
          <div class="col-sm-4">
           <input type="password" class="form-control" id="pass" name="pass" placeholder="Пароль">
          </div>
         </div>
         <div class="form-group">
          <div class="col-sm-offset-4 col-sm-4">
           <button type="button" class="btn btn-primary" onclick="login_form();">Подтвердить</button>
           <span style="padding-left: 15px;"><a href="/page/registration.php">Зарегистрироваться</a></span>
           <!--<button type="button" class="btn btn-link" onclick="registration();"></button>-->
          </div>
         </div>
        </form>
    </div>
  </div>


  </body>
</html>