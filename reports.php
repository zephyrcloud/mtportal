<?php
include("config/connection.php");
include("dictionary.php");
$dict= new dictionary();
?>

<html>
    <head>
        <title>Reports</title>
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
        <script>
            function goTo(destination) {
                window.location.href = destination;
            }
            $(function () {
                $("#especific_day").datepicker({dateFormat: 'yy-mm-dd'});
            });
        </script>
        <!-- JQuery UI -->
		<link rel="stylesheet" href="style/jquery-ui/jquery-ui.css">
		<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

    </head>
    <body>

        <?php
        include("header.php");
        include("menuadmin.php");
        ?>

        <div id="pagecontents">

            <div class="wrapper" >

               
                <div id="content">
				    
                     <div id="post">
                             <div id="postitle">
								<div class="floatleft"><h1>Reports</h1></div>
								<div class="floatright righttext tpad"></div>
								<div class="clear">&nbsp;</div>
							</div>                                                           
                                <div id="menu_list">
                                        
                                        <div id="find" >
                               <?php echo $dict->words("70");?>
                                        <select id="opciones" name="opciones" onclick="apply()" >
                                         <option value="0"><?php echo $dict->words("71");?></option>
                                         <option value="1"><?php echo $dict->words("72");?></option>
                                         <option value="2"><?php echo $dict->words("73");?></option>
                                         <option value="3"><?php echo $dict->words("74");?></option>
                                         <option value="4"><?php echo $dict->words("75");?></option>
                                         <option value="5"><?php echo $dict->words("76");?></option>                                         
                                        </select>
                                        <form method="POST" action="reports.php">	
                                        <input id="especific_day" name="especific_day" type="text" size="4" style="display:none;">
                                        <input hidden id="save" type="submit" value=" <?php echo $dict->words("78");?>" />
                                        </form>
                                        <br>
                                </div>
                                
                                <div id="user_parameter" style='display:none;'>
                               <?php echo $dict->words("77");?>:
                                        <select id="opciones_user" name="opciones_user"  onclick="buscar('user')"  >
                                         <option value="0"><?php echo $dict->words("71");?></option>
                <?php
                $select_customers_query = 'SELECT `id`, `name` FROM `customer`';
                $select_customers_result = mysql_query($select_customers_query) or die($dict->words("12").' ' . mysql_error());

                while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {

                    echo "<option  value=" . $line['id'] . ">" . $line['name'] . "</option>";
                }
                ?>
                                        </select>
                                        <br>
                                        <form method="POST" action="reports.php">
                                                <input id="user_id_field" name="user_id_field" type="text" style="display:none;">
                                                <input type="submit" value="<?php echo $dict->words("78");?>" />
                                        </form>
                                </div>
                        
                                <div id="actionType_parameter" style='display:none;'>
                               <?php echo $dict->words("77");?>:
                                        <select id="opciones_actionType" name="opciones_actionType"  onclick="buscar('actionType')"  >
                                         <option value="0"><?php echo $dict->words("71");?></option>
<?php
$select_customers_query = 'SELECT `id`, `action_name` FROM `action_type`';
$select_customers_result = mysql_query($select_customers_query) or die($dict->words("12").' ' . mysql_error());

while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {

    echo "<option  value=" . $line['id'] . ">" . $line['action_name'] . "</option>";
}
?>
                                        </select>
                                        <br>
                                        <form method="POST" action="reports.php">
                                                <input id="actionType_id_field" name="actionType_id_field" type="text" style="display:none;">
                                                <input type="submit" value="<?php echo $dict->words("78");?>" />
                                        </form>
                                </div>
                        
                                <div id="table_modified_parameter" style='display:none;'>
                               <?php echo $dict->words("77");?>:
                                        <select id="opciones_table_modified" name="opciones_actionType"  onclick="buscar('table_modified')"  >
                                         <option value="0"><?php echo $dict->words("71");?></option>
<?php
$select_customers_query = 'SELECT `id`, `table_name` FROM `table_modified`';
$select_customers_result = mysql_query($select_customers_query) or die($dict->words("12").' ' . mysql_error());

