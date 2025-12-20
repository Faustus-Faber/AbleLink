<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Certificate of Completion</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            text-align: center;
            color: #333;
            padding: 40px;
        }
        .border-pattern {
            position: fixed;
            top: 0px;
            left: 0px;
            bottom: 0px;
            right: 0px;
            border: 10px solid #1e293b; /* Slate-800 */
            border-radius: 10px;
        }
        .inner-border {
            position: fixed;
            top: 15px;
            left: 15px;
            bottom: 15px;
            right: 15px;
            border: 2px solid #cbd5e1; /* Slate-300 */
            border-radius: 5px;
        }
        .container {
            padding-top: 60px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5; /* Indigo-600 */
            margin-bottom: 20px;
        }
        .header {
            font-size: 36px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #1e293b;
            margin-bottom: 10px;
        }
        .sub-header {
            font-size: 18px;
            color: #64748b;
            margin-bottom: 40px;
        }
        .student-name {
            font-size: 48px;
            font-weight: bold;
            color: #0f172a;
            margin: 20px 0;
            border-bottom: 2px solid #e2e8f0;
            display: inline-block;
            padding-bottom: 10px;
            min-width: 400px;
        }
        .course-title {
            font-size: 28px;
            font-weight: bold;
            color: #334155;
            margin: 20px 0;
        }
        .ai-message {
            font-style: italic;
            font-size: 16px;
            color: #475569;
            margin: 30px auto;
            max-width: 700px;
            line-height: 1.6;
            padding: 20px;
            background-color: #f8fafc;
            border-radius: 8px;
        }
        .footer {
            margin-top: 60px;
            font-size: 12px;
            color: #94a3b8;
        }
        .signature-line {
            margin-top: 40px;
            display: inline-block;
            border-top: 1px solid #94a3b8;
            width: 200px;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="border-pattern"></div>
    <div class="inner-border"></div>

    <div class="container">
        <div class="logo">AbleLink Academy</div>
        
        <div class="header">Certificate of Completion</div>
        <div class="sub-header">This certifies that</div>

        <div class="student-name">{{ $user->name }}</div>

        <div class="sub-header">has successfully completed the course</div>

        <div class="course-title">{{ $course->title }}</div>

        <div class="ai-message">
            "{{ $certificate->ai_generated_message }}"
        </div>

        <div class="footer">
            <p>Certificate ID: {{ $certificate->certificate_code }}</p>
            <p>Issued on: {{ $date }}</p>
        </div>
    </div>
</body>
</html>
