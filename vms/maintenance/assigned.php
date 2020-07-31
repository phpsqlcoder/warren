<?php
include("../config.php");
session_start();
$url = "assigned.php";
$table = "assigned";
$msg = '';
if(!$_SESSION['esdvms_username']){
	header("location:../login.php");
}

if(isset($_GET['act'])){
	if($_GET['act']=='save'){

		$insert = sqlsrv_query($conn,"insert into ".$table." (name,active)
								VALUES ('".htmlentities($_POST['name'])."','1')
			");
		header("location:".$url."?msg=SAVED");

	}

	if($_GET['act']=='edit'){

		$e = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from ".$table." where id='".$_GET['id']."'"));

	}

	if($_GET['act']=='update'){

		$update = sqlsrv_query($conn,"update ".$table." set name='".htmlentities($_POST['name'])."' where id='".$_GET['id']."'");
		header("location:".$url."?msg=UPDATED");

	}

	if($_GET['act']=='delete'){

		$update = sqlsrv_query($conn,"update ".$table." set active='0' where id='".$_GET['id']."'");
		header("location:".$url."?msg=DELETED");

	}

	
}
?>
<html>
	<head>
		<title>Maintenance</title>
		<link href="../google.css" rel="stylesheet" type="text/css"/>
		<link href="../metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
		<link href="../metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
		<link href="../metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
		<link href="../metronic/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
		<link href="../metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
		<!-- END GLOBAL MANDATORY STYLES -->

		<link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/select2/select2.css"/>
		<link rel="stylesheet" type="text/css" href="../metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>


		<!-- BEGIN THEME STYLES -->
		<link href="../metronic/assets/global/css/components.css" rel="stylesheet" type="text/css"/>
		<link href="../metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
		<link href="../metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
		<link id="style_color" href="../metronic/assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css"/>
		<link href="../metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
	</head>
	<body style="background-color:white;width:90%;">
		<?php if(isset($_GET['msg'])) {
          echo '<div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                <strong>Successfully</strong> '.$_GET['msg'].' record!
             </div>';
       	}?>

    	<div class="row">
			<div class="col-md-12">
				<div class="portlet box blue tabbable">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-car"></i>Unit Owner
						</div>
					</div>
					<div class="portlet-body">
						<div class="tabbable portlet-tabs">
							<ul class="nav nav-tabs">
								<li class="">
									<a href="#portlet_tab_2" data-toggle="tab">
									Add New </a>
								</li>
								<li class="active">
									<a href="#portlet_tab_1" data-toggle="tab">
									List </a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="portlet_tab_1">
									<a href="#" class="btn green btn-xs" onclick='window.open("export.php?act=assigned", "Maintenance", "width=800,height=600")'>Export to Excel</a>
									<?php 
										if(isset($_GET['act'])){
											if($_GET['act']=='edit'){
									?>
										<div class="portlet grey-gallery box">
											<div class="portlet-title">
												<div class="caption">
													<i class="fa fa-edit"></i>Update Owner
												</div>
											</div>
											<div class="portlet-body"><br><br>
												<form action="<?php echo $url; ?>?act=update&id=<?php echo $_GET['id'];?>" method="POST">
													<div class="row">
														<div class="col-md-12 margin-bottom-10">
															<label class="control-label col-md-3">Name</label>
															<div class="col-md-9">
																<input type="text" size="16" name="name" id="name" class="form-control" value="<?php echo $e['name'];?>" required="required">
															</div>
														</div>
													</div>													
													
													<div class="form-actions" style="margin-left:20px;">
														<button type="submit" class="btn btn-sm blue">Update</button>
														<a href="<?php echo $url; ?>" class="btn btn-sm default">Cancel</a>		<br><br>											
													</div>
												</form>
												
											</div>
										</div>
									<?php 
										}}
									?>

									<table class="table table-striped table-condensed" id="sample_1x" style="font-size:12px;">
										<thead>
										<tr>
											<th>ID</th>
											<th>Name</th>											
											<th>Actions</th>
										</tr>
										</thead>
										<tbody>
									<?php 
										$q = sqlsrv_query($conn,"select * from ".$table." where active=1 order by id desc");
										while($r = sqlsrv_fetch_array($q)){
											echo '<tr>
													<td>'.$r['id'].'</td>
													<td>'.$r['name'].'</td>													
													<td align="right">
														<a href="'.$url.'?act=edit&id='.$r['id'].'" class="btn green btn-xs"><i class="fa fa-edit"></i></a>
														<a href="#" onclick="deleted('.$r['id'].');" class="btn red btn-xs"><i class="fa fa-minus-circle"></i></a>
													</td>
												</tr>';
										}
									?>
										</tbody>
									</table>
								</div>
								<div class="tab-pane" id="portlet_tab_2">
									<h3>Add New</h3>
									<form action="<?php echo $url; ?>?act=save" method="POST">
										<div class="row">
											<div class="col-md-12 margin-bottom-10">
												<label class="control-label col-md-3">Name</label>
												<div class="col-md-9">
													<input type="text" size="16" name="name" id="name" class="form-control" required="required">
												</div>
											</div>
										</div>										
										<div class="form-actions" style="margin-left:20px;">
											<button type="submit" class="btn btn-sm blue">Save</button>
											<a href="#portlet_tab_1" data-toggle="tab" class="btn btn-sm default">Cancel</a>		<br><br>											
										</div>
									</form>
								</div>
								
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	
	</body>
	<script src="../metronic/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
	<script src="../metronic/assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
	
	<script src="../metronic/assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
	<script src="../metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="../metronic/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
	<script src="../metronic/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
	<script src="../metronic/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
	<script src="../metronic/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
	<script src="../metronic/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
	<script src="../metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
	
	<script type="text/javascript" src="../metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="../metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>

	<script src="../metronic/assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
	<script src="../metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
    <script src="../metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
	 <script>
         jQuery(document).ready(function() {    
            Metronic.init(); // init metronic core components
            Layout.init(); // init current layout
            TableManaged.init();
            window.setTimeout(function() {
			    $(".alert").fadeTo(500, 0).slideUp(500, function(){
			        $(this).remove(); 
			    });
			}, 2000);
         });
      </script>
      <script>
         var TableManaged = function () {

            var initTable1 = function () {

                 var table = $('#sample_1');

                 // begin first table
                 table.dataTable({
                     "columns": [{
                         "orderable": true
                     }, {
                         "orderable": true                     
                     }, {
                         "orderable": false
                     }],
                     "lengthMenu": [
                         [5, 15, 20, -1],
                         [5, 15, 20, "All"] // change per page values here
                     ],
                     // set the initial value
                     "pageLength": 15,            
                     "pagingType": "bootstrap_full_number",
                     "language": {
                         "lengthMenu": "  _MENU_ records",
                         "paginate": {
                             "previous":"Prev",
                             "next": "Next",
                             "last": "Last",
                             "first": "First"
                         }
                     },
                     "columnDefs": [{  // set default column settings
                         'orderable': false,
                         'targets': [0]
                     }, {
                         "searchable": false,
                         "targets": [0]
                     }],
                     "order": [
                         [0, "desc"]
                     ] // set first column as a default sort by asc
                 });

                 var tableWrapper = jQuery('#sample_1_wrapper');

                 table.find('.group-checkable').change(function () {
                     var set = jQuery(this).attr("data-set");
                     var checked = jQuery(this).is(":checked");
                     jQuery(set).each(function () {
                         if (checked) {
                             $(this).attr("checked", true);
                             $(this).parents('tr').addClass("active");
                         } else {
                             $(this).attr("checked", false);
                             $(this).parents('tr').removeClass("active");
                         }
                     });
                     jQuery.uniform.update(set);
                 });

                 table.on('change', 'tbody tr .checkboxes', function () {
                     $(this).parents('tr').toggleClass("active");
                 });

                 tableWrapper.find('.dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown
             }

             return {
                 //main function to initiate the module
                 init: function () {
                     if (!jQuery().dataTable) {
                         return;
                     }
                     initTable1();
                 }
             };

         }();
      </script>
      <script>
	 	function deleted(x){	 		
			var r = confirm("Are you sure you want to delete this record?");
			if (r == true) {
			    window.location = "<?php echo $url?>?act=delete&id="+x;
			} else {
			    return false;
			}
	 	}
      </script>
	
</html>