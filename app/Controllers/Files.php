<?php

namespace App\Controllers;

use App\Models\CertificateModel;
use App\Models\StudentModel;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\Files\UploadedFile;

class Files extends BaseController
{
    private StudentModel $studentModel;
    private CertificateModel $certificateModel;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->certificateModel = new CertificateModel();
        helper(['filesystem']);
    }

    public function uploadPhoto(int $studentId)
    {
        $student = $this->studentModel->find($studentId);
        if (! $student) {
            return redirect()->back()->with('error', 'Student not found.');
        }

        if (! $this->canAccessStudent((int) $student['id'])) {
            return redirect()->to('/')->with('error', 'Unauthorized access.');
        }

        $photo = $this->request->getFile('photo');
        if (! $photo || ! $photo->isValid()) {
            return redirect()->back()->with('error', 'Please upload a valid photo.');
        }

        if (! $this->isValidPhoto($photo)) {
            return redirect()->back()->with('error', 'Photo must be JPG, PNG, or WEBP and below 2 MB.');
        }

        $newName = $this->storePhotoFile($photo);

        if (! empty($student['photo'])) {
            $oldPath = WRITEPATH . 'uploads/' . $student['photo'];
            if (is_file($oldPath)) {
                unlink($oldPath);
            }
        }

        $this->studentModel->update($studentId, ['photo' => $newName]);

        return redirect()->back()->with('success', 'Photo uploaded successfully.');
    }

    public function uploadCertificates(int $studentId)
    {
        $student = $this->studentModel->find($studentId);
        if (! $student) {
            return redirect()->back()->with('error', 'Student not found.');
        }

        if (! $this->canAccessStudent((int) $student['id'])) {
            return redirect()->to('/')->with('error', 'Unauthorized access.');
        }

        $files = $this->request->getFiles();
        $certificates = array_values(array_filter(
            $files['certificates'] ?? [],
            static fn ($certificate): bool => $certificate->getError() !== UPLOAD_ERR_NO_FILE
        ));

        if (! is_array($certificates) || $certificates === []) {
            return redirect()->back()->with('error', 'Please select at least one certificate file.');
        }

        foreach ($certificates as $certificate) {
            if (! $certificate->isValid() || ! $this->isValidCertificate($certificate)) {
                return redirect()->back()->with('error', 'Certificates must be PDF, DOC, DOCX, JPG, JPEG, or PNG and below 5 MB each.');
            }
        }

        $storedNames = $this->storeCertificateFiles($certificates);
        foreach ($storedNames as $fileName) {
            $this->certificateModel->insert([
                'student_id' => $studentId,
                'file_name'  => $fileName,
            ]);
        }

        return redirect()->back()->with('success', 'Certificates uploaded successfully.');
    }

    public function viewPhoto(int $studentId)
    {
        $student = $this->studentModel->find($studentId);
        if (! $student || empty($student['photo'])) {
            return redirect()->back()->with('error', 'Photo not found.');
        }

        if (! $this->canAccessStudent((int) $student['id'])) {
            return redirect()->to('/')->with('error', 'Unauthorized access.');
        }

        $path = WRITEPATH . 'uploads/' . $student['photo'];
        if (! is_file($path)) {
            return redirect()->back()->with('error', 'Photo file is missing.');
        }

        return $this->serveInlineFile($path);
    }

    public function viewCertificate(int $certificateId)
    {
        $certificate = $this->certificateModel->find($certificateId);
        if (! $certificate) {
            return redirect()->back()->with('error', 'Certificate not found.');
        }

        if (! $this->canAccessStudent((int) $certificate['student_id'])) {
            return redirect()->to('/')->with('error', 'Unauthorized access.');
        }

        $path = WRITEPATH . 'uploads/' . $certificate['file_name'];
        if (! is_file($path)) {
            return redirect()->back()->with('error', 'Certificate file is missing.');
        }

        return $this->serveInlineFile($path);
    }

    public function isValidPhoto(UploadedFile $file): bool
    {
        $allowedMime = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

        return in_array($file->getMimeType(), $allowedMime, true) && $file->getSizeByUnit('mb') <= 2;
    }

    public function isValidCertificate(UploadedFile $file): bool
    {
        $allowedMime = [
            'application/pdf',
            'image/jpeg',
            'image/jpg',
            'image/png',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];
        $allowedExt = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];

        return in_array(strtolower($file->getExtension()), $allowedExt, true)
            && in_array($file->getMimeType(), $allowedMime, true)
            && $file->getSizeByUnit('mb') <= 5;
    }

    public function storePhotoFile(UploadedFile $file): string
    {
        $uploadPath = $this->ensureUploadPath('photos');
        $newName = $file->getRandomName();
        $file->move($uploadPath, $newName);

        return 'photos/' . $newName;
    }

    /**
     * @param array<int, UploadedFile> $files
     * @return array<int, string>
     */
    public function storeCertificateFiles(array $files): array
    {
        $uploadPath = $this->ensureUploadPath('certificates');
        $stored = [];

        foreach ($files as $file) {
            if (! $file->isValid() || $file->hasMoved()) {
                continue;
            }

            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $stored[] = 'certificates/' . $newName;
        }

        return $stored;
    }

    private function canAccessStudent(int $studentId): bool
    {
        if (session()->get('is_admin')) {
            return true;
        }

        return session()->get('is_student') && (int) session()->get('student_id') === $studentId;
    }

    private function ensureUploadPath(string $dir): string
    {
        $path = WRITEPATH . 'uploads/' . $dir;
        if (! is_dir($path)) {
            mkdir($path, 0777, true);
        }

        return $path;
    }

    private function serveInlineFile(string $path)
    {
        $file = new File($path);
        $mime = $file->getMimeType() ?: 'application/octet-stream';
        $filename = basename($path);

        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody((string) file_get_contents($path));
    }
}
