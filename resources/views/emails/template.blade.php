{{--
    Webtech-solutions 2025, All rights reserved.
    Dynamic Email Template - wraps processed markdown content in mail layout
--}}

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .email-content {
            padding: 30px;
        }
        h1, h2, h3, h4, h5, h6 {
            color: #2d3748;
            margin-top: 0;
            margin-bottom: 16px;
        }
        p {
            margin: 0 0 16px;
        }
        a {
            color: #3490dc;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        ul, ol {
            margin: 0 0 16px;
            padding-left: 24px;
        }
        code {
            background: #f7fafc;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 0.9em;
            font-family: 'Courier New', monospace;
        }
        pre {
            background: #f7fafc;
            padding: 12px;
            border-radius: 4px;
            overflow-x: auto;
            margin: 0 0 16px;
        }
        pre code {
            background: none;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
        }
        th, td {
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background: #f7fafc;
            font-weight: 600;
        }
        blockquote {
            border-left: 4px solid #e2e8f0;
            padding-left: 16px;
            margin: 0 0 16px;
            color: #718096;
        }
        .email-footer {
            background: #f7fafc;
            padding: 20px 30px;
            text-align: center;
            font-size: 0.875rem;
            color: #718096;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-content">
            {!! $content !!}
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
