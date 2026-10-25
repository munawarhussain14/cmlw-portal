@php
    $purpose = optional(optional($data->student)->father)->purpose;
    $badge = [
        'deceased labour' => ['label' => 'Father: Deceased Mine Labour', 'class' => 'badge-danger'],
        'permanent disabled' => ['label' => 'Father: Permanent Disabled', 'class' => 'badge-warning'],
        'occupational desease' => ['label' => 'Father: Occupational Disease (Pulmonary)', 'class' => 'badge-info'],
    ][$purpose] ?? null;
@endphp
@if ($badge)
    <span class="badge {{ $badge['class'] }}" style="font-size: 1em;">{{ $badge['label'] }}</span>
@endif
