<!-- /admin/login.php -->
<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

// إذا كان المستخدم مسجلاً بالفعل، إعادة التوجيه إلى لوحة التحكم
if (isLoggedIn()) {
    header('Location: index.php'); // داخل admin
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - لوحة تحكم ظل الجنوب</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #1b6b8f 0%, #0f3a4d 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .login-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            padding: 30px;
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo h1 { color: #1b6b8f; font-size: 24px; margin-top: 10px; }
        .logo i {
            width: 50px; height: 50px;
            background: linear-gradient(135deg, #1b6b8f, #0f3a4d);
            color: white;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 20px; font-weight: bold;
        }
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block; margin-bottom: 8px; color: #0f3a4d; font-weight: 600;
        }
        .form-group input {
            width: 100%; padding: 12px 15px; border: 1px solid #ddd;
            border-radius: 8px; font-size: 16px; transition: all 0.3s;
        }
        .form-group input:focus {
            border-color: #1b6b8f; outline: none;
            box-shadow: 0 0 0 3px rgba(27, 107, 143, 0.1);
        }
        .btn {
            width: 100%; padding: 12px; background: #f2b705;
            color: #1b1b1b; border: none; border-radius: 8px;
            font-size: 16px; font-weight: 700; cursor: pointer; transition: all 0.3s;
        }
        .btn:hover { background: #d9a404; transform: translateY(-2px); }
        .alert { padding: 12px; border-radius: 8px; margin-bottom: 20px; display: none; }
        .alert-error { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <i>ظل</i>
            <h1>ظل الجنوب | لوحة التحكم</h1>
        </div>
        
        <div id="errorAlert" class="alert alert-error"></div>
        
        <!-- تعديل مسار النموذج -->
     <form id="loginForm" action="login_process.php" method="POST">
            <div class="form-group">
                <label for="username">اسم المستخدم</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">تسجيل الدخول</button>
        </form>
    </div>

    <script>
        // AJAX بديل إذا أحببت
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const errorAlert = document.getElementById('errorAlert');
            errorAlert.style.display = 'none';

            fetch('login_process.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'index.php';
                } else {
                    errorAlert.textContent = data.message;
                    errorAlert.style.display = 'block';
                }
            })
            .catch(error => {
                errorAlert.textContent = 'حدث خطأ في الاتصال بالخادم';
                errorAlert.style.display = 'block';
            });
        });
    </script>
</body>
</html>
