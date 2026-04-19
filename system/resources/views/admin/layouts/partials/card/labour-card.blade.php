<div class="labour-card-container print-container">
    
    <!-- Front of the Card -->
    <div class="labour-card front-card">
        <!-- Background Image Overlay -->
        @if(file_exists(public_path('uploads/labours/card/front.png')))
        <div class="card-background">
            <img src="{{ asset('uploads/labours/card/front.png') }}" alt="Card Background" class="background-image">
        </div>
        @endif
        
        <div class="card-body">
            <!-- Empty body to let background show -->
        </div>
        
        <div class="card-footer">
            <div class="labour-name-bottom-right urdu">
                <span class="labour-name-text">{{ $row->urdu_name ?? $row->name ?? 'N/A' }}</span>
            </div>
        </div>
    </div>
    
    <!-- Back of the Card -->
    <div class="labour-card back-card">
        <!-- Background Image Overlay -->
        @if(file_exists(public_path('uploads/labours/card/back.png')))
        <div class="card-background">
            <img src="{{ asset('uploads/labours/card/back.png') }}" alt="Card Background" class="background-image">
        </div>
        @endif
        
        <div class="back-body">
            <div class="back-layout">
                <!-- Right Section: Logo and QR Code -->
                <div class="right-section">
                    <div class="logo-top">
                        @if(file_exists(public_path('assets/images/logo.png')))
                            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo">
                        @endif
                    </div>
                    <div class="qr-section-below-logo">
                        <div id="qrcode-{{ $row->l_id ?? 'unknown' }}" class="qr-code-simple"></div>
                    </div>
                    <span style="color:black; font-size:10px;">Sr# {{str_pad($row->l_id, 5, '0', STR_PAD_LEFT)}}</span>
                </div>
                
                <!-- Left Section: Labour Details -->
                <div class="left-section urdu">
                    <div class="info-row">
                        <span class="label">نام:</span>
                        <span class="value">{{ $row->urdu_name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">والد کا نام:</span>
                        <span class="value">{{ $row->urdu_father_name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">شناختی کارڈ:</span>
                        <span class="value">{{ $row->cnic ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">پتہ:</span>
                        <span class="value">{{ $row->urdu_perm_address ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@font-face {
  font-family: "Jameel Noori Nastaleeq";
  /*src: url('../font/jameel-noori-nastaleeq.ttf');*/
  src: url("http://www.fontsaddict.com/fontface/jameel-noori-nastaleeq.ttf");
}


.urdu {
  font-family: "Jameel Noori Nastaleeq";
  text-align: right;
}

.print-container{
		margin:auto;
	}

.labour-card-container {
    display: flex;
    gap: 20px;
    margin: 20px 0;
    flex-wrap: wrap;
}

.labour-card-container .labour-card {
    /* CR80 card size for printer: 85.72 × 53.97 mm */
    width: 85.72mm;
    height: 53.97mm;
    border-radius: 3mm;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-family: 'Arial', 'Tahoma', 'Nafees Web Naskh', sans-serif;
    transition: transform 0.3s ease;
    display: flex;
    flex-direction: column;
    direction: rtl;
    text-align: right;
}

.labour-card-container .card-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    /* opacity: 0.3; */
}

.labour-card-container .background-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 3mm;
}

.labour-card-container .labour-card .card-header,
.labour-card-container .labour-card .card-body,
.labour-card-container .labour-card .card-footer,
.labour-card-container .labour-card .back-header,
.labour-card-container .labour-card .back-body,
.labour-card-container .labour-card .back-footer {
    position: relative;
    z-index: 2;
}

.labour-card-container .labour-card:hover {
    transform: translateY(-5px);
}

.labour-card-container .front-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.labour-card-container .back-card {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.labour-card-container .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 2px solid rgba(255,255,255,0.2);
    position: relative;
    z-index: 3;
    background: rgba(0, 0, 0, 0.1);
}

.labour-card-container .logo-section img {
    height: 40px;
    width: auto;
}

.labour-card-container .card-title h3 {
    margin: 0;
    font-size: 16px;
    font-weight: bold;
    text-align: center;
}

.labour-card-container .card-title p {
    margin: 2px 0 0 0;
    font-size: 10px;
    text-align: center;
    opacity: 0.8;
}

.labour-card-container .card-body {
    display: flex;
    padding: 15px 20px;
    gap: 15px;
    flex: 1;
    position: relative;
    z-index: 2;
}

.labour-card-container .photo-section {
    flex-shrink: 0;
    position: relative;
    z-index: 3;
    background: rgba(255, 255, 255, 0.1);
    padding: 5px;
    border-radius: 5px;
}

.labour-card-container .profile-photo {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 3px solid rgba(255,255,255,0.3);
    object-fit: cover;
}

.labour-card-container .profile-placeholder {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 3px solid rgba(255,255,255,0.3);
    background: rgba(255,255,255,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.labour-card-container .info-section {
    flex: 1;
    position: relative;
    z-index: 3;
    background: rgba(0, 0, 0, 0.1);
    padding: 5px;
    border-radius: 5px;
}

.labour-card-container .back-body .info-section {
    flex: 1;
    margin-top: 20px;
    margin-right: 80px;
}

.labour-card-container .info-row {
    display: flex;
    margin-bottom: 0px;
    font-size: 16px;
    background: rgba(255, 255, 255, 0.3);
    padding: 1px 5px;
    border-radius: 3px;
    align-items: center;
}

.labour-card-container .label {
    /* font-weight: bold; */
    width: auto;
    flex-shrink: 0;
    color: #000;
    text-shadow: none;
    /* font-size: 14px; */
    /* font-weight: 600; */
    padding-left: 5px;
}

.labour-card-container .value {
    flex: 1;
    margin-left: 2px;
    color: #000;
    text-shadow: none;
    font-weight: 500;
}

.labour-card-container .card-footer {
    display: flex;
    justify-content: flex-start;
    align-items: flex-end;
    padding: 10px;
    position: relative;
    z-index: 3;
    direction: ltr;
}

.labour-card-container .labour-name-bottom-right {
    text-align: right;
    direction: rtl;
    margin-left: auto;
    padding-right: 20px;
    padding-bottom: 18px;
}

.labour-card-container .labour-name-text {
    font-size: 20px;
    /* font-weight: bold; */
    color: #fff;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
    display: inline-block;
}

.labour-card-container .qr-section {
    width: 50px;
    height: 50px;
    position: relative;
    z-index: 4;
}

.labour-card-container .qr-code {
    width: 100%;
    height: 100%;
    background: white;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.3);
}

.labour-card-container .qr-section-large {
    width: 80px;
    height: 80px;
    position: relative;
    z-index: 4;
    margin: 10px auto;
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 1;
}

.labour-card-container .qr-section-top-right {
    width: 60px;
    height: 60px;
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 5;
    display: flex;
    align-items: center;
    justify-content: center;
}

.labour-card-container .qr-code-large {
    width: 100%;
    height: 100%;
    background: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid #fff;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
}

.labour-card-container .qr-section-top-right .qr-code-large {
    border-radius: 6px;
    border: 2px solid #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
}

.labour-card-container .card-number {
    font-size: 10px;
    opacity: 0.9;
    color: #000;
    text-shadow: none;
    font-weight: bold;
}

.labour-card-container .back-header {
    padding: 15px 20px;
    border-bottom: 2px solid rgba(255,255,255,0.2);
    text-align: center;
    position: relative;
    z-index: 3;
    background: rgba(0, 0, 0, 0.1);
}

.labour-card-container .back-header h4 {
    margin: 0;
    font-size: 14px;
}

.labour-card-container .back-body {
    padding: 10px;
    position: relative;
    z-index: 3;
    flex: 1;
    display: flex;
}

.labour-card-container .back-layout {
    display: flex;
    width: 100%;
    height: 100%;
    gap: 10px;
    direction: rtl;
}

.labour-card-container .right-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 5px;
}

.labour-card-container .logo-top {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.labour-card-container .logo-top img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.labour-card-container .qr-section-below-logo {
    display: flex;
    align-items: center;
    justify-content: center;
}

.labour-card-container .left-section {
    flex: 1;
    /* display: flex; */
    flex-direction: column;
    gap: 3px;
    padding: 5px;
    justify-content: center;
}

.labour-card-container .back-content {
    display: flex;
    gap: 15px;
    height: 100%;
    align-items: flex-start;
}

.labour-card-container .back-info-section {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.labour-card-container .qr-section-end {
    /* width: 100%; */
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 3px;
    margin-bottom: 10px;
    padding: 5px;
}

.labour-card-container .qr-section-right {
    width: 70px;
    height: 70px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 5px;
}

.labour-card-container .qr-section-right .qr-code-large {
    width: 60px;
    height: 60px;
    background: white;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
}

.labour-card-container .qr-code-simple {
    width: 50px;
    height: 50px;
    background: white;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #333;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    padding: 2px;
}

.labour-card-container .info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 5px;
    margin-bottom: 10px;
}

.labour-card-container .info-item {
    display: flex;
    flex-direction: column;
    font-size: 10px;
    background: rgba(0, 0, 0, 0.2);
    padding: 5px;
    border-radius: 3px;
    margin-bottom: 3px;
}

.labour-card-container .info-item .label {
    font-weight: bold;
    margin-bottom: 2px;
    color: #000;
    text-shadow: none;
}

.labour-card-container .info-item .value {
    font-size: 9px;
    opacity: 0.9;
    color: #000;
    text-shadow: none;
    font-weight: 500;
}

.labour-card-container .bank-info {
    border-top: 1px solid rgba(255,255,255,0.2);
    padding-top: 10px;
    background: rgba(0, 0, 0, 0.2);
    padding: 10px;
    border-radius: 5px;
    margin-top: 5px;
}

.labour-card-container .bank-info h5 {
    margin: 0 0 5px 0;
    font-size: 11px;
    color: #000;
    text-shadow: none;
    font-weight: bold;
}

/* Print-specific styles to ensure exact physical dimensions */
@media print {
    /* Set page size to CR80 card dimensions */
    @page {
        size: 85.72mm 53.97mm;
        margin: 0;
    }
    
    body {
        margin: 0;
        padding: 0;
    }
    
    .labour-card-container .labour-card {
        width: 85.72mm !important;
        height: 53.97mm !important;
        box-shadow: none;
        page-break-inside: avoid;
        page-break-after: always;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        color-adjust: exact;
        margin: 0;
        border-radius: 3mm;
    }
    
    .print-container {
        margin: 0;
        padding: 0;
    }
    
    /* Ensure cards print side by side if needed */
    .labour-card-container {
        gap: 0;
        margin: 0;
        padding: 0;
    }
    
    /* Hide everything except cards when printing */
    .labour-card-container {
        display: block;
    }
}

</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate QR Code for each labour card
    const qrContainers = document.querySelectorAll('[id^="qrcode-"]');
    
    qrContainers.forEach(container => {
        const labourId = container.id.replace('qrcode-', '');
        
        // Create simple QR code with just the verification URL
        const qrData = '{{ $row->l_id ? "https://app.cmlw.gkp.pk/verification/$row->cnic" : "#" }}';
        
        // Generate QR code
        if (typeof QRCode !== 'undefined') {
            let qrSize = 40;
            if (container.classList.contains('qr-code-simple')) {
                qrSize = 40;
            } else if (container.classList.contains('qr-code-large')) {
                // Check if it's in the right section (back card)
                if (container.closest('.qr-section-right')) {
                    qrSize = 50;
                } else if (container.closest('.qr-section-top-right')) {
                    qrSize = 50;
                } else {
                    qrSize = 70;
                }
            }
            new QRCode(container, {
                text: qrData,
                width: qrSize,
                height: qrSize,
                colorDark: '#000000',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.L
            });
        } else {
            // Fallback if QRCode library is not loaded
            let fallbackSize = '50px';
            if (container.classList.contains('qr-code-simple')) {
                fallbackSize = '50px';
            } else if (container.classList.contains('qr-code-large')) {
                if (container.closest('.qr-section-right')) {
                    fallbackSize = '60px';
                } else if (container.closest('.qr-section-top-right')) {
                    fallbackSize = '60px';
                } else {
                    fallbackSize = '80px';
                }
            }
            container.innerHTML = `<div style="width: ${fallbackSize}; height: ${fallbackSize}; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 8px; color: #666;">QR</div>`;
        }
    });
    
    // Add click handler to QR codes for verification
    qrContainers.forEach(container => {
        container.addEventListener('click', function() {
            const labourId = container.id.replace('qrcode-', '');
            const verificationUrl = '{{ $row->l_id ? "https://app.cmlw.gkp.pk/verification/$row->cnic" : "#" }}';
            if (verificationUrl !== '#') {
                window.open(verificationUrl, '_blank');
            }
        });
        
        // Add hover effect
        container.style.cursor = 'pointer';
        container.title = 'Click to verify card information';
    });
});
</script>