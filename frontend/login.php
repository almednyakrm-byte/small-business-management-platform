<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #1a1d23, #2c2f36);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s;
        }
        .glassmorphic {
            background: linear-gradient(90deg, #1a1d23 0%, #2c2f36 100%);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .glassmorphic::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #1a1d23 0%, #2c2f36 100%);
            mix-blend-mode: multiply;
            opacity: 0.5;
        }
    </style>
</head>
<body class="h-screen bg-gray-900 flex justify-center items-center">
    <div class="glassmorphic bg-gradient-to-br from-slate-900 to-indigo-500 p-10 rounded-lg shadow-lg w-96">
        <h2 class="text-3xl font-bold text-white mb-4">Login</h2>
        <form id="login-form" class="space-y-4">
            <div class="flex flex-col">
                <label for="username" class="text-white">Username</label>
                <input type="text" id="username" name="username" class="bg-gray-800 text-white p-2 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                <div id="username-error" class="text-red-500 hidden"></div>
            </div>
            <div class="flex flex-col">
                <label for="password" class="text-white">Password</label>
                <input type="password" id="password" name="password" class="bg-gray-800 text-white p-2 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                <div id="password-error" class="text-red-500 hidden"></div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Login</button>
            <p class="text-white mt-4">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-700">Register</a></p>
        </form>
    </div>

    <script>
        const form = document.getElementById('login-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            try {
                const response = await fetch('../backend/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });
                const data = await response.json();
                if (data.success) {
                    alert('Login successful!');
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        });

        document.getElementById('username').addEventListener('input', () => {
            const username = document.getElementById('username').value;
            if (username.length < 3) {
                document.getElementById('username-error').classList.remove('hidden');
                document.getElementById('username-error').textContent = 'Username must be at least 3 characters long.';
            } else {
                document.getElementById('username-error').classList.add('hidden');
            }
        });

        document.getElementById('password').addEventListener('input', () => {
            const password = document.getElementById('password').value;
            if (password.length < 8) {
                document.getElementById('password-error').classList.remove('hidden');
                document.getElementById('password-error').textContent = 'Password must be at least 8 characters long.';
            } else {
                document.getElementById('password-error').classList.add('hidden');
            }
        });
    </script>
</body>
</html>