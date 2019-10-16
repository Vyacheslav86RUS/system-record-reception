<?php
//Проверяем сессию
if(!isset($_SESSION)){
  //Если сессия не существует создаем
  session_start();
}
//Если не администратор
if($_SESSION['admin'] != 1){
  //visitor
  //Меню
	$html = '<nav role="navigation" class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="http://record.sl/page/main.php" class="navbar-brand">record.sl</a>
    </div>
    <!-- Collection of nav links and other content for toggling -->
    <div id="navbarCollapse" class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
        <li><a onclick="get_specialist_list();" style="cursor:pointer">Список cпециалистов</a></li>
        <li><a onclick="get_visit_history();" style="cursor:pointer">История посещений</a></li>
        <li><a onclick="get_visit();" style="cursor:pointer">Отозвать запись на прием</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="http://record.sl">Выход</a></li>
      </ul>
    </div>
  </div>
</nav>';
} else {
  //admin
    //Меню
    $html = '<nav role="navigation" class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="http://record.sk/page/main.php" class="navbar-brand">record.sl</a>
    </div>
    <!-- Collection of nav links and other content for toggling -->
    <div id="navbarCollapse" class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
        <li><a onclick="get_user_list();" style="cursor:pointer">Посетители</a></li>
        <li><a onclick="get_schedule();" style="cursor:pointer">Мой рабочий график</a></li>
        <li><a onclick="get_statistics_visit();" style="cursor:pointer">Статистика посещений</a></li>
        <li><a onclick="get_statistics_time();" style="cursor:pointer">Статистика времени</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a onclick="settings();" style="cursor:pointer">Настройки</a></li>
        <li><a href="http://record.sl">Выход</a></li>
      </ul>
    </div>
  </div>
</nav>';
}
//Вывод меню на страницу
echo $html;
?>