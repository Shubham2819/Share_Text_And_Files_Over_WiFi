<!DOCTYPE html>
<html amp lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website Title</title>
    <!-- Load AMP runtime and boilerplate -->
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <style amp-boilerplate>
        body {
            -webkit-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            -moz-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            -ms-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            animation: -amp-start 8s steps(1, end) 0s 1 normal both;
        }
        @-webkit-keyframes -amp-start {
            from { visibility: hidden; }
            to { visibility: visible; }
        }
        @-moz-keyframes -amp-start {
            from { visibility: hidden; }
            to { visibility: visible; }
        }
        @-ms-keyframes -amp-start {
            from { visibility: hidden; }
            to { visibility: visible; }
        }
        @-o-keyframes -amp-start {
            from { visibility: hidden; }
            to { visibility: visible; }
        }
        @keyframes -amp-start {
            from { visibility: hidden; }
            to { visibility: visible; }
        }
    </style>
    <!-- Load amp-auto-ads library -->
    <script async custom-element="amp-auto-ads" src="https://cdn.ampproject.org/v0/amp-auto-ads-0.1.js"></script>
    <!-- Include canonical tag -->
    <link rel="canonical" href="https://your-website-url.com/">
</head>
<body>
    <!-- Your website content -->

    <!-- Google AdSense Auto Ads -->
    <amp-auto-ads type="adsense" data-ad-client="ca-pub-3006237552524755"></amp-auto-ads>

    <!-- Your website content -->

    <!-- Script to toggle theme -->
    <script>
        function toggleTheme() {
            document.body.classList.toggle('dark-theme'); // Toggle 'dark-theme' class on body
        }

        // Call the toggleTheme function to set the initial theme
        document.addEventListener('DOMContentLoaded', function() {
            toggleTheme();
        });
    </script>
</body>
</html>
