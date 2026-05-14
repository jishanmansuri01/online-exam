<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Result Card</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #333; }
        .header { background: #2c3e50; color: white; padding: 25px 30px; text-align: center; }
        .header h1 { font-size: 22px; margin-bottom: 5px; }
        .header p { font-size: 12px; opacity: 0.8; }
        .body { padding: 30px; }
        .result-badge {
            text-align: center; padding: 15px; margin: 20px 0;
            border-radius: 8px; font-size: 20px; font-weight: bold;
        }
        .pass { background: #d4edda; color: #155724; border: 2px solid #c3e6cb; }
        .fail { background: #f8d7da; color: #721c24; border: 2px solid #f5c6cb; }
        .info-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .info-table td { padding: 10px 15px; border: 1px solid #dee2e6; }
        .info-table td:first-child { font-weight: bold; background: #f8f9fa; width: 40%; }
        .score-box {
            text-align: center; padding: 20px;
            background: #f8f9fa; border-radius: 8px; margin: 20px 0;
        }
        .score-box .score { font-size: 48px; font-weight: bold; color: #2c3e50; }
        .score-box .label { color: #666; font-size: 14px; }
        .footer { text-align: center; margin-top: 30px; color: #999; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <p>Official Result Card</p>
    </div>

    <div class="body">
        <div class="result-badge {{ $result->status === 'pass' ? 'pass' : 'fail' }}">
            {{ $result->status === 'pass' ? '🎉 PASSED' : '❌ FAILED' }}
        </div>

        <table class="info-table">
            <tr>
                <td>Student Name</td>
                <td>{{ $result->user->name }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{ $result->user->email }}</td>
            </tr>
            <tr>
                <td>Exam Title</td>
                <td>{{ $result->exam->title }}</td>
            </tr>
            <tr>
                <td>Date</td>
                <td>{{ $result->created_at->format('d M Y, h:i A') }}</td>
            </tr>
            <tr>
                <td>Total Marks</td>
                <td>{{ $result->total_marks }}</td>
            </tr>
            <tr>
                <td>Obtained Marks</td>
                <td>{{ $result->obtained_marks }}</td>
            </tr>
            <tr>
                <td>Pass Marks</td>
                <td>{{ $result->exam->pass_marks }}</td>
            </tr>
            <tr>
                <td>Percentage</td>
                <td>{{ $result->percentage }}%</td>
            </tr>
        </table>

        <div class="score-box">
            <div class="score">{{ $result->percentage }}%</div>
            <div class="label">Final Score</div>
        </div>

        <div class="footer">
            Generated on {{ now()->format('d M Y, h:i A') }} •
            {{ config('app.name') }}
        </div>
    </div>
</body>
</html>