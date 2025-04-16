<?php

include_once('dao/PostgresDao.php');

$dao = new PostgresDao();
$connection = $dao->getConnection();
?>