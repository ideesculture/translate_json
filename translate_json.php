<?php

require_once("vendor/autoload.php");

if(!isset($argv[1])) die("No json given.\n");
if(!isset($argv[2])) die("No locale given.\n");

$json_file = $argv[1];
$locale = $argv[2];
$locale = str_replace("_", "-", $locale);
$pathinfo = pathinfo($json_file);
//var_dump($pathinfo );die();

$authKey = file_get_contents("key.ini"); // Replace with your key
$translator = new \DeepL\Translator($authKey);

//$result = $translator->translateText('Hello, world!', 'fr', 'en');

$expo = json_decode(file_get_contents($json_file), true);
$trad_expo = $expo;
foreach($expo["blocks"] as $key=>$block) {
	if(@$block["data"]["title"]) {
		$text = $block["data"]["title"];
		$result = $translator->translateText($text, 'fr', $locale);
		$trad_expo["blocks"][$key]["data"]["title"] = $result->text;
	}
	if(@$block["data"]["subtitle"]) {
		$text = $block["data"]["subtitle"];
		$result = $translator->translateText($text, 'fr', $locale);
		$trad_expo["blocks"][$key]["data"]["subtitle"] = $result->text;
	}
	if(@$block["data"]["text"]) {
		$text = $block["data"]["text"];
		$result = $translator->translateText($text, 'fr', $locale);
		$trad_expo["blocks"][$key]["data"]["text"] = $result->text;
	}
}

file_put_contents($pathinfo["dirname"]."/".$pathinfo["filename"]."_".$locale.".".$pathinfo["extension"], json_encode($trad_expo, JSON_PRETTY_PRINT));

