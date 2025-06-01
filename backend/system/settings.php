<?php

ob_start();
session_start();

date_default_timezone_set('Europe/Istanbul');

/*domain full pathini belirtin
=> geliştirme ortamında http://localhost/file-management olarak çalıştırılmıştır
=> hostingte gerçek adres belirtilmelidir örn(https://www.google.com)
*/
$domain = 'http://localhost/file-management';


// projenin kurulu olduğu dosya dizini
$basePath = '/file-management/';


// veritabanı hostname
$host = 'localhost';


// veritabanı adı
$dbname = 'file_management_db';


// vertiabanı kullanıcı adı
$username = 'root';



// veritabanı şifresi
$password = '';

//şifreleme için bir şifre kullanın
$jwtSecret = 'Admin1234!';

?>