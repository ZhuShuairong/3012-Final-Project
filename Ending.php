<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Focus Helper - Ending Page</title>
    <link rel="stylesheet" href="styles.css"> <!--background picture-->
</head>
<body>
    <div class="background-image">
        <h1>Congratulations!</h1>
        
        <p>Thank you for your dedicated focus! You've successfully completed a focused session.</p>
        
        <p>Focus Time: <?php include 'path_to_other_page_with_focus_time.php'; ?></p>
        

        <p><?php 
            $encouragingSentences = [
                "Well done! You've shown incredible discipline and focus.",
                "Pat yourself on the back for staying committed to your task.",
                "Every moment of focus brings you closer to your goals.",
                "Great job! You're making progress one focused session at a time.",
                "Remember, consistency is key. Keep up the fantastic work!"
            ];
            $randomIndex = array_rand($encouragingSentences);
            echo $encouragingSentences[$randomIndex];
        ?></p>
        
        <a href="focus_page.php">Start a New Session</a>
    </div>
</body>
</html>
