<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
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
            <a href="<?= site_url('admin/records/import') ?>" class="btn btn-outline-primary">Import Excel</a>
            <a href="<?= site_url('admin/records/export') ?>" class="btn btn-outline-success">Export Excel</a>
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
        <div class="col-md-4">
            <input type="text" name="q" value="<?= esc($query) ?>" class="form-control" placeholder="Search name, register no, email...">
        </div>
        <div class="col-md-2">
            <select name="gender" class="form-select">
                <option value="">All Genders</option>
                <?php foreach (['Male', 'Female', 'Other'] as $item): ?>
                    <option value="<?= esc($item) ?>" <?= ($gender ?? '') === $item ? 'selected' : '' ?>><?= esc($item) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="degree" class="form-select">
                <option value="">All Degrees</option>
                <?php foreach (['B.E', 'B.Tech', 'M.E', 'M.Tech'] as $item): ?>
                    <option value="<?= esc($item) ?>" <?= ($degree ?? '') === $item ? 'selected' : '' ?>><?= esc($item) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="branch" class="form-select">
                <option value="">All Branches</option>
                <?php foreach (['CSE', 'IT', 'ECE', 'EEE', 'Civil', 'Mech'] as $item): ?>
                    <option value="<?= esc($item) ?>" <?= ($branch ?? '') === $item ? 'selected' : '' ?>><?= esc($item) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="sort" class="form-select">
                <option value="id" <?= ($sort ?? 'id') === 'id' ? 'selected' : '' ?>>Sort: ID</option>
                <option value="name" <?= ($sort ?? '') === 'name' ? 'selected' : '' ?>>Sort: Name</option>
                <option value="register_number" <?= ($sort ?? '') === 'register_number' ? 'selected' : '' ?>>Sort: Register No</option>
                <option value="gender" <?= ($sort ?? '') === 'gender' ? 'selected' : '' ?>>Sort: Gender</option>
                <option value="degree" <?= ($sort ?? '') === 'degree' ? 'selected' : '' ?>>Sort: Degree</option>
                <option value="branch" <?= ($sort ?? '') === 'branch' ? 'selected' : '' ?>>Sort: Branch</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="dir" class="form-select">
                <option value="desc" <?= ($dir ?? 'desc') === 'desc' ? 'selected' : '' ?>>Descending</option>
                <option value="asc" <?= ($dir ?? '') === 'asc' ? 'selected' : '' ?>>Ascending</option>
            </select>
        </div>
        <div class="col-md-10 d-flex gap-2">
            <button type="submit" class="btn btn-outline-primary">Apply Filters</button>
            <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-outline-secondary">Reset</a>
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
                    <th>Certificates</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($students)): ?>
                    <tr><td colspan="9" class="text-center py-4">No records found.</td></tr>
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
                            <td><?= esc((string) ($certificateCounts[(int) $student['id']] ?? 0)) ?></td>
                            <td class="d-flex gap-1">
                                <a href="<?= site_url('admin/students/view/' . $student['id']) ?>" class="btn btn-sm btn-info" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?= site_url('admin/students/edit/' . $student['id']) ?>" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form method="post" action="<?= site_url('admin/students/delete/' . $student['id']) ?>" onsubmit="return confirm('Delete this student record?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
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
        <?= $pager->only(['q', 'gender', 'degree', 'branch', 'sort', 'dir'])->links('students', 'bootstrap_full') ?>
    </div>
</div>
</body>
</html>
