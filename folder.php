<?php

    global $FOLDER, $LANGS, $PLUGIN_NAME, $STRING_INDEXES, $STRINGS;
    if (isset($_GET['folder'])) {
        $FOLDER = $_GET['folder'];
    } else {
        return;
    }

    function getFolder() {
        global $FOLDER;
        return $FOLDER;
    }

    function getPluginName() {
        global $PLUGIN_NAME;
        return $PLUGIN_NAME;
    }

    function getTotalLangs() {
        global $LANGS;
        return count($LANGS);
    }

    function getHTMLTable() {
        global $LANGS, $STRING_INDEXES, $FOLDER, $PLUGIN_NAME, $STRINGS;
        $t = '<table border=1>';
        $t .= '<tr><td>String index</td>';
        foreach ($LANGS as $l) $t .= '<td>' . $l . '</td>';
        $t .= '</tr>';
        foreach ($STRING_INDEXES as $i) {
            $t .= "<tr><td>$i</td>";
            foreach ($LANGS as $l) {
                $t .= "<td><span class='$l-$i' ondblclick='open_edit(\"$l\", \"$i\")'>". $STRINGS[$l][$i]."</span>
                        <div id='$l-$i'></div></td>";
            }
            $t .= "</tr>";
        }
        $t .= '</table>';
        return $t;
    }

    function verifyFolder() {
        global $FOLDER;
        if (!file_exists($FOLDER)) return 'Path doesn\'t exist!';
        if (!is_dir($FOLDER)) return 'It\'s not a folder!';

        loadLangFolders();
        loadPluginName();
        loadStringsAndIndexes();
    }

    function loadLangFolders() {
        global $FOLDER, $LANGS;
        $dh = opendir($FOLDER);
        while (false !== ($filename = readdir($dh))) {
            if (is_dir($FOLDER . '/' . $filename) && $filename != '.' && $filename != '..')
                $LANGS[] = $filename;
        }
        $r = count($LANGS) . ' lang found: ';
        foreach($LANGS as $t) {
            $r .= $t . ', ';
        }
        return $r;
    }

    function loadPluginName() {
        global $FOLDER, $LANGS, $PLUGIN_NAME;

        foreach($LANGS as $t) {
            $dh  = opendir($FOLDER . '/' . $t);
            while (false !== ($filename = readdir($dh))) {
                if (is_file($FOLDER . '/' . $t . '/' . $filename) && strpos($filename, 'index') === False) {
                    if (strpos($FOLDER, str_replace('.php', '', $filename)) !== False) {
                        $PLUGIN_NAME = str_replace('.php', '', $filename);
                        return $PLUGIN_NAME;
                    }
                }
            }
        }
    }

    function loadStringsAndIndexes() {
        global $FOLDER, $LANGS, $PLUGIN_NAME, $STRING_INDEXES, $STRINGS;

        $STRING_INDEXES = array();

        foreach($LANGS as $t) {
            $string = [];
            require_once($FOLDER . '/' . $t . '/' . $PLUGIN_NAME . '.php');
            $keys = array_keys($string);
            
            foreach ($keys as $key) {
                if (!in_array($key, $STRING_INDEXES)){
                    $STRING_INDEXES[] = $key;
                }
            }
        }

        foreach($LANGS as $t) {
            
            $string = [];
            $STRINGS[$t] = array();

            require($FOLDER . '/' . $t . '/' . $PLUGIN_NAME . '.php');
            
            foreach($STRING_INDEXES as $i) {
                if (isset($string[$i]))
                    $STRINGS[$t][$i] = htmlentities(addslashes($string[$i]));
                else
                $STRINGS[$t][$i] = '???';
            }
            
        }
    }


?>