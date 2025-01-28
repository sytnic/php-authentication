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

?>