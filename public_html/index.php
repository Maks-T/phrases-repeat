<?php

try {
    require_once $_SERVER["DOCUMENT_ROOT"] . "/../app/bootstrap.php";
} catch (\Throwable $e) {
    de($e->getMessage());
}

echo 'Hello word!';


?>
