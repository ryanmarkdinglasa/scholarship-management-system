<?php
	error_reporting(0);
	session_start();
	include("include/conn.php");
	include("include/session.php");
	include("include/function.php");
	$page = "userlog";
	include("include/header.php");
?>
	<script>
		if (window.history.replaceState) {
		  window.history.replaceState(null, null, window.location.href);
		}
	</script>
	</head>
	<?php
	include("include/sidebar.php");
	?>
	<!-- Main content -->
	<div class="main-content" id="panel">
    <?php
    include("include/topnav.php"); //Edit topnav on this page
    ?>
	<style>
		table td{
			text-align:center;
		}
	</style>
    <!-- Header -->
    <!-- Header & Breadcrumbs -->
			<div class="header bg-primary pb-6">
			  <div class="container-fluid">
				<div class="header-body">
				  <div class="row align-items-center py-4">
					<div class="col-lg-6 col-7">
					  <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
						<ol class="breadcrumb breadcrumb-links breadcrumb-dark">
						  <li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i></a></li>
						  <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
						  <li class="breadcrumb-item active" aria-current="page">User Log History</li>
						</ol>
					  </nav>
					</div>
					<div class="col-lg-6 col-5 text-right">
					</div>
				  </div>
				</div>
			  </div>
			</div>
    <!-- Header & Breadcrumbs -->
			<!-- Page content -->
			<div class="container-fluid mt--6">
			  <!-- Table -->
			  <div class="row">
				<div class="col">
				  <div class="card">
					<!-- Card header -->
					<div class="card-header">
					  <h3 class="mb-0">User Log History</h3>
					  <p class="text-sm mb-0">
						This table is a detailed history of sign-in to sign-out of users. This table is useful for keeping track
						traces of user logins on the system. The history displayed in the table is only the latest login data <strong>last 6 months</strong> only.
					  </p>
					</div>
					<div class="table-responsive py-4">
					  <table class="table table-flush" id="tabelUserlog">
						<thead class="thead-light">
						  <tr>
							<th>
							  <center>No
							</th>
							<th>
							  <center>Username
							</th>
							<th>
							  <center>Login Time
							</th>
							<th>
							  <center>Logout Time
							</th>
						  </tr>
						</thead>
						<tfoot>
						  <tr>
							<th>
							  <center>No
							</th>
							<th>
							  <center>Username
							</th>
							<th>
							  <center>Login Time
							</th>
							<th>
							  <center>Logout Time
							</th>
						  </tr>
						</tfoot>
						<tbody>
						  <tr>
							<td>No</td>
							<td>Username</td>
							<td>Login Time</td>
							<td>Logout Time</td>
						  </tr>
						</tbody>
					  </table>
					</div>
				  </div>
				</div>
			  </div>
			  <?php
			  include("include/footer.php"); //Edit topnav on this page
			  ?>
				<script type="text/javascript">
					$(document).ready(function() {
					  var table = $('#tabelUserlog').DataTable({
						"processing": true,
						"serverSide": true,
						"pagingType": "full_numbers",
						"ajax": "scripts/get_data_userlog.php",
						"order": [
						  [2, "desc"]
						],
						"language": {
						  "lengthMenu": "Showing _MENU_ data per page",
						  "zeroRecords": "Sorry, the data you are looking for was not found.",
						  "info": "Show page _PAGE_ dari _PAGES_ page",
						  "infoEmpty": "No data available.",
						  "infoFiltered": "(Wanted from the total _MAX_ data)",
						  "paginate": {
							"previous": "<i class='fas fa-angle-left'></i>",
							"next": "<i class='fas fa-angle-right'></i>",
							"first": "<i class='fas fa-angle-double-left'></i>",
							"last": "<i class='fas fa-angle-double-right'></i>"
						  }
						},

						"columnDefs": [
						  {
							"targets": -2,
							"data": 3,

							render: function(data, type, row) {
							  moment.locale('');
							  var data_date = data;
							  var change = moment(data_date, "YYYY-MM-DD h:mm:ss").format('DD-MM-YYYY h:mm:ss');
							  return change;
							}
						  }
						]
					  });

					});
				</script>
			</div>
		</div>
	</body>
</html>
