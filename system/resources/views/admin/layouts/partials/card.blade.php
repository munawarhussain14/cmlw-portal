@php
    $districts = \App\Models\MineralTitle::selectRaw('district as value, district as text')
        ->distinct('district')
        ->orderBy('district')
        ->get()
        ->toArray();
@endphp

<!-- CNIC Form -->
<form id="cnic-form" action="{{ route('admin.updateLabourCNIC', ['labour' => $row->l_id]) }}" method="post"
    enctype="multipart/form-data">
    @csrf
    <div class="card card-primary collapsed-card">
        <div class="card-header">
            <h3 class="card-title">Card Information</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="card-body" style="display: none;">
            <div class="row">
                <div class="col-md-6">
                    @include('admin.layouts.partials.form.input', [
                        'name' => 'issue_date_cnic',
                        'label' => 'CNIC Issue Date',
                        'type' => 'date',
                        'value' => $labour->issue_date_cnic
                    ])
                </div>
                <div class="col-md-6">
                    @include('admin.layouts.partials.form.input', [
                        'name' => 'expire_date_cnic',
                        'label' => 'CNIC Expiry Date',
                        'type' => 'date',
                        'value' => $labour->expire_date_cnic,
                        'id' => 'expire_date_cnic',
                    ])
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Lifetime CNIC</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" 
                            id="lifetime_cnic" name="lifetime_cnic" value="1"
                            {{ $labour->expire_date_lifetime ? 'checked' : '' }}>
                            <label class="custom-control-label" for="lifetime_cnic">Check if CNIC is lifetime</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>CNIC Front Image <small class="text-muted">(Optional)</small></label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="cnic_front" name="cnic_front" accept="image/*">
                            <label class="custom-file-label" for="cnic_front">Choose file</label>
                        </div>
                        @error('cnic_front')
                            <label class="error">{!! $message !!}</label>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>CNIC Back Image <small class="text-muted">(Optional)</small></label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="cnic_back" name="cnic_back" accept="image/*">
                            <label class="custom-file-label" for="cnic_back">Choose file</label>
                        </div>
                        @error('cnic_back')
                            <label class="error">{!! $message !!}</label>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Profile Image <small class="text-muted">(Optional)</small></label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="profile_image" name="profile_image" accept="image/*">
                            <label class="custom-file-label" for="profile_image">Choose file</label>
                        </div>
                        @error('profile_image')
                            <label class="error">{!! $message !!}</label>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    @include('admin.layouts.partials.form.input', [
                        'name' => 'urdu_name',
                        'label' => 'Urdu Name (اردو نام)',
                        'type' => 'text',
                        'value' => $labour->urdu_name,
                        'required' => true,
                    ])
                </div>
                <div class="col-md-6">
                    @include('admin.layouts.partials.form.input', [
                        'name' => 'urdu_father_name',
                        'label' => 'Urdu Father Name (اردو والد کا نام)',
                        'type' => 'text',
                        'value' => $labour->urdu_father_name,
                        'required' => true,
                    ])
                </div>
                <div class="col-md-12">
                    @include('admin.layouts.partials.form.input', [
                        'name' => 'urdu_perm_address',
                        'label' => 'Urdu Permanent Address (اردو مستقل پتہ)',
                        'type' => 'textarea',
                        'value' => $labour->urdu_perm_address,
                        'required' => true,
                    ])
                </div>
                <div class="col-md-6">
                    @include('admin.layouts.partials.form.input', [
                        'name' => 'card_issue_date',
                        'label' => 'Card Issue Date',
                        'type' => 'date',
                        'value' => $labour->card_issue_date,
                        'required' => false,
                    ])
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Card Status</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" 
                            id="issued" name="issued" value="1"
                            {{ $labour->issued ? 'checked' : '' }}>
                            <label class="custom-control-label" for="issued">Card Issued</label>
                        </div>
                        @can('print-card')
                        <div class="custom-control custom-checkbox mt-2">
                            <input type="checkbox" class="custom-control-input" 
                            id="card_printed" name="card_printed" value="1"
                            {{ $labour->card_printed ? 'checked' : '' }}>
                            <label class="custom-control-label" for="card_printed">Card Printed</label>
                        </div>
                        @endcan
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="info_acknowledgment" name="info_acknowledgment" value="1" required>
                            <label class="custom-control-label" for="info_acknowledgment">
                                I add info as best of my knowledge
                            </label>
                        </div>
                        @error('info_acknowledgment')
                            <label class="error">{!! $message !!}</label>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer" style="display: none;">
            <button type="submit" class="btn btn-primary pull-right">Update CNIC Information</button>
        </div>
    </div>
