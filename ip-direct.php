<?php
// Mendapatkan alamat IP pengguna
$ip = $_SERVER['REMOTE_ADDR'];

// Mengidentifikasi apakah alamat IP dari Indonesia
$ipDetails = json_decode(file_get_contents("http://ip-api.com/json/{$ip}"));
$isFromIndonesia = ($ipDetails->country == "Indonesia");

// Menentukan URL tujuan berdasarkan negara
$url = ($isFromIndonesia) ? "https://google.co.id/" : "https://google.com/";

// Mengarahkan pengguna ke URL yang tepat
header("Location: {$url}");
exit();
?>