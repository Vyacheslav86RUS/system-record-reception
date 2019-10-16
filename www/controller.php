<?php
//Указываем заголовок для страницы
header('Content-Type: text/html; charset=utf-8');
//Подключаем файл конфигурации для подключения к БД
require_once($_SERVER['DOCUMENT_ROOT'].'/include/confing.php');
//Подключаем класс работы с БД
require_once($_SERVER['DOCUMENT_ROOT'].'/include/class.sql.php');
//Включаем сессию
session_start();
//создаем экземпляр класса работы с БД
$sql = new db_sql();
//Ловим get запрос по id
switch ($_GET['id']) {
	//регистрация
	case 'reg':
		//Проверка post данных
		if(isset($_POST)){
			//Результат выполнения
			$result = registration($_POST);
			//если результат положительный
			if(isset($result['ok'])){
				//записываем в сессию id пользователя
				$_SESSION['uid'] = intval($result['ok']);
			}
		} else {
			//Отправляем ошибку
			$result = array('error'=>'not found POST');
		}
		break;
	//вход в систему
	case 'login':
		//Проверка post данных
		if(isset($_POST)){
			//Результат выполнения
			$result = login($_POST);
			//Записываем права в сессию
			$_SESSION['admin'] = $result['admin'];
			//Записываем id пользователя
			$_SESSION['uid'] = $result['id'];
			//записываем данные пользователя
			$_SESSION['user']= $result['user'];
		} else {
			//Отправляем ошибку
			$result = array('error'=>'not found POST');
		}
		break;
	//получение списка посетителей
	case 'get_user_list':
		//проверка прав в системе
		if($_SESSION['admin'] == 1){
			//если есть права администратора отправляем результат
			$result = get_user_list();
		} else {
			//иначе отправляем ошибку об отсутсвие прав администратора
			$result = array('error'=>'not found rights');
		}
		break;
	//получение списка специалистов
	case 'get_specialist_list':
		//отправляем список
		$result = get_specialist_list();
		break;
	//получение списка времени приема специалиста
	case 'get_specialist_timetable':
		//проверяем id пользователя
		if(isset($_SESSION['uid'])){
			//список приема к специалисту
			$result = get_specialist_timetable($_POST['sid']);
		} else {
			//отправляем ошибку не найденого пользователя
			$result = array('error'=>'not found uid');
		}
		break;
	//получение своего списка времени приема
	case 'get_specialist_timetable_admin':
		//проверка прав в системе
		if($_SESSION['admin']==1){
			//записываем id посетителя, для записи на прием
			$_SESSION['reception']['uid'] = $_POST['uid'];
			//если есть права администратора отправляем результат
			$result = get_specialist_timetable($_SESSION['uid']);
		} else {
			//иначе отправляем ошибку об отсутсвие прав администратора
			$result = array('error'=>'not found rights');
		}
		break;
	//получение свой определенный прием
	case 'get_specialist_timetable_one':
		//проверяем id пользователя
		if(isset($_SESSION['uid'])){
			//отправляем время одного приема специалиста
			$result = get_specialist_timetable_one($_POST['schid'],$_POST['uid'],$_SESSION['uid']);
		} else {
			//отправляем ошибку не найденого пользователя
			$result = array('error'=>'not found uid');
		}
		break;
	//запись на прием к специалисту
	case 'set_reception':
		//проверка прав в системе
		if($_SESSION['admin'] == 1){
			//получение данных специалиста
			$sid = get_specialist_data($_POST['schid']);
			//проверка id специалиста
			if($sid > 0){
				//проверка id посетителя для записи к себе на прием
				if(isset($_SESSION['reception']['uid'])){
					//результат записи на прием
					$result = set_reception($_SESSION['reception']['uid'],$sid,$_POST);
				} else {
					//не найден посетитель
					$result = array('error'=>'not found pid');
				}
			} else {
				//отправляем ошибку об не существованиии специалиста
				$result = array('error'=>'not found sid');
			}
		} else { //если не администратор
			//получение данных специалиста
			$sid = get_specialist_data($_POST['schid']);
			//проверка id специалиста
			if($sid > 0){
				//результат записи на прием
				$result = set_reception($_SESSION['uid'],$sid,$_POST);
			} else {
				//отправляем ошибку об не существованиии специалиста
				$result = array('error'=>'not found sid');
			}
		}
		break;
	//получение истории визитов к специалисту
	case 'get_visit_history':
		//проверяем id пользователя
		if(isset($_SESSION['uid'])){
			//список визитов к врачам
			$result = get_visit_history($_SESSION['uid']);
		} else {
			//отправляем ошибку не найденого пользователя
			$result = array('error'=>'not found uid');
		}
		break;
	//получение рабочего графика
	case 'get_schedule':
		//проверка прав в системе
		if($_SESSION['admin'] == 1){
			//список рабочих дней
			$result = get_schedule();
		} else {
			//иначе отправляем ошибку об отсутсвие прав администратора
			$result = array('error'=>'not found rights');
		}
		break;
	//иначе отправляем ошибку об отсутсвие прав администратора
	case 'set_schedule':
		//проверка прав в системе
		if($_SESSION['admin'] == 1){
			//устанавливаем новый рабочий день
			$result = set_schedule($_POST);
		} else {
			//иначе отправляем ошибку об отсутсвие прав администратора
			$result = array('error'=>'not found rights');
		}
		break;
	//удаление посетителя с приема
	case 'del_reception_user':
		//проверка прав в системе
		if($_SESSION['admin'] == 1){
			//результат удаления
			$result = del_reception_user($_POST);
		} else {
			//иначе отправляем ошибку об отсутсвие прав администратора
			$result = array('error'=>'not found rights');
		}
		break;
	//удаление себя с приема
	case 'del_reception':
		//проверка прав в системе
		if(isset($_SESSION['uid'])){
			//результат удаления
			$result = del_reception($_POST,$_SESSION['uid']);
		} else {
			//иначе отправляем ошибку об отсутсвие прав администратора
			$result = array('error'=>'not found uid');
		}
		break;
	//статистика
	case 'statistics':
		//проверка прав в системе
		if($_SESSION['admin'] == 1){
			//результат статистики
			$result = statistics($_POST['type'],$_SESSION['uid']);
		} else {
			//иначе отправляем ошибку об отсутсвие прав администратора
			$result = array('error'=>'not found rights');
		}
		break;
	//получение настроек
	case 'get_settings':
		//проверка прав в системе
		if($_SESSION['admin']==1){
			//результат настроек
			$result = get_settings($_SESSION['uid']);
		} else {
			//иначе отправляем ошибку об отсутсвие прав администратора
			$result = array('error'=>'not found rights');
		}
		break;
	//обновление настроек
	case 'update_settings':
		//проверка прав в системе
		if($_SESSION['admin']==1){
			//результат обновления настроек
			$result = update_settings($_POST,$_SESSION['uid']);
		} else {
			//иначе отправляем ошибку об отсутсвие прав администратора
			$result = array('error'=>'not found rights');
		}
		break;
	default:
		//если не нашли запрос отправляем ошибку
		$result = array('error'=>'Не верный запрос');
		break;
}

