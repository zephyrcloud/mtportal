<?php
include("config/connection.php");
include("dictionary.php");
$dict= new dictionary();
?>

<html>
    <head>
        <title>Archive reports</title>
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
								<div class="floatleft"><h1>Archive reports</h1></div>
								<div class="floatright righttext tpad"></div>
								<div class="clear">&nbsp;</div>
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
    $select_customers_query = 'SELECT l.timeStamp as time ,l.ipAddress as ip, c.name as name , at.action_name as action , tm.table_name as table_name, r.result_name as result
														   FROM log l , customer c , action_type at, table_modified tm , result r 
														   WHERE l.id_user = c.id AND at.id = l.id_actionType AND tm.id = l.id_tableModified AND r.id = l.id_result
														   AND l.timeStamp < NOW() AND id_counter <> 0  ORDER BY l.timeStamp ';

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