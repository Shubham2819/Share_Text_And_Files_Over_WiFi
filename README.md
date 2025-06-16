📡 Share Text Over WiFi – Setup Instructions

This project allows you to share and update text in real-time with any device connected to the same WiFi network. Follow the steps below to run the application and access it via a browser using your local IP.

---

✅ Access the PHP App Over WiFi

1. 🔍 Find Your Local IP Address (Windows)

   - Open Command Prompt and run:
     ipconfig

   - Look for your IPv4 Address. For example:
     IPv4 Address. . . . . . . . . . . : 192.168.167.51

2. 🚀 Start the PHP Server Using That IP

   - Open a terminal or command prompt.
   - Navigate to the folder where your `index.php` file is located (e.g., `ssavr`).
   - Run the following command:
     php -S 192.168.167.51:8000

3. 🌐 Access the App From Any Device on the Same WiFi

   - On any phone, tablet, or computer connected to the same WiFi network, open a browser and enter:
     http://192.168.167.51:8000/index.php
     OR
     http://192.168.167.51:8000/
     

   **For iOS Users:**
   - Open Safari (or any browser) on your iPhone or iPad.
   - Enter the URL in the address bar:
     http://192.168.167.51:8000/index.php
     OR
     http://192.168.167.51:8000/

---

✅ The server will automatically load `index.php` without needing to type it in the URL.

---

📂 File Details

- `index.php` – The main file where the shared text is displayed and updated.
- `shared_text.txt` – The file that stores the shared content. It updates in real-time as users make changes.
- The app works seamlessly in real-time for all users connected to the same WiFi.

---

✅ Summary

- 📲 All connected devices can view and edit the same text instantly.
- 🚫 No need for a save button – all changes are saved automatically.
- 🌐 Can be accessed from any modern browser across devices (phone, tablet, laptop).

---

🛡️ Tip: Ensure your firewall allows access to **port 8000**, or the server may not be reachable from other devices.

//////////////////////////////////////////////////////////

✅ Now anyone on your WiFi can access and edit the same shared text in real-time.
✅ Enjoy seamless text sharing across all your devices!

//////////////////////////////////////////////////////////
