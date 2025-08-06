<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Template: {{ $loaTemplate->name }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 20px;
            line-height: 1.6;
            color: #333;
        }
        .preview-header {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .template-content {
            border: 1px solid #ccc;
            padding: 30px;
            background: white;
            min-height: 500px;
        }
        .preview-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 14px;
        }
        /* Custom CSS from template */
        {!! $loaTemplate->css_styles ?: '' !!}
    </style>
</head>
<body>
    <div class="preview-header">
        <h3>Preview Template: {{ $loaTemplate->name }}</h3>
        <small>
            <strong>Bahasa:</strong> 
            @switch($loaTemplate->language)
                @case('id') Indonesia @break
                @case('en') English @break
                @case('both') Bilingual @break
            @endswitch
            | <strong>Format:</strong> {{ strtoupper($loaTemplate->format) }}
            @if($loaTemplate->publisher)
                | <strong>Publisher:</strong> {{ $loaTemplate->publisher->name }}
            @endif
        </small>
    </div>

    <div class="preview-note">
        <i class="fas fa-info-circle"></i>
        <strong>Preview Note:</strong> Ini adalah preview template dengan data sample. 
        Data sebenarnya akan diisi secara dinamis saat generate LOA.
    </div>

    <div class="template-content">
        @if($loaTemplate->header_template)
            <div class="template-header">
                {!! str_replace(
                    [
                        '{{article_title}}',
                        '{{author_name}}',
                        '{{registration_number}}',
                        '{{publisher_name}}',
                        '{{journal_name}}',
                        '{{submission_date}}',
                        '{{approval_date}}',
                        '{{website}}',
                        '{{email}}'
                    ],
                    [
                        $sampleData['article_title'],
                        $sampleData['author_name'],
                        $sampleData['registration_number'],
                        $sampleData['publisher_name'],
                        $sampleData['journal_name'],
                        $sampleData['submission_date'],
                        $sampleData['approval_date'],
                        $sampleData['website'],
                        $sampleData['email']
                    ],
                    $loaTemplate->header_template
                ) !!}
            </div>
        @endif

        <div class="template-body">
            {!! str_replace(
                [
                    '{{article_title}}',
                    '{{author_name}}',
                    '{{registration_number}}',
                    '{{publisher_name}}',
                    '{{journal_name}}',
                    '{{submission_date}}',
                    '{{approval_date}}',
                    '{{website}}',
                    '{{email}}'
                ],
                [
                    $sampleData['article_title'],
                    $sampleData['author_name'],
                    $sampleData['registration_number'],
                    $sampleData['publisher_name'],
                    $sampleData['journal_name'],
                    $sampleData['submission_date'],
                    $sampleData['approval_date'],
                    $sampleData['website'],
                    $sampleData['email']
                ],
                $loaTemplate->body_template
            ) !!}
        </div>

        @if($loaTemplate->footer_template)
            <div class="template-footer">
                {!! str_replace(
                    [
                        '{{article_title}}',
                        '{{author_name}}',
                        '{{registration_number}}',
                        '{{publisher_name}}',
                        '{{journal_name}}',
                        '{{submission_date}}',
                        '{{approval_date}}',
                        '{{website}}',
                        '{{email}}'
                    ],
                    [
                        $sampleData['article_title'],
                        $sampleData['author_name'],
                        $sampleData['registration_number'],
                        $sampleData['publisher_name'],
                        $sampleData['journal_name'],
                        $sampleData['submission_date'],
                        $sampleData['approval_date'],
                        $sampleData['website'],
                        $sampleData['email']
                    ],
                    $loaTemplate->footer_template
                ) !!}
            </div>
        @endif
    </div>

    <div class="preview-header mt-4">
        <h4>Sample Data Used:</h4>
        <small>
            <strong>Article Title:</strong> {{ $sampleData['article_title'] }}<br>
            <strong>Author:</strong> {{ $sampleData['author_name'] }}<br>
            <strong>Registration Number:</strong> {{ $sampleData['registration_number'] }}<br>
            <strong>Publisher:</strong> {{ $sampleData['publisher_name'] }}<br>
            <strong>Journal:</strong> {{ $sampleData['journal_name'] }}<br>
            <strong>Submission Date:</strong> {{ $sampleData['submission_date'] }}<br>
            <strong>Approval Date:</strong> {{ $sampleData['approval_date'] }}<br>
            <strong>Website:</strong> {{ $sampleData['website'] }}<br>
            <strong>Email:</strong> {{ $sampleData['email'] }}
        </small>
    </div>

    <script>
        // Add print functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Add print button
            const printButton = document.createElement('button');
            printButton.innerHTML = '<i class="fas fa-print"></i> Print Preview';
            printButton.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; z-index: 1000;';
            printButton.onclick = function() {
                window.print();
            };
            document.body.appendChild(printButton);

            // Hide preview notes when printing
            const style = document.createElement('style');
            style.textContent = `
                @media print {
                    .preview-header, .preview-note {
                        display: none !important;
                    }
                    body {
                        margin: 0;
                    }
                    .template-content {
                        border: none;
                        padding: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</body>
</html>
