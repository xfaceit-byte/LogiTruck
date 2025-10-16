<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['partner_id'])) {
    header("Location: partner.php");
    exit();
}

// Get partner information
try {
    $stmt = $pdo->prepare("SELECT * FROM partners WHERE id = ?");
    $stmt->execute([$_SESSION['partner_id']]);
    $partner = $stmt->fetch();
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ef4444">
    <title>Partner Dashboard - LogiTruck</title>
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
                            <p class="text-[10px] uppercase tracking-widest text-white/60">Partner Dashboard</p>
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
                        <a href="partner.php" class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium text-white/80 hover:text-white border border-white/10 bg-white/5">Partner Portal</a>
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
                      <a href="partner.php" class="block text-sm text-white/80 hover:text-white py-2">Partner Portal</a>
                    </div>
                  </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-32 pb-12">
        <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
            <h2 class="text-2xl font-bold">Welcome, <?php echo htmlspecialchars($partner['company_name']); ?>!</h2>
            <p class="mt-1 text-white/70">Here's your partner dashboard overview.</p>
        </div>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                <h3 class="text-sm text-white/70">Active Deliveries</h3>
                <div class="mt-2 text-3xl font-extrabold text-red-400">0</div>
                <div class="text-xs text-white/60">Current deliveries in progress</div>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                <h3 class="text-sm text-white/70">Completed Deliveries</h3>
                <div class="mt-2 text-3xl font-extrabold text-red-400">0</div>
                <div class="text-xs text-white/60">Total completed deliveries</div>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                <h3 class="text-sm text-white/70">Revenue</h3>
                <div class="mt-2 text-3xl font-extrabold text-red-400">$0</div>
                <div class="text-xs text-white/60">Total earnings</div>
            </div>
        </div>

        <div class="mt-6 rounded-2xl border border-white/10 bg-white/5 p-6">
            <h3 class="text-lg font-semibold">Partner Information</h3>
            <div class="mt-3 grid sm:grid-cols-2 gap-4 text-sm">
                <div><span class="text-white/60">Company Name:</span> <?php echo htmlspecialchars($partner['company_name']); ?></div>
                <div><span class="text-white/60">Email:</span> <?php echo htmlspecialchars($partner['email']); ?></div>
                <div><span class="text-white/60">Phone:</span> <?php echo htmlspecialchars($partner['phone']); ?></div>
                <div><span class="text-white/60">Address:</span> <?php echo htmlspecialchars($partner['address']); ?></div>
            </div>
            <a href="logout.php" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-red-500 to-rose-600 px-5 py-2.5 text-sm font-medium text-white shadow-lg shadow-red-500/25 hover:from-red-400 hover:to-rose-500 transition">Logout</a>
        </div>
    </main>
    <script src="script.js"></script>
</body>
</html>