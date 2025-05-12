<?php
// Check if there is new text submitted, and update the stored text if needed
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['text'])) {
    $text = $_POST['text'];
    file_put_contents('shared_text.txt', $text);
}

// Load the shared text from the file or use a default value if the file doesn't exist
$sharedText = file_exists('shared_text.txt') ? file_get_contents('shared_text.txt') : '';

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['files'])) {
    $uploadDir = 'uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $success = true;

    foreach ($_FILES['files']['tmp_name'] as $index => $tmpName) {
        $fileName = basename($_FILES['files']['name'][$index]);
        $fileSize = $_FILES['files']['size'][$index];
        $target = $uploadDir . $fileName;

        // Check if file size exceeds the limit
        if ($fileSize > 262144000) { // 250MB file size limit
            echo 'File size exceeds 250MB!';
            exit;
        }

        // Move file if it's under the size limit
        if ($fileSize <= 262144000) {
            if (!move_uploaded_file($tmpName, $target)) {
                $success = false;
                break;
            }
        }
    }

    echo $success ? 'success' : 'error';
    exit;
}
// Handle file list
// List files in the uploads directory
// This will be used to display the list of uploaded files
if (isset($_GET['files'])) {
    $files = array_values(array_filter(scandir('uploads'), fn($f) => !is_dir("uploads/$f")));
    $list = array_map(function ($file) {
        return [
            'name' => $file,
            'url' => 'uploads/' . rawurlencode($file),
            'time' => date("d M Y h:i A", filemtime("uploads/$file"))
        ];
    }, $files);
    echo json_encode(array_reverse($list)); // newest first
    exit;
}

