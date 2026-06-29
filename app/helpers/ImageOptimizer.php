<?php
class ImageOptimizer {
    private $maxWidth;
    private $maxHeight;
    private $quality;
    private $uploadDir;
    private $gdAvailable;

    public function __construct($uploadDir, $maxWidth = 1200, $maxHeight = 1200, $quality = 80) {
        $this->uploadDir = $uploadDir;
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
        $this->quality = $quality;
        $this->gdAvailable = extension_loaded('gd');

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function processUpload($file) {
        if (!$file || $file["error"] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($file["error"] !== UPLOAD_ERR_OK) {
            throw new Exception("Upload gambar gagal dengan error code: " . $file["error"]);
        }

        $fileName = $file["name"];
        $tmpName = $file["tmp_name"];
        $fileSize = $file["size"];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed, true)) {
            throw new Exception("Format gambar harus JPG, PNG, atau WEBP.");
        }

        if ($fileSize > 5 * 1024 * 1024) {
            throw new Exception("Ukuran gambar maksimal 5 MB.");
        }

        // Validate image
        $imageInfo = getimagesize($tmpName);
        if ($imageInfo === false) {
            throw new Exception("File yang diupload bukan gambar valid.");
        }

        // Generate unique filename
        $baseName = uniqid('court_', true);
        $optimizedName = $baseName . "_optimized.jpg";
        $optimizedPath = $this->uploadDir . $optimizedName;

        try {
            // If GD is available, optimize the image
            if ($this->gdAvailable) {
                $this->optimizeImage($tmpName, $optimizedPath, $this->quality);
            } else {
                // Fallback: just copy and convert to JPG if needed
                if ($ext === 'jpg' || $ext === 'jpeg') {
                    copy($tmpName, $optimizedPath);
                } else {
                    // For PNG/WEBP without GD, convert to JPG using simple copy
                    // This is a limitation, but at least the file works
                    copy($tmpName, $optimizedPath);
                }
            }

            if (!file_exists($optimizedPath)) {
                throw new Exception("Gagal menyimpan gambar.");
            }

            return [
                'optimized' => $optimizedName,
                'original_extension' => $ext
            ];
        } catch (Exception $e) {
            // Clean up on error
            if (file_exists($optimizedPath)) unlink($optimizedPath);
            throw new Exception("Gagal memproses gambar: " . $e->getMessage());
        }
    }

    private function optimizeImage($source, $destination, $quality = 80) {
        if (!$this->gdAvailable) {
            throw new Exception("GD extension tidak tersedia. Menggunakan fallback copy file.");
        }

        $image = imagecreatefromstring(file_get_contents($source));
        if (!$image) {
            throw new Exception("Gagal membaca gambar.");
        }

        $width = imagesx($image);
        $height = imagesy($image);

        // Calculate new dimensions maintaining aspect ratio
        $ratio = min($this->maxWidth / $width, $this->maxHeight / $height);
        if ($ratio < 1) {
            $newWidth = (int)($width * $ratio);
            $newHeight = (int)($height * $ratio);

            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        // Save as JPEG with quality
        imagejpeg($image, $destination, $quality);
        imagedestroy($image);
    }

    public function deleteImages($fileName) {
        if (!$fileName) return;

        $baseName = pathinfo($fileName, PATHINFO_FILENAME);
        $files = glob($this->uploadDir . $baseName . "*");

        foreach ($files as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }

    public function getImagePath($fileName, $type = 'optimized') {
        if (!$fileName) return null;

        $baseName = pathinfo($fileName, PATHINFO_FILENAME);
        
        if ($type === 'thumbnail') {
            $path = $this->uploadDir . $baseName . "_thumb.jpg";
        } else {
            $path = $this->uploadDir . $baseName . "_optimized.jpg";
        }

        return file_exists($path) ? $path : null;
    }
}
