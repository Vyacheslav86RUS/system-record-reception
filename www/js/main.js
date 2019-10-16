//регистрация
function registration(){
	//jquery селектору с классом result пристовить пустое значение
	$('.result').html('');
	//получения из поля ввода значение введенного логина
	var login = $('input[name="Login"]').val();
	//получения из поля ввода значение введенного email
	var email = $('input[name="inputEmail"]').val();
	//получения из поля ввода значение введенного пароля
	var password = $('input[name="inputPassword"]').val();
	//получения из поля ввода значение введенного пароля
	var confirm = $('input[name="confirmPassword"]').val();
	//получения из поля ввода значение введенного ФИО
	var fio = $('input[name="inputfio"]').val();
	//переменная права доступа к сервису
	var admin;
	//проверяем нажатие на чекбокс
	if($('#checkboxAdmin').prop('checked')) {
	  admin = 1;
	} else {
	  admin = 0;
	}
	//объект для передачи данных
	var dataPost = {Login: login, inputEmail: email, inputPassword: password, confirmPassword: confirm, inputfio: fio, checkboxAdmin:admin};
	//вывод объект
	console.log(dataPost);
	//AJAX запрос на сервис
	$.ajax({
	  type: "POST", //тип запроса
	  url: "../controller.php?id=reg", //адрес запроса
	  dataType: "json", //формат данных
	  data: dataPost, //данные
	  //в случае успеха срабатывает событие
	  success: function(data){
	    //вывод результата в консоль
	    console.log(data);
	    //проверка ошибок
	    if(data['error'] == 'this user register'){
	    	$('.result').html('<span style="color: red;">Такой пользователь уже существует</span>');
	    } else if(data['error'] == 'passwords do not match'){
	    	$('.result').html('<span style="color: red;">Пароли не совпадают</span>');
	    } else if(data['error'] == 'insert error'){
	    	$('.result').html('<span style="color: green;">Пожалуйста, обновите страницу</span>');
	    } else if(data['error'] == 'not valid login'){
	    	$('.result').html('<span style="color: red;">Логин должен состоять из латинских букв</span>');
	    } else if(data['error'] == 'not valid pass'){
			$('.result').html('<span style="color: red;">Пароль может состоять из латинских букв или цифр</span>');
	    } else if(data['error'] == 'not valid email'){
	    	$('.result').html('<span style="color: red;">Не верно указан email адрес</span>');
	    } else if(data['error'] == 'not all fields'){
	    	$('.result').html('<span style="color: red;">Пожалуйста, заполните все поля</span>');
	    } else if (data['error'] == 'not valid fio') {
	    	$('.result').html('<span style="color: red;">ФИО должно состоять из латинских символов</span>');
	    } else {
	    	//перенаправление на сайт
	    	document.location.href='http://record.sl';
	    }
	  }
	});
}
//логин на сервис
function login_form(){
	//jquery селектору с классом result пристовить пустое значение
	$('.result').html('');
	//получения из поля ввода значение введенного логина
	var login = $('input[name="login"]').val();
	//получения из поля ввода значение введенного пароля
	var password = $('input[name="pass"]').val();
	//объект для передачи данных
	var dataPost = {login: login, pass: password};
	//AJAX запрос на сервис
	$.ajax({
	  type: "POST",//тип запроса
	  url: "../controller.php?id=login",//адрес запроса
	  dataType: "json",//формат данных
	  data: dataPost,//данные
	  //в случае успеха срабатывает событие
	  success: function(data){
	  	//проверка ошибок
	    if(data['error'] == 'not found user'){
	    	$('.result').html('<span style="color: red; padding-left: 270px;">Не верно введен логин или пароль. Повторите попытку</span>');
	    } else {
	    	//перенаправление на сайт
	    	document.location.href='http://record.sl/page/main.php';
	    }
	  }
	});
}
//получение расписания рабочих дней специалиста
function get_specialist_timetable(sid){
	//jquery селектору с классом row пристовить пустое значение
	$('.row').html('');
	//объект для передачи данных
	var dataPost = {sid:sid}
	//AJAX запрос на сервис
	$.ajax({
	  type: "POST",//тип запроса
	  url: '../controller.php?id=get_specialist_timetable',//адрес запроса
	  dataType: "json",//формат данных
	  data: dataPost,//данные
	  //в случае успеха срабатывает событие
	  success: function(jsondata){
	  	//вывод результата в консоль
	    console.log(jsondata);
	    //переменная для вставки в сайт
	    var div = '';
	    //проверка ошибок
	    if(jsondata == ""){
	    	$('.row').html('<span style="color: red;">Нет специалистов</span>');
	    } else if (jsondata['error'] == 'not found uid') {
	    	$('.row').html('<span style="color: red;">Пожалуйста, перезайдите на сервис</span>');
	    } else {
	    	for(var base in jsondata){
	    		//создание разворачивающейся панели с помощью bootstrap
	    			div += '<div class="panel-group" id="collapse-group">'
	    				div += '<div class="panel panel-default">'
	    					div += '<div class="panel-heading">'
	    						div += '<h4 class="panel-title">'
	    							div += '<a data-toggle="collapse" data-parent="#collapse-group" href="#el'+jsondata[base]['id']+'">Дата приема: '+jsondata[base]['date']+'. С '+jsondata[base]['start']+' до '+jsondata[base]['end']+'</a>'
	    						div += '</h4>'
	    					div += '</div>'
	    					div += '<div id="el'+jsondata[base]['id']+'" class="panel-collapse collapse">'
	    						div += '<div class="panel-body">'
					    			for (var index in jsondata[base]['calendar']) {
					    				var dis = '';
					    				if(jsondata[base]['calendar'][index] == 1){
					    					dis = 'disabled';
					    				} else {
					    					dis = ''
					    				}
					    				div += '<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2">'
					    					div += '<input class="btn btn-default'+dis+'" type="button" value="'+index+'" onclick="set_reception(\''+index+'\','+jsondata[base]['id']+');">'
					    				div += '</div>'
					    			}
					    		div += '</div>'
					    	div += '</div>'
	    				div += '</div>'
	    			div += '</div>'
	    	}
	    	//вставляем результат рисовки
	    	$('.row').html(div);
	    }
	  },
	  //событие в случае ошибки
	  error: function(jqxhr, status, errorMsg) {
	  		//вывод сообщения ошибки в консоль
	  		console.log(errorMsg);
			}
	});
}
//получение своего расписания
function get_specialist_timetable_admin(uid){
	//jquery селектору с классом row пристовить пустое значение
	$('.row').html('');
	//объект для передачи данных
	var dataPost = {uid:uid}
	//AJAX запрос на сервис
	$.ajax({
	  type: "POST",//тип запроса
	  url: '../controller.php?id=get_specialist_timetable_admin',//адрес запроса
	  dataType: "json",//формат данных
	  data: dataPost,//данные
	  //в случае успеха срабатывает событие
	  success: function(jsondata){
	    //вывод результата в консоль
	    console.log(jsondata);
	    //переменная для вставки в сайт
	    var div = '';
	    //проверка ошибок
	    if(jsondata == ""){
	    	$('.row').html('<span style="color: red;">Нет специалистов</span>');
	    } else if (jsondata['error'] == 'not found rights') {
	    	$('.row').html('<span style="color: red;">Пожалуйста, перезайдите на сервис</span>');
	    } else {
	    	for(var base in jsondata){
	    		//создание разворачивающейся панели с помощью bootstrap
	    			div += '<div class="panel-group" id="collapse-group">'
	    				div += '<div class="panel panel-default">'
	    					div += '<div class="panel-heading">'
	    						div += '<h4 class="panel-title">'
	    							div += '<a data-toggle="collapse" data-parent="#collapse-group" href="#el'+jsondata[base]['id']+'">Дата приема: '+jsondata[base]['date']+'. С '+jsondata[base]['start']+' до '+jsondata[base]['end']+'</a>'
	    						div += '</h4>'
	    					div += '</div>'
	    					div += '<div id="el'+jsondata[base]['id']+'" class="panel-collapse collapse">'
	    						div += '<div class="panel-body">'
					    			for (var index in jsondata[base]['calendar']) {
					    				var dis = '';
					    				if(jsondata[base]['calendar'][index] == 1){
					    					dis = 'disabled';
					    				} else {
					    					dis = ''
					    				}
					    				div += '<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2">'
					    					div += '<input class="btn btn-default'+dis+'" type="button" value="'+index+'" onclick="set_reception(\''+index+'\','+jsondata[base]['id']+');">'
					    				div += '</div>'
					    			}
					    		div += '</div>'
					    	div += '</div>'
	    				div += '</div>'
	    			div += '</div>'
	    	}
	    	//вставляем результат рисовки
	    	$('.row').html(div);
	    }
	  },
	  //событие в случае ошибки
	  error: function(jqxhr, status, errorMsg) {
	  		//вывод сообщения ошибки в консоль
	  		console.log(errorMsg);
			}
	});
}
//запись на прием
function set_reception(time,schid){
	//jquery селектору с классом row пристовить пустое значение
	$('.row').html('');
	var coment = '';
	coment = prompt('Причина посещения специалиста', 'Введите причину');
	var dataPost = {ptime: time, schid: schid, coment:coment};
	//AJAX запрос на сервис
	$.ajax({
	  type: "POST",//тип запроса
	  url: '../controller.php?id=set_reception',//адрес запроса
	  dataType: "json",//формат данных
	  data: dataPost,//данные
	  //в случае успеха срабатывает событие
	  success: function(jsondata){
	    //вывод результата в консоль
	    console.log(jsondata);
	     //переменная для вставки в сайт
	    var div = '';
	    //проверка ошибок
	    if(jsondata == ""){
	    	$('.row').html('<span style="color: red;">Нет специалистов</span>');
	    } else if (jsondata['error'] == 'not found sid') {
	    	$('.row').html('<span style="color: red;">Не найден специалист</span>');
	    } else if(jsondata['error'] == 'not found ptime'){
			$('.row').html('<span style="color: red;">Не указано время</span>');
	    } else if(jsondata['error'] == 'not found schid'){
	    	$('.row').html('<span style="color: red;">Не указан график специалиста </span>');
	    } else if(jsondata['error'] == 'count is complated'){
	    	$('.row').html('<span style="color: red;">Нет свободных мест</span>');
	    } else if(jsondata['error'] == 'not found pid'){
	    	$('.row').html('<span style="color: red;">Не указан посетитель</span>');
	    } else if(jsondata['error'] == 'insert'){
	    	$('.row').html('<span style="color: red;">Пожалуйста, попробуйте еще</span>');
	    } else if(jsondata['error'] == 'you is reception'){
	    	$('.row').html('<span style="color: red;">Вы уже записывались</span>');
	    } else {
	    	alert('Успешная запись к специалисту');
	    }
	  },
	  //событие в случае ошибки
	  error: function(jqxhr, status, errorMsg) {
	  		//вывод сообщения ошибки в консоль
	  		console.log(errorMsg);
			}
	});
}
//получение истории посищения
function get_visit_history(){
	//jquery селектору с классом row пристовить пустое значение
	$('.row').html('');
	//AJAX запрос на сервис
	$.ajax({
	  dataType: 'json',//формат данных
	  url: '../controller.php?id=get_visit_history',//адрес запроса
	  //в случае успеха срабатывает событие
	  success: function(jsondata){
	    //$('.results').html(jsondata);
	    //вывод результата в консоль
	    console.log(jsondata);
	     //переменная для вставки в сайт
	    var div = '';
	    if(jsondata == ""){
	    	$('.row').html('<span style="color: red;">История посещений пуста</span>');
	    } else if (jsondata['error'] == 'not found uid') {
	    	$('.row').html('<span style="color: red;">Пожалуйста, перезайдите на сервис</span>');
	    } else {
	    	//рисуем таблицу
	    	var i = 1;
	    	div += '<div class="table-responsive">'
				div += '<table class="table table-hover">'
					div += '<thead>'
						div += '<tr>'
			    			div += '<td align="center">ID</td>'
			    			div += '<td align="center">Должность</td>'
			    			div += '<td align="center">ФИО</td>'
			    			div += '<td align="center">Email</td>'
			    			div += '<td align="center">Приём</td>'
			    			div += '<td align="center">Причина</td>'
						div += '</tr>'
					div += '</thead>'
					div += '<tbody>'
	    			for(var index in jsondata){
	    				div += '<tr>'
		    				div += '<td align="center">'+i+'</td>'
		    				div += '<td align="center">'+jsondata[index]['specialist']+'</td>'
		    				div += '<td align="center">'+jsondata[index]['fio']+'</td>'
		    				div += '<td align="center">'+jsondata[index]['email']+'</td>'
		    				div += '<td align="center">'+jsondata[index]['date']+'</td>'
		    				div += '<td align="center">'+jsondata[index]['coment']+'</td>'
	    				div += '</tr>'
	    				i++;
	    			}
					div += '</tbody>'
				div += '</table>'
			div += '</div>'
    		//вставляем результат рисовки
    		$('.row').html(div);
	    }
	  },
	  //событие в случае ошибки
	  error: function(jqxhr, status, errorMsg) {
	  		//вывод сообщения ошибки в консоль
	  		console.log(errorMsg);
			}
	});
}