// Delete file(s)
if (isset($_GET['delete'])) {
    $file = $_GET['delete'];
    if ($file === 'all') {
        array_map('unlink', glob("uploads/*"));
        echo 'deleted';
    } else {
        $filePath = 'uploads/' . basename(urldecode($file));
        if (file_exists($filePath)) {
            unlink($filePath);
            echo 'deleted';
        } else {
            echo 'not found';
        }
    }
    exit;
}
// Load shared text
$text = file_exists('shared_text.txt') ? file_get_contents('shared_text.txt') : '';

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
            /* height: 350px; */
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
        @media (max-width: 1400px) {
            textarea {
                width: 80%;
                height: 300px;
                text-decoration: none;
            }
        }


        @media (max-width: 1200px) {
            textarea {
                width: 80%;
                height: 300px;
                text-decoration: none;
            }
        }

        @media (max-width: 992px) {
            textarea {
                width: 100%;
                height: 1200px;
                /* color: #fff; */
                font-size: x-large;
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
                height: 30px;
                text-decoration: none;
            }
        }

        @media (max-width: 230px) {
            textarea {
                width: 100%;
                height: 30px;
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
        <button id="themeSwitcher" class="btn btn-secondary" onclick="toggleTheme()">Light Mode</button>
    </div>
    <!-- progress bar and upload alert -->
    <div id="uploadProgressContainer"
        style="display: none; position: fixed; bottom: 20px; right: 20px; width: 300px; z-index: 9999;">
        <div class="progress">
            <div id="uploadProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                role="progressbar" style="width: 0%">0%</div>
        </div>
    </div>
    <div class="container mt-4">
        <h1>Share Text over WiFi!</h1>
        <textarea id="textInput" name="text"><?php echo htmlspecialchars($sharedText); ?></textarea>
        <br>
        <ul id="linksList"></ul>
        <div class="d-flex justify-content-center">
            <div id="copyAlert" class="alert alert-success text-center"
                style="display: none; position: fixed; top: 20px; right: 20px; z-index: 9999;">
                Text copied to clipboard!
                <button type="button" class="close" onclick="closeAlert()">&times;</button>
            </div>
            <div id="deleteAlert" class="alert alert-success text-center"
                style="display: none; position: fixed; top: 70px; right: 20px; z-index: 9999;">
                File deleted successfully!
                <button type="button" class="close" onclick="closeAlert('deleteAlert')">&times;</button>
            </div>
            <div id="uploadAlert" class="alert alert-success text-center"
                style="display: none; position: fixed; top: 120px; right: 20px; z-index: 9999;">
                Upload status here.
                <button type="button" class="close" onclick="closeAlert('uploadAlert')">&times;</button>
            </div>
            <div id="uploadAlert" class="alert alert-success text-center"
                style="display: none; position: fixed; top: 120px; right: 20px; z-index: 9999;">
                Upload status here.
                <button type="button" class="close" onclick="closeAlert('uploadAlert')">&times;</button>
            </div>
            <button class="btn btn-primary mx-2" onclick="copyText()">Copy</button>
            <button class="btn btn-secondary mx-2" onclick="displayLinks()">Links</button>
            <!-- <button class="btn btn-success mx-2">Upload</button>
            <button class="btn btn-warning mx-2">Download</button> -->
            <button class="btn btn-success btn-custom mr-2" onclick="document.getElementById('fileInput').click()">⬆️
                Share File</button>
            <button class="btn btn-warning btn-custom" onclick="showFiles()">📂 Show Files</button>
            <!-- <button class="btn btn-info mx-2">Dark Mode</button> -->
        </div>
        <input type="file" id="fileInput" style="display: none;" multiple>
        <!-- Links list will be displayed here -->
        <!-- File List Modal -->
        <div class="modal fade" id="fileListModal" tabindex="-1" role="dialog" aria-labelledby="fileListModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content bg-dark text-white">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fileListModalLabel">📂 Shared Files</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- <button class="btn btn-danger mb-3" onclick="deleteAllFiles()">🗑️ Delete All</button> -->
                        <div id="fileListContent" style="max-height: 400px; overflow-y: auto;">
                            Loading…
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
    <script>
        document.getElementById('fileInput').addEventListener('change', function(event) {
            const files = event.target.files;
            const formData = new FormData();
            let tooLarge = false;

            for (let i = 0; i < files.length; i++) {
                if (files[i].size > 262144000) { // Check for 250MB limit
                    tooLarge = true;
                    break;
                }
                formData.append('files[]', files[i]);
            }

            if (tooLarge) {
                showAlert('uploadAlert', 'File size exceeds the 250MB limit. Please upload smaller files.', false);
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    const progressBar = document.getElementById('uploadProgressBar');
                    progressBar.style.width = percent + '%';
                    progressBar.textContent = percent + '%';
                    document.getElementById('uploadProgressContainer').style.display = 'block';
                }
            });

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    document.getElementById('uploadProgressContainer').style.display = 'none';
                    if (xhr.status === 200 && xhr.responseText.trim() === 'success') {
                        showAlert('uploadAlert', 'Files uploaded successfully!');
                        showFiles(); // Optional: refresh file list
                    } else {
                        showAlert('uploadAlert', 'Upload failed: ' + xhr.responseText, false);
                    }
                }
            };

            xhr.open('POST', '', true);
            xhr.send(formData);
        });

        function showFiles() {
            fetch('?files=1')
                .then(res => res.json())
                .then(files => {
                    let html = '';

                    if (files.length) {
                        // Only show "Delete All" if files > 2
                        if (files.length > 2) {
                            html += `<button class="btn btn-danger mb-3" onclick="deleteAllFiles()">🗑️ Delete All</button>`;
                        }

                        html += files.map(f => `
                    <div class="d-flex justify-content-between align-items-center border p-2 my-2 bg-secondary rounded">
                        <div>
                            <strong>${f.name}</strong><br>
                            <small>${f.time}</small>
                        </div>
                        <div>
                            <a href="${f.url}" class="btn btn-sm btn-success mr-2" download>⬇️ Download</a>
                            <button class="btn btn-sm btn-danger" onclick="deleteFile('${encodeURIComponent(f.name)}')">🗑️ Delete</button>
                        </div>
                    </div>
                `).join('');
                    } else {
                        html = 'No files yet.';
                    }

                    document.getElementById('fileListContent').innerHTML = html;
                    $('#fileListModal').modal('show');
                });
        }

        function deleteFile(fileName) {
            fetch('?delete=' + fileName)
                .then(res => res.text())
                .then(result => {
                    if (result === 'deleted') {
                        showAlert('deleteAlert', 'File deleted successfully!');
                        showFiles(); // Refresh file list
                    } else {
                        showAlert('deleteAlert', 'Failed to delete file.', false);
                    }
                });
        }

        function deleteAllFiles() {
            fetch('?delete=all')
                .then(res => res.text())
                .then(result => {
                    if (result === 'deleted') {
                        showAlert('deleteAlert', '🗑️ All files deleted successfully!');
                        showFiles(); // Refresh list
                    } else {
                        showAlert('deleteAlert', 'Failed to delete all files.', false);
                    }
                });
        }

        function showAlert(id, message, isSuccess = true) {
            const alertBox = document.getElementById(id);
            alertBox.classList.remove('alert-success', 'alert-danger');
            alertBox.classList.add(isSuccess ? 'alert-success' : 'alert-danger');
            alertBox.innerHTML = message + '<button type="button" class="close" onclick="closeAlert(\'' + id + '\')">&times;</button>';
            alertBox.style.display = 'block';
            setTimeout(() => alertBox.style.display = 'none', 3000);
        }

        function closeAlert(id) {
            document.getElementById(id).style.display = 'none';
        }
    </script>
    <!-- at the very end of body, BEFORE your custom <script> blocks: -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>