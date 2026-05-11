<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { background: #1E293B; color: #fff; padding: 20px; border-radius: 8px 8px 0 0; text-align: center; }
        .content { padding: 20px; }
        .footer { font-size: 12px; color: #777; text-align: center; margin-top: 20px; }
        .label { font-weight: bold; color: #1E293B; }
        .message-box { background: #f8fafc; padding: 15px; border-radius: 5px; border-left: 4px solid #2563eb; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Nouveau message de contact</h2>
        </div>
        <div class="content">
            <p><span class="label">Nom :</span> {{ $data['first_name'] }} {{ $data['last_name'] }}</p>
            <p><span class="label">Email :</span> {{ $data['email'] }}</p>
            <p><span class="label">Sujet :</span> {{ $data['subject'] }}</p>
            <p><span class="label">Message :</span></p>
            <div class="message-box">
                {!! nl2br(e($data['message'])) !!}
            </div>
        </div>
        <div class="footer">
            Cet email a été envoyé depuis le formulaire de contact du site ATLAS AND CO.
        </div>
    </div>
</body>
</html>
