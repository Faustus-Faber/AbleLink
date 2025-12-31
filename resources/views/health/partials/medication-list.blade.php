{{-- F19 - Evan Munshi --}}
<ul class="list-group list-group-flush">
    @forelse($medications as $medication)
        <li class="list-group-item">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="mb-1">{{ $medication->medication_name }} ({{ $medication->dosage }})</h6>
                    <small class="text-muted">
                        {{ ucfirst($medication->frequency) }}
                        @if($medication->scheduled_time)
                            at {{ \Carbon\Carbon::parse($medication->scheduled_time)->format('h:i A') }}
                        @endif
                    </small>
                </div>
                <div>
                    <!-- Log Intake Button -->
                    <form action="{{ route('health.medications.log', $medication) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="status" value="taken">
                        <button type="submit" class="btn btn-sm btn-outline-success" title="Mark as Taken">
                            <i class="fas fa-check"></i> Taken
                        </button>
                    </form>
                </div>
            </div>
            @if($medication->notes)
                <p class="mb-0 mt-2 small text-muted fst-italic">{{ $medication->notes }}</p>
            @endif
        </li>
    @empty
        <li class="list-group-item text-muted">No active medications scheduled.</li>
    @endforelse
</ul>
