
<?php

if($data_ini == $data_fin) {
	$datas = "LIKE '".$data_ini."%'";
}

else {
	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
}

$query4 = "
SELECT glpi_requesttypes.name as req_name, COUNT(glpi_tickets.id) as req_tick, glpi_requesttypes.id
FROM  glpi_tickets_users, glpi_tickets
LEFT JOIN glpi_requesttypes
ON glpi_tickets.requesttypes_id = glpi_requesttypes.id
WHERE glpi_tickets.is_deleted = '0'
AND glpi_tickets.date ".$datas."
AND glpi_tickets_users.users_id = ".$id_tec."
AND glpi_tickets_users.tickets_id = glpi_tickets.id
AND glpi_tickets_users.type = 2
GROUP BY glpi_requesttypes.id
ORDER BY `req_tick` DESC
LIMIT 10 ";

$result4 = $DB->query($query4) or die('erro');

$arr_grf4 = array();
while ($row_result = $DB->fetch_assoc($result4))	{
    $row_result['req_name'] = $row_result['req_name'] ? $row_result['req_name'] : 'Без типа';
    $row_result['id'] = $row_result['id'] ? $row_result['id'] : '-1';
    $v_row_result = "<a href=\"?date1=".$data_ini."&date2=".$data_fin."&sel_tec=".$id_tec."&req_tec=".$row_result['id']."&req_name=".$row_result['req_name']."&con=1\">".$row_result['req_name']."</a>";
    $arr_grf4[$v_row_result] = $row_result['req_tick'];
}

$grf4 = array_keys($arr_grf4) ;
$quant4 = array_values($arr_grf4) ;
$soma4 = array_sum($arr_grf4);

$grf_3a = json_encode($grf4);
$quant_2a = implode(',',$quant4);


echo "
<script type='text/javascript'>

$(function () {
        $('#graf_req_tec').highcharts({
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Top 10 - Заявки по типу запроса'
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
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
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
            series: [{
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
            }]
        });
    });

		</script>"; ?>
