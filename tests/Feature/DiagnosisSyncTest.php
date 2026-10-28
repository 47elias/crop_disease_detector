<?php

use App\Models\Crop;
use App\Models\Diagnosis;
use App\Models\DiagnosisImage;
use App\Models\Disease;
use App\Models\User;
use Database\Seeders\CropDiseaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Storage::fake('public');
    config(['filesystems.default' => 'public']);
    $this->withoutMiddleware();
    $this->seed(CropDiseaseSeeder::class);
    $this->diseaseId = Disease::query()->where('slug', 'maize-streak-virus')->value('id');
});

function syncPayload(string $localUuid, UploadedFile $image): array
{
    return [
        'device_uuid' => '550e8400-e29b-41d4-a716-446655440000',
        'records' => json_encode([[
            'local_uuid' => $localUuid,
            'disease_id' => Disease::query()->where('slug', 'maize-streak-virus')->value('id'),
            'confidence' => 0.91,
            'model_version' => 'mobilenetv3-int8-1.0.0',
            'inference_ms' => 1200,
        ]], JSON_THROW_ON_ERROR),
        'images' => [$image],
    ];
}

test('it stores a camera image and creates its thumbnail', function (): void {
    $response = $this->post('/api/v1/sync/diagnoses', syncPayload('550e8400-e29b-41d4-a716-446655440001', UploadedFile::fake()->image('leaf.jpg', 300, 300)));

    $response->assertOk()->assertJsonPath('data.0.status', 'synced');
    expect(DiagnosisImage::query()->count())->toBe(1);
    $image = DiagnosisImage::query()->firstOrFail();
    Storage::disk('public')->assertExists($image->file_path);
    Storage::disk('public')->assertExists($image->thumbnail_path);
    expect($image->public_url)->not->toBeNull();
});

test('syncing the same local uuid twice is idempotent', function (): void {
    $uuid = '550e8400-e29b-41d4-a716-446655440002';
    $this->post('/api/v1/sync/diagnoses', syncPayload($uuid, UploadedFile::fake()->image('leaf.jpg', 300, 300)))->assertOk();
    $this->post('/api/v1/sync/diagnoses', syncPayload($uuid, UploadedFile::fake()->image('leaf.jpg', 300, 300)))
        ->assertOk()->assertJsonPath('data.0.status', 'already_synced');

    expect(Diagnosis::query()->count())->toBe(1)
        ->and(DiagnosisImage::query()->count())->toBe(1);
});

test('it rejects an exact duplicate image with a different local uuid', function (): void {
    $image = UploadedFile::fake()->image('leaf.jpg', 300, 300);
    $this->post('/api/v1/sync/diagnoses', syncPayload('550e8400-e29b-41d4-a716-446655440003', $image))->assertOk();
    $this->post('/api/v1/sync/diagnoses', syncPayload('550e8400-e29b-41d4-a716-446655440004', $image))
        ->assertOk()->assertJsonPath('data.0.status', 'rejected');
    expect(DiagnosisImage::query()->count())->toBe(1);
});

test('it returns validation errors for an undersized image', function (): void {
    $this->post('/api/v1/sync/diagnoses', syncPayload('550e8400-e29b-41d4-a716-446655440005', UploadedFile::fake()->image('small.jpg', 100, 100)))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['diagnoses.0.image']);
});

test('crop disease and farmer relationships are connected in both directions', function (): void {
    $crop = Crop::query()->where('name', 'maize')->firstOrFail();
    $disease = Disease::query()->where('slug', 'maize-streak-virus')->firstOrFail();
    $farmer = User::factory()->create(['ward' => 'Bindura Ward 1']);
    $image = DiagnosisImage::factory()->create(['crop_id' => $crop->id, 'user_id' => $farmer->id]);
    $diagnosis = Diagnosis::factory()->create(['diagnosis_image_id' => $image->id, 'disease_id' => $disease->id]);

    expect($crop->diseases->contains($disease))->toBeTrue()
        ->and($disease->crop->is($crop))->toBeTrue()
        ->and($image->diagnosis->is($diagnosis))->toBeTrue()
        ->and($farmer->diagnoses->contains($diagnosis))->toBeTrue();
});