//Вход в систему
function login($data){
	//глобальный объект работы с БД
	global $sql;
	//результат
	$ans = array();
	//запрос на существования логина и пароля
	$sql->db_sql_select('id,admin','user','WHERE login=\''.$sql->escape_string($data['login']).'\' && password=\''.$sql->escape_string(md5($data['pass'])).'\'');
	//если запись существует
	if($sql->d_select_count > 0){
		//считываем данные с запрос
		$row = mysql_fetch_array($sql->d_select,MYSQL_ASSOC);
		//записываем данные в результат
		$ans = array('id'=>intval($row['id']), 'admin'=>intval($row['admin']),'user'=>$data['login']);
	} else {
		//иначе отправляем ошибку об отсутсвие пользователя с таким логином и паролем
		$ans = array('error'=>'not found user');
	}
	//возврат результата
	return $ans;
}
//Регистрация в системе
function registration($data){
	//глобальный объект работы с БД
	global $sql;
	//результат работы
	$ans = array();
	//переменная для логина
	$login = '';
	//переменная для пароля
	$password = '';
	//переменная для email
	$email = '';
	//переменная для ФИО
	$fio = '';
	//флаг совпадения паролей
	$flag_pass = false;
	//флаг проверки существования пользователя
	$match = true;
	//права администратора
	$admin = 0;
	//проверка существования логина и он не пустой
	if(isset($data['Login']) && $data['Login'] != ''){
		$login = $data['Login'];
		//проверяем валидность логина с помощью регулярного выражения
		if (!preg_match('/^[a-zA-Z]+$/', $login)){
			//не корректный логин
		   return array('error'=>'not valid login');
		}
	} else {
		//не все поля заполнены
		return array('error'=>'not all fields');
	}
	//проверка существования email и не он не пустой
	if(isset($data['inputEmail']) && $data['inputEmail'] != ''){
		$email = $data['inputEmail'];
		//проверяем валидность email адреса с помощью регулярного выражения
		if (!preg_match("/[-0-9a-z_]+@[-0-9a-z_]+\.[a-z]{2,6}/i", $email)){
			//не корректный email
			return array('error'=>'not valid email');
		}
	} else {
		//не все поля заполнены
		return array('error'=>'not all fields');
	}
	//проверка существования паролей и они не пустые
	if(isset($data['inputPassword']) && isset($data['confirmPassword']) && $data['inputPassword'] != '' && $data['confirmPassword'] != ''){
		//проверка на схожесть паролей
		if($data['inputPassword'] == $data['confirmPassword']){
			//проверка валидности пароля
			if (!preg_match('/^[0-9a-zA-Z]+$/', $data['inputPassword'])){
				//не валидный пароль
			   return array('error'=>'not valid pass');
			} else {
				//шифруем пароль для хранения в БД
				$password = md5($data['inputPassword']);
				//устанавливаем флаг корекктности пароля
				$flag_pass = true;
			}
		} else {
			//пароли не совпадают
			$ans = array('error'=>'passwords do not match');
			$match = false;
		}
	} else {
		//не все поля заполнены
		return array('error'=>'not all fields');
	}
	//проверка существования ФИО
	if(isset($data['inputfio'])){
		$fio = $data['inputfio'];
		//проверка валидности ФИО с помощью регулярного выражения
		if (!preg_match('/[а-яА-Я]+/', $fio)){
			//ФИО не валидно
		   return array('error'=>'not valid fio');
		}
	}
	//проверка прав администратора
	if(isset($data['checkboxAdmin'])){
		$admin = intval($data['checkboxAdmin']);
	}
	//если все данные верны
	if($match){
		//запрос на существования пользователя
		$sql->db_sql_select('id','user','WHERE login=\''.$sql->escape_string($login).'\' && password=\''.$sql->escape_string($password).'\'');
		//если есть данные
		if($sql->d_select_count > 0){
			//устанавливаем флаг о существовании пользователя
			$flag_pass = false;
			//такой пользователь уже существует
			$ans = array('error'=>'this user register');
		}
	}
	//проверка правильности паролей
	if($flag_pass){
		//записываем нового пользователя
		if($sql->db_sql_insert('user(login,fio,password,email,admin,d_create)','\''.$sql->escape_string($login).'\',\''.$sql->escape_string($fio).'\',\''.$sql->escape_string($password).'\',\''.$sql->escape_string($email).'\','.$admin.','.time())){
			//если все прошло успешно
			$ans = array('ok'=>$sql->last_insert());
		} else {
			//иначе ошибка записи данных
			$ans = array('error'=>'insert error');
		}
	}
	return $ans;
}

