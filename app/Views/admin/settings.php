<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-0">Admin Settings</h1>
            <small class="text-muted">Change account password</small>
        </div>
        <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-outline-secondary">Back to Dashboard</a>
    </div>

    <?php $validation = $validation ?? session('validation'); ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post" action="<?= site_url('admin/settings/password') ?>">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="old_password" class="form-label">Old Password</label>
                    <input type="password" id="old_password" name="old_password" class="form-control <?= $validation && $validation->hasError('old_password') ? 'is-invalid' : '' ?>">
                    <?php if ($validation && $validation->hasError('old_password')): ?>
                        <div class="invalid-feedback"><?= esc($validation->getError('old_password')) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-control <?= $validation && $validation->hasError('new_password') ? 'is-invalid' : '' ?>">
                    <?php if ($validation && $validation->hasError('new_password')): ?>
                        <div class="invalid-feedback"><?= esc($validation->getError('new_password')) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control <?= $validation && $validation->hasError('confirm_password') ? 'is-invalid' : '' ?>">
                    <?php if ($validation && $validation->hasError('confirm_password')): ?>
                        <div class="invalid-feedback"><?= esc($validation->getError('confirm_password')) ?></div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary">Change Password</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
