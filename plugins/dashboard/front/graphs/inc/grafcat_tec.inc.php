
<?php

if($data_ini == $data_fin) {
	$datas = "LIKE '".$data_ini."%'";
}

else {
	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
}

$query4 = "
SELECT glpi_itilcategories.completename as cat_name, COUNT(glpi_tickets.id) as cat_tick, glpi_itilcategories.id";
if (!empty($_REQUEST['sel_field'])) {
    $query4 .= ", SUM(".$field_table.".".$field_name.") as field_sum";
}
$query4 .= " FROM glpi_itilcategories, glpi_tickets_users, glpi_tickets";
if (!empty($_REQUEST['sel_field'])) {
    $query4 .= " LEFT JOIN ".$field_table."
    ON glpi_tickets.id = ".$field_table.".items_id";
}
$query4 .= " WHERE glpi_itilcategories.id = glpi_tickets.itilcategories_id
AND glpi_tickets.is_deleted = '0'
AND glpi_tickets.date ".$datas."
AND glpi_tickets_users.users_id = ".$id_tec."
AND glpi_tickets_users.tickets_id = glpi_tickets.id
AND glpi_tickets_users.type = 2 ";
if(isset($id_req)) {
    $query4 .= "AND glpi_tickets.requesttypes_id = ".($id_req < 0 ? '0' : $id_req);
}
$query4 .= " GROUP BY glpi_itilcategories.id
ORDER BY `cat_tick` DESC
LIMIT 10 ";

$result4 = $DB->query($query4) or die('Ошибка SQL!');

$arr_grf4 = array();
$arr_field = array();

while ($row_result = $DB->fetch_assoc($result4))	{
    $row_result['cat_name'] = str_replace('>', '-', $row_result['cat_name']);
    if(!empty($_REQUEST['sel_field'])) {
        $v_row_result = "<a href=\"../reports/rel_tecnico.php?sel_field=".$_REQUEST['sel_field']."&";
    } else {
        $v_row_result = "<a href=\"../reports/rel_tecnico.php?";
    }
    if(isset($id_req)) {
        $v_row_result .= "date1=".$data_ini."&date2=".$data_fin."&sel_tec=".$id_tec."&req_tec=".$id_req."&cat_tec=".$row_result['id']."&cat_name=".$row_result['cat_name']."&req_name=".$req_name."&con=1\">".$row_result['cat_name']." (".$row_result['id'].")</a>";
    } else {
        $v_row_result .= "date1=".$data_ini."&date2=".$data_fin."&sel_tec=".$id_tec."&cat_tec=".$row_result['id']."&cat_name=".$row_result['cat_name']."&con=1\">".$row_result['cat_name']." (".$row_result['id'].")</a>";
    }
    $arr_grf4[$v_row_result] = $row_result['cat_tick'];
    if (!empty($_REQUEST['sel_field'])) {
        $arr_field[$v_row_result] = ($row_result['field_sum'] == '') ? 0 : $row_result['field_sum'];
    }
}

$grf4 = array_keys($arr_grf4) ;
$quant4 = array_values($arr_grf4) ;
$soma4 = array_sum($arr_grf4);

$grf_3a = json_encode($grf4);
$quant_2a = implode(',',$quant4);

if (!empty($_REQUEST['sel_field'])) {
    $quant_field = implode(',', array_values($arr_field));
}

echo "
<script type='text/javascript'>

$(function () {
        $('#graf4').highcharts({
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Top 10 - ".__('Tickets by Category','dashboard')."'
            },

            xAxis: {
                categories: $grf_3a,
                labels: {
                    rotation: 0,
                    align: 'right',
                    style: {
                        //fontSize: '11px',
                        //fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: [{
                min: 0,
                title: {
                    text: ''
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
            /* tooltip: {
                headerFormat: '<span style=\"font-size:10px\">{point.key}</span><table>',
                pointFormat: '<tr><td style=\"color:{series.color};padding:0\">{series.name}: </td>' +
                    '<td style=\"padding:0\"><b>{point.y:.1f} </b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            }, */
            plotOptions: {
                bar: {
                    pointPadding: 0.2,
                    borderWidth: 0,
                    borderWidth: 2,
               	    borderColor: 'white',
                	  shadow:true,
                	  showInLegend: false,
                }
            },
            series: [
                {
                    colorByPoint: true, 
                    name: '".__('Tickets','dashboard')."',
                    data: [$quant_2a],
                    dataLabels: {
                        enabled: true,
                        // color: '#000099',
                        align: 'center',
                        x: 12,
                        y: 1,
                        style: {
                            //fontSize: '13px',
                            //fontFamily: 'Verdana, sans-serif'
                        }
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

		</script>"; ?>
