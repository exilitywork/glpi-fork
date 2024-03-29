<?php

if($data_ini == $data_fin) {
	$datas = "LIKE '".$data_ini."%'";
}

$data1 = $data_ini;
$data2 = $data_fin;

$unix_data1 = strtotime($data1);
$unix_data2 = strtotime($data2);

$interval = ($unix_data2 - $unix_data1) / 86400;
$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
$arr_months = array();

if($interval <= "31") {
	
	$queryd = "
	SELECT DISTINCT   DATE_FORMAT(date, '%b-%d') AS day_l,  COUNT(id) AS nb, DATE_FORMAT(date, '%Y-%m-%d') AS day
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'";
	if(isset($id_req)) {
		$queryd .= " AND glpi_tickets.requesttypes_id =".($id_req < 0 ? '0' : $id_req);
	}
	$queryd .= " AND glpi_tickets.date ".$datas." 
	GROUP BY day
	ORDER BY day ";

	$resultd = $DB->query($queryd) or die('erro');

	$arr_days = array();
	
	while ($row_result = $DB->fetch_assoc($resultd))
	{
		$v_row_result = $row_result['day'];
		$arr_days[$v_row_result] = 0;		
	}

	$days = array_keys($arr_days) ;
	$quantd = array_values($arr_days) ;
	
	$DB->data_seek($resultd, 0);
	while ($row_result = $DB->fetch_assoc($resultd))
	{
		$v_row_result = $row_result['day_l'];
		$arr_daysn[$v_row_result] = 0;		
	}
}

else {
	
	$queryd = "
	SELECT DISTINCT   DATE_FORMAT(date, '%b-%Y') AS day_l,  COUNT(id) AS nb, DATE_FORMAT(date, '%Y-%m') AS day
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.date ".$datas;
	if(isset($id_req)) {
		$queryd .= " AND glpi_tickets.requesttypes_id =".($id_req < 0 ? '0' : $id_req);
	}
	$queryd .= " GROUP BY day
	ORDER BY day ";

	$resultd = $DB->query($queryd) or die('erro');
	
	while ($row_result = $DB->fetch_assoc($resultd))
	{
		$v_row_result = $row_result['day'];
		$arr_months[$v_row_result] = 0;		
	}

	$months = array_keys($arr_months) ;
	$monthsq = array_values($arr_months) ;
	
	$DB->data_seek($resultd, 0);	
	while ($row_result = $DB->fetch_assoc($resultd))
	{
		$v_row_result = $row_result['day_l'];
		$arr_monthsn[$v_row_result] = 0;		
	}
}

/*chamados mensais
$arr_grfm = array();
$arr_opened = array();

if($interval >= "31") {
	
	$DB->data_seek($resultd, 0);
	while ($row_result = $DB->fetch_assoc($resultd))
	{
	
		$querym = "
		SELECT DISTINCT DATE_FORMAT(glpi_tickets.date, '%b-%Y') as day_l,  COUNT(glpi_tickets.id) as nb, DATE_FORMAT(glpi_tickets.date, '%Y-%m') as day
		FROM glpi_tickets_users, glpi_tickets
		WHERE glpi_tickets.is_deleted = '0'
		AND glpi_tickets.date ".$datas." 
		AND glpi_tickets_users.users_id = ".$id_tec."
		AND glpi_tickets_users.type = 2
		".$entidade_age."
		AND glpi_tickets_users.tickets_id = glpi_tickets.id";
		if(isset($id_req)) {
			$querym .= " AND glpi_tickets.requesttypes_id =".($id_req < 0 ? '0' : $id_req);
		}
		$querym .= " AND DATE_FORMAT(glpi_tickets.date, '%Y-%m' ) = '".$row_result['day']."'
		GROUP BY day
		ORDER BY day ";
		
		$resultm = $DB->query($querym) or die('erro m');			
		$row_result2 = $DB->fetch_assoc($resultm);
	
		$v_row_result = $row_result['day'];
		if($row_result2['nb'] != '') {
			$arr_grfm[$v_row_result] = $row_result2['nb'];
		}
		else {
			$arr_grfm[$v_row_result] = 0;
		}
	}	
		$arr_opened = $arr_grfm;
		$label = json_encode(array_keys($arr_monthsn));
}

else {
	
	$DB->data_seek($resultd, 0);
	while ($row_result = $DB->fetch_assoc($resultd)) {
		
		$querym = "
		SELECT DISTINCT DATE_FORMAT(glpi_tickets.date, '%b-%d') as day_l,  COUNT(glpi_tickets.id) as nb, DATE_FORMAT(glpi_tickets.date, '%Y-%m-%d') as day
		FROM glpi_tickets_users, glpi_tickets
		WHERE glpi_tickets.is_deleted = '0'
		AND glpi_tickets.date ".$datas." 
		AND glpi_tickets_users.users_id = ".$id_tec."
		AND glpi_tickets_users.type = 2
		".$entidade_age;
		if(isset($id_req)) {
			$querym .= " AND glpi_tickets.requesttypes_id =".($id_req < 0 ? '0' : $id_req);
		}
		$querym .= " AND glpi_tickets_users.tickets_id = glpi_tickets.id
		AND DATE_FORMAT(glpi_tickets.date, '%Y-%m-%d' ) = '".$row_result['day']."'
		GROUP BY day
		ORDER BY day ";
	
		$resultm = $DB->query($querym) or die('erro m');
		$row_result2 = $DB->fetch_assoc($resultm);
	
		$v_row_result = $row_result['day'];
		if($row_result2['nb'] != '') {
			$arr_grfm[$v_row_result] = $row_result2['nb'];
		}
		else {
			$arr_grfm[$v_row_result] = 0;
		}
	}	
		$arr_opened = $arr_grfm;
		$label = json_encode(array_keys($arr_daysn));
}

$quant_o = array_values($arr_opened) ;
$quant_o2 = implode(',',$quant_o);

// closed
$status = "('5','6')";
$arr_grff = array();
*/
//chamados mensais 2
$arr_grfm = array();
$arr_opened = array();