while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {

    echo "<option  value=" . $line['id'] . ">" . $line['table_name'] . "</option>";
}
?>
                                        </select>
                                        

                                        <br>
                                        <form method="POST" action="reports.php">
                                                <input id="table_modified_id_field" name="table_modified_id_field" type="text" style="display:none;">
                                                <input type="submit" value="Apply" />
                                        </form>
                                </div>
                                
                                <div id="result_parameter" style='display:none;'>
                               <?php echo $dict->words("77");?>:
                                        <select id="opciones_result" name="opciones_result"  onclick="buscar('result')"  >
                                         <option value="0"><?php echo $dict->words("71");?></option>
<?php
$select_customers_query = 'SELECT `id`, `result_name` FROM `result`';
$select_customers_result = mysql_query($select_customers_query) or die($dict->words("12").' ' . mysql_error());

while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {

    echo "<option  value=" . $line['id'] . ">" . $line['result_name'] . "</option>";
}
?>
                                        </select>
                                        
                                        
                                        <br>
                                        <form method="POST" action="reports.php">
                                                <input id="result_id_field" name="result_id_field" type="text" style="display:none;">
                                                <input type="submit" value="Apply" />
                                        </form>
                                </div>

                                        <br>						
                                        <form method="POST" action="reports.php">
                                        <input id="close" name="close" type="text" size="4" style="display:none;" value="1">						
                                        <input  id="end_report" type="submit" value="<?php echo $dict->words("79");?>" />
<?php
$select_customers_query = 'SELECT min(id) as minimo FROM `log` WHERE id_counter = 0';

$select_customers_result = mysql_query($select_customers_query)or die($dict->words("12").' ' . mysql_error());

while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
    $minimo = $line['minimo'];
    echo '<input id="minimo" name="minimo" type="text" size="4" style="display:none;" value="' . $minimo . '">';
}
$select_customers_query = 'SELECT max(id) as maximo FROM `log` WHERE id_counter = 0';
$select_customers_result = mysql_query($select_customers_query) or die($dict->words("12").' ' . mysql_error());

while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {

    $maximo = $line['maximo'];
    echo '<input id="maximo" name="maximo" type="text" size="4" style="display:none;" value="' . $maximo . '">';
}
?>
                                        </form>
                                    </div>
                                <table>
                                                <col width="170px">
                                                <col width="150px">
                                                <col width="150px">
                                                <col width="150px">
                                                <col width="170px">
												<col width="190px">
                                                <tr>
                                                        <th style="border: 1px solid;"><?php echo $dict->words("80");?></th>
                                                        <th style="border: 1px solid;"><?php echo $dict->words("81");?></th>
                                                        <th style="border: 1px solid;"><?php echo $dict->words("82");?></th>
                                                        <th style="border: 1px solid;"><?php echo $dict->words("83");?></th>
                                                        <th style="border: 1px solid;"><?php echo $dict->words("84");?></th>
                                                        <th style="border: 1px solid;"><?php echo $dict->words("85");?></th>
                                                </tr>
                                                
<?php
if (isset($_POST["close"])) {


    for ($i = $_POST["minimo"]; $i <= $_POST["maximo"]; $i++) {

        $update_app_query = 'UPDATE `log` SET `id_counter` = 1 WHERE `id` = ' . $i;
        $update_app_result = mysql_query($update_app_query);
    }
}

