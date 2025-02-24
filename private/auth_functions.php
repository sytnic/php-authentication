<?php

// Performs all actions necessary to log in an admin
// Ставим "штамп на руку" входящему админу: сохраняем в сессию
function log_in_admin($admin) {
  $_SESSION['admin_id'] = $admin['id'];
  $_SESSION['username'] = $admin['username'];
  return true;
}

function log_out_admin() {
  unset($_SESSION['admin_id']);
  unset($_SESSION['username']);
  return true;
}

// is_logged_in() contains all the logic for determining if a
// request should be considered a "logged in" request or not.
// It is the core of require_login() but it can also be called
// on its own in other contexts (e.g. display one link if an admin
// is logged in and display another link if they are not)

// is_logged_in() содержит всю логику для определения
// того, следует ли считать запрос "вошедшим в систему" или нет.
// Это ядро функции require_login(), но ее также можно вызвать
// саму по себе в других контекстах (например, отображать одну ссылку, если администратор
// вошел в систему и отображает другую ссылку, если это не так)
  
function is_logged_in() {
  // Having a admin_id in the session serves a dual-purpose:
  // - Its presence indicates the admin is logged in.
  // - Its value tells which admin for looking up their record.

  // Наличие admin_id в сеансе служит двойной цели:
  // - Его наличие указывает на то, что администратор вошел в систему.
  // - Его значение указывает, какой администратор просматривает свою запись.
  return isset($_SESSION['admin_id']);
}

// Returns true if a page is in the allow-list and is
// exempt from user authentication
// Возвращается истина, если страница, к-рая была вызвана (login.php),
// совпала с данными в массиве.
// Возвращается ложь, если страница, к-рая была вызвана, 
// не перечислена в массиве.
function page_exempt_from_auth() {
  $no_auth_pages = [
    '/staff/login.php'
  ];
  $current_page = str_replace(WWW_ROOT, '', $_SERVER['SCRIPT_NAME']);
  // If it is in the array, it is not restricted
  return in_array($current_page, $no_auth_pages);
}

// Call require_login() at the top of any page which needs to
// require a valid login before granting access to the page.
// Теперь, если пользователь не вошёл
// и выполняет доступ со страницы login.php, 
// то редирект не запускается, ветка else 
// (перевёрнутое !page_exemt равно false).
// Если не вошёл и выполняет доступ с других страниц, 
// то происходит редирект 
// (перевёрнутое !page_exemt равно true).
function require_login() {
  if(!is_logged_in() && !page_exempt_from_auth()) {
    redirect_to(url_for('/staff/login.php'));
  } else {
    // Do nothing, let the rest of the page proceed.
  }
}


?>