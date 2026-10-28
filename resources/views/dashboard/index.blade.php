@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Maize health overview')
@section('page-description', 'Synced maize observations, field devices and current disease coverage.')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3"><div class="small-box text-bg-success"><div class="inner"><h3>{{ number_format($diagnosisCount) }}</h3><p>Maize diagnoses</p></div><span class="small-box-icon">⌕</span><a href="{{ route('diagnoses.index') }}" class="small-box-footer link-light link-underline-opacity-0">View records <span aria-hidden="true">›</span></a></div></div>
        <div class="col-12 col-sm-6 col-xl-3"><div class="small-box text-bg-warning"><div class="inner"><h3>{{ number_format($pendingReviewCount) }}</h3><p>Awaiting expert review</p></div><span class="small-box-icon">✓</span><a href="{{ route('reviews.index') }}" class="small-box-footer link-dark link-underline-opacity-0">Open reviews <span aria-hidden="true">›</span></a></div></div>
        <div class="col-12 col-sm-6 col-xl-3"><div class="small-box text-bg-info"><div class="inner"><h3>{{ number_format($deviceCount) }}</h3><p>Registered field devices</p></div><span class="small-box-icon">▣</span><a href="{{ route('devices.index') }}" class="small-box-footer link-dark link-underline-opacity-0">View devices <span aria-hidden="true">›</span></a></div></div>
        <div class="col-12 col-sm-6 col-xl-3"><div class="small-box text-bg-light"><div class="inner"><h3 class="fs-4">{{ $activeModel?->version ?? 'Not published' }}</h3><p>Active diagnosis model</p></div><span class="small-box-icon">◇</span><a href="{{ route('models.index') }}" class="small-box-footer link-secondary link-underline-opacity-0">Model releases <span aria-hidden="true">›</span></a></div></div>
    </div>
    <div class="row g-3">
        <div class="col-12 col-xl-7"><div class="card h-100"><div class="card-header"><h2 class="card-title fw-semibold">Maize disease classes</h2><a class="float-end link-success" href="{{ route('diseases.index') }}">Disease library</a></div><div class="card-body"><div class="row g-3">
            @forelse ($diseases as $disease)
                <div class="col-12 col-md-6"><article class="border rounded p-3 h-100"><div class="d-flex justify-content-between gap-2"><h3 class="fs-6 fw-semibold mb-2">{{ $disease->name }}</h3><span class="badge {{ $disease->is_healthy_class ? 'text-bg-success' : ($disease->severity_level === 'high' ? 'text-bg-danger' : 'text-bg-warning') }} align-self-start">{{ $disease->severity_level }}</span></div><p class="text-secondary small mb-0">{{ $disease->symptoms ?: $disease->description }}</p><span class="text-secondary small">Model class {{ $disease->model_class_index }}</span></article></div>
            @empty
                <div class="col-12"><div class="empty-state">No maize disease classes are configured yet.</div></div>
            @endforelse
        </div></div></div></div>
        <div class="col-12 col-xl-5"><div class="card h-100"><div class="card-header"><h2 class="card-title fw-semibold">Recent maize diagnoses</h2></div><div class="card-body p-0"><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Disease</th><th>Confidence</th><th>Status</th><th>Received</th></tr></thead><tbody>
            @forelse ($recentDiagnoses as $diagnosis)
                <tr><td>{{ $diagnosis->disease?->name ?? 'Unknown' }}</td><td>{{ number_format((float) $diagnosis->confidence * 100, 1) }}%</td><td><span class="badge text-bg-secondary">{{ str($diagnosis->status->value)->headline() }}</span></td><td>{{ $diagnosis->created_at?->format('d M Y') }}</td></tr>
            @empty
                <tr><td colspan="4"><div class="empty-state">No synced maize diagnoses yet.</div></td></tr>
            @endforelse
        </tbody></table></div></div><div class="card-footer"><a href="{{ route('diagnoses.index') }}">Browse all maize diagnoses</a></div></div></div>
    </div>
    <div class="row g-3 mt-1"><div class="col-12 col-md-4"><a href="{{ route('treatments.index') }}" class="card card-body text-decoration-none text-body h-100"><strong>Treatment guidance</strong><span class="text-secondary small mt-1">Open maize recommendations in English and Shona.</span></a></div><div class="col-12 col-md-4"><a href="{{ route('field-trials.index') }}" class="card card-body text-decoration-none text-body h-100"><strong>Field trials</strong><span class="text-secondary small mt-1">Review maize trial sites and schedules.</span></a></div><div class="col-12 col-md-4"><a href="{{ route('field-feedback.index') }}" class="card card-body text-decoration-none text-body h-100"><strong>Field feedback</strong><span class="text-secondary small mt-1">Read feedback from field programme participants.</span></a></div></div>
@endsection