//Получение списка поциентов
function get_user_list(){
  //глобальный объект работы с БД
  global $sql;
  //результат работы
  $ans = array();
  //запрос на получения посетителей
  $sql->db_sql_select('id,email,login,fio,d_create','user','WHERE id != '.$_SESSION['uid'].' && admin = 0 ORDER BY id');
  //если пришли данные
  if($sql->d_select_count > 0){
  	//сохраняем результат запроса в переменную
  	$u = $sql->d_select;
  	//цикл считывания данных
  	while ($rows = mysql_fetch_array($u)) {
  		//список посетителей
  		$ans[] = array('id'=>intval($rows['id']),
  					   'fio'=>$rows['fio'],
  					   'email'=>$rows['email'],
  					   'login'=>$rows['login'],
  					   'd_create'=>get_convert_unixtime_to_data(intval($rows['d_create'])));
  	}
  }
  //возврат результата
  return $ans;
}

function get_specialist_data($schid){
	//глобальный объект работы с БД
	global $sql;
	//результат работы
	$sid = array();
	//id рабочего графика
	$schid = intval($schid);
	//запрос получения данных
	$sql->db_sql_select('sid,date','schedule','WHERE id='.$schid);
	//если есть данные
	if($sql->d_select_count > 0){
		//считываем данные
		$row = mysql_fetch_array($sql->d_select);
		//данные специалиста
		$sid = array('sid'=>intval($row['sid']),'date'=>date('d.m.y',$row['date']));
	}
	//возврат результата
	return $sid;
}

