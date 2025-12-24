{{--
    Webtech-solutions 2025, All rights reserved.
    Email Template Preview - renders markdown content
--}}

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-wrapper {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1, h2, h3 { margin-top: 0; color: #2d3748; }
        a { color: #3490dc; text-decoration: none; }
        a:hover { text-decoration: underline; }
        code { background: #f7fafc; padding: 2px 6px; border-radius: 3px; font-size: 0.9em; }
        pre { background: #f7fafc; padding: 12px; border-radius: 4px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin: 16px 0; }
        th, td { border: 1px solid #e2e8f0; padding: 8px; text-align: left; }
        th { background: #f7fafc; }
    </style>
</head>
<body>
    <div class="email-wrapper">
        {!! $content !!}
    </div>
</body>
</html>
