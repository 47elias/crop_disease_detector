<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('phone')->nullable()->unique();
            $table->string('ward')->nullable();
            $table->string('district')->default('Bindura');
            $table->string('role')->default('farmer')->index();
            $table->string('preferred_language', 2)->default('en');
        });

        Schema::create('crops', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('scientific_name')->nullable();
            $table->timestamps();
        });

        Schema::create('diseases', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('crop_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('symptoms')->nullable();
            $table->boolean('is_healthy_class')->default(false);
            $table->unsignedTinyInteger('model_class_index')->unique();
            $table->string('severity_level')->default('moderate');
            $table->timestamps();
            $table->index(['crop_id', 'is_healthy_class']);
        });

        Schema::create('treatment_guidelines', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('disease_id')->constrained()->cascadeOnDelete();
            $table->string('language', 2);
            $table->string('title');
            $table->json('steps');
            $table->text('chemical_options')->nullable();
            $table->text('cultural_practices')->nullable();
            $table->string('sms_text', 160);
            $table->timestamps();
            $table->unique(['disease_id', 'language']);
        });

        Schema::create('devices', function (Blueprint $table): void {
            $table->id();
            $table->uuid('device_uuid')->unique();
            $table->string('model')->nullable();
            $table->string('android_version')->nullable();
            $table->unsignedInteger('ram_mb')->nullable();
            $table->string('app_version')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
        });

        Schema::create('ml_model_versions', function (Blueprint $table): void {
            $table->id();
            $table->string('version')->unique();
            $table->string('file_path')->nullable();
            $table->decimal('size_mb', 7, 2);
            $table->string('quantisation')->default('int8');
            $table->decimal('accuracy', 6, 4)->nullable();
            $table->decimal('precision', 6, 4)->nullable();
            $table->decimal('recall', 6, 4)->nullable();
            $table->decimal('f1_score', 6, 4)->nullable();
            $table->boolean('is_active')->default(false)->index();
            $table->timestamp('released_at')->nullable();
            $table->timestamps();
        });

        Schema::create('diagnosis_images', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('device_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->uuid('local_uuid')->unique();
            $table->string('file_path');
            $table->string('thumbnail_path')->nullable();
            $table->string('original_filename')->nullable();
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('size_bytes');
            $table->unsignedInteger('width');
            $table->unsignedInteger('height');
            $table->char('sha256', 64)->unique();
            $table->timestamp('captured_at')->index();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->foreignId('crop_id')->nullable()->constrained()->nullOnDelete();
            $table->string('capture_source')->default('camera');
            $table->boolean('is_training_candidate')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['device_id', 'captured_at']);
        });

        Schema::create('diagnoses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('diagnosis_image_id')->constrained()->cascadeOnDelete();
            $table->foreignId('disease_id')->constrained()->restrictOnDelete();
            $table->decimal('confidence', 5, 4);
            $table->json('top_k_predictions')->nullable();
            $table->foreignId('model_version_id')->nullable()->constrained('ml_model_versions')->nullOnDelete();
            $table->unsignedInteger('inference_ms')->nullable();
            $table->unsignedInteger('ram_used_mb')->nullable();
            $table->decimal('battery_delta_percent', 5, 2)->nullable();
            $table->string('status')->default('pending_review')->index();
            $table->timestamp('synced_at')->nullable()->index();
            $table->uuid('local_uuid')->unique();
            $table->timestamps();
            $table->index(['disease_id', 'status']);
        });

        Schema::create('expert_reviews', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('diagnosis_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('verified_disease_id')->nullable()->constrained('diseases')->nullOnDelete();
            $table->boolean('is_correct');
            $table->text('notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->index(['reviewer_id', 'reviewed_at']);
        });

        Schema::create('sms_dispatches', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('diagnosis_id')->constrained()->cascadeOnDelete();
            $table->foreignId('device_id')->nullable()->constrained()->nullOnDelete();
            $table->string('recipient_phone');
            $table->string('message_body', 160);
            $table->string('status')->default('queued')->index();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();
        });

        Schema::create('field_trials', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('ward');
            $table->date('starts_at');
            $table->date('ends_at')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index(['ward', 'starts_at']);
        });

        Schema::create('field_feedback', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('field_trial_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('usability_rating');
            $table->unsignedTinyInteger('readability_score')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
            $table->unique(['field_trial_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_feedback');
        Schema::dropIfExists('field_trials');
        Schema::dropIfExists('sms_dispatches');
        Schema::dropIfExists('expert_reviews');
        Schema::dropIfExists('diagnoses');
        Schema::dropIfExists('diagnosis_images');
        Schema::dropIfExists('ml_model_versions');
        Schema::dropIfExists('devices');
        Schema::dropIfExists('treatment_guidelines');
        Schema::dropIfExists('diseases');
        Schema::dropIfExists('crops');

        Schema::table('users', function (Blueprint $table): void {
            $table->dropUnique(['phone']);
            $table->dropIndex(['role']);
            $table->dropColumn(['phone', 'ward', 'district', 'role', 'preferred_language']);
        });
    }
};
