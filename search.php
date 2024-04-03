<?php
require_once 'fw/ElasticSearchLogger.php';
$logger = new ElasticSearchLogger();
if (!isset($_POST["provider"]) || !isset($_POST["terms"]) || !isset($_POST["userid"])) {
   $logger->log('WARN', 'Search attempted with insufficient information.', ['POST' => $_POST]);
   exit("Not enough information provided");
}
$provider = htmlspecialchars($_POST["provider"], ENT_QUOTES, 'UTF-8');
$terms = htmlspecialchars($_POST["terms"], ENT_QUOTES, 'UTF-8');
$userid = intval($_POST["userid"]);
$logger->log('INFO', "Search initiated", ['provider' => $provider, 'terms' => $terms, 'userid' => $userid]);

function callAPI($method, $url, $data)
{
   $logger = new ElasticSearchLogger();
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
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   $result = curl_exec($curl);
   if (!$result) {
      $logger->log('WARN', 'Search API call returned no results', ['url' => $url]);
      $result = "No results found!";
   } else {
      $logger->log('INFO', 'Search API call successful', ['url' => $url, 'result' => $result]);
   }
   curl_close($curl);
   return $result;
}
$theurl = 'http://localhost' . $provider . '?userid=' . urlencode($userid) . '&terms=' . urlencode($terms);
$get_data = callAPI('GET', $theurl, false);
echo $get_data;
?>