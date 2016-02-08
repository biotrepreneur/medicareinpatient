<!DOCTYPE html>
<html lang="en">
<head>

	<meta name="viewport" content="initial-scale=1" />
	<meta charset="utf-8">
	<title>WNY Healthcare</title>
	<link rel="stylesheet" href="stylesheets/style.css"/>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script type="text/javascript" src="js/highcharts.js"></script>
	<!--<script type="text/javascript" src="js/modules/exporting.js"></script>-->

	<style type="text/css">
		@-moz-document url-prefix(){
		    div.partner img {
		        display: inline;
		        margin-left: 150px;
		    }
		}
	</style>

</head>
<body>



<div id="row">
<img class="logo" src='images/wnyhealthsmall.png'/>

<div id="big-button">
	<h3 class="action">Search By DRG Definition</h3>
<?php 
	$options = array(
			'069'=>'069 - Transient Ischemia',
			'101'=>'101 - Seizures w/o MCC',
			'176'=>'176 - Pulmonary Embolism w/o MCC',
			'189'=>'189 - Pulmonary Edema & Respiratory Failure',
			'190'=>'190 - Chronic Obstructive Pulmonary Disease w/ MCC',
			'193'=>'193 - Simple Pneumonia & Pleurisy w/ MCC',
			'202'=>'202 - Bronchitis & Asthma w/ CC/MCC',
			'238'=>'238 - Major Cardiovasc Procedures w/o MCC',
			'243'=>'243 - Permanent Cardiac Pacemaker Implant W CC',
			'291'=>'291 - Heart Failure & Shock w/ MCC',
			'300'=>'300 - Peripheral Vascular Disorders w/ CC',
			'303'=>'303 - Atherosclerosis w/o MCC',
			'308'=>'308 - Cardiac Arrhythmia & Conduction Disorders w/ MCC',
			'312'=>'312 - Syncope & Collapse',
			'313'=>'313 - Chest Pain',
			'460'=>'460 - Spinal Fusion Except Cervical w/o MCC',
			'473'=>'473 - Cervical Spinal Fusion w/o CC/MCC',
			'480'=>'480 - Hip & Femur Procedures Except Major Joint w/ MCC',
			'491'=>'491 - Back & Neck Proc Exc Spinal Fusion w/o CC/MCC',
			'552'=>'552 - Medical Back Problems w/o MCC',
			'602'=>'602 - Cellulitis w/ MCC',
			'638'=>'638 - Diabetes w/ CC',
			'682'=>'682 - Renal Failure w/ MCC',
			'689'=>'689 - Kidney & Urinary Tract Infections w/ MCC',
			'897'=>'897 - Alcohol/drug Abuse Or Dependence w/o Rehabilitation Therapy w/o MCC');
	echo form_dropdown('DRD', $options, '069', 'id="DRD" class="drop"');
?>
</div>
</div>

<div id="results">
	<script type="text/javascript" src="js/sorttable.js"></script>
	<div id="results-table">
		<!--<div class="tip" id="tip" onclick="this.style.display = 'none';">Click to Sort Results &nbsp; <b>X</b></div>-->
		<table class="sortable">
			<thead>
				<tr>
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
</div>

<div class="footer">


	




	<p class="citation">*<a href="https://www.cms.gov/Research-Statistics-Data-and-Systems/Statistics-Trends-and-Reports/Medicare-Provider-Charge-Data/index.html" target="_blank">Medicare Inpatient Provider Charge Data<br /><span class="info">FY2011, Centers for Medicare & Medicaid Services</span></a></p>


<!--	
	<a href="http://immenseanalytics.com" target="_blank"><img src="http://www.immenseanalytics.com/img/logo.png"/></a>
-->
</div>

<script type="text/javascript">
	$('#DRD').change(function() { 
		var newDRD = $('#DRD').val();
		//$.post('http://wnyhealth.immenseanalytics.com/index.php/inpatient/query_data', {id:newDRD},
		$.post('/WNYhealth/index.php/inpatient/query_data', {id:newDRD},      
		      // when the Web server responds to the request
		      function(result) {
		        // if the result is TRUE write a message to the page
		        if (result) {
		          $('#results').html(result);
		        }
		      }
		    );
		return false;
	});
</script>

<script type="text/javascript">

var chart1; 
$(document).ready(function() {

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

/*
setTimeout(function() {
		document.getElementById('tip').style.display = 'none';
	}, 4000);
*/
});
	

	

</script>

<script type="text/javascript">
/*
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-35675397-2']);
  _gaq.push(['_setDomainName', 'wnyhealth.immenseanalytics.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
 */

</script>

</body>
</html>