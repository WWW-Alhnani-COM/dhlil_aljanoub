RewriteEngine On

# توجيه جميع الطلبات إلى مجلد admin
RewriteCond %{REQUEST_URI} !^/admin/
RewriteRule ^(.*)$ admin/$1 [L]

# إذا كان الطلب للمجلد الرئيسي، توجيه إلى admin/login.php
RewriteRule ^$ admin/login.php [L]
