<!DOCTYPE html>
<html lang="fr" >
<head>
    <meta charset="utf-8" />
    <title>A2MI | Formulaire</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />

    <!-- ================== BEGIN core-css ================== -->
    <link href="assets/css/vendor.min.css" rel="stylesheet" />
    <link href="assets/css/transparent/app.min.css" rel="stylesheet" />
    <link href="assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />
    <link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
    <link href="assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" rel="stylesheet" />
    <link href="assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" />
    <link href="assets/plugins/ion-rangeslider/css/ion.rangeSlider.min.css" rel="stylesheet" />
    <link href="assets/plugins/tag-it/css/jquery.tagit.css" rel="stylesheet" />
    <link href="assets/plugins/spectrum-colorpicker2/dist/spectrum.min.css" rel="stylesheet" />
    <link href="assets/plugins/select-picker/dist/picker.min.css" rel="stylesheet" />

    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- ================== END core-css ================== -->
</head>
<?php
session_start();
require_once('includes/_functions.php');
require_once('connexion/traitement_connexion.php');
if (isset($_SESSION['role']) AND $_SESSION['role'] == 'admin' AND isset($_SESSION['mail'])): ?>

<body>
<!-- BEGIN page-cover -->
<div class="app-cover"></div>
<!-- END page-cover -->

<!-- BEGIN #loader -->
<div id="loader" class="app-loader">
    <span class="spinner"></span>
</div>
<!-- END #loader -->