if($interval >= "31") {
	
	$DB->data_seek($resultd, 0);
	while ($row_result = $DB->fetch_assoc($resultd))
	{
	
		$querym = "
		SELECT DISTINCT DATE_FORMAT(glpi_tickets.date, '%b-%Y') as day_l,  COUNT(glpi_tickets.id) as nb, DATE_FORMAT(glpi_tickets.date, '%Y-%m') as day";
		if (!empty($_REQUEST['sel_field'])) {
			$querym .= ", SUM(".$field_table.".".$field_name.") as field_sum";
		}
		$querym .= " FROM glpi_tickets_users, glpi_tickets";
		if (!empty($_REQUEST['sel_field'])) {
			$querym .= "LEFT JOIN ".$field_table."
			ON glpi_tickets.id = ".$field_table.".items_id";
		}
		$querym .= " WHERE glpi_tickets.is_deleted = '0'
		AND glpi_tickets.date ".$datas." 
		AND glpi_tickets_users.users_id = ".$id_tec."
		AND glpi_tickets_users.type = 2
		".$entidade_age."
		AND glpi_tickets_users.tickets_id = glpi_tickets.id";
		if(isset($id_req)) {
			$querym .= " AND glpi_tickets.requesttypes_id =".($id_req < 0 ? '0' : $id_req);
		}
		$querym .= " AND DATE_FORMAT(glpi_tickets.date, '%Y-%m' ) = '".$row_result['day']."'
		GROUP BY day
		ORDER BY day ";
		
		$resultm = $DB->query($querym) or die('erro m');			
		$row_result2 = $DB->fetch_assoc($resultm);
	
		$v_row_result = $row_result['day'];
		if($row_result2['nb'] != '') {
			$arr_grfm[$v_row_result] = $row_result2['nb'];
		}
		else {
			$arr_grfm[$v_row_result] = 0;
		}
	}	
		$arr_opened = $arr_grfm;
		$label = json_encode(array_keys($arr_monthsn));
}

else {
	
	$DB->data_seek($resultd, 0);
	while ($row_result = $DB->fetch_assoc($resultd)) {
		
		$querym = "
		SELECT DISTINCT DATE_FORMAT(glpi_tickets.date, '%b-%d') as day_l,  COUNT(glpi_tickets.id) as nb, DATE_FORMAT(glpi_tickets.date, '%Y-%m-%d') as day";
		if (!empty($_REQUEST['sel_field'])) {
			$querym .= ", SUM(".$field_table.".".$field_name.") as field_sum";
		}
		$querym .= " FROM glpi_tickets_users, glpi_tickets ";
		if (!empty($_REQUEST['sel_field'])) {
			$querym .= "LEFT JOIN ".$field_table."
			ON glpi_tickets.id = ".$field_table.".items_id";
		}
		$querym .= " WHERE glpi_tickets.is_deleted = '0'
		AND glpi_tickets.date ".$datas." 
		AND glpi_tickets_users.users_id = ".$id_tec."
		AND glpi_tickets_users.type = 2
		".$entidade_age;
		if(isset($id_req)) {
			$querym .= " AND glpi_tickets.requesttypes_id =".($id_req < 0 ? '0' : $id_req);
		}
		$querym .= " AND glpi_tickets_users.tickets_id = glpi_tickets.id
		AND DATE_FORMAT(glpi_tickets.date, '%Y-%m-%d' ) = '".$row_result['day']."'
		GROUP BY day
		ORDER BY day ";
	
		$resultm = $DB->query($querym) or die('SQL Error. Check logs!');
		$row_result2 = $DB->fetch_assoc($resultm);
	
		$v_row_result = $row_result['day'];
		if($row_result2['nb'] != '') {
			$arr_grfm[$v_row_result] = $row_result2['nb'];
		}
		else {
			$arr_grfm[$v_row_result] = 0;
		}
		if (!empty($_REQUEST['sel_field'])) {
			if($row_result2['field_sum'] != '') {
				$arr_field[$v_row_result] = $row_result2['field_sum'];
			}
			else {
				$arr_field[$v_row_result] = 0;
			}
		}
	}	
		$arr_opened = $arr_grfm;
		$label = json_encode(array_keys($arr_daysn));
}

