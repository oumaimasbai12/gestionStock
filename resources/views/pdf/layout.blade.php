<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Stocket')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #5C4F4A;
            line-height: 1.4;
        }
        .header {
            border-bottom: 2px solid #5C4F4A;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 20px;
            color: #5C4F4A;
            margin-bottom: 4px;
        }
        .header p {
            font-size: 10px;
            color: #5C4F4A;
            opacity: 0.7;
        }
        .meta {
            margin-top: 8px;
            font-size: 10px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #5C4F4A;
            border-bottom: 1px solid #C9996B;
            padding-bottom: 4px;
            margin: 18px 0 10px;
        }
        .kpi-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .kpi-grid td {
            width: 25%;
            padding: 10px;
            border: 1px solid #5C4F4A;
            vertical-align: top;
        }
        .kpi-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #5C766D;
            margin-bottom: 4px;
        }
        .kpi-value {
            font-size: 14px;
            font-weight: bold;
            color: #5C4F4A;
        }
        .kpi-sub {
            font-size: 9px;
            color: #5C4F4A;
            opacity: 0.6;
            margin-top: 2px;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        table.data th {
            background: #5C766D;
            color: #EDE9E6;
            font-size: 9px;
            text-transform: uppercase;
            padding: 8px 6px;
            text-align: left;
        }
        table.data td {
            border-bottom: 1px solid #EDE9E6;
            padding: 7px 6px;
            font-size: 10px;
        }
        table.data tr:nth-child(even) td {
            background: #EDE9E6;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 9px;
            font-weight: bold;
            border: 1px solid #C9996B;
            background: #EDE9E6;
        }
        .badge-paid { border-color: #5C766D; color: #5C766D; }
        .badge-unpaid { border-color: #C9996B; color: #C9996B; }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid #5C4F4A;
            padding-top: 6px;
            font-size: 9px;
            color: #5C4F4A;
            opacity: 0.6;
            text-align: center;
        }
        .totals-row td {
            font-weight: bold;
            background: #C9996B !important;
            color: #EDE9E6;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Stocket</h1>
        <p>Système de Gestion des Stocks — Distribution BTP</p>
        @hasSection('meta')
            <div class="meta">@yield('meta')</div>
        @endif
    </div>

    @yield('content')

    <div class="footer">
        Document généré le {{ now()->format('d/m/Y à H:i') }} — Stocket
    </div>
</body>
</html>
