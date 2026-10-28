<div class="card">
    <div class="card-header"><h2 class="card-title fw-semibold">{{ $title }}</h2><span class="float-end text-secondary small">{{ number_format($records->total()) }} records</span></div>
    <div class="card-body p-0">
        <div class="table-responsive"><table class="table table-hover table-striped mb-0">
            <thead><tr>@foreach ($columns as $label)<th scope="col">{{ $label }}</th>@endforeach</tr></thead>
            <tbody>
                @forelse ($records as $record)
                    <tr>@foreach ($columns as $field => $label) @php($value = data_get($record, $field))<td>@if ($value instanceof \BackedEnum){{ str($value->value)->headline() }}@elseif ($value instanceof \Illuminate\Support\Carbon || $value instanceof \DateTimeInterface){{ $value->format('d M Y H:i') }}@elseif (is_bool($value)){{ $value ? 'Yes' : 'No' }}@elseif (is_string($value) && $value !== ''){{ \Illuminate\Support\Str::limit($value, 100) }}@elseif ($value !== null && $value !== ''){{ $value }}@else<span class="text-secondary">—</span>@endif</td>@endforeach</tr>
                @empty
                    <tr><td colspan="{{ count($columns) }}"><div class="empty-state">No records are available for this module.</div></td></tr>
                @endforelse
            </tbody>
        </table></div>
    </div>
    @if ($records->hasPages())<div class="card-footer">{{ $records->links('pagination::bootstrap-5') }}</div>@endif
</div>
