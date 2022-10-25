<?php
require "config.php";

define("API_URL", "https://api.shodan.io/shodan/host/search?");
$page_size = 100;


function request_construct(){
    $key = API_KEY;

    $query = "Minecraft " . MC_VERSION;
    $data = array("key" => $key, "query" => $query);
    return http_build_query($data);
}

function request_exec($data){
    echo $data;
   $output = file_get_contents(API_URL . $data);
   $output = json_decode($output);
   #$output = API_URL . $data;
    return $output;
}

function gen_html($ip, $port, $active){
    return "
    <div class='server active-$active'>$ip:$port</div>
    ";
}

function build_all($json){
    $serverlist = "";
    foreach($json->matches as $match){
        $ip = $match->ip_str;
        $port = $match->port;
        if(strpos($match->data,"Online Players: 0") !== false){
            $active = "false";
        } else{
            $active = "true";
        }
        $serverlist = $serverlist . gen_html($ip, $port, $active);
    }
    $header = "<link rel='stylesheet' href='./style.css'>
    <title>popbobhook - TrollSec</title>
    <h1>popbobhook - Trollface Security, GNAA International</h1>
    <span class='message'>
    100 most recently indexed minecraft 1.17.1 servers. 
    Updated every 2 mins. Enjoy. -GNAA Founder, Jonathan Negro</br>
    <a href='https://gnaa.tk'>GNAA website</a>
    <br>Red = players are online</br>
    </span>";
    file_put_contents(OUTPUT_FILE, $header . $serverlist);
}

$json = request_exec(
    request_construct()
);

build_all($json);
