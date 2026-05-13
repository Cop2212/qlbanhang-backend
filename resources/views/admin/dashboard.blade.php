@extends('admin.layouts.app')

@section('content')

<style>
    .dash-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }
    .dash-header h3 {
        font-weight: 800;
        color: #1f2937;
        margin: 0;
    }
    .dash-header .date {
        color: #6b7280;
        font-size: 14px;
    }
    .metric-card {
        background: white;
        border: none;
        border-radius: 20px;
        padding: 25px;
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1) !important;
    }
    .metric-card .icon-box {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 20px;
    }
    .metric-card.p-card .icon-box { background: #eef2ff; color: #4338ca; }
    .metric-card.c-card .icon-box { background: #ecfdf5; color: #059669; }
    .metric-card.t-card .icon-box { background: #fff7ed; color: #c2410c; }
    
    .metric-card h6 {
        color: #6b7280;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 11px;
        margin-bottom: 10px;
    }
    .metric-card .value {
        font-size: 28px;
        font-weight: 800;
        color: #111827;
        margin: 0;
    }
    .recent-box {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
    }
    .recent-box h5 {
        font-weight: 800;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .status-badge {
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
    }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-contacted { background: #dcfce7; color: #166534; }
    .status-failed { background: #fee2e2; color: #991b1b; }
</style>

<div class="dash-header">
    <div>
        <h3>Tổng quan hệ thống</h3>
        <p class="text-muted">Chào mừng trở lại, Admin!</p>
    </div>
    <div class="date">
        📅 {{ date('d/m/Y') }}
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="metric-card p-card shadow-sm">
            <div class="icon-box">📦</div>
            <h6>Tổng sản phẩm</h6>
            <p class="value">{{ number_format($totalProducts ?? 0) }}</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="metric-card c-card shadow-sm">
            <div class="icon-box">💬</div>
            <h6>Yêu cầu tư vấn</h6>
            <p class="value">{{ number_format($totalConsultations ?? 0) }}</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="metric-card t-card shadow-sm">
            <div class="icon-box">👥</div>
            <h6>Tổng đối tác</h6>
            <p class="value">{{ number_format($totalTraders ?? 0) }}</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="recent-box">
            <h5><span>📞</span> Yêu cầu tư vấn mới nhất</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">Khách hàng</th>
                            <th class="border-0">Liên hệ</th>
                            <th class="border-0">Ngày gửi</th>
                            <th class="border-0">Trạng thái</th>
                            <th class="border-0 text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($recentConsultations)
                            @foreach($recentConsultations as $item)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $item->name }}</div>
                                    <small class="text-muted">{{ Str::limit($item->message, 40) }}</small>
                                </td>
                                <td>
                                    <div class="small">{{ $item->phone }}</div>
                                    <div class="small text-muted">{{ $item->email }}</div>
                                </td>
                                <td>
                                    <div class="small">{{ $item->created_at->format('H:i d/m/Y') }}</div>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $item->status }}">
                                        @if($item->status == 'pending') Chờ xử lý
                                        @elseif($item->status == 'contacted') Đã liên hệ
                                        @else {{ $item->status }}
                                        @endif
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.consultations.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                        Chi tiết
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @if($recentConsultations->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Chưa có yêu cầu tư vấn nào.</td>
                                </tr>
                            @endif
                        @else
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Dữ liệu đang được cập nhật...</td>
                            </tr>
                        @endisset
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection