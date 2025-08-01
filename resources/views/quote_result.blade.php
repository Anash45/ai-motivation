<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generated Quote</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
            background-color: #f0f4f8;
            color: #333;
        }
        .quote-box {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            max-width: 600px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        audio {
            margin-top: 1.5rem;
            width: 100%;
        }
        h2 {
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }
        .quote {
            font-size: 1.25rem;
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="quote-box">
        <h2>Your Daily Motivation</h2>
        <p class="quote">"{{ $quote }}"</p>

        <audio controls>
            <source src="{{ $audioUrl }}" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
    </div>

</body>
</html>