</form>

@if($labour->issued)
<div class="card card-primary collapsed-card" id="card-cnic-info">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-id-card mr-2"></i>CNIC Details
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- CNIC Details -->
            <div class="col-md-12">
            <a href="{{ route('admin.labours.print', $row->l_id) }}" target="_blank" class="btn btn-primary">
                <i class="fas fa-print mr-2"></i>Print Card
            </a>
                @include('admin.layouts.partials.card.labour-card', ["row"=>$labour])
            </div>
            <div class="col-md-6">
                <div class="info-box bg-light">
                    <span class="info-box-icon bg-info">
                        <i class="fas fa-calendar-alt"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">CNIC Issue Date</span>
                        <span class="info-box-number">
                            {{ $labour->issue_date_cnic ? \Carbon\Carbon::parse($labour->issue_date_cnic)->format('d M Y') : 'Not provided' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="info-box bg-light">
                    <span class="info-box-icon bg-warning">
                        <i class="fas fa-calendar-times"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">CNIC Expiry Date</span>
                        <span class="info-box-number">
                            @if($labour->expire_date_cnic)
                                {{ \Carbon\Carbon::parse($labour->expire_date_cnic)->format('d M Y') }}
                            @else
                                <span class="text-success">Lifetime CNIC</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-box bg-light">
                    <span class="info-box-icon bg-primary">
                        <i class="fas fa-id-card"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Card Issue Date</span>
                        <span class="info-box-number">
                            {{ $labour->card_issue_date ? \Carbon\Carbon::parse($labour->card_issue_date)->format('d M Y') : 'Not Issued Yet' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="info-box bg-light">
                    <span class="info-box-icon {{ $labour->issued ? 'bg-success' : 'bg-secondary' }}">
                        <i class="fas {{ $labour->issued ? 'fa-check-circle' : 'fa-hourglass-half' }}"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Card Status</span>
                        <span class="info-box-number">
                            @if($labour->issued)
                                <span class="badge badge-success">Issued</span>
                            @else
                                <span class="badge badge-secondary">Not Issued</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-box bg-light">
                    <span class="info-box-icon {{ $labour->card_printed ? 'bg-info' : 'bg-secondary' }}">
                        <i class="fas {{ $labour->card_printed ? 'fa-print' : 'fa-times-circle' }}"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Card Print Status</span>
                        <span class="info-box-number">
                            @if($labour->card_printed)
                                <span class="badge badge-info">Printed</span>
                            @else
                                <span class="badge badge-secondary">Not Printed</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Image Previews -->
            <div class="col-12">
                <h5 class="mb-3">
                    <i class="fas fa-images mr-2"></i>Document Images
                </h5>
            </div>

            @if($labour->profile_image)
            <div class="col-md-4">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">Profile Image</h3>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset($labour->profile_image) }}" 
                             alt="Profile Image" 
                             class="img-fluid rounded-circle shadow-sm cnic-image-preview"
                             style="width: 150px; height: 150px; object-fit: cover; cursor: pointer;"
                             data-toggle="modal" 
                             data-target="#imageModal"
                             data-image="{{ asset($labour->profile_image) }}"
                             data-title="Profile Image">
                        <p class="text-muted mt-2 mb-0">Click to view full size</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Status Information -->
            <div class="col-12 mt-3">
                <div class="info-box bg-light">
                    <span class="info-box-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Information Status</span>
                        <span class="info-box-number">
                            @if($labour->info_acknowledgment)
                                <span class="badge badge-success">Verified & Acknowledged</span>
                            @else
                                <span class="badge badge-warning">Pending Acknowledgment</span>
                            @endif
                        </span>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: {{ $labour->info_acknowledgment ? '100' : '50' }}%"></div>
                        </div>
                        <span class="progress-description">
                            {{ $labour->info_acknowledgment ? 'All information has been verified' : 'Information verification pending' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Image Preview Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a href="" id="downloadImage" class="btn btn-primary" download>
                    <i class="fas fa-download"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>


<!-- Cropper Modal -->
<div class="modal fade" id="cropperModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Image</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div style="max-height: 500px;">
                    <img id="cropperImage" style="max-width: 100%;">
                </div>
            </div>
            <div class="modal-footer">
                <button id="cropSave" type="button" class="btn btn-primary">Save Crop</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>


@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <style>
        .is-invalid {
            border-color: #dc3545 !important;
        }
        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }
        .alert {
            margin-bottom: 1rem;
        }
        
        /* CNIC Card Custom Styles */
        .cnic-image-preview {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .cnic-image-preview:hover {
            transform: scale(1.05);
            border-color: #007bff;
            box-shadow: 0 4px 8px rgba(0,123,255,0.3);
        }
        
        .info-box {
            margin-bottom: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .info-box-icon {
            border-radius: 8px 0 0 8px;
        }
        
        .info-box-content {
            padding: 10px 15px;
        }
        
        .info-box-text {
            font-weight: 600;
            color: #495057;
            font-size: 0.875rem;
        }
        
        .info-box-number {
            font-size: 1.25rem;
            font-weight: 700;
            color: #212529;
        }
        
        .card-outline {
            border-top: 3px solid;
            transition: all 0.3s ease;
        }
        
        .card-outline:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .card-outline.card-info {
            border-top-color: #17a2b8;
        }
        
        .card-outline.card-success {
            border-top-color: #28a745;
        }
        
        #card-cnic-info .card-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border-bottom: none;
        }
        
        #card-cnic-info .card-header h3 {
            color: white;
            margin: 0;
        }
        
        .progress {
            height: 6px;
            border-radius: 3px;
            margin-top: 5px;
        }
        
        .progress-bar {
            border-radius: 3px;
        }
        
        .badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .info-box-content {
                padding: 8px 12px;
            }
            
            .info-box-number {
                font-size: 1rem;
            }
            
            .cnic-image-preview {
                max-height: 150px !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            // Initialize custom file input
            if (typeof bsCustomFileInput !== 'undefined') {
                bsCustomFileInput.init();
            }

            // Image modal functionality
            $('#imageModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var imageSrc = button.data('image');
                var title = button.data('title');
                
                var modal = $(this);
                modal.find('#imageModalLabel').text(title);
                modal.find('#modalImage').attr('src', imageSrc);
                modal.find('#downloadImage').attr('href', imageSrc);
                modal.find('#downloadImage').attr('download', title.replace(/\s+/g, '_').toLowerCase() + '.jpg');
            });

            let cropper;
            let currentInput; // track which file input triggered cropper
            let aspectRatio; // declare aspectRatio variable

            function handleFileSelect(input) {
                if (input.files && input.files[0]) {
                    currentInput = input;

                    // Choose aspect ratio based on input
                    if (input.id === 'profile_image') {
                        aspectRatio = 7 / 9; // Passport size ratio
                    } else if (input.id === 'cnic_front' || input.id === 'cnic_back') {
                        aspectRatio = 4 / 3; // CNIC ratio (you can tweak if needed)
                    } else {
                        aspectRatio = NaN; // free crop
                    }

                    let reader = new FileReader();
                    reader.onload = function (e) {
                        $('#cropperImage').attr('src', e.target.result);
                        $('#cropperModal').modal('show');
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Attach change event on inputs
            $('#profile_image, #cnic_front, #cnic_back').on('change', function () {
                handleFileSelect(this);
            });

            // Initialize cropper when modal is shown
            $('#cropperModal').on('shown.bs.modal', function () {
                cropper = new Cropper(document.getElementById('cropperImage'), {
                    aspectRatio: aspectRatio,
                    viewMode: 1,
                    autoCropArea: 1,
                });
            }).on('hidden.bs.modal', function () {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
            });

            // Save cropped image
            $('#cropSave').on('click', function () {
                if (cropper) {
                    cropper.getCroppedCanvas({
                        width: 413,
                        height: 531
                    }).toBlob(function (blob) {
                        // Replace original file with cropped blob
                        let file = new File([blob], "cropped.jpg", { type: "image/jpeg" });
                        let dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        currentInput.files = dataTransfer.files;

                        $('#cropperModal').modal('hide');
                    });
                }
            });

            // Handle lifetime CNIC checkbox
            $('#lifetime_cnic').change(function() {
                if ($(this).is(':checked')) {
                    $('#expire_date_cnic').prop('disabled', true).val('');
                    // Remove any existing error styling
                    $('#expire_date_cnic').removeClass('is-invalid');
                    $('#expire_date_cnic').next('.invalid-feedback').remove();
                } else {
                    $('#expire_date_cnic').prop('disabled', false);
                }
            });

            // Form validation before submission
            $('#cnic-form').on('submit', function(e) {
                e.preventDefault();
                
                let isValid = true;
                let errorMessage = '';
                
                // // Check if lifetime CNIC is not checked and expiry date is empty
                // if (!$('#lifetime_cnic').is(':checked')) {
                //     const expiryDate = $('#expire_date_cnic').val();
                //     if (!expiryDate || expiryDate.trim() === '') {
                //         isValid = false;
                //         errorMessage = 'CNIC Expiry Date is required when CNIC is not lifetime.';
                        
                //         // Add error styling
                //         $('#expire_date_cnic').addClass('is-invalid');
                //         if ($('#expire_date_cnic').next('.invalid-feedback').length === 0) {
                //             $('#expire_date_cnic').after('<div class="invalid-feedback">' + errorMessage + '</div>');
                //         }
                //     } else {
                //         // Remove error styling if date is provided
                //         $('#expire_date_cnic').removeClass('is-invalid');
                //         $('#expire_date_cnic').next('.invalid-feedback').remove();
                //     }
                // }
                
                // Check if issue date is provided
                // const issueDate = $('input[name="issue_date_cnic"]').val();
                // if (!issueDate || issueDate.trim() === '') {
                //     isValid = false;
                //     errorMessage = 'CNIC Issue Date is required.';
                    
                //     // Add error styling
                //     $('input[name="issue_date_cnic"]').addClass('is-invalid');
                //     if ($('input[name="issue_date_cnic"]').next('.invalid-feedback').length === 0) {
                //         $('input[name="issue_date_cnic"]').after('<div class="invalid-feedback">' + errorMessage + '</div>');
                //     }
                // } else {
                //     // Remove error styling if date is provided
                //     $('input[name="issue_date_cnic"]').removeClass('is-invalid');
                //     $('input[name="issue_date_cnic"]').next('.invalid-feedback').remove();
                // }
                
                // Check if info acknowledgment is checked
                if (!$('#info_acknowledgment').is(':checked')) {
                    isValid = false;
                    errorMessage = 'You must accept the information acknowledgment.';
                    
                    // Add error styling
                    $('#info_acknowledgment').addClass('is-invalid');
                    if ($('#info_acknowledgment').next('.invalid-feedback').length === 0) {
                        $('#info_acknowledgment').after('<div class="invalid-feedback">' + errorMessage + '</div>');
                    }
                } else {
                    // Remove error styling if checked
                    $('#info_acknowledgment').removeClass('is-invalid');
                    $('#info_acknowledgment').next('.invalid-feedback').remove();
                }
                
                // If validation passes, submit the form
                if (isValid) {
                    // Remove any existing error styling
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();
                    
                    // Submit the form
                    this.submit();
                } else {
                    // Show error message
                    if ($('.alert-danger').length === 0) {
                        $('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                          '<strong>Validation Error:</strong> Please fix the errors below before submitting.' +
                          '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                          '<span aria-hidden="true">&times;</span>' +
                          '</button>' +
                          '</div>').prependTo('.card-body');
                    }
                }
            });
        });
    </script>
@endpush
