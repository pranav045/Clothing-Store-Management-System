<?php
session_start();
include("database.php");

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : '';

// Process form submission
$feedbackSent = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $feedback = trim($_POST['feedback'] ?? '');
    $rating = $_POST['rating'] ?? 0;
    
    if (empty($name)) {
        $errors['name'] = 'Name is required';
    }
    
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address';
    }
    
    if (empty($feedback)) {
        $errors['feedback'] = 'Feedback is required';
    } elseif (strlen($feedback) < 10) {
        $errors['feedback'] = 'Feedback should be at least 10 characters';
    }
    
    if (empty($errors)) {
        // Save to database
        $stmt = $conn->prepare("INSERT INTO feedback (user_id, name, email, rating, feedback, created_at) 
                               VALUES (?, ?, ?, ?, ?, NOW())");
        $userId = $isLoggedIn ? $_SESSION['user_id'] : NULL;
        $stmt->bind_param("issis", $userId, $name, $email, $rating, $feedback);
        
        if ($stmt->execute()) {
            $feedbackSent = true;
        } else {
            $errors['database'] = 'There was an error submitting your feedback. Please try again.';
        }
        
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - Fashion World</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Use the same custom configuration as main.php -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#8b5e3c",
                        secondary: "#6d4c35",
                        accent: "#a07856",
                        dark: "#2d2d2d",
                        light: "#f8f5f2"
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                },
            },
        };
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f5f2;
        }
        .rating-star {
            cursor: pointer;
            transition: all 0.2s;
        }
        .rating-star:hover {
            transform: scale(1.2);
        }
        .selected-rating {
            color: #f59e0b;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Top Bar (same as main.php) -->
    <div class="bg-dark text-white px-4 py-3 text-sm text-center relative">
        <div class="max-w-7xl mx-auto">
            <span class="hidden md:inline-block">FREE SHIPPING - PAN INDIA | POST BILLING, REACH OUT TO +91-7078911359 FOR STITCHING / CUSTOMIZATION ASSISTANCE</span>
            <span class="inline-block md:hidden">FREE SHIPPING PAN INDIA | CALL +91-6395204834</span>
            <span class="absolute right-4 top-1/2 transform -translate-y-1/2">
                <?php if ($isLoggedIn): ?>
                    <div class="relative inline-block group">
                        <button class="flex items-center font-medium hover:text-accent transition duration-300">
                            <span class="user-avatar"><?= strtoupper(substr($userName, 0, 1)) ?></span>
                            <span><?= htmlspecialchars($userName) ?></span>
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="user-dropdown group-hover:block">
                            <a href="profile.php"><i class="fas fa-user mr-2"></i> My Profile</a>
                            <a href="orders.php"><i class="fas fa-shopping-bag mr-2"></i> My Orders</a>
                            <a href="wishlist.php"><i class="fas fa-heart mr-2"></i> Wishlist</a>
                            <a href="logout.php"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="ml-4 font-medium hover:text-accent transition duration-300">
                        <i class="fas fa-user mr-1"></i>Login
                    </a>
                    <a href="createaccount.php" class="ml-3 font-medium hover:text-accent transition duration-300">
                        <i class="fas fa-user-plus mr-1"></i>Register
                    </a>
                <?php endif; ?>
            </span>
        </div>
    </div>

    <!-- Main Navbar (same as main.php) -->
    <nav class="bg-white shadow-md py-3 px-4 md:px-6">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="main.php" class="text-2xl font-bold text-dark hover:text-primary transition duration-300 flex items-center">
                <i class="fas fa-tshirt text-primary mr-2"></i>
                <span>Fashion World</span>
            </a>

            <ul class="hidden lg:flex list-none space-x-8">
                <li><a href="main.php" class="nav-link text-dark font-medium hover:text-primary">Home</a></li>
                <li><a href="newarrivales.html" class="nav-link text-dark font-medium hover:text-primary">New Arrivals</a></li>
                <li><a href="shop.html" class="nav-link text-dark font-medium hover:text-primary">Shop</a></li>
                <li class="relative group">
                    <a href="men.html" class="nav-link text-dark font-medium hover:text-primary flex items-center">
                        Categories <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </a>
                    <div class="absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-md py-2 z-10 hidden group-hover:block">
                        <a href="men.html" class="block px-4 py-2 text-dark hover:bg-primary hover:text-white">Men</a>
                        <a href="women.html" class="block px-4 py-2 text-dark hover:bg-primary hover:text-white">Women</a>
                        <a href="kids.html" class="block px-4 py-2 text-dark hover:bg-primary hover:text-white">Kids</a>
                    </div>
                </li>
                <li><a href="readytoship.html" class="nav-link text-dark font-medium hover:text-primary">Ready to Ship</a></li>
                <li><a href="help.html" class="nav-link text-dark font-medium hover:text-primary">Help</a></li>
            </ul>

            <div class="flex items-center space-x-4">
                <div class="relative hidden md:block">
                    <input type="text" id="search-bar" class="py-2 px-4 pl-10 border border-gray-200 rounded-full focus:outline-none focus:ring-1 focus:ring-primary" placeholder="Search...">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <a href="cart.html" class="relative">
                    <i class="fas fa-shopping-bag text-xl text-dark hover:text-primary transition duration-300"></i>
                    <span class="absolute -top-2 -right-2 bg-primary text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center cart-count">0</span>
                </a>
                <div class="menu-btn lg:hidden text-2xl cursor-pointer text-dark hover:text-primary transition duration-300">
                    <i class="fas fa-bars"></i>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-12 px-4 md:px-6">
        <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-md overflow-hidden p-6 md:p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-dark mb-2">Share Your Feedback</h1>
                <div class="w-20 h-1 bg-primary mx-auto mb-4"></div>
                <p class="text-gray-600">We value your opinion! Please let us know about your experience with Fashion World.</p>
            </div>

            <?php if ($feedbackSent): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <strong class="font-bold">Thank you!</strong>
                    <span class="block sm:inline">Your feedback has been submitted successfully.</span>
                    <a href="main.php" class="text-primary hover:underline font-medium mt-2 inline-block">Return to Home</a>
                </div>
            <?php elseif (!empty($errors['database'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline"><?= htmlspecialchars($errors['database']) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input type="text" id="name" name="name" 
                               value="<?= htmlspecialchars($_POST['name'] ?? ($isLoggedIn ? $userName : '')) ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary <?= !empty($errors['name']) ? 'border-red-500' : '' ?>">
                        <?php if (!empty($errors['name'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($errors['name']) ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                        <input type="email" id="email" name="email" 
                               value="<?= htmlspecialchars($_POST['email'] ?? ($isLoggedIn ? $_SESSION['user_email'] ?? '' : '')) ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary <?= !empty($errors['email']) ? 'border-red-500' : '' ?>">
                        <?php if (!empty($errors['email'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($errors['email']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">How would you rate your experience? *</label>
                    <div class="flex items-center space-x-2" id="rating-stars">
                        <?php 
                        $selectedRating = $_POST['rating'] ?? 0;
                        for ($i = 1; $i <= 5; $i++): 
                        ?>
                            <i class="fas fa-star text-2xl rating-star <?= $i <= $selectedRating ? 'selected-rating text-yellow-500' : 'text-gray-300' ?>" 
                               data-rating="<?= $i ?>"></i>
                        <?php endfor; ?>
                        <input type="hidden" name="rating" id="rating" value="<?= $selectedRating ?>">
                    </div>
                </div>
                
                <div>
                    <label for="feedback" class="block text-sm font-medium text-gray-700 mb-1">Your Feedback *</label>
                    <textarea id="feedback" name="feedback" rows="5" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary <?= !empty($errors['feedback']) ? 'border-red-500' : '' ?>"><?= htmlspecialchars($_POST['feedback'] ?? '') ?></textarea>
                    <?php if (!empty($errors['feedback'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($errors['feedback']) ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-primary text-white font-medium py-2 px-6 rounded-md hover:bg-secondary transition duration-300">
                        Submit Feedback
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer (same as main.php) -->
    <footer class="bg-dark text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-tshirt text-primary mr-2"></i> Fashion World
                    </h3>
                    <p class="text-gray-300 mb-4">Premium clothing store offering handcrafted apparel with timeless elegance.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Shop</h3>
                    <ul class="space-y-2">
                        <li><a href="men.html" class="text-gray-300 hover:text-white">Men</a></li>
                        <li><a href="women.html" class="text-gray-300 hover:text-white">Women</a></li>
                        <li><a href="kids.html" class="text-gray-300 hover:text-white">Kids</a></li>
                        <li><a href="newarrivales.html" class="text-gray-300 hover:text-white">New Arrivals</a></li>
                        <li><a href="readytoship.html" class="text-gray-300 hover:text-white">Ready to Ship</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Customer Service</h3>
                    <ul class="space-y-2">
                        <li><a href="help.html" class="text-gray-300 hover:text-white">Contact Us</a></li>
                        <li><a href="feedback.php" class="text-gray-300 hover:text-white">Give Feedback</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">FAQs</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Shipping Policy</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Returns & Exchanges</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Track Order</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">About Us</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white">Our Story</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Sustainability</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Careers</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Terms & Conditions</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400 text-sm">
                <p>© 2024 Fashion World. All rights reserved.</p>
                <p class="mt-2">Conditions of Use & Sale | Privacy Notice | Interest-Based Ads</p>
            </div>
        </div>
    </footer>

    <script>
        // Rating stars interaction
        document.querySelectorAll('.rating-star').forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.dataset.rating;
                document.getElementById('rating').value = rating;
                
                // Update star display
                document.querySelectorAll('.rating-star').forEach((s, index) => {
                    if (index < rating) {
                        s.classList.add('selected-rating', 'text-yellow-500');
                        s.classList.remove('text-gray-300');
                    } else {
                        s.classList.remove('selected-rating', 'text-yellow-500');
                        s.classList.add('text-gray-300');
                    }
                });
            });
            
            star.addEventListener('mouseover', function() {
                const rating = this.dataset.rating;
                document.querySelectorAll('.rating-star').forEach((s, index) => {
                    if (index < rating) {
                        s.classList.add('text-yellow-300');
                    }
                });
            });
            
            star.addEventListener('mouseout', function() {
                const selectedRating = document.getElementById('rating').value;
                document.querySelectorAll('.rating-star').forEach((s, index) => {
                    s.classList.remove('text-yellow-300');
                    if (index < selectedRating) {
                        s.classList.add('text-yellow-500');
                    }
                });
            });
        });
    </script>
</body>
</html>