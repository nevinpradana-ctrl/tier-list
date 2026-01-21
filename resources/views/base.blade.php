<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Gacha Tier List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --warning-gradient: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%);
            --dark-gradient: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --shadow-glow: 0 0 40px rgba(102, 126, 234, 0.4);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--dark-gradient);
            min-height: 100vh;
            color: #ffffff;
            overflow-x: hidden;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 20%, rgba(102, 126, 234, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(240, 147, 251, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 40% 60%, rgba(69, 123, 157, 0.2) 0%, transparent 40%);
            animation: bgPulse 15s ease-in-out infinite;
            z-index: -1;
        }

        @keyframes bgPulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.1); }
        }

        /* Navbar */
        .navbar {
            background: rgba(26, 26, 46, 0.8) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 2rem;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            padding: 0.7rem 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.6rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 1px;
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 500;
            padding: 0.6rem 1.2rem !important;
            border-radius: 12px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--primary-gradient);
            transition: left 0.3s ease;
            z-index: -1;
            border-radius: 12px;
        }

        .nav-link:hover::before {
            left: 0;
        }

        .nav-link:hover {
            color: white !important;
        }

        .nav-link.active {
            background: var(--primary-gradient);
            color: white !important;
        }

        /* Sidebar */
        .sidebar {
            background: rgba(26, 26, 46, 0.95) !important;
            backdrop-filter: blur(20px);
            min-height: 100vh;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            position: sticky;
            top: 0;
            padding-top: 20px;
        }

        .sidebar .p-3 {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 15px;
        }

        .sidebar h5 {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .sidebar nav a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            border-radius: 12px;
            margin-bottom: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .sidebar nav a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--primary-gradient);
            opacity: 0.1;
            transition: left 0.3s ease;
        }

        .sidebar nav a:hover::before,
        .sidebar nav a.active::before {
            left: 0;
        }

        .sidebar nav a i {
            margin-right: 12px;
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .sidebar nav a:hover i {
            transform: scale(1.2);
        }

        .sidebar nav a:hover,
        .sidebar nav a.active {
            color: white;
        }

        .sidebar hr {
            border-color: rgba(255, 255, 255, 0.1);
        }

        /* Content Wrapper */
        .content-wrapper {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            margin: 25px;
            padding: 35px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .content-wrapper:hover {
            box-shadow: 0 30px 60px rgba(102, 126, 234, 0.2);
        }

        .content-wrapper h1 {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            margin-bottom: 30px;
            font-size: 2rem;
            letter-spacing: -0.5px;
        }

        .content-wrapper h2 {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            margin-top: 25px;
        }

        /* Buttons */
        .btn {
            font-weight: 600;
            border-radius: 12px;
            padding: 12px 24px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.85rem;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.5);
            color: white;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: white;
            color: white;
            transform: translateY(-3px);
        }

        .btn-danger {
            background: var(--secondary-gradient);
            border: none;
            box-shadow: 0 8px 25px rgba(245, 87, 108, 0.4);
        }

        .btn-danger:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(245, 87, 108, 0.5);
            color: white;
        }

        .btn-success {
            background: var(--success-gradient);
            border: none;
            box-shadow: 0 8px 25px rgba(17, 153, 142, 0.4);
        }

        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(17, 153, 142, 0.5);
            color: white;
        }

        .btn-warning {
            background: var(--warning-gradient);
            border: none;
            color: white;
        }

        .btn-warning:hover {
            transform: translateY(-3px);
            color: white;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.75rem;
        }

        .btn-info {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            border: none;
            color: white;
        }

        .btn-info:hover {
            transform: translateY(-3px);
            color: white;
        }

        /* Cards */
        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            transition: all 0.4s ease;
            overflow: hidden;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .card:hover::before {
            transform: scaleX(1);
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-glow);
            border-color: rgba(102, 126, 234, 0.3);
        }

        .card-body {
            padding: 24px;
        }

        .card-title {
            background: linear-gradient(135deg, #fff 0%, #e0e0e0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .card-text {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
        }

        /* Forms */
        .form-control,
        .form-select {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 14px;
            padding: 14px 18px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
            color: white;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-label {
            color: white;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }

        .form-text {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
        }

        .form-check-input {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
        }

        .form-check-input:checked {
            background: var(--primary-gradient);
            border-color: transparent;
        }

        /* Tables */
        .table {
            color: white;
            border-color: rgba(255, 255, 255, 0.1);
        }

        .table th {
            background: var(--dark-gradient);
            color: white;
            font-weight: 700;
            border-color: rgba(255, 255, 255, 0.1);
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
            padding: 16px;
        }

        .table td {
            border-color: rgba(255, 255, 255, 0.1);
            vertical-align: middle;
            padding: 16px;
            background: rgba(255, 255, 255, 0.02);
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(102, 126, 234, 0.1);
            transform: scale(1.01);
        }

        /* Badges */
        .badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .badge-ss { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
        .badge-s { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); }
        .badge-a { background: linear-gradient(135deg, #eab308 0%, #ca8a04 100%); color: black; }
        .badge-b { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
        .badge-c { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .badge-d { background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%); }

        /* Alerts */
        .alert {
            border-radius: 16px;
            border: none;
            padding: 18px 24px;
            font-weight: 500;
            backdrop-filter: blur(10px);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.2);
            border-left: 4px solid #ef4444;
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.2);
            border-left: 4px solid #10b981;
            color: #6ee7b7;
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.2);
            border-left: 4px solid #f59e0b;
            color: #fcd34d;
            box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.2);
            border-left: 4px solid #3b82f6;
            color: #93c5fd;
        }

        .alert-dismissible .btn-close {
            filter: invert(1);
        }

        /* Images */
        img {
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        img:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
        }

        /* Text utilities */
        .text-muted {
            color: rgba(255, 255, 255, 0.5) !important;
        }

        .text-primary {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.3) 0%, rgba(118, 75, 162, 0.3) 100%);
            backdrop-filter: blur(20px);
            color: white;
            text-align: center;
            padding: 100px 20px;
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.4) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -50%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(240, 147, 251, 0.3) 0%, transparent 70%);
            animation: float 8s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(5deg); }
        }

        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            z-index: 1;
            animation: fadeInUp 1s ease;
        }

        .hero-section p {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.8);
            position: relative;
            z-index: 1;
            animation: fadeInUp 1s ease 0.2s both;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Pagination */
        .pagination {
            gap: 8px;
        }

        .pagination .page-link {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 12px;
            font-weight: 600;
            padding: 10px 16px;
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background: var(--primary-gradient);
            border-color: transparent;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .pagination .page-item.active .page-link {
            background: var(--primary-gradient);
            border-color: transparent;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .pagination .page-item.disabled .page-link {
            background: rgba(255, 255, 255, 0.02);
            color: rgba(255, 255, 255, 0.3);
        }

        /* Loading animations */
        .spinner-border {
            color: #667eea;
            border-right-color: transparent;
        }

        /* Links */
        a {
            transition: all 0.3s ease;
        }

        a:hover {
            color: #667eea;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-gradient);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        /* Animations */
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .animate-pulse {
            animation: pulse 2s ease-in-out infinite;
        }

        .animate-bounce {
            animation: bounce 1s ease infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(-5%); }
            50% { transform: translateY(0); }
        }

        /* Glass effect utilities */
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
        }

        /* Gradient text */
        .gradient-text {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Neon glow effects */
        .neon-glow {
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.5),
                        0 0 40px rgba(102, 126, 234, 0.3),
                        0 0 60px rgba(102, 126, 234, 0.1);
        }

        /* Footer */
        footer {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
            text-align: center;
        }

        footer a {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 600;
        }

        footer a:hover {
            opacity: 0.8;
        }

        /* Custom animations */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-slide-up {
            animation: slideInUp 0.5s ease forwards;
        }

        .animate-slide-left {
            animation: slideInLeft 0.5s ease forwards;
        }

        /* Stagger animation delays */
        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        .stagger-4 { animation-delay: 0.4s; }
        .stagger-5 { animation-delay: 0.5s; }
    </style>
    @yield('extra-css')
</head>
<body>
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('extra-js')
    <script>
        // Navbar scroll effect
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.querySelector('.navbar');
            if (navbar) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 50) {
                        navbar.classList.add('scrolled');
                    } else {
                        navbar.classList.remove('scrolled');
                    }
                });
            }

            // Auto-dismiss alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
</body>
</html>

