<?php
$connect = mysqli_connect("localhost", "root", "", "db_sistemptt");

if (!$connect) {
    die("Error" . mysqli_connect_error());
}