$quant_o = array_values($arr_opened) ;
$quant_o2 = implode(',',$quant_o);

if (!empty($_REQUEST['sel_field'])) {
	$quant_field = array_values($arr_field);
	$quant_field2 = implode(',',$quant_field);
	$field_sum = array_sum($quant_field);
}

// closed
$status = "('5','6')";
$arr_grff = array();

// fechados mensais
if($interval >= "31") {

	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	$queryf = "
	SELECT DISTINCT DATE_FORMAT(glpi_tickets.closedate, '%b-%Y') as day_l,  COUNT(glpi_tickets.id) as nb, DATE_FORMAT(glpi_tickets.closedate, '%Y-%m') as day
	FROM glpi_tickets_users, glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.closedate ".$datas." 
	AND glpi_tickets_users.users_id = ".$id_tec."
	AND glpi_tickets_users.type = 2
	".$entidade_age."
	AND glpi_tickets_users.tickets_id = glpi_tickets.id	";
	if(isset($id_req)) {
		$queryf .= " AND glpi_tickets.requesttypes_id =".($id_req < 0 ? '0' : $id_req);
	}
	$queryf .= "
	GROUP BY day
	ORDER BY day ";
	
	$resultf = $DB->query($queryf) or die('erro f');
	
	while ($row_result = $DB->fetch_assoc($resultf)) {
	
		$v_row_result = $row_result['day'];
		if($row_result['nb'] != '') {
			$arr_grff[$v_row_result] = $row_result['nb'];
		}
		else {
			$arr_grff[$v_row_result] = 0;
		}
	}
	$arr_closed = array_unique(array_merge($arr_months,$arr_grff));
	$label = json_encode(array_keys($arr_monthsn));
 }

else {
	
	$DB->data_seek($resultd, 0);
	while ($row_result = $DB->fetch_assoc($resultd))	{
		$queryf = "
		SELECT DISTINCT DATE_FORMAT(glpi_tickets.closedate, '%b-%d') as day_l,  COUNT(glpi_tickets.id) as nb, DATE_FORMAT(glpi_tickets.closedate, '%Y-%m-%d') as day
		FROM glpi_tickets_users, glpi_tickets
		WHERE glpi_tickets.is_deleted = '0'
		AND glpi_tickets.closedate ".$datas."
		AND glpi_tickets_users.users_id = ".$id_tec."
		AND glpi_tickets_users.type = 2
		".$entidade_age."
		AND glpi_tickets_users.tickets_id = glpi_tickets.id	";
		if(isset($id_req)) {
			$queryf .= " AND glpi_tickets.requesttypes_id =".($id_req < 0 ? '0' : $id_req);
		}
		$queryf .= "
		AND DATE_FORMAT(glpi_tickets.closedate, '%Y-%m-%d' ) = '".$row_result['day']."'	
		GROUP BY day
		ORDER BY day ";
		
 		$resultf = $DB->query($queryf) or die('erro f');
		$row_result2 = $DB->fetch_assoc($resultf);
	
		$v_row_result = $row_result['day'];
		
		if($row_result2['nb'] != '') {
			$arr_grff[$v_row_result] = $row_result2['nb'];
		}
		else {
			$arr_grff[$v_row_result] = 0;
		}
	}
		$arr_closed = $arr_grff;
		$label = json_encode(array_keys($arr_daysn));
 }

$quant_c = array_values($arr_closed) ;
$quant_c2 = implode(',',$quant_c);


