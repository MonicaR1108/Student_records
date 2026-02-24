<?php

namespace App\Controllers;

use App\Models\CertificateModel;
use App\Models\StudentModel;

class Student extends BaseController
{
    private StudentModel $studentModel;
    private CertificateModel $certificateModel;
    private Files $filesController;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->certificateModel = new CertificateModel();
        $this->filesController = new Files();
        helper(['form', 'url']);
    }

    public function login()
    {
        if (session()->get('is_student')) {
            session()->remove(['is_student', 'student_id', 'student_name']);
            session()->setFlashdata('success', 'Please login again.');
        }

        return view('student/login');
    }

    public function loginSubmit()
    {
        $rules = [
            'name'            => 'required|min_length[2]|max_length[100]',
            'register_number' => 'required|max_length[50]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/student/login')->withInput()->with('validation', $this->validator);
        }

        $name = trim((string) $this->request->getPost('name'));
        $registerNumber = trim((string) $this->request->getPost('register_number'));

        $student = $this->studentModel
            ->where('name', $name)
            ->where('register_number', $registerNumber)
            ->first();

        if (! $student) {
            return redirect()->to('/student/login')->withInput()->with('error', 'Invalid student credentials.');
        }

        session()->set([
            'is_student'   => true,
            'student_id'   => $student['id'],
            'student_name' => $student['name'],
        ]);

        return redirect()->to('/student/profile');
    }

    public function profile()
    {
        $student = $this->requireStudent();
        $isEdit = $this->request->getGet('edit') === '1';

        return view('student/profile', [
            'student'      => $student,
            'certificates' => $this->certificateModel->where('student_id', $student['id'])->findAll(),
            'validation'   => session('validation'),
            'isEdit'       => $isEdit,
        ]);
    }

    public function updateProfile()
    {
        $student = $this->requireStudent();

        $rules = [
            'name'        => 'required|min_length[2]|max_length[100]',
            'father_name' => 'required|min_length[2]|max_length[100]',
            'mother_name' => 'required|min_length[2]|max_length[100]',
            'gender'      => 'required|in_list[Male,Female,Other]',
            'email'       => "required|valid_email|is_unique[students.email,id,{$student['id']}]",
            'phone'       => 'required|min_length[10]|max_length[15]',
            'degree'      => 'required|in_list[B.E,B.Tech,M.E,M.Tech]',
            'branch'      => 'required|in_list[CSE,IT,ECE,EEE,Civil,Mech]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/student/profile?edit=1')->withInput()->with('validation', $this->validator);
        }

        $photo = $this->request->getFile('photo');
        $certificates = $this->extractUploadedCertificates($this->request->getFiles()['certificates'] ?? []);

        if ($photo && $photo->isValid() && ! $photo->hasMoved() && ! $this->filesController->isValidPhoto($photo)) {
            return redirect()->to('/student/profile?edit=1')->withInput()->with('error', 'Invalid photo format or size.');
        }

        foreach ($certificates as $certificate) {
            if (! $this->filesController->isValidCertificate($certificate)) {
                return redirect()->to('/student/profile?edit=1')->withInput()->with('error', 'One or more certificate files are invalid.');
            }
        }

        $this->studentModel->update($student['id'], [
            'name'        => trim((string) $this->request->getPost('name')),
            'father_name' => trim((string) $this->request->getPost('father_name')),
            'mother_name' => trim((string) $this->request->getPost('mother_name')),
            'gender'      => trim((string) $this->request->getPost('gender')),
            'email'       => trim((string) $this->request->getPost('email')),
            'phone'       => trim((string) $this->request->getPost('phone')),
            'degree'      => trim((string) $this->request->getPost('degree')),
            'branch'      => trim((string) $this->request->getPost('branch')),
        ]);

        if ($photo && $photo->isValid() && ! $photo->hasMoved()) {
            $photoName = $this->filesController->storePhotoFile($photo);
            if (! empty($student['photo'])) {
                $oldPath = WRITEPATH . 'uploads/' . $student['photo'];
                if (is_file($oldPath)) {
                    unlink($oldPath);
                }
            }
            $this->studentModel->update($student['id'], ['photo' => $photoName]);
        }

        if ($certificates !== []) {
            $storedCertificates = $this->filesController->storeCertificateFiles($certificates);
            foreach ($storedCertificates as $fileName) {
                $this->certificateModel->insert([
                    'student_id' => $student['id'],
                    'file_name'  => $fileName,
                ]);
            }
        }

        return redirect()->to('/student/profile')->with('success', 'Profile updated successfully.');
    }

    public function deleteCertificate(int $certificateId)
    {
        $student = $this->requireStudent();

        $certificate = $this->certificateModel->find($certificateId);
        if (! $certificate || (int) $certificate['student_id'] !== (int) $student['id']) {
            return redirect()->to('/student/profile')->with('error', 'Certificate not found.');
        }

        $path = WRITEPATH . 'uploads/' . $certificate['file_name'];
        if (is_file($path)) {
            unlink($path);
        }

        $this->certificateModel->delete($certificateId);

        return redirect()->to('/student/profile')->with('success', 'Certificate removed successfully.');
    }

    public function logout()
    {
        session()->remove(['is_student', 'student_id', 'student_name']);
        return redirect()->to('/student/login')->with('success', 'Logged out successfully.');
    }

    private function requireStudent(): array
    {
        if (! session()->get('is_student')) {
            redirect()->to('/student/login')->send();
            exit;
        }

        $student = $this->studentModel->find((int) session()->get('student_id'));
        if (! $student) {
            session()->remove(['is_student', 'student_id', 'student_name']);
            redirect()->to('/student/login')->send();
            exit;
        }

        return $student;
    }

    private function extractUploadedCertificates(array $certificates): array
    {
        return array_values(array_filter(
            $certificates,
            static fn ($certificate): bool => $certificate->getError() !== UPLOAD_ERR_NO_FILE
        ));
    }
}
