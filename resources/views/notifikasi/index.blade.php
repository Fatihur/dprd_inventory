@extends('layouts.app')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-bell me-2"></i>Semua Notifikasi</span>
        @if($notifikasi->where('dibaca_pada', null)->count() > 0)
        <form action="{{ route('notifikasi.baca-semua') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-check-all me-1"></i>Tandai Semua Dibaca
            </button>
        </form>
        @endif
    </div>
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @forelse($notifikasi as $n)
            <div class="list-group-item {{ $n->dibaca_pada ? '' : 'bg-light' }}">
                <div class="d-flex align-items-start">
                    <div class="me-3 mt-1">
                        <i class="bi {{ $n->icon }}" style="font-size: 1.25rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1 {{ $n->dibaca_pada ? '' : 'fw-bold' }}">{{ $n->judul }}</h6>
                                <p class="mb-1 text-muted">{{ $n->pesan }}</p>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>{{ $n->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <div>
                                @if(!$n->dibaca_pada)
                                <form action="{{ route('notifikasi.baca', $n) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Tandai dibaca">
                                        <i class="bi bi-check"></i>
                                    </button>
                                </form>
                                @endif
                                @if($n->link)
                                <a href="{{ route('notifikasi.baca', $n) }}" onclick="event.preventDefault(); document.getElementById('form-link-{{ $n->id }}').submit();" class="btn btn-sm btn-outline-primary" title="Lihat detail">
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                                <form id="form-link-{{ $n->id }}" action="{{ route('notifikasi.baca', $n) }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="list-group-item text-center py-5 text-muted">
                <i class="bi bi-bell-slash" style="font-size: 3rem;"></i>
                <p class="mb-0 mt-3">Belum ada notifikasi</p>
            </div>
            @endforelse
        </div>
    </div>
    @if($notifikasi->hasPages())
    <div class="card-footer">
        {{ $notifikasi->links() }}
    </div>
    @endif
</div>
@endsection
