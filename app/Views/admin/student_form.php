<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0"><?= esc($title) ?></h1>
        <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-outline-secondary">Back</a>
    </div>

    <?php $validation = $validation ?? session('validation'); ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post" action="<?= esc($action) ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control <?= $validation && $validation->hasError('name') ? 'is-invalid' : '' ?>" value="<?= esc(old('name', $student['name'] ?? '')) ?>">
                        <?php if ($validation && $validation->hasError('name')): ?><div class="invalid-feedback"><?= esc($validation->getError('name')) ?></div><?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Register Number</label>
                        <input type="text" name="register_number" class="form-control <?= $validation && $validation->hasError('register_number') ? 'is-invalid' : '' ?>" value="<?= esc(old('register_number', $student['register_number'] ?? '')) ?>">
                        <?php if ($validation && $validation->hasError('register_number')): ?><div class="invalid-feedback"><?= esc($validation->getError('register_number')) ?></div><?php endif; ?>
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
                        <input type="email" name="email" class="form-control <?= $validation && $validation->hasError('email') ? 'is-invalid' : '' ?>" value="<?= esc(old('email', $student['email'] ?? '')) ?>">
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
                        <label class="form-label">Photo (JPG/PNG/WEBP, max 2MB)</label>
                        <input type="file" name="photo" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Certificates (PDF/DOC/DOCX/JPG/PNG, max 5MB each)</label>
                        <input type="file" name="certificates[]" class="form-control" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    </div>
                </div>

                <?php if (! empty($student['photo'])): ?>
                    <div class="mt-3">
                        <strong>Existing Photo:</strong>
                        <a href="<?= site_url('files/photo/view/' . $student['id']) ?>" target="_blank" class="ms-2"><?= esc(basename((string) $student['photo'])) ?></a>
                    </div>
                <?php endif; ?>

                <?php if (! empty($certificates ?? [])): ?>
                    <div class="mt-3">
                        <strong>Existing Certificates:</strong>
                        <ul class="mb-0 mt-2">
                            <?php foreach ($certificates as $certificate): ?>
                                <?php $fileName = basename((string) $certificate['file_name']); ?>
                                <li><a href="<?= site_url('files/certificate/view/' . $certificate['id']) ?>" target="_blank"><?= esc($fileName) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary mt-4">Save</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
