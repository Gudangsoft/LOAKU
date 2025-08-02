<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOA Template Preview - {{ $template->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .preview-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            min-height: 80vh;
        }
        .preview-header {
            background: #fff;
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            display: flex;
            justify-content: between;
            align-items: center;
        }
        .preview-content {
            margin-top: 60px;
        }
        .btn {
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            display: inline-block;
            margin-right: 10px;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn:hover {
            opacity: 0.8;
        }
        @media print {
            .preview-header {
                display: none;
            }
            .preview-content {
                margin-top: 0;
            }
            body {
                background: white;
                padding: 0;
            }
            .preview-container {
                box-shadow: none;
                padding: 0;
            }
        }
        
        /* Include custom CSS from template */
        {{ $template->css_styles }}
    </style>
</head>
<body>
    <div class="preview-header">
        <div>
            <strong>Preview: {{ $template->name }}</strong>
            <span style="margin-left: 20px; color: #666;">
                Language: 
                @switch($template->language)
                    @case('id') Indonesian @break
                    @case('en') English @break
                    @case('both') Bilingual @break
                    @default {{ $template->language }}
                @endswitch
            </span>
        </div>
        <div>
            <a href="javascript:window.print()" class="btn btn-primary">
                Print / PDF
            </a>
            <a href="{{ route('admin.loa-templates.show', $template->id) }}" class="btn btn-secondary">
                Back to Template
            </a>
        </div>
    </div>

    <div class="preview-content">
        <div class="preview-container">
            {!! $renderedTemplate !!}
        </div>
    </div>
</body>
</html>
