<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-3">Student Login</h1>
                    <p class="text-muted small">Login with Student Name and Register Number.</p>

                    <?php $validation = session('validation'); ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
                    <?php endif; ?>

                    <form method="post" action="<?= site_url('student/login') ?>">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Student Name</label>
                            <input type="text" name="name" id="name" class="form-control <?= $validation && $validation->hasError('name') ? 'is-invalid' : '' ?>" value="<?= esc(old('name')) ?>">
                            <?php if ($validation && $validation->hasError('name')): ?>
                                <div class="invalid-feedback"><?= esc($validation->getError('name')) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="register_number" class="form-label">Register Number</label>
                            <input type="password" name="register_number" id="register_number" class="form-control <?= $validation && $validation->hasError('register_number') ? 'is-invalid' : '' ?>">
                            <?php if ($validation && $validation->hasError('register_number')): ?>
                                <div class="invalid-feedback"><?= esc($validation->getError('register_number')) ?></div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>

                    <a href="<?= site_url('/') ?>" class="btn btn-link px-0 mt-2">Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