//backlog
if($interval >= "31") {
	
	//primeiro dia do mes
	//$data_ini = date('Y-m-01', strtotime($data_ini));
	
   $queryb = "
	SELECT COUNT(glpi_tickets.id) as nb
	FROM glpi_tickets,  glpi_tickets_users
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.date < '".$data_ini." 00:00:00'
	AND glpi_tickets_users.users_id = ".$id_tec."
	AND glpi_tickets_users.type = 2
	".$entidade_age."
	AND glpi_tickets_users.tickets_id = glpi_tickets.id
	AND glpi_tickets.status <> 6 ";
	if(isset($id_req)) {
		$queryf .= " AND glpi_tickets.requesttypes_id =".($id_req < 0 ? '0' : $id_req);
	}
	
	$resultb = $DB->query($queryb) or die('erro b');
	$row_result2 = $DB->fetch_assoc($resultb);

	$back_ini = $row_result2['nb'];
	$conta = count($quant_o);
	
	$arr_bk[0] = $back_ini + ($quant_o[0] - $quant_c[0]);
	
	for ($i=1; $i < ($conta); $i++) {
		$j = $i-1;
		$arr_bk[$i] = $arr_bk[$j] + ($quant_o[$i] - $quant_c[$i]);	
	}		
}

else {
	
   $queryb = "
	SELECT COUNT(glpi_tickets.id) as nb
	FROM glpi_tickets,  glpi_tickets_users
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.date < '".$data_ini." 00:00:00'
	AND glpi_tickets_users.users_id = ".$id_tec."
	AND glpi_tickets_users.type = 2
	".$entidade_age."
	AND glpi_tickets_users.tickets_id = glpi_tickets.id
	AND glpi_tickets.status <> 6 ";
	if(isset($id_req)) {
		$queryf .= " AND glpi_tickets.requesttypes_id =".($id_req < 0 ? '0' : $id_req);
	}

	$resultb = $DB->query($queryb) or die('erro b');
	$row_result2 = $DB->fetch_assoc($resultb);

	$back_ini = $row_result2['nb'];	
	$conta = count($quant_o);

	$arr_bk[0] = $back_ini + ($quant_o[0] - $quant_c[0]);
	
	for ($i=1; $i < ($conta); $i++) {
		$j = $i-1;
		$arr_bk[$i] = $arr_bk[$j] + ($quant_o[$i] - $quant_c[$i]);	
	}
}

$back = implode(',',$arr_bk);

echo "
<script type='text/javascript'>
$(function ()
{
        $('#graf_linhas').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: '".__('Tickets','dashboard')."'
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                x: 0,
                y: 0,
                //floating: true,
                borderWidth: 0,
					 adjustChartSize: true
            },
            xAxis: {
                categories: $label,
						  labels: {
                    rotation: -45,
                    align: 'right'
                }

            },
            yAxis: [{
                title: { // Primary yAxis
                    text: '".__('Tickets','dashboard')."'
				}";
			if (!empty($_REQUEST['sel_field'])) {
				echo "}, {
					title: { // Secondary yAxis
						text: '".$field_label."'
					},
					opposite: true";
			}
			echo "}],
            tooltip: {
                shared: true
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                column: {
                    fillOpacity: 0.5,
                    borderWidth: 1,
                	  borderColor: 'white',
                	  shadow:true,
                    dataLabels: {
	                 	enabled: true
	                 },
                },
                spline: {
		            lineWidth: 4,
		            states: {
		                hover: {
		                    lineWidth: 5
		                }
		            },
		            marker: {
		                enabled: false
		            },
				},
				series: {
                    events: {
                        legendItemClick: function() {
							var type = this.visible;
							(!type) ? this.show(): this.hide();
                            var _i = this._i;
                            Highcharts.each(this.chart.series, function(p, i) {
                                //if (type === p.yAxis && _i !== p._i) {
                                    (!p.visible) ? p.show(): p.hide()
								//} else {
									//(!p.visible) ? p.show(): p.hide()
								//}
                            })
                        }
                    }
                }
            },
          series: [{

                name: '".__('Opened','dashboard')." (".array_sum($quant_o).")',
                data: [$quant_o2] },

                {
                name: '".__('Closed','dashboard')." (".array_sum($quant_c).")',
                data: [$quant_c2] },
                
                {
                name: '".__('Backlog','dashboard')." (".end($arr_bk).")',
                type: 'spline',
                dataLabels: { enabled: false },
                color: '#db5e5e',
                data: [$back],";
			if (!empty($_REQUEST['sel_field'])) {
				echo "},
					{
					name: '".$field_label." (".$field_sum.")',
					dataLabels: { enabled: true },
					data: [$quant_field2],
					color: '#db5e5e',
					//color: 'transparent',
					yAxis: 1,
					visible: false";
			}
			echo "}]
        });
    });
  </script>
";

?>