if (isset($_POST["rep_history"])) {
    $select_customers_query = 'SELECT l.timeStamp as time ,l.ipAddress as ip, c.name as name , at.action_name as action , tm.table_name as table_name, r.result_name as result
														   FROM log l , customer c , action_type at, table_modified tm , result r 
														   WHERE l.id_user = c.id AND at.id = l.id_actionType AND tm.id = l.id_tableModified AND r.id = l.id_result
														   AND l.timeStamp < NOW() AND id_counter <> 0  ORDER BY l.timeStamp DESC ';

    $select_customers_result = mysql_query($select_customers_query) or die($dict->words("12").' ' . mysql_error());

    while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {

       if($line['result'] == 'Success'){
	        echo "<tr bgcolor='#DFF2BF' id='tr" . $line['id'] . "'>";
			echo "<td style='border: 1px solid;'><span id='spanName'>" . $line['time'] . "</span></td>";
			echo "<td style='border: 1px solid;'><span id='spanUserName'>" . $line['ip'] . "</span></td>";
			echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['name'] . "</span></td>";
			echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['action'] . "</span></td>";
			echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['table_name'] . "</span></td>";
			echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['result'] . "</span></td>";
			echo "</tr>";
		}else{
			 echo "<tr bgcolor='#FFBABA' id='tr" . $line['id'] . "'>";
			echo "<td style='border: 1px solid;'><span id='spanName'>" . $line['time'] . "</span></td>";
			echo "<td style='border: 1px solid;'><span id='spanUserName'>" . $line['ip'] . "</span></td>";
			echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['name'] . "</span></td>";
			echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['action'] . "</span></td>";
			echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['table_name'] . "</span></td>";
			echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['result'] . "</span></td>";
			echo "</tr>";
		}
    }
}


if (isset($_POST["user_id_field"])) {
    if ($_POST["user_id_field"] != "0") {
        $select_customers_query = 'SELECT l.timeStamp as time ,l.ipAddress as ip, c.name as name , at.action_name as action , tm.table_name as table_name, r.result_name as result
														   FROM log l , customer c , action_type at, table_modified tm , result r 
														   WHERE l.id_user = c.id AND at.id = l.id_actionType AND tm.id = l.id_tableModified AND r.id = l.id_result
														   AND id_user =' . $_POST["user_id_field"] . ' AND id_counter <> 1 ORDER BY l.timeStamp DESC ';
    }
} else {
    if (isset($_POST["actionType_id_field"])) {
        if ($_POST["actionType_id_field"] != "0") {
            $select_customers_query = 'SELECT l.timeStamp as time ,l.ipAddress as ip, c.name as name , at.action_name as action , tm.table_name as table_name, r.result_name as result
															   FROM log l , customer c , action_type at, table_modified tm , result r 
															   WHERE l.id_user = c.id AND at.id = l.id_actionType AND tm.id = l.id_tableModified AND r.id = l.id_result
															   AND id_actionType =' . $_POST["actionType_id_field"] . ' AND id_counter <> 1 ORDER BY l.timeStamp DESC';
        }
    } else {
        if (isset($_POST["actionType_id_field"])) {
            if ($_POST["actionType_id_field"] != "0") {
                $select_customers_query = 'SELECT l.timeStamp as time ,l.ipAddress as ip, c.name as name , at.action_name as action , tm.table_name as table_name, r.result_name as result
														   FROM log l , customer c , action_type at, table_modified tm , result r 
														   WHERE l.id_user = c.id AND at.id = l.id_actionType AND tm.id = l.id_tableModified AND r.id = l.id_result
														   AND id_actionType =' . $_POST["actionType_id_field"] . ' AND id_counter <> 1 ORDER BY l.timeStamp DESC';
            }
        } else {
            if (isset($_POST["table_modified_id_field"])) {
                if ($_POST["table_modified_id_field"] != "0") {
                    $select_customers_query = 'SELECT l.timeStamp as time ,l.ipAddress as ip, c.name as name , at.action_name as action , tm.table_name as table_name, r.result_name as result
																	   FROM log l , customer c , action_type at, table_modified tm , result r 
																	   WHERE l.id_user = c.id AND at.id = l.id_actionType AND tm.id = l.id_tableModified AND r.id = l.id_result
																	   AND id_tableModified =' . $_POST["table_modified_id_field"] . ' AND id_counter <> 1 ORDER BY l.timeStamp DESC';
                }
            } else {
                if (isset($_POST["result_id_field"])) {
                    if ($_POST["result_id_field"] != "0") {
                        $select_customers_query = 'SELECT l.timeStamp as time ,l.ipAddress as ip, c.name as name , at.action_name as action , tm.table_name as table_name, r.result_name as result
																		   FROM log l , customer c , action_type at, table_modified tm , result r 
																		   WHERE l.id_user = c.id AND at.id = l.id_actionType AND tm.id = l.id_tableModified AND r.id = l.id_result
																		   AND id_result =' . $_POST["result_id_field"] . ' AND id_counter <> 1 ORDER BY l.timeStamp DESC';
                    }
                } else {
                    $select_customers_query = 'SELECT l.timeStamp as time ,l.ipAddress as ip, c.name as name , at.action_name as action , tm.table_name as table_name, r.result_name as result
														   FROM log l , customer c , action_type at, table_modified tm , result r 
														   WHERE l.id_user = c.id AND at.id = l.id_actionType AND tm.id = l.id_tableModified AND r.id = l.id_result AND id_counter <> 1
														   ORDER BY l.timeStamp DESC';
                }
            }
        }
    }
}

