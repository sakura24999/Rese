<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>{{$subject}}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .email-header {
            background-color: #1d65f6;
            padding: 20px;
            text-align: center;
        }

        .email-logo {
            color: white;
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .email-content {
            padding: 30px;
        }

        .email-subject {
            margin-top: 0;
            color: #333;
        }

        .email-message {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .email-button {
            display: inline-block;
            background-color: #1d65f6;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            margin-top: 20px;
        }

        .email-footer {
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            margin-top: 20px;
            padding: 20px;
            border-top: 1px solid #eee;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h1 class="email-logo">Rese</h1>
        </div>

        <div class="email-content">
            <h2 class="email-subject">{{$subject}}</h2>

            <div class="email-message">
                {!! nl2br(e($messageContent)) !!}
            </div>

            <a href="{{route('shops.index')}}" class="email-button">サイトに移動</a>
        </div>

        <div class="email-footer">
            <p>© {{ date('Y') }} Rese. All rights reserved.</p>
            <p>このメールはシステムから自動送信されています。ご返信いただいてもお答えできません。</p>
        </div>
    </div>
</body>

</html>
