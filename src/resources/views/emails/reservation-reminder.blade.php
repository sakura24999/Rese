<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>予約リマインダー</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
        }

        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #3a6aee;
        }

        .content {
            padding: 20px 0;
        }

        .message {
            margin-bottom: 20px;
            font-size: 16px;
        }

        .reservation-details {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .detail-item {
            margin-bottom: 10px;
        }

        .detail-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }

        .footer {
            text-align: center;
            padding-top: 20px;
            font-size: 14px;
            color: #777;
            border-top: 1px solid #eee;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo">Rese</div>
        </div>

        <div class="content">
            <div class="message">
                <p>{{$data['user_name']}}様</p>

                <p>{{$emailMessage}}</p>

                <p>ご来店をお待ちしております。</p>
            </div>

            <div class="reservation-details">
                <div class="detail-item">
                    <span class="detail-label">予約日時:</span>
                    <span>{{$data['reservation_date']}} {{$data['reservation_time']}}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">人数:</span>
                    <span>{{$data['number_of_guests']}}</span>
                </div>
            </div>

            <p>キャンセルや変更がある場合は、マイページから手続きをお願いいたします。</p>
        </div>

        <div class="footer">
            <p>※このメールは自動送信されています。返信はできませんのでご了承ください。</p>
            <p>&copy; {{date('Y')}} Rese. All rights reserved.</p>
        </div>
    </div>
</body>

</html>