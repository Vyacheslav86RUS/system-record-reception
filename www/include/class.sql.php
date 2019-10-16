<?php


class db_sql{
	//объект подключения
	public $db;
	//подсчет количества записей
	public $d_select_count;
	//все найденные записи
	public $d_select;
	//ошибка
	public $error;
	//Конструктор, создает экземпляр класса
	function __construct(){
		GLOBAL $db;
		//конект к базе данных
		$this->db = mysql_connect($db['host'], $db['user'], $db['pass']);
		//коннект к названию базы
		mysql_select_db($db['name'], $this->db);
		//использование кодировки для получения и записи
		mysql_query('set character_set_client="utf8"');
		mysql_query('set character_set_results="utf8"');
		$this->d_select_count = - 1;
		$this->d_select_count_queries = 0;
		$this->error = false;
	}
	//Деструктор закрываем соединение
	function __destruct(){
		mysql_close($this->db);
	}
    //функция выборки данных из таблиц
	public function db_sql_select($what, $table, $other = ''){
		$this->d_select_count = 0;//количество записей
		$this->error = false;//ошибка
		if($what == '') $what = '*';//если выбираемые значения пусты
		if($table == '') return false;//проверка таблицы
		if($what == 'count(*)') $what = 'count(*) as coun';//если нужны подсчет всех записей
		if($this->d_select = mysql_query('SELECT '.$what.' FROM '.$table.' '.$other, $this->db)){
			//успешное выполнение
			if($what == 'count(*) as coun'){
				//если были выбраны все записи
				$rows = mysql_fetch_array($this->d_select);
				$this->d_select_count = $rows['coun'];
				$this->d_select_count_queries++;
				return true;
			} else {
				//если были выбраны конкретные значения записи
				$this->d_select_count = mysql_num_rows($this->d_select);
				$this->d_select_count_queries++;
				return true;
			}
		} else {
			//ошибка при выполнение запроса
			$this->error = mysql_error($this->db);
			return false;
		}
	}
    //Функция обновления данных в таблицах
	public function db_sql_update($table, $set, $where){
		if($table == '') return false;//проверка имени таблицы
		if($set == '') return false;//проверка значений для обновления
		if(mysql_query('UPDATE '.$table.' SET '.$set.' '.$where, $this->db)){
			//успешное выполнение
			$this->d_select_count_queries++;
			return true;
		} else {
			//ошибка при выполнение запроса
			$this->error = mysql_error($this->db);
			return false;
		}
	}
	//функция удаления данных
	public function db_sql_delete($table, $where){
		if($table == '') return false;//проверка имени таблицы
		//создаем запрос
		if(mysql_query("DELETE FROM ".$table." ".$where, $this->db)){
			//успешное выполнение
			$this->d_select_count_queries++;
			return true;
		} else {
			//ошибка при выполнение запроса
			$this->error = mysql_error($this->db);
			return false;
		}
	}
	//функция записи данных в таблицы
	public function db_sql_insert($table, $values, $duplicate = ''){
		if($table == '') return false;//проверка имени таблицы
		if($values == '') return false;//проверка значений
		if($duplicate != '')			$duplicate = ' ON DUPLICATE KEY UPDATE '.$duplicate;//если существует дубликат
		//создаем запрос
		if(mysql_query("INSERT INTO ".$table." VALUES(".$values.")".$duplicate, $this->db)){
			//успешное выполнение
			$this->d_select_count_queries++;
			return true;
		} else {
			//ошибка при выполнение запроса
			$this->error = mysql_error($this->db);
			return false;
		}
	}
	//функция убирает все запрещенные символы
	public function escape_string($str){
		//get_magic_quotes_gpc - получает текущую активную установку конфигурации "магических" кавычек gpc.
		if(get_magic_quotes_gpc())			$str = stripslashes($str);//убираем экронирование
		//заменяем запрещенные символы на пустоту
		$str = str_replace("\r", '', $str);
		$str = str_replace("\n", '', $str);
		$str = str_replace("\\", '', $str);
		$str = str_replace("~", '', $str);
		$str = str_replace("'", '&#39;', $str);
		//возвращаем строку при этом экранируя символы для использования в SQL запросе
		return mysql_real_escape_string($str);
	}
	//функция получения последней записи
	public function last_insert(){
		//возвращает id последней записи
		return mysql_insert_id($this->db);
	}
}
?>