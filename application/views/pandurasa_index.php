<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 2 | Lockscreen</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/dist/css/AdminLTE.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition lockscreen">
    <!-- Automatic element centering -->
    <div class="lockscreen-wrapper">
        <div class="lockscreen-logo">
            <a href="<?= base_url() ?>assets/index2.html"><b>Welcome</b> to <b>Pandurasa</b> Management System</a>
        </div>
        <div class="text-center">
            <a href="http://192.168.100.121/pandurasa-activity/auth/login" class="btn btn-app" style="width:150px;">
                <i class="fa fa-edit"></i> <b>Pandurasa Activity</b>
            </a>
            <a href="http://192.168.100.121/anp/auth/login" class="btn btn-app" style="width:150px;">
                <i class="fa fa-money"></i> <b>Pandurasa ANP</b>
            </a>
            <a href="http://192.168.100.121/pandurasa-dashboard/dashboardpandurasa" class="btn btn-app" style="width:150px;">
                <i class="fa fa-bar-chart"></i> <b>Pandurasa Dashboard</b>
            </a>
        </div>
        <div class="text-center">
        </div>
        <div class="lockscreen-footer text-center">
            Copyright &copy; 2021-2022 <b><a href="https://adminlte.io" class="text-black">Pandurasa Kharisma</a></b><br>
            All rights reserved
        </div>
    </div>
    <!-- /.center -->

    <!-- jQuery 3 -->
    <script src="<?= base_url() ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="<?= base_url() ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>

</html>