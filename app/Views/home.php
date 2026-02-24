<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Arunachala Institute of Engineering and Technology</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero {
            background: linear-gradient(120deg, #0d3b66 0%, #145da0 60%, #1e81b0 100%);
            color: #fff;
        }
        .section-title {
            border-left: 4px solid #145da0;
            padding-left: 12px;
        }
        .stat-box {
            border: 1px solid #e9ecef;
            border-radius: 0.75rem;
            background: #fff;
        }
    </style>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= site_url('/') ?>">AIET College Portal</a>
        <div class="ms-auto d-flex gap-2">
            <a href="<?= site_url('student/login') ?>" class="btn btn-outline-light btn-sm">Student Login</a>
            <a href="<?= site_url('admin/login') ?>" class="btn btn-warning btn-sm">Admin Login</a>
        </div>
    </div>
</nav>

<section class="hero py-5">
    <div class="container py-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <h1 class="display-6 fw-bold mb-3">Arunachala Institute of Engineering and Technology</h1>
                <p class="lead mb-4">
                    Accredited engineering institution focused on academic excellence, research, and industry-ready graduates.
                    This portal provides access to student records, certificates, and profile management.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge text-bg-light px-3 py-2">NBA Accredited Programs</span>
                    <span class="badge text-bg-light px-3 py-2">NAAC Grade A</span>
                    <span class="badge text-bg-light px-3 py-2">Autonomous Curriculum</span>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow border-0">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Portal Access</h2>
                        <div class="d-grid gap-2">
                            <a href="<?= site_url('student/login') ?>" class="btn btn-primary">Student Login</a>
                            <a href="<?= site_url('admin/login') ?>" class="btn btn-dark">Admin Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container py-4">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-box p-3 text-center shadow-sm">
                <h3 class="h4 mb-1">4200+</h3>
                <p class="mb-0 text-muted">Students</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box p-3 text-center shadow-sm">
                <h3 class="h4 mb-1">280+</h3>
                <p class="mb-0 text-muted">Faculty Members</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box p-3 text-center shadow-sm">
                <h3 class="h4 mb-1">94%</h3>
                <p class="mb-0 text-muted">Placement Rate</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box p-3 text-center shadow-sm">
                <h3 class="h4 mb-1">40+</h3>
                <p class="mb-0 text-muted">Recruiting Companies</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h2 class="h5 section-title mb-3">About the Institution</h2>
                    <p class="text-muted mb-0">
                        AIET offers undergraduate and postgraduate engineering programs with strong laboratory facilities,
                        innovation centers, and industry partnerships. The institution emphasizes technical depth,
                        leadership skills, and ethical engineering practices.
                    </p>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h2 class="h5 section-title mb-3">Programs Offered</h2>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="mb-0">
                                <li>B.E - Computer Science and Engineering</li>
                                <li>B.E - Electronics and Communication Engineering</li>
                                <li>B.E - Electrical and Electronics Engineering</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="mb-0">
                                <li>B.Tech - Information Technology</li>
                                <li>B.E - Civil Engineering</li>
                                <li>B.E - Mechanical Engineering</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h2 class="h5 section-title mb-3">Campus Facilities</h2>
                    <div class="row g-2">
                        <div class="col-sm-6"><span class="badge text-bg-secondary w-100 py-2">Central Library</span></div>
                        <div class="col-sm-6"><span class="badge text-bg-secondary w-100 py-2">Smart Classrooms</span></div>
                        <div class="col-sm-6"><span class="badge text-bg-secondary w-100 py-2">Hostel & Transport</span></div>
                        <div class="col-sm-6"><span class="badge text-bg-secondary w-100 py-2">Research Labs</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h2 class="h5 section-title mb-3">Latest Announcements</h2>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0">Semester examinations commence from March 18.</li>
                        <li class="list-group-item px-0">Final year project review schedule published.</li>
                        <li class="list-group-item px-0">Campus drive registrations open for 2026 batch.</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h2 class="h5 section-title mb-3">Contact</h2>
                    <p class="mb-1"><strong>Office:</strong> +91 44 4000 1234</p>
                    <p class="mb-1"><strong>Email:</strong> info@aiet.edu.in</p>
                    <p class="mb-0"><strong>Address:</strong> Chennai, Tamil Nadu, India</p>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="bg-dark text-light mt-5">
    <div class="container py-3 d-flex flex-wrap justify-content-between">
        <small>&copy; <?= date('Y') ?> Arunachala Institute of Engineering and Technology</small>
        <small>Student Record Management Portal</small>
    </div>
</footer>
</body>
</html>
