<?php
// Get the host from $_SERVER['HTTP_HOST'] and check if it contains localhost or 127.0.0.1
$is_local = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);

if ($is_local) {
  echo "You are in the local environment (localhost or 127.0.0.1).";
} else {
  echo "You are in the remote (production) environment.";
}
