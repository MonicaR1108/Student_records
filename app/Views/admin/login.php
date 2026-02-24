<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-3">Admin Login</h1>
                    <p class="text-muted small">Login with your registered username and password.</p>

                    <?php $validation = session('validation'); ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
                    <?php endif; ?>

                    <form method="post" action="<?= site_url('admin/login') ?>">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control <?= $validation && $validation->hasError('username') ? 'is-invalid' : '' ?>" value="<?= esc(old('username')) ?>">
                            <?php if ($validation && $validation->hasError('username')): ?>
                                <div class="invalid-feedback"><?= esc($validation->getError('username')) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control <?= $validation && $validation->hasError('password') ? 'is-invalid' : '' ?>">
                            <?php if ($validation && $validation->hasError('password')): ?>
                                <div class="invalid-feedback"><?= esc($validation->getError('password')) ?></div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-dark w-100">Login</button>
                    </form>

                    <a href="<?= site_url('/') ?>" class="btn btn-link px-0 mt-2">Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
