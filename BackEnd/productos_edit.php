<?php
include_once './PHP/util.php';
include_once './PHP/bd.php';
include_once './PHP/secciones.php';

$secciones = new secciones($BD);
$secciones->validarSesion();
$edit = filter_input(INPUT_GET, "ID", FILTER_SANITIZE_NUMBER_INT);
$subcategoria = filter_input(INPUT_GET, "IDSUBCAT", FILTER_SANITIZE_NUMBER_INT);
?>

<!DOCTYPE html>
<html lang="en">

    <?php
    echo $secciones->libreriasHead();
    ?>
    <body>

	<!-- *** TOPBAR ***
     _________________________________________________________ -->

	<!-- *** TOP BAR END *** -->

	<!-- *** NAVBAR ***
     _________________________________________________________ -->
        <?php
        echo $secciones->menu();
        ?>
        <!-- /#navbar -->

	<!-- *** NAVBAR END *** -->
    <script src="../js/jquery-1.11.0.min.js"></script>
    <script src="js/validarProducto.js"></script>
	<div id="all">

		<div id="content">
			<div class="container">

				<div class="col-md-12">
                        <?php
                        if ($edit != "") {
                            echo $secciones->migasPan("", "", "", "", $edit, "", "");
                        } else {
                            echo $secciones->migasPan("", "", "", "", $subcategoria, "", 1);
                        }
                        
                        ?>
                    </div>

				<div class="col-md-12">

                        <?php
                        echo $secciones->productosEdit($edit, $subcategoria);
                        ?>
                        <script>
                            function BrowseServer(functionData)
                            {
                                // You can use the "CKFinder" class to render CKFinder in a page:
                                var finder = new CKFinder();

                                // The path for the installation of CKFinder (default = "/ckfinder/").
                                //finder.basePath = "http://jadevu.hol.es/BackEnd/ck/ckfinder/" ;
                                finder.basePath = "http://jade-vu.com/web/BackEnd/ck/ckfinder/" ;

                                // Name of a function which is called when a file is selected in CKFinder.
                                finder.selectActionFunction = SetFileField;

                                // Additional data to be passed to the selectActionFunction in a second argument.
                                // We'll use this feature to pass the Id of a field that will be updated.
                                finder.selectActionData = functionData;

                                // Name of a function which is called when a thumbnail is selected in CKFinder.
                                finder.selectThumbnailActionFunction = ShowThumbnails;

                                // Launch CKFinder
                                finder.popup();
                            }
                            function SetFileField(fileUrl, data)
                            {
                                document.getElementById(data["selectActionData"]).value = fileUrl;
                            }

                            // This is a sample function which is called when a thumbnail is selected in CKFinder.
                            function ShowThumbnails(fileUrl, data)
                            {
                                // this = CKFinderAPI
                                var sFileName = this.getSelectedFile().name;
                                document.getElementById('thumbnails').innerHTML +=
                                        '<div class="thumb">' +
                                        '<img src="' + fileUrl + '" />' +
                                        '<div class="caption">' +
                                        '<a href="' + data["fileUrl"] + '" target="_blank">' + sFileName + '</a> (' + data["fileSize"] + 'KB)' +
                                        '</div>' +
                                        '</div>';

                                document.getElementById('preview').style.display = "";
                                // It is not required to return any value.
                                // When false is returned, CKFinder will not close automatically.
                                return false;
                            }
                        </script>

				</div>
				<!-- /.col-md-9 -->
			</div>
			<!-- /.container -->
		</div>
		<!-- /#content -->

		<!-- *** FOOTER ***
     _________________________________________________________ -->
            <?php
            echo $secciones->footer();
            ?>
            <!-- *** COPYRIGHT END *** -->

	</div>
	<!-- /#all -->

	<!-- *** SCRIPTS TO INCLUDE ***
     _________________________________________________________ -->
	<script src="../js/jquery-1.11.0.min.js"></script>
    <script src="js/validarProducto.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/jquery.cookie.js"></script>
	<script src="../js/waypoints.min.js"></script>
	<script src="../js/modernizr.js"></script>
	<script src="../js/bootstrap-hover-dropdown.js"></script>
	<script src="../js/owl.carousel.min.js"></script>
	<script src="../js/front.js"></script>
	<script src="../js/tooltips.js"></script>

</body>

</html>