<?php
// Check if there is new text submitted, and update the stored text if needed
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['text'])) {
    $text = $_POST['text'];
    file_put_contents('shared_text.txt', $text);
}

// Load the shared text from the file or use a default value if the file doesn't exist
$sharedText = file_exists('shared_text.txt') ? file_get_contents('shared_text.txt') : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shubham Text over WiFi</title>
    <script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            text-align: center;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #1e2931;
        }

        .header .logo {
            font-size: 24px;
            color: #fff;
            font-family: 'Arial', sans-serif;
        }

        textarea {
            width: 80%;
            height: 300px;
            margin: 20px auto;
        }

        /* Dark Theme */
        body.dark-mode {
            background-color: #181e22;
            color: #fff;
        }

        /* Light Theme */
        body.light-mode {
            background-color: #fff;
            color: #333;
        }

        #themeSwitcher {
            position: fixed;
            right: 10px;
            top: 10px;
            z-index: 9999;
        }

        textarea {
            width: 70%;
            height: 350px;
            margin: 20px auto;
            color: #fff;
            font-size: large;
            background-color: #1e2931; /* Set the background color here */
            border: 2px solid #ccc; /* Optional: Add a border for better visibility */
            padding: 10px; /* Optional: Add padding for better appearance */
            text-decoration: none;
        }
        /* Responsive adjustments for textarea */
        @media (max-width: 1200px) {
            textarea {
                width: 80%;
                height: 350px;
                text-decoration: none;
            }
        }

        @media (max-width: 992px) {
            textarea {
                width: 100%;
                height: 1450px;
                color: #fff;
            font-size:xx-large;
            text-decoration: none;
            }
        }

        @media (max-width: 768px) {
            textarea {
                width: 95%;
                height: 400px;
                text-decoration: none;
            }
        }

        @media (max-width: 576px) {
            textarea {
                width: 100%;
                height: 400px;
                text-decoration: none;
            }
        }
    </style>
</head>

<body class="light-mode">
    <div class="header">
        <div class="logo">Share Text</div>
        <div>
            <button id="themeSwitcher" class="btn btn-secondary" onclick="toggleTheme()">Light Mode</button>
        </div>
    </div>

    <div class="container mt-4">
        <h1>Share Text over WiFi!</h1>
        <textarea id="textInput" name="text"><?php echo htmlspecialchars($sharedText); ?></textarea>
        <br>
        <!-- <div class="d-flex justify-content-center">
            <button class="btn btn-primary mx-2">Copy</button>
            <button class="btn btn-secondary mx-2">Links</button>
            <button class="btn btn-success mx-2">Upload</button>
            <button class="btn btn-warning mx-2">Download</button>
            <button class="btn btn-info mx-2">Dark Mode</button>
        </div> -->
    </div>
    <div class="ad-container">
        <?php
            echo '
            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3006237552524755"
     crossorigin="anonymous"></script>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-format="autorelaxed"
     data-ad-client="ca-pub-3006237552524755"
     data-ad-slot="7421004376"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
';
        ?>
    </div>

    <script>
        // Function to update the shared text without a save button
        function updateSharedText() {
            var text = document.getElementById('textInput').value;
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'text=' + encodeURIComponent(text),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Update the shared text as the user types
        document.getElementById('textInput').addEventListener('input', function() {
            updateSharedText();
        });

        function toggleTheme() {
            var body = document.body;
            if (body.classList.contains('light-mode')) {
                body.classList.remove('light-mode');
                body.classList.add('dark-mode');
                document.getElementById('themeSwitcher').innerText = 'Light Mode';
            } else {
                body.classList.remove('dark-mode');
                body.classList.add('light-mode');
                document.getElementById('themeSwitcher').innerText = 'Dark Mode';
            }
        }

        // Set the initial theme to dark mode when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            toggleTheme(); // Call the toggleTheme function to set the initial theme
        });
    </script>
</body>
</html>
