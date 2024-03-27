<?php

require_once 'fw/ElasticSearchLogger.php';
$logger = new ElasticSearchLogger();

if (!isset ($_POST["provider"]) || !isset ($_POST["terms"]) || !isset ($_POST["userid"])) {
   $logger->log('WARN', 'Search attempted with insufficient information.');
   exit ("Not enough information provided");
}

$provider = $_POST["provider"];
$terms = $_POST["terms"];
$userid = $_POST["userid"];

sleep(1); // this is a long, long search!!

$logger->log('INFO', "Search performed by user $userid: $terms", ['provider' => $provider]);

function callAPI($method, $url, $data)
{
   $curl = curl_init();
   switch ($method) {
      case "POST":
         curl_setopt($curl, CURLOPT_POST, 1);
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         break;
      case "PUT":
         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         break;
      default:
         if ($data)
            $url = sprintf("%s?%s", $url, http_build_query($data));
   }
   // OPTIONS:
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   // EXECUTE:
   $result = curl_exec($curl);
   if (!$result) {
      $result = "No results found!";
   }
   curl_close($curl);
   return $result;
}


$theurl = 'http://localhost' . $provider . '?userid=' . $userid . '&terms=' . $terms;
$get_data = callAPI('GET', $theurl, false);

echo $get_data;
?>