<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Labour Card</title>
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">
    <script src="{{ asset('assets/plugins/jQuery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/bootstrap.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode-generator/1.4.4/qrcode.min.js"></script>
    <style>
        @font-face {
            font-family: "Jameel Noori Nastaleeq";
            src: url("http://www.fontsaddict.com/fontface/jameel-noori-nastaleeq.ttf");
        }

        .urdu {
            font-family: "Jameel Noori Nastaleeq";
            text-align: right;
        }

        .print-container {
            margin: auto;
            max-width: 800px;
            padding: 20px;
        }

        .labour-card-container {
            display: flex;
            gap: 20px;
            margin: 20px 0;
            flex-wrap: wrap;
            justify-content: center;
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
        
        /* Ensure cards have proper background in all views */
        .labour-card-container .front-card {
            /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; */
        }
        
        .labour-card-container .back-card {
            /* background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important; */
        }

        .labour-card-container .card-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
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

        .labour-card-container .front-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .labour-card-container .back-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        /* Ensure cards have background in print view */
        .labour-card-container .labour-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .labour-card-container .back-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .labour-card-container .card-body {
            display: flex;
            padding: 15px 20px;
            gap: 15px;
            flex: 1;
            position: relative;
            z-index: 2;
        }

        .labour-card-container .back-body {
            padding: 15px 20px;
            height: 120px;
            overflow: hidden;
            position: relative;
            z-index: 3;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
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

        .labour-card-container .info-row {
            display: flex;
            font-size: 11px;
            border-radius: 3px;
        }

        .labour-card-container .label {
            width: 60px;
            flex-shrink: 0;
            color: white;
            /* text-shadow: 1px 1px 2px rgba(0,0,0,0.5); */
            font-size: 12px;
        }

        .labour-card-container .value {
            flex: 1;
            margin-left: 5px;
            color: white;
            /* text-shadow: 1px 1px 2px rgba(0,0,0,0.5); */
            font-weight: 500;
        }

        .labour-card-container .qr-section-end {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 3px;
            margin-bottom: 10px;
            padding: 5px;
            position: relative;
            overflow: hidden;
        }

        .labour-card-container .qr-code-simple {
            width: 40px;
            height: 40px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }
        
        /* Ensure QR code stays within container */
        .labour-card-container .qr-code-simple canvas,
        .labour-card-container .qr-code-simple img,
        .labour-card-container .qr-code-simple svg {
            max-width: 100% !important;
            max-height: 100% !important;
            width: 40px !important;
            height: 40px !important;
            position: relative !important;
            display: block !important;
        }
        
        /* Additional constraints for QR code positioning */
        .labour-card-container .info-row {
            position: relative;
            overflow: hidden;
        }
        
        .labour-card-container .qr-section-end {
            position: relative;
            z-index: 10;
        }
        
        /* Prevent QR code from breaking out of card */
        .labour-card-container .labour-card {
            position: relative;
            overflow: hidden;
        }

        /* Print styles */
        @media print {
            /* Set exact page size for CR80 card printer */
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
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
            }
            
            .print-controls {
                display: none !important;
            }
            
            .print-container {
                margin: 0 !important;
                padding: 0 !important;
            }
            
            .labour-card-container {
                display: block !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            
            .labour-card {
                width: 85.72mm !important;
                height: 53.97mm !important;
                page-break-inside: avoid !important;
                page-break-after: always !important;
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
                display: flex !important;
                flex-direction: column !important;
                position: relative !important;
                overflow: hidden !important;
            }
            
            .labour-card:last-child {
                page-break-after: auto !important;
            }
            
            /* Ensure backgrounds and images are visible */
            .labour-card-container .card-background,
            .labour-card-container .background-image {
                display: block !important;
                opacity: 1 !important;
                visibility: visible !important;
            }
            
            /* Ensure all content is visible */
            .labour-card-container .card-body,
            .labour-card-container .card-footer,
            .labour-card-container .back-body,
            .labour-card-container .labour-name-text,
            .labour-card-container .info-row,
            .labour-card-container .label,
            .labour-card-container .value,
            .labour-card-container .back-layout,
            .labour-card-container .left-section,
            .labour-card-container .right-section {
                visibility: visible !important;
                opacity: 1 !important;
            }
            
            /* Ensure gradients print */
            .labour-card-container .front-card {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            }
            
            .labour-card-container .back-card {
                background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
            }
            
            /* Show background images for print but with reduced opacity */
            .labour-card .card-background {
                display: block !important;
                /* opacity: 0.3 !important; */
            }
            
            .labour-card-container .labour-card {
                /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; */
                color: #ffffff !important;
                opacity: 1 !important;
            }
            
            .labour-card-container .front-card {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
                color: white !important;
            }
            
            .labour-card-container .back-card {
                /* background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important; */
                color: white !important;
            }
            
            /* Ensure background images are visible in print */
            .labour-card-container .background-image {
                /* opacity: 0.3 !important; */
                display: block !important;
            }
            
            .labour-card-container .info-row {
                color: white !important;
            }
            
            .labour-card-container .label,
            .labour-card-container .value {
                color: #ffffff !important;
                font-weight: bold !important;
                text-shadow: none !important;
            }
            
            /* Ensure all text elements are super white in print */
            .labour-card-container .labour-card * {
                color: #ffffff !important;
                text-shadow: none !important;
            }
            
            /* Force super white for all text content */
            .labour-card-container .labour-card,
            .labour-card-container .labour-card *,
            .labour-card-container .labour-card span,
            .labour-card-container .labour-card div,
            .labour-card-container .labour-card p {
                color: #ffffff !important;
                text-shadow: none !important;
            }
            
            .labour-card-container .labour-card h1,
            .labour-card-container .labour-card h2,
            .labour-card-container .labour-card h3,
            .labour-card-container .labour-card h4,
            .labour-card-container .labour-card h5,
            .labour-card-container .labour-card h6 {
                color: #ffffff !important;
                text-shadow: none !important;
            }
            
            .labour-card-container .labour-card p,
            .labour-card-container .labour-card span,
            .labour-card-container .labour-card div {
                color: #ffffff !important;
                text-shadow: none !important;
            }
            
            /* Force super white text for all card content */
            .labour-card-container .back-info-section,
            .labour-card-container .back-info-section * {
                color: #ffffff !important;
                text-shadow: none !important;
            }
            
            .labour-card-container .back-header,
            .labour-card-container .back-header * {
                color: #ffffff !important;
                text-shadow: none !important;
            }
            
            /* Ensure Urdu text is also super white */
            .labour-card-container .urdu,
            .labour-card-container .urdu * {
                color: #ffffff !important;
                text-shadow: none !important;
            }
            
            /* Additional super white overrides */
            .labour-card-container .info-row,
            .labour-card-container .info-row * {
                color: #ffffff !important;
                text-shadow: none !important;
            }
            
            /* Ensure QR code is visible in print */
            .qr-code-simple {
                background: white !important;
                border: 1px solid #000 !important;
            }
            
            /* Print-specific background image handling */
            .labour-card-container .card-background {
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                z-index: 1 !important;
                /* opacity: 0.3 !important; */
            }
            
            .labour-card-container .background-image {
                width: 100% !important;
                height: 100% !important;
                object-fit: cover !important;
                border-radius: 3mm !important;
                /* opacity: 0.3 !important; */
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <!-- Print Controls -->
        <div class="print-controls mb-3 text-center">
            <button type="button" class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print mr-2"></i>Print Card
            </button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary ml-2">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>

        <!-- Labour Card -->
        <div class="labour-card-container">
            @include('admin.layouts.partials.card.labour-card', ['row' => $labour])
        </div>
        
        <!-- Debug Info -->
        <!-- <div class="debug-info" style="margin-top: 20px; padding: 10px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px;">
            <h6>Debug Information:</h6>
            <p><strong>Labour ID:</strong> {{ $labour->l_id ?? 'N/A' }}</p>
            <p><strong>CNIC:</strong> {{ $labour->cnic ?? 'N/A' }}</p>
            <p><strong>QR Container ID:</strong> qrcode-{{ $labour->l_id ?? 'unknown' }}</p>
            <p><strong>Verification URL:</strong> {{ $labour->l_id ? "http://localhost/app/verification/$labour->cnic" : "#" }}</p>
        </div> -->
    </div>

    <script>
        // Function to generate QR code using multiple methods
        function generateQRCode() {
            console.log('Starting QR code generation...');
            
            // Generate QR Code for each labour card
            const qrContainers = document.querySelectorAll('[id^="qrcode-"]');
            console.log('Found QR containers:', qrContainers.length);
            
            if (qrContainers.length === 0) {
                console.log('No QR containers found, retrying in 500ms...');
                setTimeout(generateQRCode, 500);
                return;
            }
            
            qrContainers.forEach((container, index) => {
                const labourId = container.id.replace('qrcode-', '');
                console.log(`Processing QR container ${index + 1}:`, container.id);
                
                // Create simple QR code with just the verification URL
                const qrData = '{{ $labour->l_id ? "https://app.cmlw.gkp.pk/verification/$labour->cnic" : "#" }}';
                console.log('QR Data:', qrData);
                
                // Clear container first
                container.innerHTML = '';
                
                let qrSize = 40;
                if (container.classList.contains('qr-code-simple')) {
                    qrSize = 40;
                } else if (container.classList.contains('qr-code-large')) {
                    qrSize = 50;
                }
                
                // Method 1: Try QRCode library
                if (typeof QRCode !== 'undefined') {
                    try {
                        console.log('Using QRCode library...');
                        const qr = new QRCode(container, {
                            text: qrData,
                            width: qrSize,
                            height: qrSize,
                            colorDark: '#000000',
                            colorLight: '#ffffff',
                            correctLevel: QRCode.CorrectLevel.L
                        });
                        console.log(`QR code ${index + 1} generated successfully with QRCode library`);
                        
                        // Ensure QR code elements are properly sized
                        setTimeout(() => {
                            const canvas = container.querySelector('canvas');
                            const img = container.querySelector('img');
                            if (canvas) {
                                canvas.style.maxWidth = '100%';
                                canvas.style.maxHeight = '100%';
                                canvas.style.width = qrSize + 'px';
                                canvas.style.height = qrSize + 'px';
                            }
                            if (img) {
                                img.style.maxWidth = '100%';
                                img.style.maxHeight = '100%';
                                img.style.width = qrSize + 'px';
                                img.style.height = qrSize + 'px';
                            }
                        }, 100);
                        
                        addClickHandler(container);
                        return;
                    } catch (error) {
                        console.error(`Error with QRCode library:`, error);
                    }
                }
                
                // Method 2: Try qrcode-generator library
                if (typeof qrcode !== 'undefined') {
                    try {
                        console.log('Using qrcode-generator library...');
                        const qr = qrcode(0, 'M');
                        qr.addData(qrData);
                        qr.make();
                        
                        const qrSvg = qr.createSvgTag(4, 0);
                        container.innerHTML = qrSvg;
                        console.log(`QR code ${index + 1} generated successfully with qrcode-generator`);
                        addClickHandler(container);
                        return;
                    } catch (error) {
                        console.error(`Error with qrcode-generator library:`, error);
                    }
                }
                
                // Method 3: Fallback - create a simple placeholder
                console.log('Using fallback method...');
                container.innerHTML = `
                    <div style="width: ${qrSize}px; height: ${qrSize}px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 8px; color: #666; border: 1px solid #ccc; flex-direction: column;">
                        <div>QR</div>
                        <div style="font-size: 6px;">{{ $labour->cnic ?? 'N/A' }}</div>
                    </div>
                `;
                addClickHandler(container);
            });
        }
        
        // Function to add click handler
        function addClickHandler(container) {
            container.addEventListener('click', function() {
                const verificationUrl = '{{ $labour->l_id ? "https://app.cmlw.gkp.pk/verification/$labour->cnic" : "#" }}';
                if (verificationUrl !== '#') {
                    window.open(verificationUrl, '_blank');
                }
            });
            
            // Add hover effect
            container.style.cursor = 'pointer';
            container.title = 'Click to verify card information';
        }
        
        // Try multiple times to ensure QR code generation
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, attempting QR code generation...');
            generateQRCode();
        });
        
        // Also try when window loads
        window.addEventListener('load', function() {
            console.log('Window loaded, attempting QR code generation...');
            generateQRCode();
        });
        
        // Fallback after 2 seconds
        setTimeout(function() {
            console.log('Fallback QR code generation...');
            generateQRCode();
        }, 2000);
        
        // Manual test after 3 seconds
        setTimeout(function() {
            console.log('Manual QR code test...');
            const testContainer = document.getElementById('qrcode-{{ $labour->l_id ?? "unknown" }}');
            if (testContainer) {
                console.log('Test container found:', testContainer);
                console.log('Container classes:', testContainer.className);
                console.log('Container innerHTML:', testContainer.innerHTML);
                
                // Force create a simple QR code
                if (typeof QRCode !== 'undefined') {
                    try {
                        testContainer.innerHTML = '';
                        new QRCode(testContainer, {
                            text: '{{ $labour->l_id ? "https://app.cmlw.gkp.pk/verification/$labour->cnic" : "#" }}',
                            width: 40,
                            height: 40,
                            colorDark: '#000000',
                            colorLight: '#ffffff',
                            correctLevel: QRCode.CorrectLevel.L
                        });
                        
                        // Ensure proper sizing
                        setTimeout(() => {
                            const canvas = testContainer.querySelector('canvas');
                            const img = testContainer.querySelector('img');
                            if (canvas) {
                                canvas.style.maxWidth = '100%';
                                canvas.style.maxHeight = '100%';
                                canvas.style.width = '40px';
                                canvas.style.height = '40px';
                            }
                            if (img) {
                                img.style.maxWidth = '100%';
                                img.style.maxHeight = '100%';
                                img.style.width = '40px';
                                img.style.height = '40px';
                            }
                        }, 100);
                        
                        console.log('Manual QR code created successfully');
                    } catch (error) {
                        console.error('Manual QR code creation failed:', error);
                    }
                }
            } else {
                console.log('Test container not found');
            }
        }, 3000);
    </script>
</body>
</html>