//получение истории посищения
function get_visit(){
	//jquery селектору с классом row пристовить пустое значение
	$('.row').html('');
	//AJAX запрос на сервис
	$.ajax({
	  dataType: 'json',//формат данных
	  url: '../controller.php?id=get_visit_history',//адрес запроса
	  //в случае успеха срабатывает событие
	  success: function(jsondata){
	    //$('.results').html(jsondata);
	    //вывод результата в консоль
	    console.log(jsondata);
	     //переменная для вставки в сайт
	    var div = '';
	    if(jsondata == ""){
	    	$('.row').html('<span style="color: red;">История посещений пуста</span>');
	    } else if (jsondata['error'] == 'not found uid') {
	    	$('.row').html('<span style="color: red;">Пожалуйста, перезайдите на сервис</span>');
	    } else {
	    	//рисуем таблицу
	    	var i = 1;
	    	div += '<div class="table-responsive">'
				div += '<table class="table table-hover">'
					div += '<thead>'
						div += '<tr>'
			    			div += '<td align="center">ID</td>'
			    			div += '<td align="center">Должность</td>'
			    			div += '<td align="center">ФИО</td>'
			    			div += '<td align="center">Email</td>'
			    			div += '<td align="center">Приём</td>'
			    			div += '<td align="center">Причина</td>'
			    			div += '<td align="center"></td>'
						div += '</tr>'
					div += '</thead>'
					div += '<tbody>'
	    			for(var index in jsondata){
	    				div += '<tr>'
		    				div += '<td align="center">'+i+'</td>'
		    				div += '<td align="center">'+jsondata[index]['specialist']+'</td>'
		    				div += '<td align="center">'+jsondata[index]['fio']+'</td>'
		    				div += '<td align="center">'+jsondata[index]['email']+'</td>'
		    				div += '<td align="center">'+jsondata[index]['date']+'</td>'
		    				div += '<td align="center">'+jsondata[index]['coment']+'</td>'
		    				div += '<td align="center"><a onclick="del_reception('+jsondata[index]['schid']+')"><span class="glyphicon glyphicon-remove"></span></a>'
	    				div += '</tr>'
	    				i++;
	    			}
					div += '</tbody>'
				div += '</table>'
			div += '</div>'
    		//вставляем результат рисовки
    		$('.row').html(div);
	    }
	  },
	  //событие в случае ошибки
	  error: function(jqxhr, status, errorMsg) {
	  		//вывод сообщения ошибки в консоль
	  		console.log(errorMsg);
			}
	});
}

