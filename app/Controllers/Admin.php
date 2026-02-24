<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\CertificateModel;
use App\Models\StudentModel;

class Admin extends BaseController
{
    private AdminModel $adminModel;
    private StudentModel $studentModel;
    private CertificateModel $certificateModel;
    private Files $filesController;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
        $this->studentModel = new StudentModel();
        $this->certificateModel = new CertificateModel();
        $this->filesController = new Files();
        helper(['form', 'url']);
    }

    public function login()
    {
        if (session()->get('is_admin')) {
            return redirect()->to('/admin/dashboard');
        }

        if ($this->canRegisterAdmin()) {
            return redirect()->to('/admin/register');
        }

        return view('admin/login');
    }

    public function loginSubmit()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/admin/login')->withInput()->with('validation', $this->validator);
        }

        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        $admin = $this->adminModel->where('username', $username)->first();

        if (! $admin || ! password_verify($password, $admin['password'])) {
            return redirect()->to('/admin/login')->withInput()->with('error', 'Invalid username or password.');
        }

        session()->set([
            'is_admin'   => true,
            'admin_id'   => $admin['id'],
            'admin_name' => $admin['username'] ?? $admin['name'],
        ]);

        return redirect()->to('/admin/dashboard');
    }

    public function register()
    {
        if (session()->get('is_admin')) {
            return redirect()->to('/admin/dashboard');
        }

        if (! $this->canRegisterAdmin()) {
            return redirect()->to('/admin/login')->with('error', 'Admin is already registered. Please login.');
        }

        return view('admin/register');
    }

    public function registerSubmit()
    {
        if (! $this->canRegisterAdmin()) {
            return redirect()->to('/admin/login')->with('error', 'Admin is already registered. Please login.');
        }

        $rules = [
            'username'         => 'required|min_length[3]|max_length[50]|alpha_numeric_punct',
            'password'         => 'required|min_length[6]|max_length[255]',
            'confirm_password' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/admin/register')->withInput()->with('validation', $this->validator);
        }

        $username = strtolower(trim((string) $this->request->getPost('username')));
        $password = (string) $this->request->getPost('password');

        $existing = $this->adminModel->where('username', $username)->first();
        if ($existing) {
            return redirect()->to('/admin/register')->withInput()->with('error', 'Username already exists.');
        }

        $seedAdmin = $this->getSeededAdmin();
        if ($seedAdmin) {
            $this->adminModel->update($seedAdmin['id'], [
                'name'     => $username,
                'username' => $username,
                'email'    => null,
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ]);
        } else {
            $this->adminModel->insert([
                'name'     => $username,
                'username' => $username,
                'email'    => null,
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ]);
        }

        return redirect()->to('/admin/login')->with('success', 'Admin registered successfully. Please login.');
    }

    public function dashboard()
    {
        $this->requireAdmin();

        $query = trim((string) $this->request->getGet('q'));

        $builder = $this->studentModel;
        if ($query !== '') {
            $builder = $builder
                ->groupStart()
                ->like('name', $query)
                ->orLike('register_number', $query)
                ->orLike('email', $query)
                ->orLike('gender', $query)
                ->orLike('degree', $query)
                ->orLike('branch', $query)
                ->groupEnd();
        }

        $students = $builder->orderBy('id', 'desc')->paginate(10);

        $certificateCounts = [];
        if ($students !== []) {
            $studentIds = array_map(static fn (array $student): int => (int) $student['id'], $students);
            $rows = $this->certificateModel
                ->select('student_id, COUNT(*) as total')
                ->whereIn('student_id', $studentIds)
                ->groupBy('student_id')
                ->findAll();

            foreach ($rows as $row) {
                $certificateCounts[(int) $row['student_id']] = (int) $row['total'];
            }
        }

        return view('admin/dashboard', [
            'students'          => $students,
            'pager'             => $this->studentModel->pager,
            'query'             => $query,
            'certificateCounts' => $certificateCounts,
        ]);
    }

    public function settings()
    {
        $this->requireAdmin();

        return view('admin/settings', [
            'validation' => session('validation'),
        ]);
    }

    public function changePassword()
    {
        $this->requireAdmin();

        $rules = [
            'old_password'     => 'required|min_length[6]',
            'new_password'     => 'required|min_length[6]|max_length[255]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/admin/settings')->withInput()->with('validation', $this->validator);
        }

        $adminId = (int) session()->get('admin_id');
        $admin = $this->adminModel->find($adminId);
        if (! $admin) {
            session()->remove(['is_admin', 'admin_id', 'admin_name']);
            return redirect()->to('/admin/login')->with('error', 'Session expired. Please login again.');
        }

        $oldPassword = (string) $this->request->getPost('old_password');
        $newPassword = (string) $this->request->getPost('new_password');

        if (! password_verify($oldPassword, $admin['password'])) {
            return redirect()->to('/admin/settings')->withInput()->with('error', 'Old password is incorrect.');
        }

        if (password_verify($newPassword, $admin['password'])) {
            return redirect()->to('/admin/settings')->withInput()->with('error', 'New password must be different from old password.');
        }

        $this->adminModel->update($adminId, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/admin/settings')->with('success', 'Password changed successfully.');
    }

    public function createStudent()
    {
        $this->requireAdmin();

        return view('admin/student_form', [
            'title'      => 'Add Student',
            'action'     => site_url('admin/students/store'),
            'student'    => null,
            'validation' => session('validation'),
        ]);
    }

    public function storeStudent()
    {
        $this->requireAdmin();

        $rules = $this->studentValidationRules();

        if (! $this->validate($rules)) {
            return redirect()->to('/admin/students/create')->withInput()->with('validation', $this->validator);
        }

        $photo = $this->request->getFile('photo');
        $certificates = $this->extractUploadedCertificates($this->request->getFiles()['certificates'] ?? []);

        if ($photo && $photo->isValid() && ! $photo->hasMoved() && ! $this->filesController->isValidPhoto($photo)) {
            return redirect()->to('/admin/students/create')->withInput()->with('error', 'Invalid photo format or size.');
        }

        foreach ($certificates as $certificate) {
            if (! $this->filesController->isValidCertificate($certificate)) {
                return redirect()->to('/admin/students/create')->withInput()->with('error', 'One or more certificate files are invalid.');
            }
        }

        $studentData = $this->buildStudentPayload();
        $studentId = (int) $this->studentModel->insert($studentData, true);

        if ($photo && $photo->isValid() && ! $photo->hasMoved()) {
            $photoName = $this->filesController->storePhotoFile($photo);
            $this->studentModel->update($studentId, ['photo' => $photoName]);
        }

        if ($certificates !== []) {
            $storedCertificates = $this->filesController->storeCertificateFiles($certificates);
            foreach ($storedCertificates as $fileName) {
                $this->certificateModel->insert([
                    'student_id' => $studentId,
                    'file_name'  => $fileName,
                ]);
            }
        }

        return redirect()->to('/admin/dashboard')->with('success', 'Student created successfully.');
    }

    public function editStudent(int $id)
    {
        $this->requireAdmin();

        $student = $this->studentModel->find($id);
        if (! $student) {
            return redirect()->to('/admin/dashboard')->with('error', 'Student not found.');
        }

        return view('admin/student_form', [
            'title'       => 'Edit Student',
            'action'      => site_url('admin/students/update/' . $id),
            'student'     => $student,
            'validation'  => session('validation'),
            'certificates'=> $this->certificateModel->where('student_id', $id)->findAll(),
        ]);
    }

    public function updateStudent(int $id)
    {
        $this->requireAdmin();

        $student = $this->studentModel->find($id);
        if (! $student) {
            return redirect()->to('/admin/dashboard')->with('error', 'Student not found.');
        }

        $rules = $this->studentValidationRules($id);

        if (! $this->validate($rules)) {
            return redirect()->to('/admin/students/edit/' . $id)->withInput()->with('validation', $this->validator);
        }

        $photo = $this->request->getFile('photo');
        $certificates = $this->extractUploadedCertificates($this->request->getFiles()['certificates'] ?? []);

        if ($photo && $photo->isValid() && ! $photo->hasMoved() && ! $this->filesController->isValidPhoto($photo)) {
            return redirect()->to('/admin/students/edit/' . $id)->withInput()->with('error', 'Invalid photo format or size.');
        }

        foreach ($certificates as $certificate) {
            if (! $this->filesController->isValidCertificate($certificate)) {
                return redirect()->to('/admin/students/edit/' . $id)->withInput()->with('error', 'One or more certificate files are invalid.');
            }
        }

        $this->studentModel->update($id, $this->buildStudentPayload());

        if ($photo && $photo->isValid() && ! $photo->hasMoved()) {
            $photoName = $this->filesController->storePhotoFile($photo);
            if (! empty($student['photo'])) {
                $oldPath = WRITEPATH . 'uploads/' . $student['photo'];
                if (is_file($oldPath)) {
                    unlink($oldPath);
                }
            }
            $this->studentModel->update($id, ['photo' => $photoName]);
        }

        if ($certificates !== []) {
            $storedCertificates = $this->filesController->storeCertificateFiles($certificates);
            foreach ($storedCertificates as $fileName) {
                $this->certificateModel->insert([
                    'student_id' => $id,
                    'file_name'  => $fileName,
                ]);
            }
        }

        return redirect()->to('/admin/dashboard')->with('success', 'Student updated successfully.');
    }

    public function deleteStudent(int $id)
    {
        $this->requireAdmin();

        $student = $this->studentModel->find($id);
        if (! $student) {
            return redirect()->to('/admin/dashboard')->with('error', 'Student not found.');
        }

        $certificates = $this->certificateModel->where('student_id', $id)->findAll();
        foreach ($certificates as $certificate) {
            $path = WRITEPATH . 'uploads/' . $certificate['file_name'];
            if (is_file($path)) {
                unlink($path);
            }
        }

        if (! empty($student['photo'])) {
            $photoPath = WRITEPATH . 'uploads/' . $student['photo'];
            if (is_file($photoPath)) {
                unlink($photoPath);
            }
        }

        $this->certificateModel->where('student_id', $id)->delete();
        $this->studentModel->delete($id);

        return redirect()->to('/admin/dashboard')->with('success', 'Student deleted successfully.');
    }

    public function logout()
    {
        session()->remove(['is_admin', 'admin_id', 'admin_name']);
        return redirect()->to('/admin/login')->with('success', 'Logged out successfully.');
    }

    private function requireAdmin(): void
    {
        if (! session()->get('is_admin')) {
            redirect()->to('/admin/login')->send();
            exit;
        }
    }

    private function studentValidationRules(?int $id = null): array
    {
        $registerRule = 'required|max_length[50]|is_unique[students.register_number]';
        $emailRule = 'required|valid_email|is_unique[students.email]';

        if ($id !== null) {
            $registerRule = "required|max_length[50]|is_unique[students.register_number,id,{$id}]";
            $emailRule = "required|valid_email|is_unique[students.email,id,{$id}]";
        }

        return [
            'name'            => 'required|min_length[2]|max_length[100]',
            'father_name'     => 'required|min_length[2]|max_length[100]',
            'mother_name'     => 'required|min_length[2]|max_length[100]',
            'gender'          => 'required|in_list[Male,Female,Other]',
            'email'           => $emailRule,
            'phone'           => 'required|min_length[10]|max_length[15]',
            'degree'          => 'required|in_list[B.E,B.Tech,M.E,M.Tech]',
            'branch'          => 'required|in_list[CSE,IT,ECE,EEE,Civil,Mech]',
            'register_number' => $registerRule,
        ];
    }

    private function buildStudentPayload(): array
    {
        return [
            'name'            => trim((string) $this->request->getPost('name')),
            'father_name'     => trim((string) $this->request->getPost('father_name')),
            'mother_name'     => trim((string) $this->request->getPost('mother_name')),
            'gender'          => trim((string) $this->request->getPost('gender')),
            'email'           => trim((string) $this->request->getPost('email')),
            'phone'           => trim((string) $this->request->getPost('phone')),
            'degree'          => trim((string) $this->request->getPost('degree')),
            'branch'          => trim((string) $this->request->getPost('branch')),
            'register_number' => trim((string) $this->request->getPost('register_number')),
        ];
    }

    private function extractUploadedCertificates(array $certificates): array
    {
        return array_values(array_filter(
            $certificates,
            static fn ($certificate): bool => $certificate->getError() !== UPLOAD_ERR_NO_FILE
        ));
    }

    private function canRegisterAdmin(): bool
    {
        $adminCount = $this->adminModel->countAllResults();
        if ($adminCount === 0) {
            return true;
        }

        return $this->getSeededAdmin() !== null;
    }

    private function getSeededAdmin(): ?array
    {
        $admins = $this->adminModel->findAll();
        if (count($admins) !== 1) {
            return null;
        }

        $admin = $admins[0];
        $isSeededEmail = ($admin['email'] ?? '') === 'admin@college.edu';
        $isSeededName = in_array(strtolower((string) ($admin['name'] ?? '')), ['portal admin', 'portal_admin'], true);

        if ($isSeededEmail && $isSeededName) {
            return $admin;
        }

        return null;
    }
}
