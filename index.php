<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/path/to/cryptyone/Cryptyone.php";

$token = "";
$URL = "";
$cryptyone = Cryptyone::getInstance();
$cryptyone->setParameters(["point" => 1, "senderNm" => "someuserid"), "memo" => AUTHORIZE_ID]);
$response = json_decode($cryptyone->create_token());
