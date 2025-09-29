// فتح Modal لإضافة منتج
function openProductModal() {
    document.getElementById('modalTitle').textContent = 'إضافة منتج جديد';
    document.getElementById('productForm').reset();
    document.getElementById('productId').value = '';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('productModal').style.display = 'flex';
}

// إغلاق Modal
function closeModal() {
    document.getElementById('productModal').style.display = 'none';
}

// معاينة الصورة قبل الرفع
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('previewImg').src = event.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});

// إرسال نموذج المنتج
document.getElementById('productForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('../admin/save_product.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('تم حفظ المنتج بنجاح', 'success');
            closeModal();
            // إعادة تحميل الصفحة أو تحديث قائمة المنتجات
            setTimeout(() => { location.reload(); }, 1000);
        } else {
            showAlert('حدث خطأ أثناء حفظ المنتج: ' + data.message, 'error');
        }
    })
    .catch(error => {
        showAlert('حدث خطأ في الاتصال بالخادم', 'error');
    });
});

// تعديل منتج موجود
function editProduct(id) {
    fetch('../admin/get_product.php?id=' + id)
    .then(response => response.json())
    .then(product => {
        document.getElementById('modalTitle').textContent = 'تعديل المنتج';
        document.getElementById('productId').value = product.id;
        document.getElementById('name_ar').value = product.name_ar;
        document.getElementById('description_ar').value = product.description_ar;
        document.getElementById('category').value = product.category;
        document.getElementById('type').value = product.type;
        document.getElementById('price').value = product.price;
        document.getElementById('price_label').value = product.price_label;
        document.getElementById('warranty').value = product.warranty;
        document.getElementById('tags').value = product.tags;
        document.getElementById('badge_text').value = product.badge_text;
        document.getElementById('whatsapp_message').value = product.whatsapp_message;
        
        if (product.image_url) {
            document.getElementById('previewImg').src = '../' + product.image_url;
            document.getElementById('imagePreview').style.display = 'block';
        }
        
        document.getElementById('productModal').style.display = 'flex';
    })
    .catch(error => {
        showAlert('حدث خطأ في تحميل بيانات المنتج', 'error');
    });
}

// حذف منتج
function deleteProduct(id) {
    if (confirm('هل أنت متأكد من حذف هذا المنتج؟')) {
        fetch('../admin/delete_product.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('تم حذف المنتج بنجاح', 'success');
                // إزالة المنتج من القائمة دون إعادة تحميل الصفحة
                document.querySelector(`.product-card[data-id="${id}"]`).remove();
            } else {
                showAlert('حدث خطأ أثناء حذف المنتج', 'error');
            }
        })
        .catch(error => {
            showAlert('حدث خطأ في الاتصال بالخادم', 'error');
        });
    }
}

// تسجيل الخروج
function logout() {
    fetch('../admin/logout.php')
    .then(() => {
        window.location.href = 'login.php';
    });
}

// عرض رسائل التنبيه
function showAlert(message, type) {
    const alertBox = document.getElementById('alertBox');
    alertBox.textContent = message;
    alertBox.className = 'alert alert-' + type;
    alertBox.style.display = 'block';
    
    setTimeout(() => {
        alertBox.style.display = 'none';
    }, 5000);
}

// إغلاق Modal بالنقر خارج المحتوى
window.onclick = function(event) {
    const modal = document.getElementById('productModal');
    if (event.target === modal) {
        closeModal();
    }
}
