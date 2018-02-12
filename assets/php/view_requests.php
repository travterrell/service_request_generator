<div class="col">
	<h2>Service Requests</h2>
	<table>
		<tr>
		  <th>Agency Name</th>
		  <th>Project Name</th> 
		  <th>Created At</th>
		</tr>
		<?php
		spl_autoload_register(function ($class) {
    		include_once($class.".class.php");
		});
		$db = new DB;
		$request = new Request;

		$result = $request->viewRequests($db);
		if ( isset($result[1]) ) {
			foreach($result as $row) {
				echo "<tr>";
				echo "<td>".$row["agency_name"]."</td>";
				echo "<td>".$row["project_name"]."</td>";
				echo "<td><a class='service_request' data-request_id=".$row["id"].">View Order</a></td>";
				echo "</tr>";
			} 
		} else {
			echo "<tr>";
			echo "<td>".$result["agency_name"]."</td>";
			echo "<td>".$result["project_name"]."</td>";
			echo "<td><a class='service_request' data-request_id=".$result["id"].">View Order</a></td>";
			echo "</tr>";
		}
		?>
		<script>
			$(".service_request").click(function(){
				var id = $(this).data("request_id");
				Generator.viewServiceRequest(id);
			});
		</script>
		</table>
</div>