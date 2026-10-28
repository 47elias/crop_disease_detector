@extends('layouts.app')

@section('title', 'Overview')
@section('breadcrumb', 'Overview')

@section('content')
    <section class="page-heading">
        <div>
            <p class="eyebrow">Bindura · Crop health overview</p>
            <h1>Good day, field team.</h1>
            <p class="page-description">A clear view of synced crop observations and the disease classes supported by the model.</p>
        </div>
        <div class="date-chip"><span>◷</span> Local time · {{ now()->timezone(config('app.timezone'))->format('g:i A') }}</div>
    </section>

    <section class="hero" aria-labelledby="hero-title">
        <div class="hero-copy">
            <div class="hero-kicker">Plant health, closer to the field</div>
            <h2 id="hero-title">A healthier harvest starts with an early look.</h2>
            <p>Cropwise helps the project team review disease observations shared by farmers across Bindura. On-device diagnosis continues to work without internet.</p>
            <a class="hero-button" href="#diseases">Explore disease library <span aria-hidden="true">↗</span></a>
        </div>
        <div class="hero-art" aria-hidden="true">
            <span class="sun"></span><span class="leaf one"></span><span class="leaf two"></span><span class="leaf three"></span><span class="stem"></span>
        </div>
    </section>

    <section class="stats" aria-label="Project summary">
        <article class="stat-card">
            <div class="stat-top"><span>Synced diagnoses</span><span class="stat-symbol">⌕</span></div>
            <div class="stat-value">{{ number_format($diagnosisCount) }}</div>
            <div class="stat-note">Records received from farmer devices</div>
        </article>
        <article class="stat-card">
            <div class="stat-top"><span>Awaiting review</span><span class="stat-symbol">◷</span></div>
            <div class="stat-value">{{ number_format($pendingReviewCount) }}</div>
            <div class="stat-note">Predictions to verify with an expert</div>
        </article>
        <article class="stat-card">
            <div class="stat-top"><span>Connected devices</span><span class="stat-symbol">▣</span></div>
            <div class="stat-value">{{ number_format($deviceCount) }}</div>
            <div class="stat-note">Phones registered for synchronisation</div>
        </article>
        <article class="stat-card">
            <div class="stat-top"><span>Active model</span><span class="stat-symbol">✳</span></div>
            <div class="stat-value" style="font-size:21px">{{ $activeModel?->version ?? '—' }}</div>
            <div class="stat-note">{{ $activeModel ? number_format((float) $activeModel->size_mb, 1).' MB · '.strtoupper($activeModel->quantisation) : 'No active model published' }}</div>
        </article>
    </section>

    <div class="section-grid">
        <section class="panel" id="diseases" aria-labelledby="disease-heading">
            <div class="panel-header">
                <div>
                    <h2 class="panel-title" id="disease-heading">Disease library</h2>
                    <p class="panel-subtitle">The six classes recognised by the phone model</p>
                </div>
                <a class="text-link" href="#diseases">{{ $diseases->count() }} classes <span aria-hidden="true">→</span></a>
            </div>

            @if ($diseases->isNotEmpty())
                <div class="disease-list">
                    @foreach ($diseases as $disease)
                        <article class="disease-card {{ $disease->is_healthy_class ? 'healthy' : '' }}">
                            <span class="crop-tag">{{ $disease->crop?->name ?? 'Maize · Tomato' }} <span aria-hidden="true">·</span> class {{ $disease->model_class_index }}</span>
                            <span class="severity {{ strtolower($disease->severity_level) }}">{{ $disease->severity_level }}</span>
                            <h3>{{ $disease->name }}</h3>
                            <p>{{ $disease->symptoms ?: $disease->description }}</p>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div><span class="empty-icon">✳</span><strong>Disease classes are not seeded yet</strong><p>Run the project database seeder to load the supported maize and tomato classes.</p></div>
                </div>
            @endif

            <div class="crop-strip" aria-label="Supported crops">
                @forelse ($crops as $crop)
                    <span class="crop-pill">{{ ucfirst($crop->name) }} <strong>{{ $crop->diseases_count }} classes</strong></span>
                @empty
                    <span class="crop-pill">Crop catalogue is empty</span>
                @endforelse
                @if ($healthyClass)
                    <span class="crop-pill">Healthy <strong>shared class</strong></span>
                @endif
            </div>
        </section>

        <section class="panel" id="activity" aria-labelledby="activity-heading">
            <div class="panel-header">
                <div>
                    <h2 class="panel-title" id="activity-heading">Recent diagnoses</h2>
                    <p class="panel-subtitle">Latest observations synced from the field</p>
                </div>
                <span class="text-link">Latest {{ $recentDiagnoses->count() }}</span>
            </div>

            @if ($recentDiagnoses->isNotEmpty())
                <div class="activity-list">
                    @foreach ($recentDiagnoses as $diagnosis)
                        @php($status = $diagnosis->status->value)
                        <article class="activity-item">
                            <div class="activity-thumb">
                                @if ($diagnosis->diagnosisImage?->thumbnail_url)
                                    <img src="{{ $diagnosis->diagnosisImage->thumbnail_url }}" alt="Leaf submitted for {{ $diagnosis->disease->name }} review">
                                @else
                                    <span aria-hidden="true">❋</span>
                                @endif
                            </div>
                            <div>
                                <h3 class="activity-name">{{ $diagnosis->disease->name }}</h3>
                                <div class="activity-meta">{{ $diagnosis->diagnosisImage?->crop?->name ?? $diagnosis->disease->crop?->name ?? 'Crop not recorded' }} · {{ $diagnosis->diagnosisImage?->captured_at?->diffForHumans() ?? $diagnosis->created_at->diffForHumans() }}</div>
                            </div>
                            <span class="activity-status {{ $status }}">{{ str_replace('_', ' ', $status) }}</span>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div><span class="empty-icon">⌕</span><strong>No synced diagnoses yet</strong><p>New observations will appear here after a farmer’s phone reconnects.</p></div>
                </div>
            @endif
        </section>
    </div>

    <div id="field-trials" style="height:1px"></div>
@endsection
