<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Регистрация</title>

    <!-- Bootstrap -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/main.js"></script>
  </head>
  <body>
  <div class="container">
    <div class="row">
      <h2>Регистрация</h2>
      <div class="result"></div>
      <form class="form-horizontal">
        <div class="form-group">
          <label class="control-label col-xs-3" for="Login">Login:</label>
          <div class="col-xs-9">
            <input type="text" class="form-control" id="Login" name="Login" placeholder="Введите логин">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-xs-3" for="inputEmail">Email:</label>
          <div class="col-xs-9">
            <input type="email" class="form-control" id="inputEmail" name="inputEmail" placeholder="Email">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-xs-3" for="inputfio">ФИО:</label>
          <div class="col-xs-9">
            <input type="text" class="form-control" id="inputfio" name="inputfio" placeholder="fio">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-xs-3" for="inputPassword">Пароль:</label>
          <div class="col-xs-9">
            <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Введите пароль">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-xs-3" for="confirmPassword">Подтвердите пароль:</label>
          <div class="col-xs-9">
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Введите пароль ещё раз">
          </div>
        </div>
          <div class = "form-group">
            <div class="checkbox">
              <div class = "col-xs-offset-3 col-xs-10">
                <label>
                  <input type="checkbox" id="checkboxAdmin" name="checkboxAdmin">Я специалист
                </label>
              </div>
            </div>
          </div>
        <div class="form-group">
          <div class="col-xs-offset-3 col-xs-9">
            <input type="button" class="btn btn-primary" value="Регистрация" onclick="registration();">
            <input type="reset" class="btn btn-default" value="Очистить форму">
          <input type="button" class="btn btn-success" value="Назад" onclick="javascript:document.location.href='http://record.sl'">
          </div>
        </div>
      </form>
    </div>
  </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>-->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <!--<script src="js/bootstrap.min.js"></script>-->
  </body>
</html>