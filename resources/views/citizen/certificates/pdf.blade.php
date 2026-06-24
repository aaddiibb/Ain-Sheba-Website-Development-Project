<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'DejaVu Sans', Arial, sans-serif;
        background: #ffffff;
        color: #1a1a2e;
        width: 297mm;
        min-height: 210mm;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }
    .page {
        width: 100%;
        min-height: 210mm;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10mm;
    }
    .outer {
        width: 100%;
        border: 6px double #1a3c5e;
        padding: 12mm;
        text-align: center;
        background: #fff;
    }
    .inner {
        border: 2px solid #c9a84c;
        padding: 10mm;
        text-align: center;
    }
    .wordmark {
        font-size: 22pt;
        font-weight: bold;
        color: #1a3c5e;
        letter-spacing: 4px;
        text-transform: uppercase;
        margin-bottom: 2mm;
    }
    .wordmark-sub {
        font-size: 9pt;
        color: #666;
        letter-spacing: 2px;
        margin-bottom: 6mm;
    }
    .divider {
        border: none;
        border-top: 2px solid #c9a84c;
        width: 80px;
        margin: 4mm auto;
    }
    .cert-title {
        font-size: 14pt;
        font-weight: bold;
        color: #1a3c5e;
        letter-spacing: 4px;
        text-transform: uppercase;
        margin: 4mm 0;
    }
    .certifies-text {
        font-size: 10pt;
        color: #555;
        margin: 3mm 0 1mm;
    }
    .citizen-name {
        font-size: 28pt;
        font-weight: bold;
        color: #1a3c5e;
        letter-spacing: 1px;
        margin: 3mm 0;
    }
    .program-label {
        font-size: 10pt;
        color: #555;
        margin: 2mm 0;
    }
    .program-title {
        font-size: 14pt;
        font-style: italic;
        font-weight: 600;
        color: #1a1a2e;
        margin: 3mm 0 5mm;
    }
    .issue-label {
        font-size: 9pt;
        color: #888;
    }
    .issue-date {
        font-size: 11pt;
        font-weight: 600;
        margin: 1mm 0 5mm;
    }
    .sig-line {
        border-top: 1px solid #dee2e6;
        display: inline-block;
        padding: 3mm 20mm 0;
        margin-top: 4mm;
    }
    .sig-name {
        font-size: 10pt;
        font-weight: bold;
        color: #1a3c5e;
    }
    .sig-sub {
        font-size: 8pt;
        color: #888;
    }
    .cert-code {
        font-size: 8pt;
        color: #999;
        font-family: 'Courier New', monospace;
        margin-top: 5mm;
    }
</style>
</head>
<body>
<div class="page">
    <div class="outer">
        <div class="inner">

            <div class="wordmark">Ain Sheba</div>
            <div class="wordmark-sub">Legal Awareness Platform</div>

            <hr class="divider">

            <div class="cert-title">Legal Literacy Certificate</div>

            <hr class="divider">

            <div class="certifies-text">This certifies that</div>
            <div class="citizen-name">{{ $certificate->citizen->name }}</div>
            <div class="program-label">has successfully completed the legal awareness program</div>
            <div class="program-title">"{{ $certificate->program->title }}"</div>

            <div class="issue-label">Issued on</div>
            <div class="issue-date">{{ $certificate->issued_at->format('d F Y') }}</div>

            <div class="sig-line">
                <div class="sig-name">Authorized by Ain Sheba</div>
                <div class="sig-sub">Legal Awareness Platform</div>
            </div>

            <div class="cert-code">
                Certificate Code: {{ $certificate->certificate_code }}<br>
                Verify at: /verify/{{ $certificate->certificate_code }}
            </div>

        </div>
    </div>
</div>
</body>
</html>