//получение списка специалистов
function get_specialist_list(){
	//jquery селектору с классом row пристовить пустое значение
	$('.row').html('');
	//AJAX запрос на сервис
	$.ajax({
	  dataType: 'json',//формат данных
	  url: '../controller.php?id=get_specialist_list',//адрес запроса
	  //в случае успеха срабатывает событие
	  success: function(jsondata){
	    //вывод результата в консоль
	    console.log(jsondata);
	     //переменная для вставки в сайт
	    var div = '';
	    //проверка ошибок
	    if(jsondata == ""){
	    	$('.row').html('<span style="color: red;">История посещений пуста</span>');
	    } else if (jsondata['error'] == 'not found uid') {
	    	$('.row').html('<span style="color: red;">Пожалуйста, перезайдите на сервис</span>');
	    } else {
	    	//рисуем таблицу
	    	var i = 1;
	    	div += '<div class="table-responsive">'
				div += '<table class="table table-hover">'
					div += '<thead>'
						div += '<tr>'
			    			div += '<td align="center">ID</td>'
			    			div += '<td align="center">Должность</td>'
			    			div += '<td align="center">ФИО</td>'
			    			div += '<td align="center">Email</td>'
			    			div += '<td align="center">Запись</td>'
						div += '</tr>'
					div += '</thead>'
					div += '<tbody>'
	    			for(var index in jsondata){
	    				div += '<tr>'
		    				div += '<td align="center">'+i+'</td>'
		    				div += '<td align="center">'+jsondata[index]['specialist']+'</td>'
		    				div += '<td align="center">'+jsondata[index]['fio']+'</td>'
		    				div += '<td align="center">'+jsondata[index]['email']+'</td>'
		    				div += '<td align="center"><a onclick="get_specialist_timetable('+jsondata[index]['id']+')"><span class="glyphicon glyphicon-dashboard"></span></a></td>'
	    				div += '</tr>'
	    				i++;
	    			}
					div += '</tbody>'
				div += '</table>'
			div += '</div>'
    		//вставляем результат рисовки
    		$('.row').html(div);
	    }
	  },
	  //событие в случае ошибки
	  error: function(jqxhr, status, errorMsg) {
	  		//вывод сообщения ошибки в консоль
	  		console.log(errorMsg);
			}
	});
}
//получение списка посетителей
function get_user_list(){
	//jquery селектору с классом row пристовить пустое значение
	$('.row').html('');
	//AJAX запрос на сервис
	$.ajax({
	  dataType: 'json',//формат данных
	  url: '../controller.php?id=get_user_list',//адрес запроса
	  //в случае успеха срабатывает событие
	  success: function(jsondata){
	    //вывод результата в консоль
	    console.log(jsondata);
	     //переменная для вставки в сайт
	    var div = '';
	    if(jsondata == ""){
	    	$('.row').html('<span style="color: red;">Нет пользователей</span>');
	    } else if(jsondata['error'] == 'not found rights') {
			$('.row').html('<span style="color: red;">У вас нет прав</span>');
	    } else {
	    	//рисуем таблицу
	    	var i = 1;
	    	div += '<table class="table">'
	    		div += '<tr>'
	    			div += '<td align="center">ID</td>'
	    			div += '<td align="center">ФИО</td>'
	    			div += '<td align="center">Email</td>'
	    			div += '<td align="center">Дата регистрации</td>'
	    			div += '<td align="center">Запись</td>'
	    		div += '</tr>'
	    	for(var index in jsondata){
	    		div += '<tr>'
		    		div += '<td align="center">'+jsondata[index]['id']+'</td>'
		    		div += '<td align="center">'+jsondata[index]['fio']+'</td>'
		    		div += '<td align="center">'+jsondata[index]['email']+'</td>'
		    		div += '<td align="center">'+jsondata[index]['d_create']+'</td>'
	    		div += '<td align="center"><a onclick="get_specialist_timetable_admin('+jsondata[index]['id']+')"><span class="glyphicon glyphicon-dashboard"></span></a></td>'
	    		div += '</tr>'
	    	}
	    	div += '</table>'
    	//вставляем результат рисовки
    	$('.row').html(div);
	    }
	  },
	  //событие в случае ошибки
	  error: function(jqxhr, status, errorMsg) {
	  		//вывод сообщения ошибки в консоль
	  		console.log(errorMsg);
			}
	});
}
//получение расписание рабочего графика
function get_schedule(){
	var div = ''
	//AJAX запрос на сервис
	$.ajax({
	  dataType: 'json',//формат данных
	  url: '../controller.php?id=get_schedule',//адрес запроса
	  //в случае успеха срабатывает событие
	  success: function(jsondata){
	    //$('.results').html(jsondata);
	    //вывод результата в консоль
	    console.log(jsondata);
	     //переменная для вставки в сайт
	    var div = '';
	    if(jsondata === ""){
	    	$('.results').html('Данных');
	    } else if(jsondata['error'] == 'not found rights'){
			$('.results').html('<span style="color: red;">У вас нет прав</span>');
	    } else {
	    	div +='<div class="results"></div>'
	    	//рисуем раскрывающую панель с помощью bootstrap
	    	for(var base in jsondata){
	    			div += '<div class="panel-group" id="collapse-group">'
	    				div += '<div class="panel panel-default">'
	    					div += '<div class="panel-heading">'
	    						div += '<h4 class="panel-title">'
	    							div += '<a data-toggle="collapse" data-parent="#collapse-group" href="#el'+jsondata[base]['id']+'">Начало приема: '+jsondata[base]['start']+'. Конец приема: '+jsondata[base]['end']+'</a>'
	    						div += '</h4>'
	    					div += '</div>'
	    					div += '<div id="el'+jsondata[base]['id']+'" class="panel-collapse collapse">'
	    						div += '<div class="panel-body">'
	    							div += '<div class="table-responsive">'
										div += '<table class="table table-hover">'
								    		div += '<thead>'
									    		div += '<tr>'
									    			div += '<td align="center">№</td>'
									    			div += '<td align="center">Посетитель</td>'
									    			div += '<td align="center">Email</td>'
									    			div += '<td align="center">Время</td>'
									    			div += '<td align="center">Причина</td>'
									    			div += '<td align="center"></td>'
									    		div += '</tr>'
								    		div += '</thead>'
								    		div += '<tbody>'
	    		for(var index in jsondata[base]){
	    			if(typeof jsondata[base][index] == 'object'){
	    				var i=1;
		    			for(var key in jsondata[base][index]){
							    				div += '<tr>'
												div += '<td align="center">'+i+'</td>'
												div += '<td align="center">'+jsondata[base][index][key]['fio']+'</td>'
												div += '<td align="center">'+jsondata[base][index][key]['email']+'</td>'
												div += '<td align="center">'+jsondata[base][index][key]['ptime']+'</td>'
												div += '<td align="center">'+jsondata[base][index][key]['coment']+'</td>'
												//событие на нажатие вызывются функции удаления и редактирование записи
							    			    div += '<td align="center"><a onclick="del_reception_user('+jsondata[base][index][key]['id']+','+jsondata[base]['id']+')"><span class="glyphicon glyphicon-remove"></span></a>&nbsp;'
							    			    div += '<a onclick="edit_reception('+jsondata[base][index][key]['id']+','+jsondata[base]['id']+')"><span class="glyphicon glyphicon-pencil"></span></a></td>'
												div += '</tr>'
												i++;
		    			}
	    			}
	    		}
					    				div += '</tbody>'
					    				div += '</table>'
					    			div += '</div>'
					    		div += '</div>'
					    	div += '</div>'
	    				div += '</div>'
	    			div += '</div>'
	    			
	    	}
	    	//рисуем форму создания нового рабочего дня
			div += '<form class="form-horizontal">'
			    div += '<div class="form-group">'
					div += '<label class="control-label col-xs-3" for="col">Количество человек:</label>'
					div += '<div class="col-xs-9">'
					    div += '<input type="text" class="form-control" id="col" name="col" placeholder="Введите количество человек" onkeyup="return check_chose(this);" onkeypress="return check_chose(this);">'
					div += '</div>'
				div += '</div>'
				div += '<div class="form-group">'
					div += '<label class="control-label col-xs-3" for="stime">Время смены с:</label>'
					div += '<div class="col-xs-3">'
						div += '<div class="input-group date " id="stime1">'
							div += '<input type="text" class="form-control" name="stime1" onkeyup="return proverka(this);" onkeypress="return proverka(this);"/>'
							div += '<span class="input-group-addon">'
								div += '<span class="glyphicon glyphicon-time" onclick="set_time(1);"></span>'
							div += '</span>'
						div += '</div>'
					div += '</div>'
					div += '<label class="control-label col-xs-3" for="stime">До:</label>'
					div += '<div class="col-xs-3">'
						div += '<div class="input-group date " id="stime2">'
							div += '<input type="text" class="form-control" name="stime2" onkeyup="return proverka(this);" onkeypress="return proverka(this);"/>'
							div += '<span class="input-group-addon">'
								div += '<span class="glyphicon glyphicon-time" onclick="set_time(2);"></span>'
							div += '</span>'
						div += '</div>'
					div += '</div>'
				div += '</div>'
				div += '<div class="form-group">'
					div += '<label class="control-label col-xs-3" for="date">Дата:</label>'
					div += '<div class="col-xs-9">'
						div += '<div class="input-group date " id="datetimepicker2">'
							div += '<input type="text" class="form-control" name="calendar" onkeyup="return proverka(this);" onkeypress="return proverka(this);"/>'
							div += '<span class="input-group-addon">'
								div += '<span class="glyphicon glyphicon-calendar" onclick="set_date();"></span>'
							div += '</span>'
						div += '</div>'
					div += '</div>'
				div += '</div>'
				div += '<div class="form-group">'
					div += '<label class="control-label col-xs-3" for="tcol">Количество минут:</label>'
					div += '<div class="col-xs-9">'
					    div += '<input type="text" class="form-control" id="tcol" name="tcol" placeholder="Введите количество минут на человека" onkeyup="return check_chose(this);" onkeypress="return check_chose(this);">'
					div += '</div>'
				div += '</div>'
				div += '<div class="form-group">'
					div += '<div class="col-xs-offset-3 col-xs-9">'
						div += '<button type="button" class="btn btn-default" onclick="set_schedule();">Добавить рабочий день</button>'
					div += '</div>'
				div += '</div>'
			div += '</form>'

    	//вставляем результат рисовки
    	$('.row').html(div);
	    }
	  },
	  //событие в случае ошибки
	  error: function(jqxhr, status, errorMsg) {
	  		//вывод сообщения ошибки в консоль
	  		console.log(errorMsg);
			}
	});
}

