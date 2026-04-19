@if ($status === 'pending')
    <i class='fa fa-pause-circle text-warning fa-2x'></i>
@elseif($status === 'in process')
    <i class='fa fa-clock text-primary fa-2x'></i>
@elseif($status === 'in-progress')
    <i class='fa fa-clock text-primary fa-2x'></i>
@elseif($status === 'approved')
    <i class='fa fa-check text-success fa-2x'></i>
@elseif($status === 'rejected')
    <i class='fa fa-times text-danger fa-2x'></i>
@elseif($status === 'document verified')
    <i class='fas fa-clipboard-check text-success fa-2x'></i>
@elseif($status === 'complete')
    <i class='fas fa-clipboard-check text-success fa-2x'></i>
@endif
