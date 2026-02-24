<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-0">Student Profile</h1>
            <small class="text-muted">Welcome, <?= esc((string) session('student_name')) ?></small>
        </div>
        <div class="d-flex gap-2">
            <?php if (! ($isEdit ?? false)): ?>
                <a href="<?= site_url('student/profile?edit=1') ?>" class="btn btn-primary">Edit Details</a>
            <?php else: ?>
                <a href="<?= site_url('student/profile') ?>" class="btn btn-outline-secondary">Cancel</a>
            <?php endif; ?>
            <a href="<?= site_url('student/logout') ?>" class="btn btn-outline-dark">Logout</a>
        </div>
    </div>

    <?php $validation = $validation ?? session('validation'); ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center g-3">
                <div class="col-md-3 text-center">
                    <?php if (! empty($student['photo'])): ?>
                        <a href="<?= site_url('files/photo/view/' . $student['id']) ?>" target="_blank">
                            <img src="<?= site_url('files/photo/view/' . $student['id']) ?>" alt="Profile Photo" class="img-fluid rounded" style="max-height: 220px;">
                        </a>
                        <div class="small text-muted mt-2"><?= esc(basename((string) $student['photo'])) ?></div>
                    <?php else: ?>
                        <div class="border rounded p-4 text-muted">No Profile Photo</div>
                    <?php endif; ?>
                </div>
                <div class="col-md-9">
                    <h2 class="h5 mb-3">Student Details</h2>
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($isEdit ?? false): ?>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h2 class="h5 mb-3">Edit Profile</h2>
                <form method="post" action="<?= site_url('student/profile/update') ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control <?= $validation && $validation->hasError('name') ? 'is-invalid' : '' ?>" value="<?= esc(old('name', $student['name'])) ?>">
                            <?php if ($validation && $validation->hasError('name')): ?><div class="invalid-feedback"><?= esc($validation->getError('name')) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Register Number</label>
                            <input type="text" class="form-control" value="<?= esc($student['register_number']) ?>" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Father's Name</label>
                            <input type="text" name="father_name" class="form-control <?= $validation && $validation->hasError('father_name') ? 'is-invalid' : '' ?>" value="<?= esc(old('father_name', $student['father_name'] ?? '')) ?>">
                            <?php if ($validation && $validation->hasError('father_name')): ?><div class="invalid-feedback"><?= esc($validation->getError('father_name')) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mother's Name</label>
                            <input type="text" name="mother_name" class="form-control <?= $validation && $validation->hasError('mother_name') ? 'is-invalid' : '' ?>" value="<?= esc(old('mother_name', $student['mother_name'] ?? '')) ?>">
                            <?php if ($validation && $validation->hasError('mother_name')): ?><div class="invalid-feedback"><?= esc($validation->getError('mother_name')) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gender</label>
                            <?php $selectedGender = old('gender', $student['gender'] ?? ''); ?>
                            <select name="gender" class="form-select <?= $validation && $validation->hasError('gender') ? 'is-invalid' : '' ?>">
                                <option value="">Select Gender</option>
                                <?php foreach (['Male', 'Female', 'Other'] as $gender): ?>
                                    <option value="<?= esc($gender) ?>" <?= $selectedGender === $gender ? 'selected' : '' ?>><?= esc($gender) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($validation && $validation->hasError('gender')): ?><div class="invalid-feedback"><?= esc($validation->getError('gender')) ?></div><?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control <?= $validation && $validation->hasError('email') ? 'is-invalid' : '' ?>" value="<?= esc(old('email', $student['email'])) ?>">
                            <?php if ($validation && $validation->hasError('email')): ?><div class="invalid-feedback"><?= esc($validation->getError('email')) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control <?= $validation && $validation->hasError('phone') ? 'is-invalid' : '' ?>" value="<?= esc(old('phone', $student['phone'] ?? '')) ?>">
                            <?php if ($validation && $validation->hasError('phone')): ?><div class="invalid-feedback"><?= esc($validation->getError('phone')) ?></div><?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Degree</label>
                            <?php $selectedDegree = old('degree', $student['degree'] ?? ''); ?>
                            <select name="degree" class="form-select <?= $validation && $validation->hasError('degree') ? 'is-invalid' : '' ?>">
                                <option value="">Select Degree</option>
                                <?php foreach (['B.E', 'B.Tech', 'M.E', 'M.Tech'] as $degree): ?>
                                    <option value="<?= esc($degree) ?>" <?= $selectedDegree === $degree ? 'selected' : '' ?>><?= esc($degree) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($validation && $validation->hasError('degree')): ?><div class="invalid-feedback"><?= esc($validation->getError('degree')) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Branch</label>
                            <?php $selectedBranch = old('branch', $student['branch'] ?? ''); ?>
                            <select name="branch" class="form-select <?= $validation && $validation->hasError('branch') ? 'is-invalid' : '' ?>">
                                <option value="">Select Branch</option>
                                <?php foreach (['CSE', 'IT', 'ECE', 'EEE', 'Civil', 'Mech'] as $branch): ?>
                                    <option value="<?= esc($branch) ?>" <?= $selectedBranch === $branch ? 'selected' : '' ?>><?= esc($branch) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($validation && $validation->hasError('branch')): ?><div class="invalid-feedback"><?= esc($validation->getError('branch')) ?></div><?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Update Photo</label>
                            <input type="file" name="photo" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Upload Certificates</label>
                            <input type="file" name="certificates[]" class="form-control" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4">Save Changes</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="h6">Certificates</h2>
            <?php if (empty($certificates)): ?>
                <p class="text-muted mb-0">No certificates uploaded yet.</p>
            <?php else: ?>
                <ul class="list-group">
                    <?php foreach ($certificates as $certificate): ?>
                        <?php $fileName = basename((string) $certificate['file_name']); ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="<?= site_url('files/certificate/view/' . $certificate['id']) ?>" target="_blank">
                                <?= esc($fileName) ?>
                            </a>
                                <?php if ($isEdit ?? false): ?>
                                    <form method="post" action="<?= site_url('student/certificates/delete/' . $certificate['id']) ?>" onsubmit="return confirm('Delete this certificate?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
