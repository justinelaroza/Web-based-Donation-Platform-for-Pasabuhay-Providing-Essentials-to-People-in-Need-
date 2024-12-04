<?php

    require_once __DIR__ . '/../../../Database/db.php';
    require_once __DIR__ . '/../utility/util.php';

    $controllerName = pathinfo(basename($_SERVER['PHP_SELF']), PATHINFO_FILENAME);
    $modelFile = __DIR__ . "/../../model/{$controllerName}.model.php"; 

    if (file_exists($modelFile)) {
        require_once $modelFile;
    } else {
        die("Error: Model file '{$modelFile}' not found.");
    }

?>