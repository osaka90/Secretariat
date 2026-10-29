<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>سجل رئاسة النيابة العامة</title>
    <style>
        @font-face {
            font-family: 'MasadAlMaghribi';
            src: url('data:font/truetype;base64,{{ base64_encode(file_get_contents(public_path('fonts/masad-al-maghribi.ttf'))) }}') format('truetype');
        }

        .maghribi-font { font-family: 'MasadAlMaghribi', 'Arial', sans-serif; }
        .cover-page, .cover-page * { font-family: 'MasadAlMaghribi', 'Arial', sans-serif !important; }
        
        .cover-page-container {
            width: 100%;
            font-size: 14pt;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 2px dashed #bbb;
        }

        body { font-family: 'Arial', sans-serif; margin: 0; padding: 20px; font-size: 11pt; }

        table { width: 100%; border-collapse: collapse; table-layout: fixed; }

        th, td {
            border: 2px solid #000;
            padding: 4px;
            text-align: center;
            vertical-align: top;
            word-wrap: break-word;
            font-size: 10pt;
        }
        th { background-color: #f2f2f2; font-weight: bold; }

        .col-seq      { width: 4%; }
        .col-date-arr { width: 6%; }
        .col-num      { width: 5%; }
        .col-date-let { width: 6%; }
        .col-sender   { width: 12%; }
        .col-subj-inc { width: 15%; }
        .col-date-out { width: 6%; }
        .col-recip-out{ width: 10%; }
        .col-subj-out { width: 12%; }
        .col-source-in{ width: 8%; }
        .col-reply-in { width: 10%; }
        .col-result   { width: 6%; }

        td strong, td b { font-weight: bold !important; }
        td em, td i     { font-style: italic !important; }
        td u            { text-decoration: underline !important; }
        td s            { text-decoration: line-through !important; }
        td p            { margin: 0; padding: 0; }
        td ul           { margin: 0; padding-right: 16px; list-style-type: disc; }
        td ol           { margin: 0; padding-right: 16px; list-style-type: decimal; }



        @media print {
            body {
                padding: 0; margin: 0;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            .no-print { display: none !important; }

            @page { size: A4 landscape; margin: 1cm; }
            
            .cover-page-container {
                page-break-after: always;
                margin-bottom: 0;
                padding-bottom: 0;
                border-bottom: none;
            }

            thead { display: table-header-group; }
            tfoot { display: table-footer-group; }
            tr    { page-break-inside: avoid; }
        }

        .btn {
            padding: 10px 20px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14pt;
        }
        .btn-print { background: #2563eb; }
        .btn-excel { background: #16a34a; }


        /* RichEditor color support */
        .color { color: var(--color, inherit) !important; }
        [data-color] { color: var(--color, inherit) !important; }
        [data-text-color] { color: var(--rich-color, inherit); }
    </style>
    <script>
        function applyColors() {
            document.querySelectorAll('[data-color]').forEach(function (el) {
                el.style.setProperty('color', el.getAttribute('data-color'), 'important');
            });
            document.querySelectorAll('[data-text-color]').forEach(function (el) {
                el.style.setProperty('color', el.getAttribute('data-text-color'), 'important');
            });
        }



        function exportExcel() {
            var url = new URL(window.location.href);
            url.searchParams.set('format', 'excel');
            window.location.href = url.toString();
        }

        document.addEventListener('DOMContentLoaded', applyColors);
        window.addEventListener('beforeprint', applyColors);
    </script>
</head>

<body style="font-family: Arial, sans-serif; font-size: 12pt;">
    @php
        if (!function_exists('richHtml')) {
            function richHtml(?string $html): string {
                if (!$html) return '';
                return preg_replace(
                    '/style="--color:\s*(#[0-9a-fA-F]{3,8})[^"]*"/',
                    'style="color: $1 !important"',
                    $html
                );
            }
        }
    @endphp

    @php
        $isExcel = ($format ?? null) === 'excel';
        $thStyle =
            'border: 1pt solid #000000; padding: 5px; background-color: #f2f2f2; font-weight: bold; text-align: center; vertical-align: middle; font-size: 13pt; height: 35px;';
        $thBlueStyle =
            'border: 1pt solid #000000; padding: 5px; background-color: #d9edf7; font-weight: bold; text-align: center; vertical-align: middle; font-size: 13pt; height: 35px;';
        $baseTd              = 'border: 1pt solid #000000; padding: 5px; text-align: center; vertical-align: middle; font-size: 12pt; height: 55px;';
        $tdStyle             = $baseTd . ' word-wrap: break-word;';
        $tdDateStyle         = $baseTd . ' white-space: nowrap; direction: rtl;';
        $tdFirstRowStyle     = $baseTd . ' border-top: 3pt solid #000000; word-wrap: break-word;';
        $tdFirstRowDateStyle = $baseTd . ' border-top: 3pt solid #000000; white-space: nowrap; direction: rtl;';
        $emptyRowStyle       = 'border: 1pt solid #000000; height: 55px;';
    @endphp

    @if (!$isExcel)
        <div class="no-print" style="display: flex; gap: 10px; margin-bottom: 20px;">
            <button class="btn btn-print" onclick="window.print()">طباعة A4</button>
            <button class="btn btn-excel" onclick="exportExcel()">تصدير Excel</button>
        </div>

        <!-- Cover page -->
        <div class="cover-page maghribi-font cover-page-container">
            <div style="display: flex; flex-direction: column; padding: 20px; box-sizing: border-box;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 50px;">
                    <div class="maghribi-font" style="text-align: right; font-weight: bold; line-height: 1.5; width: 33%;">
                        المملكة المغربية<br>
                        محكمة الاستئناف بالدار البيضاء<br>
                        المحكمة الابتدائية المدنية بالدار البيضاء<br>
                        النيابة العامة
                    </div>
                    <div style="text-align: center; width: 33%;">
                        <img src="{{ asset('images/coat_of_arms.svg') }}" alt="Coat of Arms of Morocco" style="width: 100px; height: auto;">
                    </div>
                    <div style="text-align: left; font-weight: bold; line-height: 1.5; width: 33%; font-size: 10pt;" dir="ltr">
                        ⵜⴰⴳⵍⴷⵉⵜ ⵏ ⵍⵎⵖⵔⵉⴱ<br>
                        ⵜⴰⵙⵏⴱⴹⴰⵢⵜ ⵏ ⵡⴰⵍⴰⵙ ⴹⴹⴰⵕⵍⴱⵉⴹⴰ<br>
                        ⵜⴰⵙⵏⴱⴹⴰⵢⵜ ⵜⴰⵎⵣⵡⴰⵓⵜ ⵜⵓⵖⵔⵉⵎⵜ ⴹⴹⴰⵕⵍⴱⵉⴹⴰ<br>
                        ⵜⵎⵓⵔⴰⵢⵜ ⵜⴰⵎⴰⵜⵉⵜ
                    </div>
                </div>
                <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; margin-top: 40px;">
                    <div class="maghribi-font" style="font-size: 22pt; font-weight: bold; margin-bottom: 20px;">{{ $title }}</div>
                    <div class="maghribi-font" style="font-size: 14pt; font-weight: bold;">رقم الإحصاء: {{ $statistics_number ?? '____' }}</div>
                    <div class="maghribi-font" style="font-size: 14pt; font-weight: bold; margin-top: 10px;">
                        الفترة: من {{ \Carbon\Carbon::parse($start_date)->format('Y/m/d') }} إلى {{ \Carbon\Carbon::parse($end_date)->format('Y/m/d') }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th colspan="6" style="{{ $thStyle }}" bgcolor="#f2f2f2">الواردات</th>
                <th colspan="5" style="{{ $thStyle }}" bgcolor="#f2f2f2">المراسلات</th>
                <th rowspan="3" style="{{ $thStyle }}" bgcolor="#f2f2f2">النتيجة أو الملاحظات</th>
            </tr>
            <tr>
                <th rowspan="2" class="col-seq"      style="{{ $thBlueStyle }}" bgcolor="#d9edf7" width="60">الرقم الترتيبي</th>
                <th rowspan="2" class="col-date-arr" style="{{ $thBlueStyle }}" bgcolor="#d9edf7" width="100">تاريخ الوصول</th>
                <th rowspan="2" class="col-num"      style="{{ $thBlueStyle }}" bgcolor="#d9edf7" width="80">رقمها</th>
                <th rowspan="2" class="col-date-let" style="{{ $thBlueStyle }}" bgcolor="#d9edf7" width="100">تاريخ الرسالة</th>
                <th rowspan="2" class="col-sender"   style="{{ $thBlueStyle }}" bgcolor="#d9edf7" width="180">اسم وموطن المرسل إليه</th>
                <th rowspan="2" class="col-subj-inc" style="{{ $thBlueStyle }}" bgcolor="#d9edf7" width="200">الموضوع</th>
                <th colspan="3" style="{{ $thBlueStyle }}" bgcolor="#d9edf7">الصادرة</th>
                <th colspan="2" style="{{ $thBlueStyle }}" bgcolor="#d9edf7">الواردة</th>
            </tr>
            <tr>
                <th style="{{ $thBlueStyle }}" bgcolor="#d9edf7" width="100">التاريخ</th>
                <th style="{{ $thBlueStyle }}" bgcolor="#d9edf7" width="150">المرسل إليه</th>
                <th style="{{ $thBlueStyle }}" bgcolor="#d9edf7" width="180">الموضوع</th>
                <th style="{{ $thBlueStyle }}" bgcolor="#d9edf7" width="150">المصدر</th>
                <th style="{{ $thBlueStyle }}" bgcolor="#d9edf7" width="180">الجواب</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($registers as $register)
                @php
                    $inc      = $register->incoming;
                    $outgoings = $register->correspondences->where('type', 'outgoing')->values();
                    $incomings = $register->correspondences->where('type', 'incoming')->values();
                    $maxRows   = max(1, count($outgoings), count($incomings));
                @endphp
                @for ($i = 0; $i < $maxRows; $i++)
                    @php
                        $rTd   = $i === 0 ? $tdFirstRowStyle     : $tdStyle;
                        $rDate = $i === 0 ? $tdFirstRowDateStyle : $tdDateStyle;
                    @endphp
                    <tr>
                        @if ($i === 0)
                            <td rowspan="{{ $maxRows + 2 }}" style="{{ $tdFirstRowStyle }}">{{ $register->sequential_number }}</td>
                            <td rowspan="{{ $maxRows + 2 }}" style="{{ $tdFirstRowDateStyle }}">{{ $inc?->arrival_date ? \Carbon\Carbon::parse($inc->arrival_date)->format('Y/m/d') : '' }}</td>
                            <td rowspan="{{ $maxRows + 2 }}" style="{{ $tdFirstRowStyle }}">{{ $inc?->letter_number }}</td>
                            <td rowspan="{{ $maxRows + 2 }}" style="{{ $tdFirstRowDateStyle }}">{{ $inc?->letter_date ? \Carbon\Carbon::parse($inc->letter_date)->format('Y/m/d') : '' }}</td>
                            <td rowspan="{{ $maxRows + 2 }}" style="{{ $tdFirstRowStyle }}">{{ $inc?->sender }}</td>
                            <td rowspan="{{ $maxRows + 2 }}" style="{{ $tdFirstRowStyle }}">{!! richHtml($inc?->subject) !!}</td>
                        @endif
                        <td style="{{ $rDate }}">{{ isset($outgoings[$i]) && $outgoings[$i]->date ? \Carbon\Carbon::parse($outgoings[$i]->date)->format('Y/m/d') : '' }}</td>
                        <td style="{{ $rTd }}">{{ isset($outgoings[$i]) ? $outgoings[$i]->correspondent : '' }}</td>
                        <td style="{{ $rTd }}">{!! richHtml(isset($outgoings[$i]) ? $outgoings[$i]->subject_or_reply : '') !!}</td>
                        <td style="{{ $rTd }}">{{ isset($incomings[$i]) ? $incomings[$i]->correspondent : '' }}</td>
                        <td style="{{ $rTd }}">{!! richHtml(isset($incomings[$i]) ? $incomings[$i]->subject_or_reply : '') !!}</td>
                        @if ($i === 0)
                            <td rowspan="{{ $maxRows + 2 }}" style="{{ $tdFirstRowStyle }}">{!! richHtml($register->result_or_notes) !!}</td>
                        @endif
                    </tr>
                @endfor
                <tr>
                    <td style="{{ $emptyRowStyle }}">&nbsp;</td><td style="{{ $emptyRowStyle }}">&nbsp;</td>
                    <td style="{{ $emptyRowStyle }}">&nbsp;</td><td style="{{ $emptyRowStyle }}">&nbsp;</td>
                    <td style="{{ $emptyRowStyle }}">&nbsp;</td>
                </tr>
                <tr>
                    <td style="{{ $emptyRowStyle }}">&nbsp;</td><td style="{{ $emptyRowStyle }}">&nbsp;</td>
                    <td style="{{ $emptyRowStyle }}">&nbsp;</td><td style="{{ $emptyRowStyle }}">&nbsp;</td>
                    <td style="{{ $emptyRowStyle }}">&nbsp;</td>
                </tr>
            @empty
                <tr><td colspan="12" style="padding:20px;font-size:14pt;">لا توجد سجلات في هذه الفترة.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>

</body>

</html>

