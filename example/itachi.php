<?php

require_once __DIR__ . "/../vendor/autoload.php";

$saucenao = new \Nekoding\Saucenao\Saucenao("496714e4bd2cc4a7486c4590f3c5bc8f2c87b7f8");
$result = $saucenao->fromUrl("https://s1.zerochan.net/Uchiha.Itachi.600.3175530.jpg");

print_r($result);
