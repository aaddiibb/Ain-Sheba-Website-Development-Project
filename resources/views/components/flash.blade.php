@foreach(['success' => 'success', 'error' => 'danger', 'warning' => 'warning'] as $key => $type)
    @if(session($key))
        <div class="alert alert-{{ $type }} alert-dismissible fade show ain-flash mb-0" role="alert">
            <i class="bi bi-{{ $type === 'success' ? 'check-circle' : ($type === 'danger' ? 'exclamation-circle' : 'exclamation-triangle') }} me-2"></i>
            {{ session($key) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endforeach