function set_reception($uid,$sid_data,$data){
	//глобальный объект работы с БД
	global $sql;
	//результат работы
	$ans = array();
	//id пользователя
	$uid = intval($uid);
	//проверка существования времени
	if(!isset($data['ptime'])){
		//ошибка не найденого времени
		return array('error'=>'not found ptime');
	}
	//id рабочего графика
	if(!isset($data['schid'])){
		//ошибка об отсутсвии id рабочего графика
		return array('error'=>'not found schid');
	}
	//проверка на количество записей в рабочем графике
	if(check_patient($data['schid'])){
		//запрос на запись
		$sql->db_sql_select('id','reception','WHERE pid='.$uid.' && schid='.$data['schid']);
		//если есть данные
		if($sql->d_select_count > 0){
			//ошибка посетитель уже записан на прием
			$ans = array('error'=>'you is reception');
		} else {
			//разбиваем время на массив
			$times = explode(':', $data['ptime']);
			//разбиваем дату на массив
			$d = explode('.', $sid_data['date']);
			//создаем дату в unix time mktime(hours,minut,second,month,day,year)
			$date = mktime($times[0],$times[1],$times[2],$d[1],$d[0],$d[2]);
			//запись на прием
			if($sql->db_sql_insert('reception(pid,sid,schid,ptime,coment)',$uid.','.$sid_data['sid'].','.$data['schid'].','.$date.',\''.$sql->escape_string($data['coment']).'\'')){
				//успешное завершение записи
				$ans = array('ok'=>$sql->last_insert(),'sid'=>$sid_data['sid']);
			} else {
				//ошибка записи
				$ans = array('error'=>'insert');
			}
		}
	} else $ans = array('error'=>'count is complated'); //количество записей равен количеству человек, которыхможет принять специалист
	//возврат результата
	return $ans;
}

