<?php

namespace App\Services;

use App\Models\DiagnosisImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class DiagnosisImageService
{
    /** @param array<string, mixed> $metadata */
    public function store(UploadedFile $image, array $metadata): DiagnosisImage
    {
        if ($image->getSize() > 5 * 1024 * 1024) {
            throw ValidationException::withMessages(['image' => 'Images may not exceed 5 MB.']);
        }

        $source = @imagecreatefromstring((string) file_get_contents($image->getRealPath()));
        if ($source === false) {
            throw ValidationException::withMessages(['image' => 'The image could not be decoded.']);
        }

        $width = imagesx($source);
        $height = imagesy($source);
        if ($width < 224 || $height < 224) {
            imagedestroy($source);
            throw ValidationException::withMessages(['image' => 'Images must be at least 224 by 224 pixels.']);
        }

        $hash = hash_file('sha256', $image->getRealPath());
        if (DiagnosisImage::withTrashed()->where('sha256', $hash)->exists()) {
            imagedestroy($source);
            throw ValidationException::withMessages(['image' => 'This exact image has already been uploaded.']);
        }

        $extension = match ($image->getMimeType()) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => null,
        };
        if ($extension === null) {
            imagedestroy($source);
            throw ValidationException::withMessages(['image' => 'Only JPEG, PNG, and WebP images are supported.']);
        }

        $disk = Storage::disk(config('filesystems.default'));
        $folder = now()->format('Y/m');
        $filename = Str::uuid()->toString();
        $path = "diagnosis-images/{$folder}/{$filename}.{$extension}";
        $thumbnailPath = "diagnosis-images/{$folder}/thumbnails/{$filename}.jpg";
        $cleanImage = $this->encode($source, $extension);
        $thumbnail = $this->thumbnail($source, 512);
        imagedestroy($source);

        try {
            $disk->put($path, $cleanImage);
            $disk->put($thumbnailPath, $thumbnail);
        } catch (RuntimeException $exception) {
            $disk->delete([$path, $thumbnailPath]);
            throw $exception;
        }

        return DiagnosisImage::query()->create([
            'device_id' => $metadata['device_id'] ?? null,
            'user_id' => $metadata['user_id'] ?? null,
            'local_uuid' => $metadata['local_uuid'],
            'file_path' => $path,
            'thumbnail_path' => $thumbnailPath,
            'original_filename' => $image->getClientOriginalName(),
            'mime_type' => $image->getMimeType(),
            'size_bytes' => strlen($cleanImage),
            'width' => $width,
            'height' => $height,
            'sha256' => $hash,
            'captured_at' => $metadata['captured_at'] ?? now(),
            'latitude' => $metadata['latitude'] ?? null,
            'longitude' => $metadata['longitude'] ?? null,
            'crop_id' => $metadata['crop_id'] ?? null,
            'capture_source' => $metadata['capture_source'] ?? 'camera',
        ]);
    }

    private function encode(\GdImage $image, string $extension): string
    {
        ob_start();
        match ($extension) {
            'jpg' => imagejpeg($image, null, 88),
            'png' => imagepng($image, null, 6),
            'webp' => imagewebp($image, null, 88),
        };

        return (string) ob_get_clean();
    }

    private function thumbnail(\GdImage $source, int $maximumSize): string
    {
        $scale = min(1, $maximumSize / max(imagesx($source), imagesy($source)));
        $width = max(1, (int) round(imagesx($source) * $scale));
        $height = max(1, (int) round(imagesy($source) * $scale));
        $thumbnail = imagecreatetruecolor($width, $height);
        imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, $width, $height, imagesx($source), imagesy($source));
        ob_start();
        imagejpeg($thumbnail, null, 82);
        $bytes = (string) ob_get_clean();
        imagedestroy($thumbnail);

        return $bytes;
    }
}
