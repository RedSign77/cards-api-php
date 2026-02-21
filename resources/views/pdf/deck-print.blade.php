<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $deck->deck_name }} - Print Sheet</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #fff;
        }

        .page {
            width: 210mm;
            height: 297mm;
            position: relative;
            page-break-after: always;
            overflow: hidden;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .grid {
            display: table;
            width: 100%;
            height: 100%;
            border-collapse: collapse;
        }

        .grid-row {
            display: table-row;
        }

        .grid-cell {
            display: table-cell;
            width: {{ number_format($cardWidth, 2) }}mm;
            height: {{ number_format($cardHeight, 2) }}mm;
            padding: 0;
            vertical-align: top;
            position: relative;
        }

        /* Card container */
        .card-wrapper {
            width: {{ number_format($cardWidth, 2) }}mm;
            height: {{ number_format($cardHeight, 2) }}mm;
            overflow: hidden;
            position: relative;
            border: 1px solid #000;
        }

        /* Background image fills entire card */
        .card-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .card-bg img {
            width: 100%;
            height: 100%;
            display: block;
        }

        /* Semi-transparent overlay to dim the background */
        .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.70);
        }

        /* Foreground text layer */
        .card-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            padding: 2mm;
            display: block;
        }

        .card-name {
            font-size: 9.1pt;
            font-weight: bold;
            color: #ffffff;
            text-align: center;
            background: rgba(0, 0, 0, 0.55);
            padding: 1mm 2mm;
            margin-bottom: 1.5mm;
        }

        .card-type {
            font-size: 7.2pt;
            color: #dddddd;
            text-align: center;
            margin-bottom: 2mm;
        }

        .card-text {
            font-size: 6.5pt;
            color: #f0f0f0;
            text-align: left;
            margin-bottom: 2mm;
            line-height: 1.3;
        }

        .card-fields {
            margin-top: 1mm;
        }

        .card-field {
            font-size: 6.5pt;
            color: #ffffff;
            margin-bottom: 0.8mm;
            line-height: 1.2;
        }

        .card-field-name {
            font-weight: bold;
            color: #ffd700;
        }

        /* Crop marks */
        .crop-mark {
            position: absolute;
            background: #000;
        }

        .crop-tl-h { top: 0; left: -5mm; width: 3mm; height: 0.25pt; }
        .crop-tl-v { top: -5mm; left: 0; width: 0.25pt; height: 3mm; }
        .crop-tr-h { top: 0; right: -5mm; width: 3mm; height: 0.25pt; }
        .crop-tr-v { top: -5mm; right: 0; width: 0.25pt; height: 3mm; }
        .crop-bl-h { bottom: 0; left: -5mm; width: 3mm; height: 0.25pt; }
        .crop-bl-v { bottom: -5mm; left: 0; width: 0.25pt; height: 3mm; }
        .crop-br-h { bottom: 0; right: -5mm; width: 3mm; height: 0.25pt; }
        .crop-br-v { bottom: -5mm; right: 0; width: 0.25pt; height: 3mm; }

        .empty-card {
            width: 100%;
            height: 100%;
            background: #e8e8e8;
            border: 1px solid #000;
        }
    </style>
</head>
<body>

@foreach ($pages as $pageIndex => $pageCards)
    <div class="page">
        <div class="grid">
            @foreach (array_chunk($pageCards, 3) as $rowCards)
                <div class="grid-row">
                    @foreach ($rowCards as $item)
                        @php
                            $card     = $item['card'];
                            $imgPath  = $item['imagePath'];
                            $cardData = is_array($card->card_data) ? $card->card_data : [];
                        @endphp
                        <div class="grid-cell">
                            <div class="card-wrapper">
                                {{-- Background image --}}
                                <div class="card-bg">
                                    <img src="{{ $imgPath }}" alt="{{ $card->name }}">
                                </div>

                                {{-- Dim overlay --}}
                                <div class="card-overlay"></div>

                                {{-- Foreground text --}}
                                <div class="card-content">
                                    <div class="card-name">{{ $card->name }}</div>

                                    @if ($card->cardType)
                                        <div class="card-type">{{ $card->cardType->name }}</div>
                                    @endif

                                    @if (!empty($card->card_text))
                                        <div class="card-text">{{ strip_tags($card->card_text) }}</div>
                                    @endif

                                    @if (!empty($cardData))
                                        <div class="card-fields">
                                            @foreach ($cardData as $field)
                                                @if (!empty($field['fieldname']) && isset($field['fieldvalue']) && $field['fieldvalue'] !== '')
                                                    <div class="card-field">
                                                        <span class="card-field-name">{{ $field['fieldname'] }}:</span>
                                                        {{ $field['fieldvalue'] }}
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                {{-- Crop marks --}}
                                <span class="crop-mark crop-tl-h"></span>
                                <span class="crop-mark crop-tl-v"></span>
                                <span class="crop-mark crop-tr-h"></span>
                                <span class="crop-mark crop-tr-v"></span>
                                <span class="crop-mark crop-bl-h"></span>
                                <span class="crop-mark crop-bl-v"></span>
                                <span class="crop-mark crop-br-h"></span>
                                <span class="crop-mark crop-br-v"></span>
                            </div>
                        </div>
                    @endforeach

                    @for ($pad = count($rowCards); $pad < 3; $pad++)
                        <div class="grid-cell">
                            <div class="card-wrapper">
                                <div class="empty-card"></div>
                            </div>
                        </div>
                    @endfor
                </div>
            @endforeach
        </div>
    </div>
@endforeach

</body>
</html>