<!-- BEGIN #app -->
<div id="app" class="app app-header-fixed app-sidebar-fixed app-content-full-height">
    <?php require_once('includes/header.php'); ?>
    <?php require_once('includes/sidebar.php'); ?>
    <?php require_once('includes/_requests.php'); ?>

    <!-- BEGIN #content -->
    <div id="content" class="app-content">
        <!-- BEGIN breadcrumb -->
        <ol class="breadcrumb float-xl-end">
            <li class="breadcrumb-item"><a href="javascript:;">Affiche</a></li>
            <li class="breadcrumb-item active">Formulaire</li>
        </ol>
        <!-- END breadcrumb -->
        <!-- BEGIN page-header -->
        <h1 class="page-header">Form Validation <small>header small text goes here...</small></h1>
        <!-- END page-header -->
        <!-- BEGIN row -->
        <div class="row">
            <!-- BEGIN col-6 -->
            <div class="col-xl-6">
                <!-- BEGIN panel -->
                <div class="panel panel-inverse" data-sortable-id="form-validation-1">
                    <!-- BEGIN panel-heading -->
                    <div class="panel-heading">
                        <h4 class="panel-title">Basic Form Validation</h4>
                    </div>
                    <!-- END panel-heading -->
                    <!-- BEGIN panel-body -->
                    <div class="panel-body">
                        <form class="form-horizontal" data-parsley-validate="true" name="demo-form">
                            <div class="form-group row mb-3">
                                <label class="col-lg-4 col-form-label form-label" for="fullname">Full Name * :</label>
                                <div class="col-lg-8">
                                    <input class="form-control" type="text" id="fullname" name="fullname" placeholder="Required" data-parsley-required="true" />
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-lg-4 col-form-label form-label" for="email">Email * :</label>
                                <div class="col-lg-8">
                                    <input class="form-control" type="text" id="email" name="email" data-parsley-type="email" placeholder="Email" data-parsley-required="true" />
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-lg-4 col-form-label form-label" for="website">Website :</label>
                                <div class="col-lg-8">
                                    <input class="form-control" type="url" id="website" name="website" data-parsley-type="url" placeholder="url" />
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-lg-4 col-form-label form-label" for="message">Message (20 chars min, 200 max) :</label>
                                <div class="col-lg-8">
                                    <textarea class="form-control" id="message" name="message" rows="4" data-parsley-minlength="20" data-parsley-maxlength="100" placeholder="Range from 20 - 200"></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-lg-4 col-form-label form-label">Required Select Box :</label>
                                <div class="col-lg-8">
                                    <select class="form-select" id="select-required" name="selectBox" data-parsley-required="true">
                                        <option value="">Please choose</option>
                                        <option value="foo">Foo</option>
                                        <option value="bar">Bar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-lg-4 col-form-label form-label">Required Radio Button :</label>
                                <div class="col-lg-8 pt-2">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="radiorequired" value="foo" id="radioRequired1" data-parsley-required="true" />
                                        <label class="form-check-label" for="radioRequired1">Radio Button 1</label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input type="radio" class="form-check-input" name="radiorequired" id="radioRequired2" value="bar" />
                                        <label class="form-check-label" for="radioRequired2">Radio Button 2</label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input type="radio" class="form-check-input" name="radiorequired" id="radioRequired3" value="key" />
                                        <label class="form-check-label" for="radioRequired3">Radio Button 2</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-lg-4 col-form-label form-label">Check at least 2 checkboxes :</label>
                                <div class="col-lg-8 pt-2">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="mincheck1" name="mincheck[]" data-parsley-mincheck="2" value="foo" required />
                                        <label class="form-check-label" for="mincheck1">Checkbox 1</label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input type="checkbox" class="form-check-input" id="mincheck2" name="mincheck[]" value="bar" />
                                        <label class="form-check-label" for="mincheck2">Checkbox 2</label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input type="checkbox" class="form-check-input" id="mincheck3" name="mincheck[]" value="baz" />
                                        <label class="form-check-label" for="mincheck3">Checkbox 3</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="form-label col-form-label col-lg-4">Auto Close Datepicker</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="datepicker-autoClose" placeholder="Auto Close Datepicker" />
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="form-label col-form-label col-lg-4">Email address</label>
                                <div class="col-lg-8">
                                    <input type="email" class="form-control mb-5px" placeholder="Enter email" />
                                    <small class="fs-12px text-gray-500-darker">We'll never share your email with anyone else.</small>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="form-label col-form-label col-lg-4">Example select</label>
                                <div class="col-lg-8">
                                    <select class="form-select">
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="form-label col-form-label col-lg-4">Example textarea</label>
                                <div class="col-lg-8">
                                    <textarea class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="form-label col-form-label col-lg-4">Example File Input</label>
                                <div class="col-lg-8">
                                    <input class="form-control" type="file" id="formFile" />
                                </div>
                            </div>



                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label form-label">&nbsp;</label>
                                <div class="col-lg-8">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- END panel-body -->
                </div>
                <!-- END panel -->
            </div>
            <!-- END col-6 -->
        </div>
        <!-- END row -->
        <?php require_once('includes/footer.php'); ?>
    </div>
    <!-- END #content -->

    <!-- BEGIN scroll-top-btn -->
    <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
    <!-- END scroll-top-btn -->
</div>
<!-- END #app -->

<!-- ================== BEGIN core-js ================== -->
<script src="assets/js/vendor.min.js"></script>
<script src="assets/js/app.min.js"></script>
<!-- ================== END core-js ================== -->

<!-- ================== BEGIN page-js ================== -->
<script src="assets/plugins/parsleyjs/dist/parsley.min.js"></script>
<script src="assets/plugins/moment/min/moment.min.js"></script>
<script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<script src="assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
<script src="assets/plugins/jquery.maskedinput/src/jquery.maskedinput.js"></script>
<script src="assets/plugins/jquery-migrate/dist/jquery-migrate.min.js"></script>
<script src="assets/plugins/tag-it/js/tag-it.min.js"></script>
<script src="assets/plugins/clipboard/dist/clipboard.min.js"></script>
<script src="assets/plugins/spectrum-colorpicker2/dist/spectrum.min.js"></script>
<script src="assets/plugins/select-picker/dist/picker.min.js"></script>
<script src="assets/js/demo/form-plugins.demo.js"></script>
<script src="assets/plugins/@highlightjs/cdn-assets/highlight.min.js"></script>
<script src="assets/js/demo/render.highlight.js"></script>
<!-- ================== END page-js ================== -->
</body>
<?php else : ?>
    <?php require_once('login.php'); ?>
<?php endif; ?>
</html>