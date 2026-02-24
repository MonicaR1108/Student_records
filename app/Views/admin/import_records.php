<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Import Student Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Import Student Records</h1>
        <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-outline-secondary">Back to Dashboard</a>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <p class="text-muted mb-3">
                Use the template first. Fill records in CSV (or XLSX if supported), then upload.
            </p>

            <div class="d-flex flex-wrap gap-2 mb-4">
                <a href="<?= site_url('admin/records/import/template') ?>" class="btn btn-outline-success">Download CSV</a>
                <button type="button" class="btn btn-outline-primary" id="selectExcelBtn">Select Excel</button>
            </div>

            <form method="post" action="<?= site_url('admin/records/import') ?>" enctype="multipart/form-data" id="importForm">
                <?= csrf_field() ?>
                <input type="file" name="import_file" id="import_file" class="d-none" accept=".csv,.xlsx">

                <div id="selectedFileBox" class="alert alert-secondary d-none mb-3">
                    Selected file: <span id="selectedFileName"></span>
                </div>

                <button type="submit" class="btn btn-primary d-none" id="uploadBtn">Upload</button>
            </form>
        </div>
    </div>
</div>

<script>
    const selectBtn = document.getElementById('selectExcelBtn');
    const fileInput = document.getElementById('import_file');
    const uploadBtn = document.getElementById('uploadBtn');
    const selectedFileBox = document.getElementById('selectedFileBox');
    const selectedFileName = document.getElementById('selectedFileName');

    selectBtn.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            selectedFileName.textContent = fileInput.files[0].name;
            selectedFileBox.classList.remove('d-none');
            uploadBtn.classList.remove('d-none');
        } else {
            selectedFileBox.classList.add('d-none');
            uploadBtn.classList.add('d-none');
        }
    });
</script>
</body>
</html>
