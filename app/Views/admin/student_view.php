<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">View Student</h1>
        <div class="d-flex gap-2">
            <a href="<?= site_url('admin/students/edit/' . $student['id']) ?>" class="btn btn-warning">Edit</a>
            <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-outline-secondary">Back</a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <tbody>
                    <tr><th>Name</th><td><?= esc($student['name']) ?></td></tr>
                    <tr><th>Register Number</th><td><?= esc($student['register_number']) ?></td></tr>
                    <tr><th>Father's Name</th><td><?= esc($student['father_name'] ?? '') ?></td></tr>
                    <tr><th>Mother's Name</th><td><?= esc($student['mother_name'] ?? '') ?></td></tr>
                    <tr><th>Gender</th><td><?= esc($student['gender'] ?? '') ?></td></tr>
                    <tr><th>Email</th><td><?= esc($student['email']) ?></td></tr>
                    <tr><th>Phone</th><td><?= esc($student['phone'] ?? '') ?></td></tr>
                    <tr><th>Degree</th><td><?= esc($student['degree'] ?? '') ?></td></tr>
                    <tr><th>Branch</th><td><?= esc($student['branch'] ?? '') ?></td></tr>
                    <tr>
                        <th>Photo</th>
                        <td>
                            <?php if (! empty($student['photo'])): ?>
                                <a href="<?= site_url('files/photo/view/' . $student['id']) ?>" target="_blank"><?= esc(basename((string) $student['photo'])) ?></a>
                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="h6">Certificates</h2>
            <?php if (empty($certificates)): ?>
                <p class="text-muted mb-0">No certificates uploaded yet.</p>
            <?php else: ?>
                <ul class="list-group">
                    <?php foreach ($certificates as $certificate): ?>
                        <?php $fileName = basename((string) $certificate['file_name']); ?>
                        <li class="list-group-item">
                            <a href="<?= site_url('files/certificate/view/' . $certificate['id']) ?>" target="_blank"><?= esc($fileName) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
