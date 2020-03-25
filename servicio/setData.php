<?php

header("Access-Control-Allow-Origin: *");
require_once '../_dbdata/_dataset.php';

$_setDatap = NEW _dataset();
$_returresult = $_setDatap->_setValorIndicador('2020-02-20','2020-03-25',$_GET['indicador_id'],null);
$_dataretorno = json_encode($_returresult);
echo $_dataretorno;
die();