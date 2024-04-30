<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Focus Helper - Pause Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(120deg, #89f7fe 0%, #66a6ff 100%);
            color: #333;
        }

        .container {
            background: white;
            padding: 40px 60px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            margin-top: 0;
            color: #333;
        }

        p {
            font-size: 1.2em;
            color: #555;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-top: 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Take a Break</h1>
        <p>It's time to pause and recharge before diving back into your focused session.</p>
        <button onclick="continueFocus()">Continue Focus</button>
    </div>

    <script>
        sessionStorage.setItem('toPause', "2");
        function continueFocus() {
            // Ensure all necessary timer state is restored correctly
            sessionStorage.setItem('isPaused', 'false');  // Ensure that the timer knows it is no longer paused
            const params = sessionStorage.getItem('query');
            let query = JSON.parse(params)
            if (query){
                window.location.href = 'timer.php?name='+query.name+"&type="+query.type+"&duration="+query.duration+"&background="+query.background; // Redirect back to the timer page
            }
        }
    </script>
</body>
</html>


