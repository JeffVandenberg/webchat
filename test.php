<!DOCTYPE HTML>
<html>
<head>
    <title>HTML5 localStorage (name/value item pairs) demo</title>
    <style >
        td, th {
            font-family: monospace;
            padding: 4px;
            background-color: #ccc;
        }
        #hoge {
            border: 1px dotted blue;
            padding: 6px;
            background-color: #ccc;
            margin-right: 50%;
        }
        #items_table {
            border: 1px dotted blue;
            padding: 6px;
            margin-top: 12px;
            margin-right: 50%;
        }
        #items_table h2 {
            font-size: 18px;
            margin-top: 0px;
            font-family: sans-serif;
        }
        label {
            vertical-align: top;
        }
    </style>
</head>
<body onload="doShowAll()">
<h1>HTML5 localStorage (name/value item pairs) demo</h1>

<form name=editor>

    <div id="hoge">
        <p>
            <label>Value: <textarea name=data cols=41 rows=10></textarea></label>
        </p>

        <p>
            <label>Name: <input name=name></label>
            <input type=button value="getItem()" onclick="doGetItem()">
            <input type=button value="setItem()" onclick="doSetItem()">
            <input type=button value="removeItem()" onclick="doRemoveItem()">
        </p>
    </div>

    <div id="items_table">
        <h2>Items</h2>
        <table id=pairs></table>
        <p>
            <label><input type=button value="clear()" onclick="doClear()"> <i>* clear() removes all items</i></label>
        </p>
    </div>


    <script>

        function doSetItem() {
            var name = document.forms.editor.name.value;
            var data = document.forms.editor.data.value;
            localStorage.setItem(name, data);
            doShowAll();
        }

        function doGetItem() {
            var name = document.forms.editor.name.value;
            document.forms.editor.data.value = localStorage.getItem(name);
            doShowAll();
        }

        function doRemoveItem() {
            var name = document.forms.editor.name.value;
            document.forms.editor.data.value = localStorage.removeItem(name);
            doShowAll();
        }

        function doClear() {
            localStorage.clear();
            doShowAll();
        }

        function doShowAll() {
            var key = "";
            var pairs = "<tr><th>Name</th><th>Value</th></tr>\n";
            var i=0;
            for (i=0; i<=localStorage.length-1; i++) {
                key = localStorage.key(i);
                pairs += "<tr><td>"+key+"</td>\n<td>"+localStorage.getItem(key)+"</td></tr>\n";
            }
            if (pairs == "<tr><th>Name</th><th>Value</th></tr>\n") {
                pairs += "<tr><td><i>empty</i></td>\n<td><i>empty</i></td></tr>\n";
            }
            document.getElementById('pairs').innerHTML = pairs;
        }

    </script>

</form>

</body>
</html>