//Получение рабочих дней специалиса
function get_specialist_timetable($sid){
	//глобальный объект работы с БД
	global $sql;
	//результат работы
	$ans = array();
	//id специалиста
	$sid = intval($sid);
	//запрос на рабочий график специалиста
	$sql->db_sql_select('id,start_time,end_time,time_patient,date','schedule','WHERE sid ='.$sid.' ORDER BY date DESC');
	//если есть результат
	if($sql->d_select_count > 0){
		//записываем результат запроса в переменную
		$specialist = $sql->d_select;
		//цикл получения данных
		while ($rows = mysql_fetch_array($specialist)) {
			//данные по графику
			$ans[] = array('id'=>intval($rows['id']),
						   'start'=>date('H:i:s',$rows['start_time']),
						   'end'=>date('H:i:s',$rows['end_time']),
						   'date'=>date('d.m.y',$rows['date']),
						   'tcol'=>intval($rows['time_patient']),
						   //проверка количества записей на прием
						   'reception'=>check_patient($rows['id']),
						   'calendar'=>array());
		}
		//цикл прохождения по графику
		foreach ($ans as $key => $value) {
			//если еще можно записаться
			if($value['reception']){
				//время начала рабочего графика
				$date_start = $value['start'];
				//результат списка времени
				$arr_data = array();
				//разбиваем дату на массив
				$date = explode('.', $value['date']);
				//пока время начала меньше или равно времени конца
				while($date_start <= $value['end']){
					//разбиваем время
					$t = explode(':', $date_start);
					//запрашиваем, занято ли время mktime(hours,minut,second,month,day,year)
					$sql->db_sql_select('id','reception','WHERE sid='.$sid.' && schid='.$value['id'].' && ptime='.mktime($t[0],$t[1],$t[2],$date[1],$date[0],$date[2]));
					//если есть данные
					if($sql->d_select_count > 0){
						//время занято
						$arr_data[$date_start] = 1;
					} else {
						//время свободно
						$arr_data[$date_start] = 0;
					}
					//прибавляем минуты к начальному времени
					$date_start = time_add_min($date_start,$value['tcol']);
				}
				//записываем список времени в результат
				$ans[$key]['calendar']=$arr_data;
			}
		}
	}
	//возврат результата
	return $ans;
}
//Получение рабочего дня специалиста
function get_specialist_timetable_one($schid,$uid,$sid){
	//глобальный объект работы с БД
	global $sql;
	//результат работы
	$ans = array();
	//id графика
	$schid = intval($schid);
	//id посетителя
	$uid = intval($uid);
	//id специалиста
	$sid = intval($sid);
	//удаляем запись на прием посетителя
	$sql->db_sql_delete('reception','WHERE pid='.$uid.' && schid='.$schid);
	//запрос на рабочий график специалиста
	$sql->db_sql_select('id,start_time,end_time,time_patient,date','schedule','WHERE id ='.$schid);
	//если есть результат
	if($sql->d_select_count > 0){
		//записываем результат запроса в переменную
		$specialist = $sql->d_select;
		//получения данных
		$rows = mysql_fetch_array($specialist);
		//данные по графику
		$ans[] = array('id'=>intval($rows['id']),
					 'start'=>date('H:i:s',$rows['start_time']),
					 'end'=>date('H:i:s',$rows['end_time']),
					 'date'=>date('d.m.y',$rows['date']),
					 'tcol'=>intval($rows['time_patient']),
					 'reception'=>check_patient($rows['id']),
					 'calendar'=>array());
		//цикл прохождения по графику
		foreach ($ans as $key => $value) {
			//если еще можно записаться
			if($value['reception']){
				//время начала рабочего графика
				$date_start = $value['start'];
				//результат списка времени
				$arr_data = array();
				//разбиваем дату на массив
				$date = explode('.', $value['date']);
				//пока время начала меньше или равно времени конца
				while($date_start <= $value['end']){
					//разбиваем время
					$t = explode(':', $date_start);
					//запрашиваем, занято ли время mktime(hours,minut,second,month,day,year)
					$sql->db_sql_select('id','reception','WHERE sid='.$sid.' && schid='.$value['id'].' && ptime='.mktime($t[0],$t[1],$t[2],$date[1],$date[0],$date[2]));
					//если есть данные
					if($sql->d_select_count > 0){
						//время занято
						$arr_data[$date_start] = 1;
					} else {
						//время свободно
						$arr_data[$date_start] = 0;
					}
					//прибавляем минуты к начальному времени
					$date_start = time_add_min($date_start,$value['tcol']);
				}
				//записываем список времени в результат
				$ans[$key]['calendar']=$arr_data;
			}
		}
	}
	//возврат результата
	return $ans;
}
//Получение истории посищения
function get_visit_history($uid){
	//глобальный объект работы с БД
	global $sql;
	//результат работы
	$ans = array();
	//id пользователя
	$uid = intval($uid);
	//запрос на получения времени посещения специалистов
	$sql->db_sql_select('u.specialist,u.fio,u.email,r.ptime,r.coment,r.schid','reception as r',
		'LEFT JOIN user as u ON r.sid = u.id
		 WHERE r.pid='.$uid.' ORDER BY r.ptime DESC');
	//если есть данные
	if($sql->d_select_count > 0){
		//записываем результат запроса
		$h = $sql->d_select;
		//цикл получения данных
		while ($rows = mysql_fetch_array($h)) {
			//список посещения специалистов
			$ans[] = array('specialist'=>$rows['specialist'],
					       'fio'=>$rows['fio'],
					       'email'=>$rows['email'],
					       'date'=>date('d.m.y H:i:s',$rows['ptime']),
					       'coment'=>$rows['coment'],
					       'schid'=>intval($rows['schid']));
		}
	}
	//возврат результата
	return $ans;
}
//список специалистов
function get_specialist_list(){
	//глобальный объект работы с БД
	global $sql;
	//результат работы
	$ans = array();
	//запрос на получения специалистов в системе
	$sql->db_sql_select('id,specialist,fio,email','user','WHERE admin != 0');
	//если есть данные
	if($sql->d_select_count > 0){
		//записываем результат запроса
		$s = $sql->d_select;
		//цикл получения данных
		while ($rows = mysql_fetch_array($s)) {
			//список специалистов
			$ans[] = array('id'=>intval($rows['id']),'specialist'=>$rows['specialist'],'fio'=>$rows['fio'],'email'=>$rows['email']);
		}
	}
	//возврат результата
	return $ans;
}
//получение рабочих дней специалиста
function get_schedule(){
	//глобальный объект работы с БД
	global $sql;
	//результат работы
	$ans = array();
	//запрос на получения своего рабочего графика
	$sql->db_sql_select('s.id,
						 s.start_time,
						 s.end_time,
						 s.count_patient,
						 s.time_patient,
						 s.date','schedule as s','WHERE s.sid='.intval($_SESSION['uid']).' ORDER BY s.date DESC');
	//если есть данные
	if($sql->d_select_count > 0){
		//записываем результат запроса
		$s = $sql->d_select;
		//цикл получения данных
		while ($rows = mysql_fetch_array($s)) {
			//список рабочих графиков
			$ans[] = array('id'=>intval($rows['id']),
						   'start'=>get_convert_unixtime_to_data($rows['start_time']),
						   'end'=>get_convert_unixtime_to_data($rows['end_time']),
						   'col'=>intval($rows['count_patient']),
						   'tcol'=>intval($rows['time_patient']));
		}
	}
	//цикл по рабочим графикам
	foreach ($ans as $key => $value) {
		//посетители в рабочем графике
		$user = array();
		//запрос посетителей в конкретном рабочем дне
		$sql->db_sql_select('u.id,u.fio,u.email,r.ptime,r.coment','reception as r','LEFT JOIN user as u ON r.pid = u.id WHERE r.schid='.$value['id'].' ORDER BY r.ptime');
		//если есть данные
		if($sql->d_select_count > 0){
			//записываем результат запроса
			$u = $sql->d_select;
			//цикл получения данных
			while ($rows = mysql_fetch_array($u)) {
				//список посетителей
				$user[] = array('id'=>intval($rows['id']),'fio'=>$rows['fio'],'email'=>$rows['email'],'ptime'=>date('H:i:s',$rows['ptime']),'coment'=>$rows['coment']);
			}
		}
		//запись списка посетителей в рабочий график
		array_push($ans[$key], $user);
	}
	//возврат результата
	return $ans;
}
//создание рабочего дня
function set_schedule($data){
	//глобальный объект работы с БД
	global $sql;
	//результат работы
	$ans = array();
	//проверка существования даты
	if(!isset($data['date'])){
		//дата не найдена
		return array('error'=>'not found date');
	}
	//проверка количества человек
	if(!isset($data['col'])){
		//количество не найдено
		return array('error'=>'not found col');
	}
	//время на одного человека
	if(!isset($data['time_col'])){
		//не найдено время на одного человека
		return array('error'=>'not found tcol');
	}
	//время начала работы
	if(!isset($data['start_time'])){
		//не найдено время начала работы
		return array('error'=>'not found start time');
	}
	//время конца работы
	if(!isset($data['end_time'])){
		//не найдено конец времени работы
		return array('error'=>'not found end time');
	}
	//разбиваем дату на массив
	$date = explode('.',$data['date']);
	//разбиваем время начала работы на массив
	$ptm_stime1 = explode(':', $data['start_time']);
	//разбиваем конечное время работы на массив
	$ptm_stime2 = explode(':', $data['end_time']);
	//устанавливаем время начала работы в полный unix time с учетом дд.мм.гг
	$stime1 = mktime($ptm_stime1[0],$ptm_stime1[1],$ptm_stime1[2],$date[1],$date[0],$date[2]);
	//устанавливаем конечное время работы в полный unix time с учетом дд.мм.гг
	$stime2 = mktime($ptm_stime2[0],$ptm_stime2[1],$ptm_stime2[2],$date[1],$date[0],$date[2]);
	//устанавливаем дату в unix time с учетом чч.мм.сс
	$work_date = mktime(0,0,0,$date[1],$date[0],$date[2]);
	//запись рабочего дня
	if($sql->db_sql_insert('schedule(sid,start_time,end_time,count_patient,time_patient,date)',intval($_SESSION['uid']).','.$stime1.','.$stime2.','.intval($data['col']).','.intval($data['time_col']).','.$work_date)){
		//успешная запись
		$ans = array('ok'=>$sql->last_insert());
	} else {
		//ошибка записи
		$ans = array('error'=>'insert');
	}
	//возврат результата
	return $ans;
}

//Проверка количества записи на прием
function check_patient($schid){
	//флаг результата
	$flag = true;
	//id рабочего дня
	$schid = intval($schid);
	//количество человек которое может принять специалист в определенный день
	$count = get_count_patient($schid);
	//количество записанных человек на прием
	$count_patient = get_count_patient_to_reception($schid);
	//проверка
	if($count_patient > $count){
		//устанавливаем флаг
		$flag = false;
	}
	//возврат результата
	return $flag;
}
//Получение количества записанных на прием
function get_count_patient_to_reception($schid){
	//глобальный объект работы с БД
	global $sql;
	//id рабочего дня
	$schid = intval($schid);
	//результат работы
	$count = 0;
	//запрос количества записанных человек
	$sql->db_sql_select('COUNT(id)','reception','WHERE schid='.$schid);
	//если есть данные
	if($sql->d_select_count > 0){
		//получение данных
		$row = mysql_fetch_array($sql->d_select);
		//количество
		$count = intval($row['COUNT(id)']);
	}
	//возврат результата
	return $count;
}
//Получение количества на прием, которое указал специалист
function get_count_patient($schid){
	//глобальный объект работы с БД
	global $sql;
	//id рабочего дня
	$schid = intval($schid);
	//результат работы
	$count = 0;
	//запрос количества человек, которое может принять специалист
	$sql->db_sql_select('count_patient','schedule','WHERE id = '.$schid);
	//если есть данные
	if($sql->d_select_count > 0){
		///получение данных
		$row = mysql_fetch_array($sql->d_select);
		//количество человек
		$count = intval($row['count_patient']);
	}
	//возврат результата
	return $count;
}
//Прибавление минуты к времени
function time_add_min($time, $min){
	//разбиваем время на соответсвующую переменную
    list($h, $m, $s) = explode(':', $time);
    //расчет часа
    $h = ($h + floor(($m + $min) / 60)) % 24;
    //расчет минут
    $m = ($m + $min) % 60;
    //возврат нового времени, str_pad - дополняет к строке строку(STR_PAD_LEFT - слева)
    return str_pad($h, 2, '0', STR_PAD_LEFT).':'.str_pad($m, 2, '0', STR_PAD_LEFT).':'.$s;
}

//удаление с приема
function del_reception_user($data){
	//глобальный объект работы с БД
	global $sql;
	//результат работы
	$ans = array();
	//проверка id посетителя
	if(!isset($data['uid'])){
		//посетитель не найден
		return array('error'=>'not found uid');
	}
	//id рабочего дня
	if(!isset($data['schid'])){
		//не найден рабочий день
		return array('error'=>'not found schid');
	}
	//удаление из таблицы
	if($sql->db_sql_delete('reception','WHERE pid='.intval($data['uid']).' && schid='.intval($data['schid']))){
		//успешное удаление
		$ans = array('ok'=>1);
	} else {
		//ошибка удаления
		$ans = array('error'=>'delete');
	}
	//возврат результата
	return $ans;
}

//удаление с приема
function del_reception($data,$uid){
	//глобальный объект работы с БД
	global $sql;
	//результат работы
	$ans = array();
	//проверка id посетителя
	//id рабочего дня
	if(!isset($data['schid'])){
		//не найден рабочий день
		return array('error'=>'not found schid');
	}
	//удаление из таблицы
	if($sql->db_sql_delete('reception','WHERE pid='.intval($uid).' && schid='.intval($data['schid']))){
		//успешное удаление
		$ans = array('ok'=>1);
	} else {
		//ошибка удаления
		$ans = array('error'=>'delete');
	}
	//возврат результата
	return $ans;
}
//статистика
function statistics($type,$sid){
	//глобальный объект работы с БД
	global $sql;
	//результат работы
	$ans = array();
	//id специалиста
	$sid = intval($sid);
	//выбор типа
	switch ($type) {
		//статистика по посещению
		case 'visit':
			//запрос получения посетителей
			$sql->db_sql_select('u.id,u.fio,u.email,r.coment','user as u','LEFT JOIN reception as r ON u.id = r.pid WHERE u.admin = 0 GROUP BY u.id');
			//если есть данные
			if($sql->d_select_count > 0){
				//записываем результат запроса в переменную
				$u = $sql->d_select;
				//цикл получения данных
				while ($row = mysql_fetch_array($u)) {
					//список посетителей
					$ans[] = array('id'=>intval($row['id']),
								   'fio'=>$row['fio'],
								   'email'=>$row['email'],
								   'count'=>0,
								   'coment'=>$row['coment']);
				}
				//проход по списку посетителей
				foreach ($ans as $key => $value) {
					//запрос на получнеия количества посещений посетитля к специалисту
					$sql->db_sql_select('COUNT(id)','reception','WHERE sid='.$sid.' && pid='.$value['id']);
					//если есть данные
					if($sql->d_select_count > 0){
						//записываем результат запроса в переменную
						$c = $sql->d_select;
						//получение данных
						$row = mysql_fetch_array($c);
						//количество посещений к специалисту
						$ans[$key]['count'] = intval($row['COUNT(id)']);
					}
				}
			}
			//сортировка данных в обратном порядку с сравнением всех полей
			ksort($ans);
			break;
		//статистика по времени пользующее наибольшей и наименьшей популярностью
		case 'time':
			//список времени
			$ptime = array();
			//список пользователей
			$user = array();
			//запрос на получение времени записи и данных посетителя
			$sql->db_sql_select('r.ptime,u.id,u.fio,u.email,r.coment','reception as r','LEFT JOIN user as u ON r.pid = u.id WHERE r.sid='.$sid);
			//если есть данные
			if($sql->d_select_count > 0){
				//записываем результат запроса в переменную
				$p = $sql->d_select;
				//цикл получения данных
				while ($row = mysql_fetch_array($p)) {
					//количество выбранного времени
					$ptime[date('H:i:s',$row['ptime'])]+=1;
					//посетитель
					$user[date('H:i:s',$row['ptime'])][] = array('id'=>intval($row['id']),'fio'=>$row['fio'],'email'=>$row['email'],'ptime'=>date('d.m.y H:i:s',$row['ptime']),'coment'=>$row['coment']);
				}
				//проход по списку времени
				foreach ($ptime as $key => $value) {
					//результат
					$ans[] = array('ptime'=>$key,'count'=>$value,'visitor'=>$user[$key]);
				}
			}
			//сортировка данных в обратном порядку с сравнением всех полей
			array_multisort($ans,SORT_ASC, SORT_REGULAR);
			break;
	}
	//возврат результата
	return $ans;
}

//конвертация даты
function get_convert_unixtime_to_data($time){
	//возврат результата
	return date('d.m.y H:i:s', $time);
}
//получение настроек
function get_settings($sid){
	//глобальный объект работы с БД
	global $sql;
	//результата работы
	$ans = array();
	//запрос на получения данных
	$sql->db_sql_select('specialist,fio,email,login','user','WHERE id='.$sid.' && admin = 1');
	//если есть данные
	if($sql->d_select_count > 0){
		//записываем результат запроса в переменную
		$a = $sql->d_select;
		//получение данных
		$row = mysql_fetch_array($a);
		//данные
		$ans = array('specialist'=>$row['specialist'],'fio'=>$row['fio'],'email'=>$row['email'],'login'=>$row['login']);
	} else {
		$ans = array('error'=>'not found sid');
	}
	//возврат результата
	return $ans;
}
//обновление данных специалиста
function update_settings($data,$uid){
	//глобальный объект работы с БД
	global $sql;
	//результата работы
	$ans = array();
	//id пользователя
	$uid = intval($uid);
	//проверка должности на пустоту
	if($data['inputspecialist'] != ''){
		//массив обновления (чистим строку от запрещенных символов)
		$update[] = 'specialist = \''.$sql->escape_string($data['inputspecialist']).'\'';
	}
	//проверка ФИО на пустоту
	if($data['inputfio'] != ''){
		//проверка валидности ФИО с помощью регулярного выражения
		if (preg_match('/^[а-яА-Я]+$/', $data['inputfio'])){
			//массив обновления (чистим строку от запрещенных символов)
		   $update[] = 'fio = \''.$sql->escape_string($data['inputfio']).'\'';
		}
	}
	//цикл по массиву обновления данных
	foreach ($update as $key => $value) {
		//если первый
		if($key == 0){
			//добавляем значения
			$str_update = $value;
		} else {
			//конкатенация строки обновления
			$str_update.=','.$value;
		}
	}
	//обновление данных
	if($sql->db_sql_update('user',$str_update,'WHERE id = '.$uid)){
		//успешное обновление
		$ans = array('ok'=>'update');
	} else {
		//ошибка обновления
		$ans = array('error'=>'update');
	}
	//возврат результата
	return $ans;
}

//возврат результата в формате JSON
print json_encode($result);

?>