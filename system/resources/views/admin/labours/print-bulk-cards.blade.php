<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Labour Cards - {{ $district->name ?? 'District' }}</title>
    
    <!-- QR Code Library -->
    <script src="{{ asset('assets/admin/plugins/qrcode/qrcode.min.js') }}"></script>
    
    <style>
        @font-face {
            font-family: "Jameel Noori Nastaleeq";
            src: url("http://www.fontsaddict.com/fontface/jameel-noori-nastaleeq.ttf");
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
        }

        .urdu {
            font-family: "Jameel Noori Nastaleeq";
            text-align: right;
        }

        /* Print Instructions */
        .print-instructions {
            background: #fff;
            padding: 20px;
            margin: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .print-instructions h2 {
            color: #333;
            margin-bottom: 15px;
        }

        .print-instructions ol {
            margin-left: 20px;
        }

        .print-instructions li {
            margin: 8px 0;
            color: #666;
        }

        .print-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
        }

        .print-btn:hover {
            background: #218838;
        }

        /* Card Container - One card per page */
        .card-page {
            width: 85.72mm;
            height: 53.97mm;
            margin: 0 auto 20px;
            background: white;
            page-break-after: always;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-page:last-child {
            page-break-after: auto;
        }

        /* Individual Card - CR80 card size for printer: 85.72 × 53.97 mm */
        .labour-card {
            width: 85.72mm;
            height: 53.97mm;
            border-radius: 3mm;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            flex-direction: column;
            direction: rtl;
            text-align: right;
        }

        .labour-card.back-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .card-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .background-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 3mm;
        }

        .card-body,
        .card-footer,
        .back-body {
            position: relative;
            z-index: 2;
        }

        .card-body {
            flex: 1;
        }

        .card-footer {
            display: flex;
            justify-content: flex-start;
            align-items: flex-end;
            padding: 10px;
            direction: ltr;
        }

        .labour-name-bottom-right {
            text-align: right;
            direction: rtl;
            margin-left: auto;
            padding-right: 20px;
            padding-bottom: 18px;
        }

        .labour-name-text {
            font-size: 20px;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
            display: inline-block;
        }

        /* Back Card Layout */
        .back-body {
            padding: 10px;
            flex: 1;
            display: flex;
        }

        .back-layout {
            display: flex;
            width: 100%;
            height: 100%;
            gap: 10px;
            direction: rtl;
        }

        .right-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 5px;
        }

        .logo-top {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-top img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .qr-section-below-logo {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-code-simple {
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

        .left-section {
            flex: 1;
            flex-direction: column;
            gap: 3px;
            padding: 5px;
            justify-content: center;
        }

        .info-row {
            display: flex;
            margin-bottom: 0px;
            font-size: 12px;
            background: rgba(255, 255, 255, 0.3);
            padding: 1px 5px;
            border-radius: 3px;
            align-items: center;
        }

        .label {
            width: auto;
            flex-shrink: 0;
            color: #000;
            text-shadow: none;
            padding-left: 5px;
        }

        .value {
            flex: 1;
            margin-left: 2px;
            color: #000;
            text-shadow: none;
            font-weight: 500;
        }

        /* Section separator */
        .section-separator {
            width: 210mm;
            height: 30mm;
            background: #333;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            margin: 0 auto;
            page-break-before: always;
            page-break-after: always;
        }

        /* Print Styles */
        @media print {
            @page {
                size: 85.72mm 53.97mm;
                margin: 0;
            }
            
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            
            body {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .print-instructions {
                display: none !important;
            }

            .section-separator {
                display: none !important;
            }

            .card-page {
                width: 85.72mm !important;
                height: 53.97mm !important;
                margin: 0 !important;
                padding: 0 !important;
                page-break-after: always !important;
                page-break-inside: avoid !important;
                box-shadow: none !important;
            }

            .card-page:last-child {
                page-break-after: auto !important;
            }

            .labour-card {
                width: 85.72mm !important;
                height: 53.97mm !important;
                page-break-inside: avoid !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
                margin: 0 !important;
                padding: 0 !important;
                display: flex !important;
                flex-direction: column !important;
                position: relative !important;
                overflow: hidden !important;
            }
            
            /* Ensure backgrounds are visible */
            .card-background,
            .background-image {
                display: block !important;
                opacity: 1 !important;
                visibility: visible !important;
            }
            
            /* Ensure all content is visible */
            .card-body,
            .card-footer,
            .back-body,
            .labour-name-text,
            .info-row,
            .label,
            .value,
            .back-layout,
            .left-section,
            .right-section,
            .logo-top,
            .qr-section-below-logo,
            .qr-code-simple {
                visibility: visible !important;
                opacity: 1 !important;
            }
            
            /* Ensure gradients print */
            .front-card {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            }
            
            .back-card {
                background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
            }
        }
    </style>
</head>
<body>
    <!-- Print Instructions (only visible on screen) -->
    <div class="print-instructions">
        <h2>Labour Cards Print Instructions - {{ $district->name ?? 'District' }}</h2>
        <p><strong>Total Cards:</strong> {{ $labours->count() }}</p>
        <p><strong>Total Pages:</strong> {{ $labours->count() * 2 }} ({{ $labours->count() }} front + {{ $labours->count() }} back)</p>
        <ol>
            <li>Click the "Print Now" button below</li>
            <li>In the print dialog, select your CR80 card printer</li>
            <li>Ensure paper size is set to <strong>Card 54.0mm x 85.6mm (85.72mm x 53.97mm)</strong></li>
            <li>Set to print <strong>ALL PAGES</strong></li>
            <li><strong>Printing Order:</strong> Each card prints on 2 pages
                <ul>
                    <li>Page 1: Front of Card 1</li>
                    <li>Page 2: Back of Card 1</li>
                    <li>Page 3: Front of Card 2</li>
                    <li>Page 4: Back of Card 2</li>
                    <li>And so on...</li>
                </ul>
            </li>
            <li>Enable "Background graphics" or "Print backgrounds" in your print settings</li>
        </ol>
        <button class="print-btn" onclick="window.print()">
            🖨️ Print Now
        </button>
    </div>

    <!-- PRINT CARDS: One card per page, front followed by back -->
    @foreach ($labours as $labour)
        <!-- Front Card Page -->
        <div class="card-page">
            <div class="labour-card front-card">
                @if(file_exists(public_path('uploads/labours/card/front.png')))
                <div class="card-background">
                    <img src="{{ asset('uploads/labours/card/front.png') }}" alt="Card Background" class="background-image">
                </div>
                @endif
                
                <div class="card-body"></div>
                
                <div class="card-footer">
                    <div class="labour-name-bottom-right urdu">
                        <span class="labour-name-text">{{ $labour->urdu_name ?? $labour->name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Card Page -->
        <div class="card-page">
            <div class="labour-card back-card">
                @if(file_exists(public_path('uploads/labours/card/back.png')))
                <div class="card-background">
                    <img src="{{ asset('uploads/labours/card/back.png') }}" alt="Card Background" class="background-image">
                </div>
                @endif
                
                <div class="back-body">
                    <div class="back-layout">
                        <div class="right-section">
                            <div class="logo-top">
                                @if(file_exists(public_path('assets/images/logo.png')))
                                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo">
                                @endif
                            </div>
                            <div class="qr-section-below-logo">
                                <div id="qrcode-{{ $labour->l_id }}" class="qr-code-simple"></div>
                            </div>
                        </div>
                        
                        <div class="left-section urdu">
                            <div class="info-row">
                                <span class="label">نام:</span>
                                <span class="value">{{ $labour->urdu_name ?? 'N/A' }}</span>
<span class="value" style="color:black; font-size:10px; text-align:left;">Reg No# {{str_pad($labour->l_id, 5, '0', STR_PAD_LEFT)}}</span>
                            </div>
                            <div class="info-row">
                                <span class="label">والد کا نام:</span>
                                <span class="value">{{ $labour->urdu_father_name ?? 'N/A' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="label">شناختی کارڈ:</span>
                                <span class="value">{{ $labour->cnic ?? 'N/A' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="label">پتہ:</span>
                                <span class="value">{{ $labour->urdu_perm_address ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Generate QR codes for all labour cards
            const labours = @json($labours);
            
            labours.forEach(labour => {
                const qrContainer = document.getElementById('qrcode-' + labour.l_id);
                
                if (qrContainer && typeof QRCode !== 'undefined') {
                    const qrData = 'https://app.cmlw.gkp.pk/verification/' + labour.cnic;
                    
                    new QRCode(qrContainer, {
                        text: qrData,
                        width: 60,
                        height: 60,
                        colorDark: '#000000',
                        colorLight: '#ffffff',
                        correctLevel: QRCode.CorrectLevel.L
                    });
                } else if (qrContainer) {
                    // Fallback if QRCode library is not loaded
                    qrContainer.innerHTML = '<div style="width: 65px; height: 65px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 8px; color: #666;">QR</div>';
                }
            });
        });
    </script>
</body>
</html>

