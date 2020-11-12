<?php require_once('folder.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LangDevMoodle</title>
</head>
<body>
    <header>
        LangDevMoodle - Insert new entries to lang files in Moodle plugins
    </header>
    <main>
        <section>
            <form>
                Moodle plugin lang folder:
                <input type="text" name="folder" value="<?= getFolder() ?>">
                <input type="submit" value="Go!">
                <span> <?= verifyFolder() ?> </span>
            </form>
        </section>
        <section>
            Plugin: <?= (getPluginName() != '' ? getPluginName() : 'Not identified') ?>
        </section>
        <section>
            <?= getHTMLTable() ?>
        </section>
        <section>
            <button onclick="add_new()">Add new string</button>
        </section>
    </main>

    <script>
        function add_new () {
            var table = document.getElementsByTagName('table')[0];
            var row = table.insertRow();
            for (i = 0; i <= <?= getTotalLangs() ?>; i++) {
                var cell1 = row.insertCell();
                var input = document.createElement('input');
                cell1.appendChild(input);
            }
        }

        function open_edit (lang, key) {
            var span = document.getElementsByClassName(lang + '-' + key)[0];
            var text = span.textContent;
            document.getElementsByClassName(lang + '-' + key)[0].style.display = 'none';

            var input = document.createElement('input');
            input.value = text;
            input.id = 'input-' + lang + '-' + key;
            document.getElementById(lang + '-' + key).appendChild(input);
            input.focus();

            var button = document.createElement('input');
            button.type = 'submit';
            button.value = 'ok';
            button.onclick = function () {
                save_edit(lang, key, input.value, '<?= getFolder() ?>', '<?= getPluginName() ?>');
                span.textContent = input.value;
                span.style.display = 'block';
                input.remove();
                button.remove();
                
            };
            document.getElementById(lang + '-' + key).appendChild(button);
        }

        function save_edit (lang, key, value, path, plugin) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    //document.getElementById("demo").innerHTML = this.responseText;
                }
            };
            xhttp.open("POST", "save.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("lang="+lang+"&key="+key+"&value="+value+"&path="+path+"&plugin="+plugin);
        }
    </script>
</body>
</html>