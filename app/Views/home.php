<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>College Student Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h3 mb-3">College Student Record Management System</h1>
                    <p class="text-muted mb-4">
                        Welcome to the college portal. Students can access and maintain personal records,
                        upload profile photos and certificates, and administrators can manage all student data.
                    </p>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
                    <?php endif; ?>

                    <div class="d-grid gap-2 d-md-flex">
                        <a href="<?= site_url('student/login') ?>" class="btn btn-primary btn-lg">Student Login</a>
                        <a href="<?= site_url('admin/login') ?>" class="btn btn-dark btn-lg">Admin Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
