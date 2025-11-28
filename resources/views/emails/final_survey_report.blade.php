<!DOCTYPE html>
<html>
    <head>
        <title>Final Survey Report</title>
    </head>
    <body>
        <h1>Final Survey Report</h1>

        <p>Hello {{ $survey->owner->name }},</p>

        <p>Your survey "<strong>{{ $survey->title }}</strong>" closed yesterday.</p>

        <p>Here are the final stats:</p>
        <ul>
            <li>Total responses received: {{ $responses_count }}</li>
            <li>Start date: {{ $survey->start_date->format('d M Y') }}</li>
            <li>End date: {{ $survey->end_date->format('d M Y') }}</li>
        </ul>

        <p>Thank you for using our survey system!</p>
    </body>
</html>
