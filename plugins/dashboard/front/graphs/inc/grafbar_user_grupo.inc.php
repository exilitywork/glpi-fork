<?php

if($data_ini == $data_fin) {
	$datas = "LIKE '".$data_ini."%'";
}

else {
	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
}

$sql_grpb = "
SELECT `glpi_groups_users`.`users_id` AS uid, `glpi_users`.`firstname` AS name ,`glpi_users`.`realname` AS sname, count(glpi_tickets_users.id) AS conta";
if (!empty($_REQUEST['sel_field'])) {
    $sql_grpb .= ", SUM(".$field_table.".".$field_name.") as field_sum";
}
$sql_grpb .= " 
FROM `glpi_groups_users`, glpi_tickets_users, glpi_users, glpi_groups_tickets, glpi_tickets";
if (!empty($_REQUEST['sel_field'])) {
    $sql_grpb .= " LEFT JOIN ".$field_table."
    ON glpi_tickets.id = ".$field_table.".items_id";
}
$sql_grpb .= " 
WHERE glpi_groups_tickets.groups_id = ".$id_grp."
AND glpi_tickets_users.users_id = glpi_groups_users.users_id
AND glpi_tickets_users.users_id = glpi_users.id
AND glpi_tickets.id = glpi_tickets_users.tickets_id
AND glpi_tickets.id = glpi_groups_tickets.tickets_id
AND glpi_groups_users.groups_id = glpi_groups_tickets.groups_id
AND glpi_tickets.date ".$datas."
AND glpi_tickets.is_deleted = 0
AND glpi_tickets_users.type = 2
". $entidade_and;
if(isset($id_req)) {
    $sql_grpb .= " AND glpi_tickets.requesttypes_id = ".($id_req < 0 ? '0' : $id_req);
}
$sql_grpb .= " GROUP BY uid
ORDER BY conta DESC
LIMIT 10 ";

$query_grp_b = $DB->query($sql_grpb)  or die('Ошибка SQL!');

$arr_grf_grp_b = array();
$arr_field = array();

$categories = array();
while ($row_result = $DB->fetch_assoc($query_grp_b)) {
    if(!empty($_REQUEST['sel_field'])) {
        $v_row_result = "<a href=\"../reports/rel_tecnico.php?sel_field=".$_REQUEST['sel_field']."&";
    } else {
        $v_row_result = "<a href=\"../reports/rel_tecnico.php?";
    }
    if(isset($id_req)) {
        $v_row_result .= "date1=".$data_ini."&date2=".$data_fin."&sel_grp=".$id_grp."&sel_tec=".$row_result['uid']."&grp_name=".$grp_name['name']."&req_tec=".$id_req."&req_name=".$req_name."&con=1\">".$row_result['name']." ".$row_result['sname']."</a>";
    } else {
        $v_row_result .= "date1=".$data_ini."&date2=".$data_fin."&sel_grp=".$id_grp."&sel_tec=".$row_result['uid']."&grp_name=".$grp_name['name']."&con=1\">".$row_result['name']." ".$row_result['sname']."</a>";
    }
    $arr_grf_grp_b[$v_row_result] = $row_result['conta'];
    if (!empty($_REQUEST['sel_field'])) {
        $arr_field[$v_row_result] = ($row_result['field_sum'] == '') ? 0 : $row_result['field_sum'];
    }
}

$grf_grp_b = array_keys($arr_grf_grp_b) ;
$quant_grp_b = array_values($arr_grf_grp_b) ;
$soma_grp_b = array_sum($arr_grf_grp_b);

$grf_3a = json_encode($grf_grp_b);
$quant_2a = implode(',',$quant_grp_b);

if (!empty($_REQUEST['sel_field'])) {
    $quant_field = implode(',', array_values($arr_field));
}

echo "
<script type='text/javascript'>

$(function () {
        $('#graf_user').highcharts({
            chart: {
                type: 'bar',
                height: 550
            },
            title: {
                text: '".__('Tickets','dashboard')." ".__('by Technician','dashboard')."'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: $grf_3a,
                title: {
                    text: null
                },
                labels: {
                	style: {
                        fontSize: '12px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: [{
                min: 0,
                title: {
                    text: '',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }";
            if (!empty($_REQUEST['sel_field'])) {
                echo "
            }, {
                title: {
                    text: '$field_label'
                },
                opposite: true";
            }
            echo "            
            }],
            tooltip: {
                valueSuffix: ''
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    },
                     borderWidth: 1,
                	borderColor: 'white',
                	shadow:true,
                	showInLegend: true
                }
            },
            /*legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 100,
                floating: true,
                borderWidth: 0,
               // backgroundColor: '#FFFFFF',
                shadow: true,
                enabled: true
            },
            credits: {
                enabled: false
            },*/
            series: [
                {
                    colorByPoint: true,
                    name: '". __('Tickets','dashboard')."',
                    data: [$quant_2a],
                    dataLabels: {
                        enabled: true,
                        //color: '#000099',
                        align: 'center',
                        x: 15,
                        y: 0,
                    }
                },";
                if (!empty($_REQUEST['sel_field'])) {
                    echo "{
                            name: '$field_label',
                            dataLabels: { enabled: true },
                            color: '#db5e5e',
                            data: [$quant_field],
                            yAxis: 1,
                            visible: false
                        }";
                }    
                echo "    
            ]
        });
    });

</script>
";

		?>
