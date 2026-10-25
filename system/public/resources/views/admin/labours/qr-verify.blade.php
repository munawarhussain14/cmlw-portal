@extends('admin.layouts.app')

@push('scripts')
    <script src="{{ asset('assets/admin/plugins/qrcode/qrcode.min.js') }}"></script>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Labour Card Verification</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="verification-info">
                                <h4>Card Information</h4>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="label">Name:</span>
                                        <span class="value" id="labour-name">{{ $labour->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">Father Name:</span>
                                        <span class="value" id="labour-father">{{ $labour->father_name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">CNIC:</span>
                                        <span class="value" id="labour-cnic">{{ $labour->cnic ?? 'N/A' }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">Card No:</span>
                                        <span class="value" id="labour-card-no">{{ $labour->l_id ?? 'N/A' }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">District:</span>
                                        <span class="value" id="labour-district">{{ $labour->district->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">Work Type:</span>
                                        <span class="value" id="labour-work">{{ $labour->work->name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="verification-status">
                                <div class="status-indicator">
                                    <div class="status-icon valid">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <h4>Card Verified</h4>
                                    <p>This is a valid labour card issued by the Government of Pakistan</p>
                                </div>
                                
                                <div class="verification-details">
                                    <h5>Verification Details</h5>
                                    <div class="detail-item">
                                        <span class="label">Verified At:</span>
                                        <span class="value" id="verified-at">{{ now()->format('d/m/Y H:i:s') }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="label">Card Status:</span>
                                        <span class="value status-valid">Active</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="label">Issuing Authority:</span>
                                        <span class="value">Government of Pakistan</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card-actions">
                                <a href="{{ route('admin.labours.show', $labour->l_id) }}" class="btn btn-primary">
                                    <i class="fas fa-eye"></i> View Full Details
                                </a>
                                <button class="btn btn-success" onclick="printCard()">
                                    <i class="fas fa-print"></i> Print Card
                                </button>
                                <button class="btn btn-info" onclick="downloadCard()">
                                    <i class="fas fa-download"></i> Download Card
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.verification-info {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.info-item {
    display: flex;
    flex-direction: column;
    margin-bottom: 10px;
}

.info-item .label {
    font-weight: bold;
    color: #495057;
    font-size: 14px;
    margin-bottom: 5px;
}

.info-item .value {
    color: #212529;
    font-size: 16px;
}

.verification-status {
    text-align: center;
    padding: 20px;
}

.status-indicator {
    margin-bottom: 30px;
}

.status-icon {
    font-size: 60px;
    margin-bottom: 15px;
}

.status-icon.valid {
    color: #28a745;
}

.status-icon.invalid {
    color: #dc3545;
}

.status-indicator h4 {
    color: #28a745;
    margin-bottom: 10px;
}

.status-indicator p {
    color: #6c757d;
    font-size: 14px;
}

.verification-details {
    background: #e9ecef;
    padding: 20px;
    border-radius: 10px;
    text-align: left;
}

.verification-details h5 {
    margin-bottom: 15px;
    color: #495057;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    padding: 5px 0;
    border-bottom: 1px solid #dee2e6;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-item .label {
    font-weight: bold;
    color: #495057;
}

.detail-item .value {
    color: #212529;
}

.status-valid {
    color: #28a745;
    font-weight: bold;
}

.card-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
    flex-wrap: wrap;
}

.card-actions .btn {
    margin: 5px;
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .card-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .card-actions .btn {
        width: 100%;
        max-width: 200px;
    }
}
</style>

<script>
function printCard() {
    window.print();
}

function downloadCard() {
    // Create a downloadable version of the card
    const cardData = {
        name: document.getElementById('labour-name').textContent,
        cnic: document.getElementById('labour-cnic').textContent,
        cardNo: document.getElementById('labour-card-no').textContent,
        verifiedAt: document.getElementById('verified-at').textContent
    };
    
    const dataStr = JSON.stringify(cardData, null, 2);
    const dataBlob = new Blob([dataStr], {type: 'application/json'});
    const url = URL.createObjectURL(dataBlob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `labour-card-${cardData.cardNo}-verification.json`;
    link.click();
    URL.revokeObjectURL(url);
}

// Auto-refresh verification status every 30 seconds
setInterval(function() {
    // Update verification timestamp
    document.getElementById('verified-at').textContent = new Date().toLocaleString();
}, 30000);
</script>
@endsection
