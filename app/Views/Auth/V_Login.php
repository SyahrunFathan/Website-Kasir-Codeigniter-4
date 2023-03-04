<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Informasi Kasir</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url() ?>/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= base_url() ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url() ?>/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <img src="<?= base_url('img/logo-1.png') ?>" width="250"><br>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Silahkan Login</p>
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger text-dark">
                        <span class="font-weight-bold">🔐 Gagal!</span> <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?= form_open('auth/checkLogin') ?>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Username" name="username" id="username" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" autocomplete="off" placeholder="Password" name="password" id="password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="social-auth-links text-center mt-4 mb-3">
                    <button type="submit" class="btn btn-block btn-primary">
                        <i class="fas fa-sign-in-alt mr-2"></i>SIGN IN
                    </button>
                </div>
                <?= form_close() ?>
            </div>
            <div class="card-footer">
                &copy; Sistem Informasi Kasir
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="<?= base_url() ?>/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url() ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url() ?>/dist/js/adminlte.min.js"></script>
</body>

</html>