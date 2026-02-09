<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $pageTitle ?? SITE_NAME; ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .bg-gradient-secondary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .shadow-custom {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .input-focus:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        
        .card {
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .error-input {
            border-color: #f56565;
        }
        
        .success-message {
            animation: slideInRight 0.5s ease-out;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <?php 
    if (Session::isLoggedIn()) {
        require_once __DIR__ . '/user-nav.php';
    } else {
        require_once __DIR__ . '/guest-nav.php';
    }
    ?>
    
    <!-- Flash Messages -->
    <?php
    $flash = Session::getFlash();
    if ($flash):
        $bgColor = $flash['type'] === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800';
        $icon = $flash['type'] === 'success' ? '✓' : '✕';
    ?>
    <div class="fixed top-20 right-4 z-50 success-message">
        <div class="<?php echo $bgColor; ?> border px-6 py-4 rounded-lg shadow-lg max-w-md">
            <div class="flex items-center">
                <span class="text-2xl mr-3"><?php echo $icon; ?></span>
                <p class="font-medium"><?php echo e($flash['message']); ?></p>
            </div>
        </div>
    </div>
    <script>
        setTimeout(function() {
            document.querySelector('.success-message').style.opacity = '0';
            setTimeout(function() {
                document.querySelector('.success-message').remove();
            }, 500);
        }, 5000);
    </script>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main class="fade-in">
