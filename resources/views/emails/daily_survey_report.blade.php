<!DOCTYPE html>
    <html>
        <head>
            <title>Daily Survey Report</title>
        </head>
        <body>
           <h1>Daily Survey Report</h1>
            <p>Hello {{ $survey->owner->name }},</p>
            <p>Your survey "{{ $survey->title }}" received {{ $responses_count }} responses yesterday.</p>
        </body>
</html>