//Виджет даты
function set_date(){
  $(function () {
    //Установим для виджета русскую локаль с помощью параметра language и значения ru и убираем выбор времени
    $('#datetimepicker2').datetimepicker(
      {pickTime: false, language: 'ru'}
    );
  });
}

//Виджет времени
function set_time(id){
  switch(id){
  	case 1:
  		$(function () {
		    //Установим для виджета русскую локаль с помощью параметра language и значения ru убираем выбор даты и добавляем выбор секунд
		    $('#stime1').datetimepicker(
		      {pickDate: false, useSeconds: true, language: 'ru'}
		    );
		});
  		break;
  	case 2:
  	  	$(function () {
		    //Установим для виджета русскую локаль с помощью параметра language и значения ru убираем выбор даты и добавляем выбор секунд
		    $('#stime2').datetimepicker(
		      {pickDate: false, useSeconds: true, language: 'ru'}
		    );
		});
  		break;
  }
}

//проверка на числа
function check_chose(input){
	//получение значения поля ввода
    var value = input.value;
    //регулярное выражения для проверки
	var rep = /[-;":'a-zA-Zа-яА-Я\\=`ё/\*++!@#$%\^&_№?><]/; 
    if (rep.test(value)) { 
    	//если нашли символ заменяем пустой строкой
        value = value.replace(rep, ''); 
        //выводим в текстовое поле
        input.value = value; 
    }
}


//Регистрация графика
function set_schedule(){
	//получение выбранной даты
	var d = $('input[name=calendar]').val();
	//получение начала рабочего дня
	var stime1 = $('input[name=stime1]').val();
	//получение завершение рабочего дня
	var stime2 = $('input[name=stime2]').val();
	//получение количества человек
	var col = $('input[name=col]').val();
	//получение времени на человека
	var tcol = $('input[name=tcol]').val();
	//объект для передачи на сервер
	var dataPost = {date:d,start_time:stime1,end_time:stime2,col:col,time_col:tcol};
	//AJAX запрос на сервис
	$.ajax({
		type: "POST",//тип запроса
		url: "../controller.php?id=set_schedule",//адрес запроса
		dataType: "json",//формат данных
		data: dataPost,//данные
		//в случае успеха срабатывает событие
		success: function(data){
			//вывод результата в консоль
			console.log(data);
			//проверка ошибок
			if(data['error'] == 'not found rights'){
				alert('У вас нет прав');
			} else if(data['error'] == 'not found date'){
				alert('Не найдена дата');
			} else if(data['error'] == 'not found col'){
				alert('Введите количество человек');
			} else if(data['error'] == 'not found tcol'){
				alert('Введите количество минут на человека');
			} else if(data['error'] == 'not found start time'){
				alert('Не найдено начало приема');
			} else if(data['error'] == 'not found end time'){
				alert('Не найдено конец приема');
			} else if(data['error'] == 'insert'){
				alert('Что то пошло не так, попробуйте еще раз');
			} else {
				//вызываем получение рабочего графика
				get_schedule();
			}
		},
	  //событие в случае ошибки
	  error: function(jqxhr, status, errorMsg) {
	  		//вывод сообщения ошибки в консоль
	  		console.log(errorMsg);
			}
	});
}
//редактирование записи на прием
function edit_reception(uid,rid){
	//объект данных для передачи на сервер
	var dataPost = {uid:uid,schid:rid};
	//AJAX запрос на сервис
	$.ajax({
		type: "POST",//тип запроса
		url: "../controller.php?id=get_specialist_timetable_one",//адрес запроса
		dataType: "json",//формат данных
		data: dataPost,//данные
		//в случае успеха срабатывает событие
		success: function(jsondata){
			//вывод результата в консоль
			console.log(jsondata);
			//обработка ошибок
			if(jsondata['error'] == 'not found rights'){
				alert('У вас нет прав');
			} else if(jsondata['error'] == 'not found uid'){
				alert('Не найден пациент');
			} else if(jsondata['error'] == 'not found schid'){
				alert('Не найден график специалиста');
			} else {
				//переменная для вставки в сайт
				var div = '';
				//рисуем  сворачивающуюся панель
				for(var base in jsondata){
	    			div += '<div class="panel-group" id="collapse-group">'
	    				div += '<div class="panel panel-default">'
	    					div += '<div class="panel-heading">'
	    						div += '<h4 class="panel-title">'
	    							div += '<a data-toggle="collapse" data-parent="#collapse-group" href="#el'+jsondata[base]['id']+'">Дата приема: '+jsondata[base]['date']+'. С '+jsondata[base]['start']+' до '+jsondata[base]['end']+'</a>'
	    						div += '</h4>'
	    					div += '</div>'
	    					div += '<div id="el'+jsondata[base]['id']+'" class="panel-collapse collapse">'
	    						div += '<div class="panel-body">'
					    			for (var index in jsondata[base]['calendar']) {
					    				var dis = '';
					    				if(jsondata[base]['calendar'][index] == 1){
					    					dis = 'disabled';
					    				} else {
					    					dis = ''
					    				}
					    				div += '<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2">'
					    				//событие на нажатие на время вызов регистрации на прием
					    					div += '<input class="btn btn-default'+dis+'" type="button" value="'+index+'" onclick="set_reception(\''+index+'\','+jsondata[base]['id']+');">'
					    				div += '</div>'
					    			}
					    		div += '</div>'
					    	div += '</div>'
	    				div += '</div>'
	    			div += '</div>'
	    	}
	    	//вставляем результат рисовки
	    	$('.row').html(div);
			}
		},
	  //событие в случае ошибки
	  error: function(jqxhr, status, errorMsg) {
	  		//вывод сообщения ошибки в консоль
	  		console.log(errorMsg);
			}
	});
}
//удаление с приема
function del_reception_user(uid,rid){
	//объект данных для передачи на сервер
	var dataPost = {uid:uid,schid:rid};
	//AJAX запрос на сервис
	$.ajax({
		type: "POST",//тип запроса
		url: "../controller.php?id=del_reception_user",//адрес запроса
		dataType: "json",//формат данных
		data: dataPost,//данные
		//в случае успеха срабатывает событие
		success: function(data){
			//вывод результата в консоль
			console.log(data);
			//проверка ошибок
			if(data['error'] == 'not found rights'){
				alert('У вас нет прав');
			} else if(data['error'] == 'not found uid'){
				alert('Не найден пациент');
			} else if(data['error'] == 'not found schid'){
				alert('Не найден график специалиста');
			} else {
				//вызов получения рабочего графика
				get_schedule();
			}
		},
	  //событие в случае ошибки
	  error: function(jqxhr, status, errorMsg) {
	  		//вывод сообщения ошибки в консоль
	  		console.log(errorMsg);
			}
	});
}

//удаление с приема
function del_reception(rid){
	//объект данных для передачи на сервер
	var dataPost = {schid:rid};
	//AJAX запрос на сервис
	$.ajax({
		type: "POST",//тип запроса
		url: "../controller.php?id=del_reception",//адрес запроса
		dataType: "json",//формат данных
		data: dataPost,//данные
		//в случае успеха срабатывает событие
		success: function(data){
			//вывод результата в консоль
			console.log(data);
			//проверка ошибок
			if(data['error'] == 'not found rights'){
				alert('У вас нет прав');
			} else if(data['error'] == 'not found uid'){
				alert('Не найден пациент');
			} else if(data['error'] == 'not found schid'){
				alert('Не найден график специалиста');
			} else {
				//вызов посещения
				get_visit();
			}
		},
	  //событие в случае ошибки
	  error: function(jqxhr, status, errorMsg) {
	  		//вывод сообщения ошибки в консоль
	  		console.log(errorMsg);
			}
	});
}
//получение статистика визитов
function get_statistics_visit(){
	//jquery селектору с классом row пристовить пустое значение
	$('.row').html('');
	//объект данных для передачи на сервер
	var dataPost = {type:'visit'};
	//AJAX запрос на сервис
	$.ajax({
	  type: "POST",//тип запроса
	  url: '../controller.php?id=statistics',//адрес запроса
	  dataType: "json",//формат данных
	  data: dataPost,//данные
	  //в случае успеха срабатывает событие
	  success: function(jsondata){
	    //вывод результата в консоль
	    console.log(jsondata);
	     //переменная для вставки в сайт
	    var div = '';
	    //проверка ошибок
	    if(jsondata == ""){
	    	$('.row').html('<span style="color: red;">Нет данных</span>');
	    } else if(jsondata['error'] == 'not found rights'){
	    	$('.row').html('<span style="color: red;">У вас нет прав</span>');
	    } else {
	    	//рисуем таблицу
	    	var i = 1;
	    	div += '<table class="table">'
	    		div += '<tr>'
	    			div += '<td align="center">ID</td>'
	    			div += '<td align="center">ФИО</td>'
	    			div += '<td align="center">Email</td>'
	    			div += '<td align="center">Количество посещений</td>'
	    		div += '</tr>'
	    	for(var index in jsondata){
	    		div += '<tr>'
	    		//for(var obj in jsondata[index]){
		    		//div += '<td align="center">'+jsondata[index][obj]+'</td>'
	    		//}
	    		div += '<td align="center">'+jsondata[index]['id']+'</td>'
				div += '<td align="center">'+jsondata[index]['fio']+'</td>'
				div += '<td align="center">'+jsondata[index]['email']+'</td>'
				div += '<td align="center">'+jsondata[index]['count']+'</td>'
	    		div += '</tr>'
	    	}
	    	div += '</table>'
    	//вставляем результат рисовки
    	$('.row').html(div);
	    }
	  },
	  //событие в случае ошибки
	  error: function(jqxhr, status, errorMsg) {
	  		//вывод сообщения ошибки в консоль
	  		console.log(errorMsg);
			}
	});
}
//получение статистики по времени посещения специалистов
function get_statistics_time(){
	//jquery селектору с классом row пристовить пустое значение
	$('.row').html('');
	//объект данных для передачи на сервер
	var dataPost = {type:'time'};
	//AJAX запрос на сервис
	$.ajax({
	  type: "POST",//тип запроса
	  url: '../controller.php?id=statistics',//адрес запроса
	  dataType: "json",//формат данных
	  data: dataPost,//данные
	  //в случае успеха срабатывает событие
	  success: function(jsondata){
	    //вывод результата в консоль
	    console.log(jsondata);
	     //переменная для вставки в сайт
	    var div = '';
	    //проверка ошибок
	    if(jsondata == ""){
	    	$('.row').html('<span style="color: red;">Нет новостей</span>');
	    } else if(jsondata['error'] == 'not found rights'){
	    	$('.row').html('<span style="color: red;">У вас нет прав</span>');
	    } else {
	    	//рисуем сворачивающую панель
	    	for(var base in jsondata){
	    			div += '<div class="panel-group" id="collapse-group">'
	    				div += '<div class="panel panel-default">'
	    					div += '<div class="panel-heading">'
	    						div += '<h4 class="panel-title">'
	    							div += '<a data-toggle="collapse" data-parent="#collapse-group" href="#el'+base+'">Время выбранное посетителями: <b>'+jsondata[base]['ptime']+'</b>. Количество: <b>'+jsondata[base]['count']+'</b></a>'
	    						div += '</h4>'
	    					div += '</div>'
	    					div += '<div id="el'+base+'" class="panel-collapse collapse">'
	    						div += '<div class="panel-body">'
	    							div += '<div class="table-responsive">'
										div += '<table class="table table-hover">'
								    		div += '<thead>'
									    		div += '<tr>'
									    			div += '<td align="center">№</td>'
									    			div += '<td align="center">Посетитель</td>'
									    			div += '<td align="center">Email</td>'
									    			div += '<td align="center">Дата</td>'
									    			div += '<td align="center">Причина</td>'
									    		div += '</tr>'
								    		div += '</thead>'
								    		div += '<tbody>'
	    		for(var index in jsondata[base]){
	    			if(typeof jsondata[base][index] == 'object'){
	    				var i=1;
		    			for(var key in jsondata[base][index]){
							    				div += '<tr>'
												div += '<td align="center">'+i+'</td>'
												div += '<td align="center">'+jsondata[base][index][key]['fio']+'</td>'
												div += '<td align="center">'+jsondata[base][index][key]['email']+'</td>'
												div += '<td align="center">'+jsondata[base][index][key]['ptime']+'</td>'
												div += '<td align="center">'+jsondata[base][index][key]['coment']+'</td>'
												div += '</tr>'
												i++;
		    			}
	    			}
	    		}
					    				div += '</tbody>'
					    				div += '</table>'
					    			div += '</div>'
					    		div += '</div>'
					    	div += '</div>'
	    				div += '</div>'
	    			div += '</div>'
	    			
	    	}
    	//вставляем результат рисовки
    	$('.row').html(div);
	    }
	  },
	  //событие в случае ошибки
	  error: function(jqxhr, status, errorMsg) {
	  		//вывод сообщения ошибки в консоль
	  		console.log(errorMsg);
			}
	});
}

//Проверка символов
function proverka(input) { 
	//получение значения поля ввода
    var value = input.value; 
    //регулярное выражения для проверк
    var rep = /[-;"'0-9&!@#$%^&*()+_a-zA-Zа-яА-Я]/; 
    if (rep.test(value)) { 
    	//если нашли символ заменяем пустой строкой
        value = value.replace(rep, ''); 
        //выводим в текстовое поле
        input.value = value; 
    } 
}

//получение настроек специалиста
function settings(){
	//jquery селектору с классом row пристовить пустое значение
	$('.row').html('');
	//AJAX запрос на сервис
	$.ajax({
	  dataType: 'json',//формат данных
	  url: '../controller.php?id=get_settings',//адрес запроса
	  //в случае успеха срабатывает событие
	  success: function(jsondata){
	    //вывод результата в консоль
	    console.log(jsondata);
	     //переменная для вставки в сайт
	    var div = '';
	    //проверка ошибок
		if (jsondata['error'] == 'not found rights') {
	    	$('.row').html('<span style="color: red;">У вас нет прав</span>');
	    } else if(jsondata['error'] == 'not found sid'){
			$('.row').html('<span style="color: red;">Пожалуйста, перезайдите на сервис</span>');
	    } else {
	    	//рисуем форму
		  div +='<form class="form-horizontal">'
	        div +='<div class="form-group">'
	          div +='<label class="control-label col-xs-3" for="specialist">Должность:</label>'
	          div +='<div class="col-xs-9">'
	            div +='<input type="text" class="form-control" id="specialist" name="specialist" value="'+jsondata['specialist']+'">'
	          div +='</div>'
	        div +='</div>'
	        div +='<div class="form-group">'
	          div +='<label class="control-label col-xs-3" for="inputfio">ФИО:</label>'
	          div +='<div class="col-xs-9">'
	            div +='<input type="text" class="form-control" id="inputfio" name="inputfio" value="'+jsondata['fio']+'">'
	          div +='</div>'
	        div +='</div>'
	        div += '<div class="form-group">'
	          div += '<div class="col-xs-offset-3 col-xs-9">'
	          //обработка на событие нажатия вызов обновления настроек
	            div += '<input type="button" class="btn btn-primary" value="Сохранить" onclick="update_settings();">'
	         div += '</div>'
	        div += '</div>'
	      div += '</form>'
	      //вставляем результат рисовки
	      $('.row').html(div);
	    }
	  },
	  //событие в случае ошибки
	  error: function(jqxhr, status, errorMsg) {
	  		//вывод сообщения ошибки в консоль
	  		console.log(errorMsg);
			}
	});
}
//обновление настроек специалиста
function update_settings(){
	//получение должности
	var specialist = $('input[name="specialist"]').val();
	//получение ФИО
	var fio = $('input[name="inputfio"]').val();
	//объект данных для передачи на сервер
	var dataPost = { inputspecialist:specialist ,inputfio: fio};
	//jquery селектору с классом row пристовить пустое значение
	$('.row').html('');
	//AJAX запрос на сервис
	$.ajax({
	  type: "POST",//тип запроса
	  url: '../controller.php?id=update_settings',//адрес запроса
	  dataType: "json",//формат данных
	  data: dataPost,//данные
	  //в случае успеха срабатывает событие
	  success: function(jsondata){
	    //вывод результата в консоль
	    console.log(jsondata);
	     //переменная для вставки в сайт
	    var div = '';
	    //проверка ошибок
		if (jsondata['error'] == 'not found rights') {
	    	$('.row').html('<span style="color: red;">У вас нет прав</span>');
	    } else if(jsondata['error'] == 'update'){
			$('.row').html('<span style="color: red;">Пожалуйста, попробуйте еще</span>');
	    } else {
	      $('.row').html(div);
	      alert('Данные успешно изменены');
	    }
	  },
	  //событие в случае ошибки
	  error: function(jqxhr, status, errorMsg) {
	  		//вывод сообщения ошибки в консоль
	  		console.log(errorMsg);
			}
	});
}