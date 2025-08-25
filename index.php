<!-- index.php --><?php
require_once 'includes/config.php';

try {
    // جلب المنتجات النشطة
    $stmt = $pdo->query("SELECT * FROM products WHERE is_active = true ORDER BY created_at DESC");
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Index page error: " . $e->getMessage());
    $products = [];
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>سواتر ومظلات ظل الجنوب | تركيب مظلات وسواتر وهناجر بالرياض</title>
    <meta name="description" content="متخصصون في تركيب المظلات والسواتر والهناجر الساندوتش بانل في الرياض، مظلات سيارات وحدائق، سواتر فلل ومدارس، بيوت شعر، عشب صناعي، شبوك، برجولات وتصميم حدائق." />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root{
            --brand:#1b6b8f; /* أزرق بترولي */
            --brand-2:#0f3a4d; /* أزرق غامق */
            --accent:#f2b705a2;  /* ذهبي */
            --accent-dark:#d9a404; /* ذهبي غامق */
            --bg:#f7f9fb; 
            --text:#1a1a1a;
            --muted:#6e7681;
            --card:#ffffff;
            --radius:14px;
            --shadow:0 10px 25px rgba(0,0,0,.08);
            --transition: all 0.3s ease;
        }
        *{box-sizing:border-box}
        html,body{margin:0;padding:0;font-family:'Cairo',system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;color:var(--text);background:var(--bg);scroll-behavior:smooth}
        a{text-decoration:none;color:inherit}
        img{max-width:100%;display:block}
        h1, h2, h3, h4, h5, h6 {color: var(--brand-2);}
        
        /* Header */
        header{
            position:sticky;top:0;z-index:40;background:rgba(255,255,255,.95);backdrop-filter:saturate(150%) blur(10px);
            border-bottom:1px solid rgba(15,58,77,.06); transition: var(--transition);
            margin-bottom: 0;
            padding: 10px 20px;
        }
        .header-scrolled {
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .nav{
            max-width:1200px;margin:auto;display:flex;align-items:center;justify-content:space-between;padding:14px 20px;
        }
        .logo{display:flex;align-items:center;gap:10px;font-weight:800;color:var(--brand); height: 50px;}
        .logo i{width:34px;height:34px;border-radius:8px;background:linear-gradient(135deg,var(--brand),var(--brand-2));display:inline-flex;align-items:center;justify-content:center;}
        .logo i::before {content: "ظل"; color: white; font-weight: bold;}
        
        /* قائمة التنقل الأساسية */
        nav ul{display:flex;gap:12px;list-style:none;margin:0;padding:0;align-items:center;height: 50px;}
        nav a{position:relative;padding:8px 12px;border-radius:8px;display: flex;flex-direction: column;align-items: center;gap:4px;transition: var(--transition);}
        nav a:hover{background:#eef5f8; color: var(--brand);}
        nav a i {font-size: 18px;}
        nav a span {font-size: 12px;}
        
        .cta{padding:10px 16px;background:var(--accent);color:#1b1b1b;border-radius:10px;box-shadow:var(--shadow);display: flex;align-items: center;gap: 8px;font-weight: 700;transition: var(--transition);}
        .cta:hover{background:var(--accent-dark); transform: translateY(-2px);}
        
        /* قائمة الهاتف - هامبرجر */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--brand);
            backdrop-filter: blur(40px);
        }
        
        /* Hero مع فيديو خلفي */
        .hero{
            isolation:isolate;min-height:85vh;display:grid;place-items:center;text-align:center;
            color:#fff;padding:100px 16px 80px;overflow: hidden;position: relative;
            margin-top: 0;
            background: linear-gradient(to bottom, rgba(27, 107, 143, 0.1), rgba(15, 58, 77, 0.5)), 
                    url('m8.jpg') no-repeat center center / cover;
            backdrop-filter: blur(40px); /* تأثير الزجاج */
        }
       
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1); /* طبقة شفافة */
            z-index: -1;
        }
        .hero-content {
            max-width: 900px;
            z-index: 1;
            color: #fff;
            padding: 20px;
        }
        .hero h1{font-size:clamp(28px,4vw,46px);margin:0 0 12px;color:rgba(255,255,255,0.9); line-height: 1.3;}
        .hero p{max-width:800px;margin:0 auto 22px;color:rgba(255,255,255,0.9); font-size: 18px;}
        .hero .actions{display:flex;gap:16px;justify-content:center;flex-wrap:wrap; margin-top: 30px;}
        .btn{
            padding:14px 28px;border-radius:12px;border:1px solid rgba(0,0,0,.1);color:var(--text);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            font-weight: 700;
            transition: var(--transition);
        }
        .btn.primary{background:var(--accent);border-color:var(--accent);color:#1b1b1b;font-weight:800}
        .btn.primary:hover{background:var(--accent-dark); transform: translateY(-3px);}
        .btn:hover {transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.15);}
        .trust{display:flex;gap:24px;justify-content:center;margin-top:28px;font-size:14px;color:rgba(255,255,255,0.8);flex-wrap:wrap}
        .trust-item {display: flex; align-items: center; gap: 8px;}
        .trust-item i {color: var(--accent);}
        
        /* Section shell */
        section{max-width:1200px;margin:70px auto;padding:0 16px}
        .section-title {text-align: center; margin-bottom: 50px;}
        .section-title h2 {font-size: 36px; position: relative; display: inline-block; margin-bottom: 15px;}
        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            right: 50%;
            transform: translateX(50%);
            width: 80px;
            height: 4px;
            background: var(--accent);
            border-radius: 2px;
        }
        .section-title p {max-width: 700px; margin: 0 auto; color: var(--muted); font-size: 18px;}
        
        .sec-head{display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:28px;gap:10px;flex-wrap: wrap;}
        .sec-head h2{margin:0;color:var(--brand-2);font-size:clamp(22px,2.6vw,30px)}
        .muted{color:var(--muted);font-size:15px}
        
        /* Filters */
        .filters{
            background:var(--card);padding:18px;border-radius:var(--radius);box-shadow:var(--shadow);display:grid;grid-template-columns:1fr;gap:12px; margin-bottom: 30px;
        }
        @media (min-width: 768px) {
            .filters {
                grid-template-columns:1.2fr 1fr 1fr .8fr;
            }
        }
        .filters input,.filters select{
            width:100%;padding:12px 14px;border-radius:10px;border:1px solid #e5eaee;background:#fff; transition: var(--transition);
        }
        .filters input:focus, .filters select:focus {
            border-color: var(--brand);
            outline: none;
            box-shadow: 0 0 0 3px rgba(27, 107, 143, 0.1);
        }
        .filters .reset{background:#fff;border:1px dashed #dcdcdc; cursor: pointer; padding: 12px; border-radius: 10px; transition: var(--transition);}
        .filters .reset:hover {border-color: var(--brand); color: var(--brand);}
        
        /* Products grid */
        .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px}
        .card{
            background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;display:flex;flex-direction:column;
            transition: var(--transition);
        }
        .card:hover {transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.12);}
        .card .thumb{aspect-ratio:4/3;background:#dfe9ef;position:relative;overflow:hidden}
        .card .thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .card:hover .thumb img {
            transform: scale(1.08);
        }
        .badge{
            position:absolute;top:12px;right:12px;background:var(--accent);color:#1b1b1b;padding:6px 12px;border-radius:999px;font-size:12px; font-weight: 700;
        }
        .card .body{padding:18px}
        .card h3{margin:0 0 10px;font-size:20px; color: var(--brand-2);}
        .meta{display:flex;justify-content:space-between;align-items:center;font-size:14px;color:var(--muted); flex-wrap: wrap; margin-bottom: 10px;}
        .price{color:var(--brand-2);font-weight:800; font-size: 18px;}
        .tags{display:flex;gap:6px;flex-wrap:wrap;margin-top:12px}
        .tag{font-size:12px;background:#eef5f8;color:#16506a;padding:5px 10px;border-radius:999px}
        .card .actions{display:flex;gap:10px;margin:15px 18px 18px}
        /* التعديلات على الأزرار لجعلها أصغر */
        .btn-ghost{flex:1;text-align:center;padding:10px 12px;border-radius:8px;border:1px solid #e5eaee;font-size:14px;transition: all 0.3s ease; font-weight: 600;}
        .btn-ghost:hover{border-color:var(--brand);color:var(--brand);transform: translateY(-2px); box-shadow: 0 5px 10px rgba(0,0,0,0.05);}
        .btn-solid{flex:1;text-align:center;padding:10px 12px;border-radius:8px;background:var(--brand);color:#fff;font-size:14px;transition: all 0.3s ease; font-weight: 600;}
        .btn-solid:hover{background:var(--brand-2);transform: translateY(-2px); box-shadow: 0 5px 10px rgba(0,0,0,0.1);}
        
        /* Services */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-top: 40px;
        }
        .service-card {
            background: var(--card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 25px;
            text-align: center;
            transition: var(--transition);
            border-bottom: 4px solid transparent;
        }
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            border-bottom: 4px solid var(--accent);
        }
        .service-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin: 0 auto 20px;
        }
        .service-card h3 {
            color: var(--brand-2);
            margin-bottom: 15px;
            font-size: 22px;
        }
        .service-card p {
            color: var(--muted);
            line-height: 1.6;
        }
        
        /* Gallery */
        .masonry{columns:1;column-gap:16px}
        @media (min-width:680px){.masonry{columns:2}}
        @media (min-width:1024px){.masonry{columns:3}}
        .masonry img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 12px;
            margin: 0 0 16px;
            cursor: pointer;
            transition: var(--transition);
        }
        .masonry img:hover{transform: scale(1.03);}

        /* Lightbox */
        .lightbox{position:fixed;inset:0;background:rgba(0,0,0,.9);display:none;place-items:center;z-index:100; padding: 20px;}
        .lightbox img{max-width:90vw;max-height:80vh;border-radius:12px; object-fit: contain;}
        .lightbox.closeable{display:grid}
        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 20px;
            color: white;
            font-size: 30px;
            cursor: pointer;
            background: rgba(0,0,0,0.5);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Contact */
        .contact{
            display:grid;grid-template-columns:1fr;gap:24px
        }
        @media (min-width:900px){
            .contact{grid-template-columns:1.2fr .8fr}
        }
        form{background:var(--card);padding:25px;border-radius:var(--radius);box-shadow:var(--shadow);display:grid;gap:15px}
        input,textarea,select{padding:14px;border-radius:10px;border:1px solid #e5eaee; transition: var(--transition); font-family: inherit;}
        input:focus, textarea:focus, select:focus {
            border-color: var(--brand);
            outline: none;
            box-shadow: 0 0 0 3px rgba(27, 107, 143, 0.1);
        }
        textarea{min-height:140px;resize:vertical}
        .submit{background:var(--accent);color:#1b1b1b;border:none;font-weight:800;padding:16px;font-size:18px; cursor: pointer; transition: var(--transition); border-radius: 10px;}
        .submit:hover{background:var(--accent-dark);}
        .panel{background:var(--card);padding:25px;border-radius:var(--radius);box-shadow:var(--shadow);display:grid;gap:15px}
        .whatsapp{
            display:inline-flex;align-items:center;gap:8px;background:#25D366;color:#fff;padding:12px 18px;border-radius:10px;font-weight:700; transition: var(--transition); justify-content: center;
        }
        .whatsapp:hover {background: #128C7E; transform: translateY(-2px);}
        .contact-info {display: flex; flex-direction: column; gap: 15px;}
        .contact-item {display: flex; align-items: center; gap: 12px;}
        .contact-item i {width: 40px; height: 40px; background: var(--bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--brand);}
        
        .strip{
            max-width:1200px;margin:50px auto;padding:20px 25px;background:linear-gradient(90deg,var(--brand),var(--brand-2));color:#fff;border-radius:12px;display:flex;gap:15px;justify-content:space-between;align-items:center; flex-wrap: wrap;
            box-shadow: var(--shadow);
        }
        .strip h3 {margin: 0; color: white; font-size: 24px;}
        .strip .cta {margin-left: auto;}
        
        /* Testimonials */
        .testimonials {
            background: var(--bg);
            padding: 60px 0;
            border-radius: var(--radius);
            margin: 70px auto;
        }
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-top: 40px;
        }
        .testimonial-card {
            background: var(--card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 25px;
            position: relative;
        }
        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 60px;
            color: var(--bg);
            font-family: serif;
            line-height: 1;
        }
        .testimonial-text {
            color: var(--text);
            line-height: 1.7;
            margin-bottom: 20px;
        }
        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--brand);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 20px;
        }
        .author-details h4 {
            margin: 0;
            color: var(--brand-2);
        }
        .author-details p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
        }
        
        footer{max-width:1200px;margin:70px auto 20px;padding:0 16px;color:var(--muted);display:flex;flex-wrap:wrap;gap:15px;justify-content:space-between; border-top: 1px solid #e5eaee; padding-top: 30px;}
        .footer-links {display: flex; gap: 20px;}
        .footer-links a {transition: var(--transition);}
        .footer-links a:hover {color: var(--brand);}
        
        /* تحسينات للاستجابة على الهواتف */
        @media (max-width: 768px) {
            .nav {
                flex-direction: row;
                gap: 15px;
                padding: 10px;
                align-items: center;
                justify-content: space-between;
            }
            
            /* إخفاء القائمة العادية وإظهار زر الهامبرجر */
            nav ul {
                display: none;
                position: absolute;
                top: 100%;
                right: 0;
                background: white;
                width: 100%;
                flex-direction: column;
                padding: 20px;
                box-shadow: 0 10px 20px rgba(0,0,0,0.1);
                height: auto;
                z-index: 100;
            }
            
            nav ul.active {
                display: flex;
            }
            
            .mobile-toggle {
                display: block;
            }
            
            /* تحسين شكل عناصر القائمة في الوضع المتنقل */
            nav a {
                width: 100%;
                flex-direction: row;
                justify-content: flex-start;
                padding: 12px 15px;
                border-radius: 8px;
            }
            
            nav a i {
                margin-left: 10px;
                font-size: 20px;
                width: 24px;
                text-align: center;
            }
            
            .logo {
                height: 40px;
                font-size: 16px;
            }
            
            .logo i {
                width: 30px;
                height: 30px;
            }
            
            .hero {
                min-height: 70vh;
                padding: 80px 16px 60px;
            }
            .hero .actions {
                flex-direction: column;
                align-items: center;
            }
            .hero .actions .btn {
                width: 100%;
                max-width: 280px;
            }
            .card .actions {
                flex-direction: column;
            }
            .strip {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            .strip .cta {
                margin-left: 0;
            }
            .section-title h2 {
                font-size: 28px;
            }
            .services-grid,
            .testimonials-grid {
                grid-template-columns: 1fr;
            }
            footer {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            .footer-links {
                justify-content: center;
            }
        }
        
        /* تحسينات للشاشات المتوسطة */
        @media (min-width: 769px) and (max-width: 1024px) {
            nav ul {
                gap: 10px;
            }
            
            nav a {
                padding: 8px;
                font-size: 14px;
            }
            
            nav a i {
                font-size: 16px;
            }
        }

        /* تحسين الحشو والهوامش في المحتوى */
        * {
            margin: 0;
            padding: 0;
        }

        /* تحسين الحشو والهوامش لعناصر محددة */
        header {
            margin-bottom: 0;
            padding: 10px 20px;
        }

        .hero {
            margin-top: 0;
            padding: 20px;
        }

        nav ul {
            margin: 0;
            padding: 0;
        }

        nav a {
            padding: 10px 15px;
        }

        .cta {
            padding: 12px 18px;
        }

        .hero-content {
            padding: 20px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header id="header">
        <div class="nav">
            <div class="logo"><i></i> <span>الجنوب | سواتر ومظلات</span></div>
            
            <nav>
                <ul>
                    <li><a href="#home"><i class="fas fa-home"></i> <span>الرئيسية</span></a></li>
                    <li><a href="#products"><i class="fas fa-th-large"></i> <span>المنتجات</span></a></li>
                    <li><a href="#services"><i class="fas fa-cogs"></i> <span>الخدمات</span></a></li>
                    <li><a href="#gallery"><i class="fas fa-images"></i> <span>أعمالنا</span></a></li>
                    <li><a href="#testimonials"><i class="fas fa-star"></i> <span>آراء العملاء</span></a></li>
                    <li><a href="#contact"><i class="fas fa-phone"></i> <span>تواصل</span></a></li>
                </ul>
                <button class="mobile-toggle">
                    <i class="fas fa-bars"></i>
                </button>
            </nav>
        </div>
    </header>

    <!-- Hero -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>ظل الجنوب لـ تركيب المظلات والسواتر والهناجر الساندوتش بانل في الرياض</h1>
            <p>نقدم حلولاً متكاملة من مظلات السيارات والحدائق، سواتر الفلل والمدارس، بيوت الشعر، العشب الصناعي، الشبوك، البرجولات وتصميم الحدائق بخبرة وثقة وجودة عالية</p>
            
            <div class="actions">
                <a class="btn primary" href="#products">تصفّح منتجاتنا</a>
                <a class="btn" href="#contact">اطلب استشارة مجانية</a>
            </div>
            
            <div class="trust">
                <div class="trust-item"><i class="fas fa-check-circle"></i> <span>ضمان يصل إلى 5 سنوات</span></div>
                <div class="trust-item"><i class="fas fa-check-circle"></i> <span>تركيب محترف</span></div>
                <div class="trust-item"><i class="fas fa-check-circle"></i> <span>أسعار تنافسية</span></div>
            </div>
        </div>
    </section>

    <!-- Services Overview -->
    <section class="services-overview">
        <div class="section-title">
            <h2>خدماتنا المتكاملة</h2>
            <p>نوفر حلولاً متكاملة لتلبية جميع احتياجاتك من المظلات والسواتر والهناجر</p>
        </div>
        
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-car"></i>
                </div>
                <h3>مظلات سيارات</h3>
                <p>تصميم وتركيب مظلات سيارات متينة بمقاسات مختلفة تناسب جميع المساحات، مصنوعة من أفضل الخامات المقاومة للعوامل الجوية.</p>
            </div>
            
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-home"></i>
                </div>
                <h3>سواتر فلل ومدارس</h3>
                <p>تركيب سواتر عالية الجودة للفلل والمدارس والمنشآت المختلفة، توفر الخصوصية والحماية مع مظهر جمالي راقي.</p>
            </div>
            
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-campground"></i>
                </div>
                <h3>بيوت شعر</h3>
                <p>تصميم وتنفيذ بيوت الشعر العربية الأصيلة بمختلف المقاسات والأشكال، تناسب المناسبات والأماكن المفتوحة.</p>
            </div>
            
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-tree"></i>
                </div>
                <h3>عشب صناعي</h3>
                <p>تركيب عشب صناعي عالي الجودة للمساحات الخارجية والداخلية، يضيف لمسة جمالية خضراء دائمة بدون متاعب الصيانة.</p>
            </div>
            
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>شبوك وأسوار</h3>
                <p>تصنيع وتركيب شبوك وأسوار بأعلى معايير الجودة والأمان، تناسب المزارع والمصانع والمنشآت الحكومية والخاصة.</p>
            </div>
            
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <h3>برجولات وتصميم حدائق</h3>
                <p>تصميم وتنفيذ برجولات خشبية ومعدنية مع تصميم حدائق متكاملة لإنشاء مساحات خارجية مريحة وجميلة.</p>
            </div>
        </div>
    </section>

    <!-- Products -->
    <section id="products">
        <div class="section-title">
            <h2>منتجاتنا</h2>
            <p>استعرض مجموعة منتجاتنا المتميزة من السواتر والمظلات بمختلف أنواعها</p>
        </div>

        <div class="filters" id="filters">
            <input id="q" type="search" placeholder="ابحث: سواتر رول، مظلة سيارات، لون، خامة…" />
            <select id="cat">
                <option value="">كل الأقسام</option>
                <option value="curtain">سواتر</option>
                <option value="canopy">مظلات</option>
                <option value="hanger">هناجر</option>
                <option value="other">منتجات أخرى</option>
            </select>
            <select id="type">
                <option value="">كل الأنواع</option>
                <option value="roll">رول</option>
                <option value="fabric">فلل - مدارس</option>
                <option value="parking">سيارات</option>
                <option value="garden">حدائق</option>
                <option value="sandwich">ساندوتش بانل</option>
                <option value="sandwich">برجولات</option>
            </select>
            <button class="reset" id="reset">إعادة تعيين</button>
        </div>

        <div class="grid" id="grid">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                <article class="card" data-cat="<?= htmlspecialchars($product['category']) ?>" data-type="<?= htmlspecialchars($product['type']) ?>" data-tags="<?= htmlspecialchars($product['tags']) ?>">
                    <div class="thumb">
                        <img src="admin/<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name_ar']) ?>">
                        <span class="badge"><?= htmlspecialchars($product['badge_text']) ?></span>
                    </div>
                    <div class="body">
                        <h3><?= htmlspecialchars($product['name_ar']) ?></h3>
                        <div class="meta"><span class="price"><?= htmlspecialchars($product['price']) ?></span></div>
                        <div class="tags">
                            <?php
                            $tags = explode(',', $product['tags']);
                            foreach ($tags as $tag):
                            ?>
                            <span class="tag"><?= htmlspecialchars(trim($tag)) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="actions">
                        <a class="btn-ghost" href="#contact">عرض سعر</a>
                        <a class="btn-solid" target="_blank" rel="noopener" 
                           href="https://wa.me/966537522808?text=<?= urlencode($product['whatsapp_message']) ?>">
                           اطلب بالواتساب
                        </a>
                    </div>
                </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; grid-column: 1 / -1; padding: 40px; color: var(--muted);">
                    لا توجد منتجات للعرض حالياً.
                </p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Gallery -->
    <section id="gallery">
        <div class="section-title">
            <h2>معرض أعمالنا</h2>
            <p>استعرض بعضاً من مشاريعنا المنفذة في الرياض والمناطق المجاورة</p>
        </div>

        <div class="masonry">
            <img src="m.jpg" alt="مشروع سواتر" onclick="openLightbox(this)" />
            <img src="m1.jpg" alt="مشروع مظلات" onclick="openLightbox(this)" />
            <img src="m2.jpg" alt="هناجر ساندوتش بانل" onclick="openLightbox(this)" />
            <img src="m3.jpg" alt="بيوت شعر" onclick="openLightbox(this)" />
            <img src="m4.jpg" alt="عشب صناعي" onclick="openLightbox(this)" />
            <img src="m5.jpg" alt="برجولات" onclick="openLightbox(this)" />
        </div>
    </section>

    <!-- Lightbox -->
    <div class="lightbox" id="lightbox" onclick="closeLightbox()">
        <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
        <img id="lightbox-img" src="" alt="" />
    </div>

    <!-- Testimonials -->
    <section id="testimonials" class="testimonials">
        <div class="section-title">
            <h2>آراء عملائنا</h2>
            <p>ما يقوله عملاؤنا عن جودة خدماتنا واحترافية فريق العمل</p>
        </div>
        
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <p class="testimonial-text">تعاملت مع شركة ظل الجنوب لتركيب سواتر للفيلا، وكانت تجربة ممتازة من حيث الاحترافية في التنفيذ والالتزام بالمواعيد والأسعار المعقولة. أنصح بالتعامل معهم.</p>
                <div class="testimonial-author">
                    <div class="author-avatar">م</div>
                    <div class="author-details">
                        <h4>محمد السليم</h4>
                        <p>عميل - سواتر فلل</p>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <p class="testimonial-text">فريق عمل محترف ومنظم، قاموا بتركيب مظلة للسيارات في منزلي وكان العمل متقناً وفي الوقت المتفق عليه. الأسعار مناسبة والجودة ممتازة.</p>
                <div class="testimonial-author">
                    <div class="author-avatar">س</div>
                    <div class="author-details">
                        <h4>سارة العتيبي</h4>
                        <p>عميلة - مظلات سيارات</p>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <p class="testimonial-text">قمت بطلب هناجر ساندوتش بانل للمصنع، وكانت النتيجة رائعة. المواد عالية الجودة والتنفيذ كان بإشراف مهندسين متخصصين. شكراً لظل الجنوب على الجودة والاحترافية.</p>
                <div class="testimonial-author">
                    <div class="author-avatar">خ</div>
                    <div class="author-details">
                        <h4>خالد الرشيد</h4>
                        <p>صاحب مصنع - هناجر</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section id="contact">
        <div class="section-title">
            <h2>تواصل معنا</h2>
            <p>نحن هنا لمساعدتك في اختيار الحل الأمثل لاحتياجاتك وتقديم استشارة مجانية</p>
        </div>

        <div class="contact">
            <form id="contact-form">
                <h3>اطلب استشارة مجانية</h3>
                <input type="text" placeholder="الاسم بالكامل" required />
                <input type="tel" placeholder="رقم الجوال" required />
                <select>
                    <option value="" disabled selected>نوع الخدمة المطلوبة</option>
                    <option>مظلات سيارات</option>
                    <option>مظلات حدائق</option>
                    <option>سواتر فلل</option>
                    <option>سواتر مدارس</option>
                    <option>هناجر ساندوتش بانل</option>
                    <option>بيوت شعر</option>
                    <option>عشب صناعي</option>
                    <option>شبوك وأسوار</option>
                    <option>برجولات وتصميم حدائق</option>
                    <option>استشارة أخرى</option>
                </select>
                <textarea placeholder="تفاصيل الطلب (اختياري)"></textarea>
                <button type="submit" class="submit">إرسال الطلب</button>
            </form>

            <div class="panel">
                <h3>طرق التواصل</h3>
                <p>يمكنك التواصل معنا عبر أي من الطرق التالية:</p>
                
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <strong>الهاتف</strong>
                            <p> 2808 752 53 966+</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <strong>البريد الإلكتروني</strong>
                            <p>info@example.com</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong>الموقع</strong>
                            <p>الرياض، المملكة العربية السعودية</p>
                        </div>
                    </div>
                </div>
                
                <a class="whatsapp" target="_blank" rel="noopener" href="https://wa.me/966537522808">
                    <i class="fab fa-whatsapp"></i> تواصل معنا عبر واتساب
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div>© 2025  سواتر ومظلات ظل الجنوب. جميع الحقوق محفوظة.</div>
        <a href="https://wa.me/967711447801">  <div>© الحناني للبرمجيــــــــات 00967 711447801</div></a>
        <div class="footer-links">
        
            <!-- <a href="#products">المنتجات</a>
            <a href="#services">الخدمات</a>
            <a href="#gallery">أعمالنا</a>
            <a href="#contact">تواصل</a> -->
        </div>
    </footer>

    <script>
        // فلترة المنتجات
        const filters = document.querySelectorAll('#filters input, #filters select');
        const grid = document.getElementById('grid');
        const cards = grid.querySelectorAll('.card');
        const reset = document.getElementById('reset');

        function filterProducts() {
            const q = document.getElementById('q').value.toLowerCase();
            const cat = document.getElementById('cat').value;
            const type = document.getElementById('type').value;

            cards.forEach(card => {
                const cardCat = card.getAttribute('data-cat');
                const cardType = card.getAttribute('data-type');
                const cardTags = card.getAttribute('data-tags').toLowerCase();
                const title = card.querySelector('h3').textContent.toLowerCase();

                const matchesQ = q === '' || title.includes(q) || cardTags.includes(q);
                const matchesCat = cat === '' || cardCat === cat;
                const matchesType = type === '' || cardType === type;

                if (matchesQ && matchesCat && matchesType) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        filters.forEach(filter => {
            filter.addEventListener('input', filterProducts);
            filter.addEventListener('change', filterProducts);
        });

        reset.addEventListener('click', () => {
            document.getElementById('q').value = '';
            document.getElementById('cat').value = '';
            document.getElementById('type').value = '';
            filterProducts();
        });

        // Lightbox
        function openLightbox(el) {
            const lightbox = document.getElementById('lightbox');
            const lightboxImg = document.getElementById('lightbox-img');
            lightboxImg.src = el.src;
            lightbox.classList.add('closeable');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            const lightbox = document.getElementById('lightbox');
            lightbox.classList.remove('closeable');
            document.body.style.overflow = 'auto';
        }

        // Form submission
        document.getElementById('contact-form').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('شكراً لتواصلكم، سنعود إليكم في أقرب وقت!');
            this.reset();
        });

        // Header scroll effect
        const header = document.getElementById('header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('header-scrolled');
            } else {
                header.classList.remove('header-scrolled');
            }
        });

        // Mobile menu toggle
        const mobileToggle = document.querySelector('.mobile-toggle');
        const navMenu = document.querySelector('nav ul');
        
        mobileToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('nav') && !e.target.closest('.mobile-toggle')) {
                navMenu.classList.remove('active');
            }
        });
    </script>
</body>
</html>