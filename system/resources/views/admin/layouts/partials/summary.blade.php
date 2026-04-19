<div class="row">
    <div class="col-4">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-copy"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Application</span>
                <span class="info-box-number">
                    {{ $total ? $total : 0 }}
                </span>
            </div>

        </div>
    </div>
    <div class="col-4">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-clipboard-check"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Approved</span>
                <span class="info-box-number">{{ $approved ? $approved : 0 }}</span>
            </div>

        </div>
    </div>
    <div class="col-4">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-times"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Rejected</span>
                <span class="info-box-number">{{ $rejected ? $rejected : 0 }}</span>
            </div>
        </div>
    </div>
</div>
@if(isset($card_issued)&&isset($card_printed))
<div class="row">
    <div class="col-6">
        <div class="info-box">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-id-card"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Card Digitized</span>
                <span class="info-box-number">
                    {{ $card_issued ? $card_issued : 0 }}
                </span>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-print"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Card Printed</span>
                <span class="info-box-number">{{ $card_printed ? $card_printed : 0 }}</span>
            </div>

        </div>
    </div>
</div>
@endif