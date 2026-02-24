<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">Admin Dashboard</h1>
            <small class="text-muted">Welcome, <?= esc((string) session('admin_name')) ?></small>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= site_url('admin/students/create') ?>" class="btn btn-primary">Add Student</a>
            <a href="<?= site_url('admin/settings') ?>" class="btn btn-outline-secondary">Settings</a>
            <a href="<?= site_url('admin/logout') ?>" class="btn btn-outline-dark">Logout</a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <form method="get" action="<?= site_url('admin/dashboard') ?>" class="row g-2 mb-3">
        <div class="col-md-8">
            <input type="text" name="q" value="<?= esc($query) ?>" class="form-control" placeholder="Search by name, register number, email, degree, or branch">
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-outline-primary w-100">Search</button>
            <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Register No</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Degree / Branch</th>
                    <th>Photo</th>
                    <th>Certificates</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($students)): ?>
                    <tr><td colspan="10" class="text-center py-4">No records found.</td></tr>
                <?php else: ?>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= esc((string) $student['id']) ?></td>
                            <td><?= esc($student['name']) ?></td>
                            <td><?= esc($student['register_number']) ?></td>
                            <td><?= esc($student['gender'] ?? '') ?></td>
                            <td><?= esc($student['email']) ?></td>
                            <td><?= esc($student['phone'] ?? '') ?></td>
                            <td><?= esc(($student['degree'] ?? '') . ' / ' . ($student['branch'] ?? '')) ?></td>
                            <td>
                                <?php if (! empty($student['photo'])): ?>
                                    <a href="<?= site_url('files/photo/view/' . $student['id']) ?>" target="_blank">View</a>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc((string) ($certificateCounts[(int) $student['id']] ?? 0)) ?></td>
                            <td class="d-flex gap-1">
                                <a href="<?= site_url('admin/students/edit/' . $student['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                <form method="post" action="<?= site_url('admin/students/delete/' . $student['id']) ?>" onsubmit="return confirm('Delete this student record?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <?= $pager->links() ?>
    </div>
</div>
</body>
</html>
