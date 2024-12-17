<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PTCISGMS - Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-dark: #1d4ed8;
            --secondary-color: #6366f1;
            --background-color: #f1f5f9;
            --text-color: #0f172a;
            --text-muted: #64748b;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1);
            --gradient-primary: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            --gradient-secondary: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.7;
            overflow-x: hidden;
        }

        .container-fluid {
            padding: 3rem;
            max-width: 1600px;
            margin: 0 auto;
        }

        /* Glass morphism effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .content-section {
            background: var(--card-bg);
            padding: 3rem;
            border-radius: 1.5rem;
            box-shadow: var(--shadow-lg);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .content-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--gradient-primary);
        }

        .feature-box {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 1.25rem;
            box-shadow: var(--shadow-md);
            height: 100%;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .feature-box::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 0;
            background: var(--gradient-secondary);
            opacity: 0.1;
            transition: height 0.4s ease;
        }

        .feature-box:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .feature-box:hover::after {
            height: 100%;
        }

        .feature-box i {
            font-size: 3rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
        }

        .feature-box h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-color);
            position: relative;
            z-index: 1;
        }

        .feature-box p {
            color: var(--text-muted);
            font-size: 1.1rem;
            position: relative;
            z-index: 1;
        }

        .login-section {
            background: var(--gradient-primary);
            padding: 3rem;
            border-radius: 1.5rem;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .login-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" stroke="white" stroke-width="2" fill="none" opacity="0.1"/></svg>') repeat;
            opacity: 0.1;
        }

        .login-container {
            background: var(--card-bg);
            padding: 2.5rem;
            border-radius: 1.25rem;
            box-shadow: var(--shadow-lg);
            position: relative;
            z-index: 1;
        }

        .login-container h2 {
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 2rem;
            text-align: center;
            font-size: 2rem;
        }

        .form-control {
            padding: 1rem 1.25rem;
            border-radius: 0.75rem;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
            font-size: 1rem;
            background-color: #f8fafc;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            background-color: white;
        }

        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            padding: 1rem 2rem;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 8px -1px rgba(59, 130, 246, 0.3);
        }

        .display-4 {
            font-weight: 800;
            color: var(--text-color);
            margin-bottom: 1.5rem;
            font-size: 3.5rem;
            line-height: 1.2;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .lead {
            color: var(--text-muted);
            font-size: 1.35rem;
            margin-bottom: 3rem;
            font-weight: 400;
            line-height: 1.6;
        }

        .form-floating {
            margin-bottom: 1.5rem;
        }

        .form-floating label {
            padding: 1rem 1.25rem;
            color: var(--text-muted);
        }

        .form-select {
            padding: 1rem 1.25rem;
            border-radius: 0.75rem;
            border: 2px solid var(--border-color);
            font-size: 1rem;
            background-color: #f8fafc;
        }

        /* Comprehensive Responsive Design */
        @media (min-width: 2560px) {
            /* 4K+ Screens */
            .container-fluid {
                max-width: 2000px;
                padding: 4rem;
            }
            .display-4 {
                font-size: 4.5rem;
            }
            .lead {
                font-size: 1.5rem;
            }
            .feature-box {
                padding: 3rem;
            }
            .feature-box i {
                font-size: 4rem;
            }
        }

        @media (max-width: 1440px) {
            /* Large Laptops */
            .container-fluid {
                max-width: 1280px;
                padding: 2.5rem;
            }
            .display-4 {
                font-size: 3rem;
            }
            .feature-box {
                padding: 1.75rem;
            }
        }

        @media (max-width: 1200px) {
            /* Small Laptops */
            .container-fluid {
                padding: 2rem;
            }
            .content-section,
            .login-section {
                padding: 2rem;
            }
            .display-4 {
                font-size: 2.75rem;
            }
            .lead {
                font-size: 1.2rem;
                margin-bottom: 2rem;
            }
        }

        @media (max-width: 992px) {
            /* Tablets */
            .container-fluid {
                padding: 1.5rem;
            }
            .row {
                flex-direction: column;
            }
            .content-section,
            .login-section {
                margin-bottom: 2rem;
            }
            .feature-box {
                margin-bottom: 1.5rem;
                min-height: auto;
            }
            .col-md-8,
            .col-md-4 {
                width: 100%;
                padding: 0;
            }
            .display-4 {
                font-size: 2.5rem;
                text-align: center;
            }
            .lead {
                text-align: center;
            }
            .features .row {
                margin: 0 -0.75rem;
            }
            .features .col-md-4 {
                padding: 0 0.75rem;
            }
        }

        @media (max-width: 768px) {
            /* Large Phones */
            .container-fluid {
                padding: 1rem;
            }
            .content-section,
            .login-section {
                padding: 1.5rem;
                border-radius: 1rem;
            }
            .feature-box {
                padding: 1.5rem;
                margin-bottom: 1rem;
            }
            .login-container {
                padding: 1.5rem;
            }
            .btn-primary {
                width: 100%;
                padding: 0.875rem;
            }
            .display-4 {
                font-size: 2.25rem;
            }
            .lead {
                font-size: 1.1rem;
            }
            .form-control,
            .form-select {
                font-size: 16px; /* Prevent zoom on iOS */
            }
        }

        @media (max-width: 576px) {
            /* Small Phones */
            .container-fluid {
                padding: 0.75rem;
            }
            .content-section,
            .login-section {
                padding: 1.25rem;
                border-radius: 0.75rem;
            }
            .feature-box {
                padding: 1.25rem;
            }
            .feature-box i {
                font-size: 2.5rem;
            }
            .display-4 {
                font-size: 2rem;
                margin-bottom: 1rem;
            }
            .lead {
                font-size: 1rem;
                margin-bottom: 1.5rem;
            }
            .form-floating {
                margin-bottom: 1rem;
            }
            .form-control,
            .form-select {
                padding: 0.75rem 1rem;
            }
            .btn-primary {
                padding: 0.75rem;
                font-size: 1rem;
            }
        }

        /* Touch Device Optimizations */
        @media (hover: none) {
            .feature-box:hover {
                transform: none;
            }
            .btn-primary:hover {
                transform: none;
            }
            .feature-box,
            .btn-primary {
                transition: none;
            }
        }

        /* Landscape Mode Optimizations */
        @media (max-height: 600px) and (orientation: landscape) {
            .container-fluid {
                min-height: auto;
                padding-top: 1rem;
                padding-bottom: 1rem;
            }
            .row.min-vh-100 {
                min-height: auto !important;
            }
            .feature-box {
                min-height: auto;
                margin-bottom: 1rem;
            }
            .login-section {
                height: auto;
            }
        }

        /* High Contrast Mode */
        @media (prefers-contrast: more) {
            :root {
                --primary-color: #0056b3;
                --text-muted: #595959;
                --border-color: #404040;
            }
            .feature-box,
            .login-container {
                border: 2px solid var(--border-color);
            }
        }

        /* Print Styles */
        @media print {
            .container-fluid {
                padding: 0;
            }
            .feature-box,
            .login-section {
                break-inside: avoid;
            }
            .loading-bar,
            .btn-primary:hover {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="loading-bar"></div>
    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Content Section (Left Side) -->
            <div class="col-lg-8 content-section p-4">
                <div class="content-container">
                    <h1 class="display-4 mb-4" data-aos="fade-up">Welcome to PTCISGMS</h1>
                    <p class="lead" data-aos="fade-up" data-aos-delay="100">Polytechnic College Information System and Grade Management System</p>
                    <div class="features mt-5">
                        <div class="row g-4">
                            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                                <div class="feature-box">
                                    <i class="fas fa-user-graduate mb-3"></i>
                                    <h3>Student Portal</h3>
                                    <p>Access your grades and academic information with ease</p>
                                </div>
                            </div>
                            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                                <div class="feature-box">
                                    <i class="fas fa-chalkboard-teacher mb-3"></i>
                                    <h3>Staff Portal</h3>
                                    <p>Efficiently manage classes and student records</p>
                                </div>
                            </div>
                            <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                                <div class="feature-box">
                                    <i class="fas fa-cog mb-3"></i>
                                    <h3>Admin Panel</h3>
                                    <p>Comprehensive system administration tools</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Login Section (Right Side) -->
            <div class="col-lg-4 login-section" data-aos="fade-left">
                <div class="login-container">
                    <h2 class="text-center mb-4">Welcome Back</h2>
                    <form action="login.php" method="POST" class="needs-validation" novalidate>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                            <label for="username">Username</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            <label for="password">Password</label>
                        </div>
                        <div class="form-floating mb-4">
                            <select class="form-select" id="userType" name="userType" required>
                                <option value="">Select user type</option>
                                <option value="student">Student</option>
                                <option value="staff">Staff</option>
                                <option value="admin">Admin</option>
                            </select>
                            <label for="userType">User Type</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Sign In</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Form validation
        (function() {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>
</html>