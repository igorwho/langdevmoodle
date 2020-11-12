<?php

    $folder = $_POST['path'];
    $lang = $_POST['lang'];
    $plugin = $_POST['plugin'];
    $key = $_POST['key'];
    $value = $_POST['value'];

    $string = [];
    require_once($folder . '/' . $lang . '/' . $plugin . '.php');

    if (isset($string[$key])) {

        $lines = array();
        $handle = @fopen($folder . '/' . $lang . '/' . $plugin . '.php', "r");
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                $lines[] = $buffer;
            }
            fclose($handle);
        }

        $fp = fopen($folder . '/' . $lang . '/' . $plugin . '.php', 'w');
        foreach($lines as $line) {
            
            fwrite($fp, $line);
        }
        fclose($fp);

    } else {

    }
?>