if (isset($_POST["id_order"])) {

    switch ($_POST["id_order"]) {
        case 1:
            $select_customers_query = 'SELECT l.timeStamp as time ,l.ipAddress as ip, c.name as name , at.action_name as action , tm.table_name as table_name, r.result_name as result FROM log l , customer c , action_type at, table_modified tm , result r WHERE l.id_user = c.id AND at.id = l.id_actionType AND tm.id = l.id_tableModified AND r.id = l.id_result AND id_counter <> 1 ORDER BY l.id_user ';
            break;
        case 2:
            $select_customers_query = 'SELECT l.timeStamp as time ,l.ipAddress as ip, c.name as name , at.action_name as action , tm.table_name as table_name, r.result_name as result FROM log l , customer c , action_type at, table_modified tm , result r WHERE l.id_user = c.id AND at.id = l.id_actionType AND tm.id = l.id_tableModified AND r.id = l.id_result AND id_counter <> 1 ORDER BY l.id_actionType ';
            break;
        case 3:
            $select_customers_query = 'SELECT l.timeStamp as time ,l.ipAddress as ip, c.name as name , at.action_name as action , tm.table_name as table_name, r.result_name as result FROM log l , customer c , action_type at, table_modified tm , result r WHERE l.id_user = c.id AND at.id = l.id_actionType AND tm.id = l.id_tableModified AND r.id = l.id_result AND id_counter <> 1 ORDER BY l.id_tableModified ';
            break;
        case 4:
            $select_customers_query = 'SELECT l.timeStamp as time ,l.ipAddress as ip, c.name as name , at.action_name as action , tm.table_name as table_name, r.result_name as result FROM log l , customer c , action_type at, table_modified tm , result r WHERE l.id_user = c.id AND at.id = l.id_actionType AND tm.id = l.id_tableModified AND r.id = l.id_result AND id_counter <> 1 ORDER BY l.id_result ';
            break;
        case 5:
            $select_customers_query = 'SELECT l.timeStamp as time ,l.ipAddress as ip, c.name as name , at.action_name as action , tm.table_name as table_name, r.result_name as result FROM log l , customer c , action_type at, table_modified tm , result r WHERE l.id_user = c.id AND at.id = l.id_actionType AND tm.id = l.id_tableModified AND r.id = l.id_result AND id_counter <> 1 ORDER BY l.timeStamp ASC ';
            break;
        case 6:
            $select_customers_query = 'SELECT l.timeStamp as time ,l.ipAddress as ip, c.name as name , at.action_name as action , tm.table_name as table_name, r.result_name as result FROM log l , customer c , action_type at, table_modified tm , result r WHERE l.id_user = c.id AND at.id = l.id_actionType AND tm.id = l.id_tableModified AND r.id = l.id_result AND id_counter <> 1 ORDER BY l.timeStamp DESC ';
            break;
    }
}

if (isset($_POST["especific_day"])) {
    $select_customers_query = 'SELECT l.timeStamp as time ,l.ipAddress as ip, c.name as name , at.action_name as action , tm.table_name as table_name, r.result_name as result
														   FROM log l , customer c , action_type at, table_modified tm , result r 
														   WHERE l.id_user = c.id AND at.id = l.id_actionType AND tm.id = l.id_tableModified AND r.id = l.id_result
														   AND l.timeStamp LIKE "%' . $_POST["especific_day"] . '%" AND id_counter <> 1 ORDER BY l.timeStamp ';
}

