<?php
session_start();
require_once 'config.php';

$error = '';
$success = '';
$pdoAvailable = isset($pdo) && $pdo instanceof PDO;

if (!$pdoAvailable) {
    $error = 'Database connection is unavailable. Please try again later.';
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        $company_name = trim($_POST['company_name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);
        $phone = trim($_POST['phone']);
        $address = trim($_POST['address']);

        if ($password !== $confirm_password) {
            $error = "Passwords do not match!";
        } else {
            try {
                $stmt = $pdo->prepare("SELECT * FROM partners WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->rowCount() > 0) {
                    $error = "Email already exists!";
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO partners (company_name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$company_name, $email, $hashed_password, $phone, $address]);
                    $success = "Registration successful! Please login.";
                }
            } catch(PDOException $e) {
                $error = "Registration failed: " . $e->getMessage();
            }
        }
    } elseif (isset($_POST['login'])) {
        $email = trim($_POST['login_email']);
        $password = trim($_POST['login_password']);

        try {
            $stmt = $pdo->prepare("SELECT * FROM partners WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                $partner = $stmt->fetch();
                if (password_verify($password, $partner['password'])) {
                    $_SESSION['partner_id'] = $partner['id'];
                    $_SESSION['company_name'] = $partner['company_name'];
                    header("Location: partner-dashboard.php");
                    exit();
                } else {
                    $error = "Invalid password!";
                }
            } else {
                $error = "Email not found!";
            }
        } catch(PDOException $e) {
            $error = "Login failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ef4444">
    <title>Partner Portal - LogiTruck</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = { theme: { extend: { colors: { brand: { red: '#ef4444', dark: '#0a0a0b' } }, backgroundImage: { 'grid': 'radial-gradient(circle at 1px 1px, rgba(255,255,255,0.08) 1px, transparent 1px)' } } } }
    </script>
    <style> 
      body { 
        font-family: 'Poppins', system-ui, -apple-system, Segoe UI, Roboto, Arial, 'Apple Color Emoji', 'Segoe UI Emoji'; 
      }
      
      /* Mobile menu styles */
      .nav-links {
        z-index: 1000;
      }
      
      .nav-links.active {
        opacity: 1 !important;
        visibility: visible !important;
        transform: translateY(0) !important;
      }
      
      /* Hamburger animation */
      .hamburger.active i {
        transform: rotate(90deg);
        transition: transform 0.3s ease;
      }
      
      .hamburger i {
        transition: transform 0.3s ease;
      }
    </style>
</head>
<body class="bg-black text-gray-100 selection:bg-red-500/30">
    <header class="fixed inset-x-0 top-0 z-50">
        <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mt-4 backdrop-blur supports-[backdrop-filter]:bg-black/40 bg-black/60 border border-white/10 rounded-2xl">
                <div class="flex items-center justify-between px-4 py-3">
                    <a href="index.html" class="flex items-center gap-3 group">
                        <span class="relative grid place-items-center w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-rose-600 text-white">
                            <i class="fas fa-truck"></i>
                            <span class="absolute -inset-0.5 rounded-xl bg-gradient-to-br from-red-500/30 to-rose-600/30 blur-md -z-10"></span>
                        </span>
                        <div>
                            <h1 class="text-lg font-semibold tracking-tight">LogiTruck</h1>
                            <p class="text-[10px] uppercase tracking-widest text-white/60">Partner Portal</p>
                        </div>
                    </a>
                    <div class="hidden md:flex items-center gap-6">
                        <a href="index.html" class="text-sm text-white/80 hover:text-white">Home</a>
                        <a href="services.html" class="text-sm text-white/80 hover:text-white">Services</a>
                        <a href="about.html" class="text-sm text-white/80 hover:text-white">About</a>
                        <a href="fleet.html" class="text-sm text-white/80 hover:text-white">Our Fleet</a>
                        <a href="contact.html" class="text-sm text-white/80 hover:text-white">Contact</a>
                    </div>
                    <div class="hidden md:block">
                        <a href="contact.html" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-red-500 to-rose-600 px-4 py-2 text-sm font-medium text-white shadow-lg shadow-red-500/25 hover:from-red-400 hover:to-rose-500 hover:shadow-red-500/40 hover:shadow-xl active:translate-y-px transition">
                            <i class="fas fa-paper-plane"></i>
                            Get a Quote
                        </a>
                    </div>
                    <button class="hamburger md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white/5 border border-white/10 text-white">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                <!-- Mobile Navigation Menu -->
                <div class="nav-links md:hidden absolute top-full left-0 right-0 mt-2 mx-4 backdrop-blur supports-[backdrop-filter]:bg-black/40 bg-black/60 border border-white/10 rounded-2xl opacity-0 invisible transform -translate-y-2 transition-all duration-300 ease-in-out">
                  <div class="px-4 py-3 space-y-3">
                    <a href="index.html" class="block text-sm text-white/80 hover:text-white py-2">Home</a>
                    <a href="services.html" class="block text-sm text-white/80 hover:text-white py-2">Services</a>
                    <a href="about.html" class="block text-sm text-white/80 hover:text-white py-2">About</a>
                    <a href="fleet.html" class="block text-sm text-white/80 hover:text-white py-2">Our Fleet</a>
                    <a href="contact.html" class="block text-sm text-white/80 hover:text-white py-2">Contact</a>
                    <div class="pt-3 border-t border-white/10 space-y-2">
                      <a href="contact.html" class="block text-sm text-white py-2 bg-gradient-to-r from-red-500 to-rose-600 rounded-lg px-3 py-2 text-center">
                        <i class="fas fa-paper-plane mr-2"></i>Get a Quote
                      </a>
                    </div>
                  </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-32 pb-12">
        <div class="flex flex-wrap items-center gap-3">
            <button class="partner-tab active inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm" onclick="showTab('login')"><i class="fas fa-sign-in-alt"></i> Login</button>
            <button class="partner-tab inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm" onclick="showTab('register')"><i class="fas fa-user-plus"></i> Register</button>
        </div>

        <?php if ($error): ?>
            <div class="mt-4 rounded-xl border border-red-500/20 bg-red-500/10 text-red-200 px-4 py-3"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="mt-4 rounded-xl border border-emerald-500/20 bg-emerald-500/10 text-emerald-200 px-4 py-3"><?php echo $success; ?></div>
        <?php endif; ?>

        <div id="login-form" class="mt-6 rounded-2xl border border-white/10 bg-white/5 p-6">
            <h2 class="text-xl font-semibold">Partner Login</h2>
            <form class="mt-4 space-y-4" method="POST" action="">
                <div class="form-group">
                    <label class="block text-sm mb-1" for="login_email">Email</label>
                    <input class="w-full rounded-lg bg-black/30 border border-white/10 px-3 py-2 outline-none focus:border-red-400" type="email" id="login_email" name="login_email" required>
                </div>
                <div class="form-group">
                    <label class="block text-sm mb-1" for="login_password">Password</label>
                    <input class="w-full rounded-lg bg-black/30 border border-white/10 px-3 py-2 outline-none focus:border-red-400" type="password" id="login_password" name="login_password" required>
                </div>
                <button type="submit" name="login" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-red-500 to-rose-600 px-5 py-2.5 text-sm font-medium text-white shadow-lg shadow-red-500/25 hover:from-red-400 hover:to-rose-500 transition">Login</button>
            </form>
        </div>

        <div id="register-form" class="mt-6 rounded-2xl border border-white/10 bg-white/5 p-6 hidden">
            <h2 class="text-xl font-semibold">Partner Registration</h2>
            <form class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4" method="POST" action="">
                <div class="form-group sm:col-span-2">
                    <label class="block text-sm mb-1" for="company_name">Company Name</label>
                    <input class="w-full rounded-lg bg-black/30 border border-white/10 px-3 py-2 outline-none focus:border-red-400" type="text" id="company_name" name="company_name" required>
                </div>
                <div class="form-group">
                    <label class="block text-sm mb-1" for="email">Email</label>
                    <input class="w-full rounded-lg bg-black/30 border border-white/10 px-3 py-2 outline-none focus:border-red-400" type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label class="block text-sm mb-1" for="phone">Phone Number</label>
                    <input class="w-full rounded-lg bg-black/30 border border-white/10 px-3 py-2 outline-none focus:border-red-400" type="tel" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label class="block text-sm mb-1" for="address">Address</label>
                    <input class="w-full rounded-lg bg-black/30 border border-white/10 px-3 py-2 outline-none focus:border-red-400" type="text" id="address" name="address" required>
                </div>
                <div class="form-group">
                    <label class="block text-sm mb-1" for="password">Password</label>
                    <input class="w-full rounded-lg bg-black/30 border border-white/10 px-3 py-2 outline-none focus:border-red-400" type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label class="block text-sm mb-1" for="confirm_password">Confirm Password</label>
                    <input class="w-full rounded-lg bg-black/30 border border-white/10 px-3 py-2 outline-none focus:border-red-400" type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <div class="sm:col-span-2">
                    <button type="submit" name="register" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-red-500 to-rose-600 px-5 py-2.5 text-sm font-medium text-white shadow-lg shadow-red-500/25 hover:from-red-400 hover:to-rose-500 transition">Register</button>
                </div>
            </form>
        </div>
    </main>

    <script>
        function showTab(tabName) {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            const tabs = document.querySelectorAll('.partner-tab');

            if (tabName === 'login') {
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
                tabs[0].classList.add('border-red-500/40');
                tabs[1].classList.remove('border-red-500/40');
            } else {
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');
                tabs[0].classList.remove('border-red-500/40');
                tabs[1].classList.add('border-red-500/40');
            }
        }
    </script>
    <script src="script.js"></script>
</body>
</html>