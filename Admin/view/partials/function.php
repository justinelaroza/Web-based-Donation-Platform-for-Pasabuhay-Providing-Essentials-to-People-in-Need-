<?php 

    function cssPath() {
        // Get the current name without the extension / walang full path
        $baseName = pathinfo(basename($_SERVER['PHP_SELF']), PATHINFO_FILENAME);
        return "../view/styles/{$baseName}.view.css";
    }

?>