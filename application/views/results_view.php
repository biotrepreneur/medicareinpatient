
<script type="text/javascript" src="js/sorttable.js"></script>

	<div id="results-table">
		<!--<div class="tip" id="tip" onclick="this.style.display = 'none';">Click to Sort Results &nbsp; <b>X</b></div>-->
		<table class="sortable">
			<thead>
				<tr onclick="document.getElementById('tip').style.display = 'none';">
					<td>Provider Name</td>
					<td>Provider City</td>
					<td>Total Discharges</td>
					<td>Average Covered Charges</td>
					<td>Average Total Payments</td>
				</tr>
			</thead>
			<tbody>
				<?php 
					$cityJS = array();
					$aCCJS = array();
					$atpJS = array();
					foreach($test as $city){
						echo "<tr>";
						echo "<td>".ucwords(strtolower($city[0]['Provider_Name']))."</td>";
						echo "<td>".ucwords(strtolower($city[0]['Provider_City']))."</td>";
						array_push($cityJS, $city[0]['Provider_Name']);
						echo "<td class='text-middle'>".$city[0]['Total_Discharges']."</td>";
						echo "<td class='text-middle'>"."$".number_format($city[0]['Average_Covered_Charges'])."</td>";
						echo "<td class='text-middle'>"."$".number_format($city[0]['Average_Total_Payments'])."</td>";
						//array_push($dollarsJS, $city[0]['Total_Discharges']);
						array_push($aCCJS, $city[0]['Average_Covered_Charges']);
						array_push($atpJS, $city[0]['Average_Total_Payments']);
						echo "</tr>";
					}
				?>
			</tbody>
		</table>
	</div>
	<div id="divide"></div>
	<div id="graph"></div>

	<script type="text/javascript">

var chart1; 
$(document).ready(function() {

	//document.getElementById('tip').style.display = 'none';
	
	  var selectedDRD = $('#DRD').val();

      chart1 = new Highcharts.Chart({
         chart: {
         	 	renderTo: 'graph',
                type: 'bar',
                marginTop: 100
            },
            title: {
                text: 'Inpatient Charge Data, FY2011 - ' + selectedDRD
            },
            subtitle: {
                text: 'Source: CMS.gov*'
            },
            xAxis: {
                categories: <?php echo json_encode($cityJS);?>,
                title: {
                    text: null
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'USD ($)',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                valueSuffix: ' USD ($)'
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -50,
                y: 50,
                floating: true,
                borderWidth: 1,
                backgroundColor: '#FFFFFF',
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Average Covered Charges',
                data: <?php echo json_encode($aCCJS, JSON_NUMERIC_CHECK);?>
            },{
            	name: 'Average Total Payments',
                data: <?php echo json_encode($atpJS, JSON_NUMERIC_CHECK);?>
            }]
      });
});