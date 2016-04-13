<?php
$currency = '$'; //Currency Character or code

$db_username = getenv('C9_USER');
$db_password = 'POOP01';
$db_name = 'project1';
$db_host = getenv('IP');
                  

$shipping_cost      = 1.50; //shipping cost
$taxes              = array( //List your Taxes percent h
                            'Service Tax' => 5
                            );                      
//connect to MySql                      
$mysqli = new mysqli($db_host, $db_username, $db_password,$db_name);                        
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}