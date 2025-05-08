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
            background-color: #1e2931;
            /* Set the background color here */
            border: 2px solid #ccc;
            /* Optional: Add a border for better visibility */
            padding: 10px;
            /* Optional: Add padding for better appearance */
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
                font-size: xx-large;
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

        /* List of Links */
        #linksList {
            display: none;
            /* hidden initially */
            margin-top: 20px;
            list-style-type: none;
            padding: 0;
        }

        /* #linksList {
            margin-top: 20px;
            list-style-type: none;
            padding: 0;
        } */

        #linksList li {
            background-color: #1e2931;
            padding: 10px;
            margin: 5px;
            border-radius: 5px;
            text-align: left;
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
        <ul id="linksList"></ul>
        <div class="d-flex justify-content-center">
            <div id="copyAlert" class="alert alert-success text-center" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 9999;">
                Text copied to clipboard!
                <button type="button" class="close" onclick="closeAlert()">&times;</button>
            </div>
            <button class="btn btn-primary mx-2" onclick="copyText()">Copy</button>
            <button class="btn btn-secondary mx-2" onclick="displayLinks()">Links</button>
            <button class="btn btn-success mx-2">Upload</button>
            <button class="btn btn-warning mx-2">Download</button>
            <button class="btn btn-info mx-2">Dark Mode</button>
        </div>

        <!-- Links list will be displayed here -->
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

        // Function to copy the text in the textarea
        // function copyText() {
        //     var textArea = document.getElementById('textInput');
        //     textArea.select();
        //     document.execCommand('copy');
        //     alert("Text copied to clipboard!");
        // }
        function copyText() {
            var textArea = document.getElementById('textInput');
            textArea.select();
            textArea.setSelectionRange(0, 99999); // For mobile
            document.execCommand('copy');

            // Show alert
            var alertBox = document.getElementById('copyAlert');
            alertBox.style.display = 'block';

            // Auto close in 3 seconds
            setTimeout(() => {
                alertBox.style.display = 'none';
            }, 3000);
        }

        // Manual close function
        function closeAlert() {
            document.getElementById('copyAlert').style.display = 'none';
        }


        // Function to display links from the text
        function displayLinks() {
    var text = document.getElementById('textInput').value;
    var linkRegex = /https?:\/\/[^\s]+/g; // Regex to find URLs
    var links = text.match(linkRegex);
    var linksList = document.getElementById('linksList');

    // Clear previous links
    linksList.innerHTML = '';

    if (links && links.length > 0) {
        linksList.style.display = 'block'; // Show the list
        links.forEach(function(link) {
            var listItem = document.createElement('li');
            listItem.classList.add('d-flex', 'justify-content-between', 'align-items-center');

            // Create a text node for the URL (link)
            var linkText = document.createElement('span');
            linkText.textContent = link;
            linkText.classList.add('mr-3'); // Optional: Adds some margin to the right of the link text

            // Create a 'Go To Website' button
            var button = document.createElement('button');
            button.textContent = 'Go To Website';
            button.classList.add('btn', 'btn-info');
            button.onclick = function() {
                window.open(link, '_blank'); // Open the link in a new tab
            };

            // Append the link and button to the list item
            listItem.appendChild(linkText);
            listItem.appendChild(button);

            // Append the list item to the links list
            linksList.appendChild(listItem);
        });
    } else {
        linksList.style.display = 'none'; // Hide the list if no links
    }
}


        // function displayLinks() {
        //     var text = document.getElementById('textInput').value;
        //     var linkRegex = /https?:\/\/[^\s]+/g; // Regex to find URLs
        //     var links = text.match(linkRegex);
        //     var linksList = document.getElementById('linksList');

        //     // Clear previous links
        //     linksList.innerHTML = '';

        //     if (links) {
        //         links.forEach(function(link) {
        //             var listItem = document.createElement('li');
        //             listItem.textContent = link;
        //             linksList.appendChild(listItem);
        //         });
        //     } else {
        //         linksList.innerHTML = '<li>No links found in the text.</li>';
        //     }
        // }

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