$select_customers_result = mysql_query($select_customers_query) or die($dict->words("12").' ' . mysql_error());

while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {

   if($line['result'] == 'Success'){
	        echo "<tr bgcolor='#DFF2BF' id='tr" . $line['id'] . "'>";
			echo "<td style='border: 1px solid;'><span id='spanName'>" . $line['time'] . "</span></td>";
			echo "<td style='border: 1px solid;'><span id='spanUserName'>" . $line['ip'] . "</span></td>";
			echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['name'] . "</span></td>";
			echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['action'] . "</span></td>";
			echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['table_name'] . "</span></td>";
			echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['result'] . "</span></td>";
			echo "</tr>";
		}else{
			 echo "<tr bgcolor='#FFBABA' id='tr" . $line['id'] . "'>";
			echo "<td style='border: 1px solid;'><span id='spanName'>" . $line['time'] . "</span></td>";
			echo "<td style='border: 1px solid;'><span id='spanUserName'>" . $line['ip'] . "</span></td>";
			echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['name'] . "</span></td>";
			echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['action'] . "</span></td>";
			echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['table_name'] . "</span></td>";
			echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['result'] . "</span></td>";
			echo "</tr>";
		}
}
?>
                                                
                                        </table>
                    
                        </div>
				
                </div>


                <script type="text/javascript">
                    jQuery(document).ready(function ($) {
                        $('#tabs').tab();
                    });
                </script>

                <script>

                    function menu_list() {
                        var lista = document.getElementById("option_menu");

                        // Obtener el índice de la opción que se ha seleccionado
                        var indiceSeleccionado = lista.selectedIndex;
                        // Con el índice y el array "options", obtener la opción seleccionada
                        var opcionSeleccionada = lista.options[indiceSeleccionado];

                        // Obtener el valor y el texto de la opción seleccionada
                        var textoSeleccionado = opcionSeleccionada.text;
                        var valorSeleccionado = opcionSeleccionada.value;

                        if (valorSeleccionado == "0") {
                            document.getElementById('find').style.display = 'none';
                            document.getElementById('order').style.display = 'none';
                            document.getElementById('user_parameter').style.display = 'none';
                            document.getElementById('actionType_parameter').style.display = 'none';
                            document.getElementById('table_modified_parameter').style.display = 'none';
                            document.getElementById('result_parameter').style.display = 'none';

                        }

                        if (valorSeleccionado == "1") {
                            document.getElementById('find').style.display = 'block';
                            document.getElementById('order').style.display = 'none';
                        }
                        if (valorSeleccionado == "2") {
                            document.getElementById('order').style.display = 'block';
                            document.getElementById('find').style.display = 'none';
                            document.getElementById('user_parameter').style.display = 'none';
                            document.getElementById('actionType_parameter').style.display = 'none';
                            document.getElementById('table_modified_parameter').style.display = 'none';
                            document.getElementById('result_parameter').style.display = 'none';
                        }
                    }

                    function apply() {
                        // Obtener la referencia a la lista
                        var lista = document.getElementById("opciones");

                        // Obtener el índice de la opción que se ha seleccionado
                        var indiceSeleccionado = lista.selectedIndex;
                        // Con el índice y el array "options", obtener la opción seleccionada
                        var opcionSeleccionada = lista.options[indiceSeleccionado];

                        // Obtener el valor y el texto de la opción seleccionada
                        var textoSeleccionado = opcionSeleccionada.text;
                        var valorSeleccionado = opcionSeleccionada.value;

                        if (valorSeleccionado == "0") {
                            document.getElementById('user_parameter').style.display = 'none';
                            document.getElementById('actionType_parameter').style.display = 'none';
                            document.getElementById('table_modified_parameter').style.display = 'none';
                            document.getElementById('result_parameter').style.display = 'none';
                            document.getElementById('especific_day').style.display = 'none';
                            document.getElementById('save').style.display = 'none';
                        }

                        if (valorSeleccionado == "1") {
                            // document.getElementById('find').style.display = 'none';
                            document.getElementById('user_parameter').style.display = 'block';
                            document.getElementById('actionType_parameter').style.display = 'none';
                            document.getElementById('table_modified_parameter').style.display = 'none';
                            document.getElementById('result_parameter').style.display = 'none';
                            document.getElementById('especific_day').style.display = 'none';
                            document.getElementById('save').style.display = 'none';
                        }

                        if (valorSeleccionado == "2") {
                            // document.getElementById('find').style.display = 'none';
                            document.getElementById('actionType_parameter').style.display = 'block';
                            document.getElementById('table_modified_parameter').style.display = 'none';
                            document.getElementById('result_parameter').style.display = 'none';
                            document.getElementById('user_parameter').style.display = 'none';
                            document.getElementById('especific_day').style.display = 'none';
                            document.getElementById('save').style.display = 'none';
                        }

                        if (valorSeleccionado == "3") {
                            // document.getElementById('find').style.display = 'none';
                            document.getElementById('table_modified_parameter').style.display = 'block';
                            document.getElementById('result_parameter').style.display = 'none';
                            document.getElementById('user_parameter').style.display = 'none';
                            document.getElementById('actionType_parameter').style.display = 'none';
                            document.getElementById('especific_day').style.display = 'none';
                            document.getElementById('save').style.display = 'none';
                        }

                        if (valorSeleccionado == "4") {
                            // document.getElementById('find').style.display = 'none';
                            document.getElementById('result_parameter').style.display = 'block';
                            document.getElementById('actionType_parameter').style.display = 'none';
                            document.getElementById('table_modified_parameter').style.display = 'none';
                            document.getElementById('user_parameter').style.display = 'none';
                            document.getElementById('especific_day').style.display = 'none';
                            document.getElementById('save').style.display = 'none';
                        }
                        if (valorSeleccionado == "5") {
                            document.getElementById('especific_day').style.display = 'block';
                            document.getElementById('save').style.display = 'block';
                            document.getElementById('actionType_parameter').style.display = 'none';
                            document.getElementById('table_modified_parameter').style.display = 'none';
                            document.getElementById('result_parameter').style.display = 'none';
                            document.getElementById('user_parameter').style.display = 'none';
                        }

                        //alert(valorSeleccionado);
                    }

                    function buscar(nombre) {
                        // Obtener la referencia a la lista
                        var lista = document.getElementById("opciones_" + nombre);

                        // Obtener el índice de la opción que se ha seleccionado
                        var indiceSeleccionado = lista.selectedIndex;
                        // Con el índice y el array "options", obtener la opción seleccionada
                        var opcionSeleccionada = lista.options[indiceSeleccionado];

                        // Obtener el valor y el texto de la opción seleccionada
                        var textoSeleccionado = opcionSeleccionada.text;
                        var valorSeleccionado = opcionSeleccionada.value;

                        document.getElementById(nombre + "_id_field").value = valorSeleccionado;
                        //alert(valorSeleccionado);
                    }

                    function find_id_order() {
                        // Obtener la referencia a la lista
                        var lista = document.getElementById("order_option");

                        // Obtener el índice de la opción que se ha seleccionado
                        var indiceSeleccionado = lista.selectedIndex;
                        // Con el índice y el array "options", obtener la opción seleccionada
                        var opcionSeleccionada = lista.options[indiceSeleccionado];

                        // Obtener el valor y el texto de la opción seleccionada
                        var textoSeleccionado = opcionSeleccionada.text;
                        var valorSeleccionado = opcionSeleccionada.value;

                        document.getElementById("id_order").value = valorSeleccionado;

                    }
                </script>

            </div> <!-- container -->
            <script type="text/javascript" src="../bootstrap/js/bootstrap.js"></script>
        </div>

    </div>



<?php
include("footer.php");
?>

</body>
</html>