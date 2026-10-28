<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Diagnosis;
use App\Models\Disease;
use App\Models\ExpertReview;
use App\Models\FieldFeedback;
use App\Models\FieldTrial;
use App\Models\MlModelVersion;
use App\Models\SmsDispatch;
use App\Models\TreatmentGuideline;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class ModuleController extends Controller
{
    public function index(string $module): View
    {
        $modules = [
            'diseases' => ['title' => 'Maize disease library', 'description' => 'Disease classes supported for maize leaf diagnosis.', 'view' => 'modules.diseases', 'columns' => ['name' => 'Disease', 'model_class_index' => 'Model class', 'severity_level' => 'Severity', 'symptoms' => 'Symptoms'], 'query' => Disease::query()->with('crop')->where(fn (Builder $query) => $query->whereHas('crop', fn (Builder $crop) => $crop->where('name', 'maize'))->orWhere('is_healthy_class', true))->orderBy('model_class_index')],
            'diagnoses' => ['title' => 'Maize diagnoses', 'description' => 'Synced diagnosis records and review status.', 'view' => 'modules.diagnoses', 'columns' => ['disease.name' => 'Prediction', 'confidence' => 'Confidence', 'status' => 'Review status', 'diagnosisImage.user.name' => 'Farmer', 'created_at' => 'Received'], 'query' => Diagnosis::query()->with(['disease.crop', 'diagnosisImage.user'])->whereHas('disease', fn (Builder $query) => $query->where(fn (Builder $disease) => $disease->whereHas('crop', fn (Builder $crop) => $crop->where('name', 'maize'))->orWhere('is_healthy_class', true)))->latest()],
            'farmers' => ['title' => 'Farmers', 'description' => 'Farmer accounts registered for the maize programme.', 'view' => 'modules.farmers', 'columns' => ['name' => 'Name', 'phone' => 'Phone', 'ward' => 'Ward', 'district' => 'District', 'preferred_language' => 'Language', 'created_at' => 'Registered'], 'query' => User::query()->where('role', UserRole::Farmer)->latest()],
            'devices' => ['title' => 'Field devices', 'description' => 'Devices that synchronise field observations.', 'view' => 'modules.devices', 'columns' => ['device_uuid' => 'Device ID', 'model' => 'Model', 'android_version' => 'Android', 'app_version' => 'App version', 'last_synced_at' => 'Last sync'], 'query' => Device::query()->latest('last_synced_at')],
            'treatments' => ['title' => 'Maize treatment guidance', 'description' => 'English and Shona guidance for maize disease classes.', 'view' => 'modules.treatments', 'columns' => ['disease.name' => 'Disease class', 'language' => 'Language', 'title' => 'Guidance', 'sms_text' => 'SMS summary'], 'query' => TreatmentGuideline::query()->with('disease.crop')->whereHas('disease', fn (Builder $query) => $query->where(fn (Builder $disease) => $disease->whereHas('crop', fn (Builder $crop) => $crop->where('name', 'maize'))->orWhere('is_healthy_class', true)))->orderBy('disease_id')->orderBy('language')],
            'reviews' => ['title' => 'Expert reviews', 'description' => 'Expert verification of maize diagnosis predictions.', 'view' => 'modules.reviews', 'columns' => ['diagnosis.disease.name' => 'Prediction', 'verifiedDisease.name' => 'Verified as', 'reviewer.name' => 'Reviewer', 'notes' => 'Notes', 'created_at' => 'Reviewed'], 'query' => ExpertReview::query()->with(['diagnosis.disease.crop', 'verifiedDisease', 'reviewer'])->whereHas('diagnosis.disease', fn (Builder $query) => $query->where(fn (Builder $disease) => $disease->whereHas('crop', fn (Builder $crop) => $crop->where('name', 'maize'))->orWhere('is_healthy_class', true)))->latest()],
            'models' => ['title' => 'Model versions', 'description' => 'Model releases and performance metadata.', 'view' => 'modules.models', 'columns' => ['version' => 'Version', 'quantisation' => 'Quantisation', 'size_mb' => 'Size (MB)', 'accuracy' => 'Accuracy', 'is_active' => 'Active', 'released_at' => 'Released'], 'query' => MlModelVersion::query()->latest('released_at')],
            'sms' => ['title' => 'SMS dispatches', 'description' => 'Delivery status of messages linked to maize diagnoses.', 'view' => 'modules.sms', 'columns' => ['recipient_phone' => 'Recipient', 'diagnosis.disease.name' => 'Disease', 'status' => 'Status', 'sent_at' => 'Sent', 'delivered_at' => 'Delivered'], 'query' => SmsDispatch::query()->with('diagnosis.disease.crop')->whereHas('diagnosis.disease', fn (Builder $query) => $query->where(fn (Builder $disease) => $disease->whereHas('crop', fn (Builder $crop) => $crop->where('name', 'maize'))->orWhere('is_healthy_class', true)))->latest()],
            'field-trials' => ['title' => 'Maize field trials', 'description' => 'Field programme sites and trial dates.', 'view' => 'modules.field-trials', 'columns' => ['name' => 'Trial', 'ward' => 'Ward', 'starts_at' => 'Starts', 'ends_at' => 'Ends', 'description' => 'Description'], 'query' => FieldTrial::query()->latest('starts_at')],
            'field-feedback' => ['title' => 'Field feedback', 'description' => 'Usability feedback submitted during maize field trials.', 'view' => 'modules.field-feedback', 'columns' => ['fieldTrial.name' => 'Trial', 'farmer.name' => 'Farmer', 'usability_rating' => 'Usability', 'readability_score' => 'Readability', 'comments' => 'Comments'], 'query' => FieldFeedback::query()->with(['fieldTrial', 'farmer'])->whereHas('fieldTrial')->latest()],
        ];

        abort_unless(isset($modules[$module]), 404);
        $page = $modules[$module];

        return view($page['view'], [
            'title' => $page['title'],
            'description' => $page['description'],
            'columns' => $page['columns'],
            'records' => $page['query']->paginate(20)->withQueryString(),
        ]);
    }
}
