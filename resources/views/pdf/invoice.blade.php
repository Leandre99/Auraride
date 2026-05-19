<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 40px;
            font-size: 14px;
        }
        .top-section {
            width: 100%;
            margin-bottom: 40px;
        }
        .top-section td {
            vertical-align: top;
        }
        .client-info {
            width: 50%;
        }
        .company-info {
            width: 50%;
            text-align: left;
        }
        .invoice-details {
            margin-bottom: 30px;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.items th {
            border-bottom: 2px solid #000;
            padding: 8px 0;
            text-align: left;
            font-weight: bold;
        }
        table.items td {
            padding: 10px 0;
            border-bottom: 1px solid #ccc;
        }
        .totals {
            width: 100%;
            margin-top: 20px;
        }
        .totals td {
            padding: 5px 0;
        }
        .totals-container {
            width: 40%;
            float: right;
        }
        .footer {
            position: absolute;
            bottom: 30px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #555;
        }
        .text-right {
            text-align: right !important;
        }
    </style>
</head>
<body>

    <div class="header">
        Facture fiscale
    </div>

    <table class="top-section">
        <tr>
            <td class="client-info">
                Client<br>
                France
            </td>
            <td class="company-info">
                Facture émise par Uber B.V. pour:<br>
                ATLAS TAXI / VTC<br>
                29 AV ANTONIN TRINQUET, 31410, CAPENS<br>
                France<br>
                SIREN/SIRET: 979122603<br>
                n° TVA: FR47979122603
            </td>
        </tr>
    </table>

    <div class="invoice-details">
        Numéro de facture: {{ $invoiceNumber ?? 'INV-' . strtoupper(Str::random(8)) }}<br>
        Date de facturation: {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>Date de la<br>transaction</th>
                <th>Description</th>
                <th>Qté</th>
                <th>TVA</th>
                <th class="text-right">TVA Montant</th>
                <th class="text-right">Montant net</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</td>
                <td>{{ $description }}</td>
                <td>1</td>
                <td>10%</td>
                <td class="text-right">{{ number_format($taxAmount, 2, ',', ' ') }} €</td>
                <td class="text-right">{{ number_format($netAmount, 2, ',', ' ') }} €</td>
            </tr>
        </tbody>
    </table>

    <div class="totals-container">
        <table class="totals" style="width: 100%;">
            <tr>
                <td>Total HT</td>
                <td class="text-right">{{ number_format($netAmount, 2, ',', ' ') }} €</td>
            </tr>
            <tr>
                <td>Total TVA 10%</td>
                <td class="text-right">{{ number_format($taxAmount, 2, ',', ' ') }} €</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Montant total à payer</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($totalAmount, 2, ',', ' ') }} €</td>
            </tr>
        </table>
    </div>
    <div style="clear: both;"></div>

    <div class="footer">
        Facture établie au nom et pour le compte de ATLAS TAXI / VTC par:<br>
        Uber B.V. / Burgerweeshuispad 301, 1076 HR Amsterdam / VAT: NL852071589B01 / COC #: 56317441
    </div>

</body>
</html>
