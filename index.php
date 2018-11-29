<form action="imgTool/imageupload.php" method="post" name="form" enctype="multipart/form-data" >

    <table border="0">
        <tr bgcolor="#cccccc">
            <td width="50">item</td>
            <td width="150">Quantity</td>
        </tr>
        <tr>
            <td>ControllerNamespace</td>
            <td align="right"><input type="text" name="ControllerNamespace" size="100" maxlength="100"/></td>
        </tr>
        <tr>
            <td>ControllerName</td>
            <td align="right"><input type="text" name="ControllerName" size="100" maxlength="100"/></td>
        </tr>
        <tr>
            <td>ModelsNamespace</td>
            <td align="right"><input type="text" name="ModelsNamespace" size="100" maxlength="100"/></td>
        </tr>
        <tr>
            <td>setRules</td>
            <td align="right"><input type="file" name="file"/></td>
        </tr>
        <tr>
            <td>How did you find bob?</td>
            <td>
                <select name="find" >
                    <option value="a">a value</option>
                    <option value="b">b value</option>
                    <option value="c">c value</option>
                    <option value="d">d value</option>
                </select>
            </td>
        </tr>
        <tr>
            <td bgcolor="aqua" align="center">Distance</td>
            <td bgcolor="aqua" align="center">Cost</td>
        </tr>
        <?php
        $distance = 50;
        while($distance<250){
            echo "<tr>
                     <td align=\"right\">".$distance."</td>
                     <td align=\"right\">".($distance/10)."</td>
                  </tr>\n";
            $distance+=50;
        }
        ?>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" value="Submit Order"></td>
        </tr>
    </table>

</form>
