<?php
session_start();
include("database.php");

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fashion World- Premium Clothing Store</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Tailwind Configuration -->
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
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f5f2;
        }
        .content {
            flex-grow: 1;
        }
        .nav-link {
            position: relative;
        }
        .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #8b5e3c;
            transition: width 0.3s ease;
        }
        .nav-link:hover:after {
            width: 100%;
        }
        .category-card {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('hero-bg.jpg');
            background-size: cover;
            background-position: center;
        }
        .user-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 0.5rem;
        }
        .user-dropdown a {
            display: block;
            padding: 0.75rem 1rem;
            color: #2d2d2d;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .user-dropdown a:hover {
            background-color: #f8f5f2;
        }
        .user-avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #8b5e3c;
            color: white;
            font-weight: bold;
            margin-right: 0.5rem;
        }
        .animate-pulse {
            animation: pulse 0.5s ease-in-out;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .cart-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #047857;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease-out;
            z-index: 1000;
        }
        .cart-notification.show {
            transform: translateY(0);
            opacity: 1;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Top Bar -->
    <div class="bg-dark text-white px-4 py-3 text-sm text-center relative">
        <div class="max-w-7xl mx-auto">
            <span class="hidden md:inline-block">FREE SHIPPING - PAN INDIA | POST BILLING, REACH OUT TO +91-9814200018 FOR STITCHING / CUSTOMIZATION ASSISTANCE</span>
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

    <!-- Main Navbar -->
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

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden lg:hidden bg-white shadow-lg">
        <div class="px-4 py-3 border-t border-gray-200">
            <div class="relative mb-3">
                <input type="text" class="w-full py-2 px-4 pl-10 border border-gray-200 rounded-full" placeholder="Search...">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <a href="main.php" class="block py-2 px-2 text-dark font-medium hover:bg-gray-100 rounded">Home</a>
            <a href="newarrivales.html" class="block py-2 px-2 text-dark font-medium hover:bg-gray-100 rounded">New Arrivals</a>
            <a href="shop.html" class="block py-2 px-2 text-dark font-medium hover:bg-gray-100 rounded">Shop</a>
            <div class="py-2 px-2">
                <div class="flex justify-between items-center text-dark font-medium cursor-pointer hover:bg-gray-100 rounded" id="mobile-categories-toggle">
                    <span>Categories</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </div>
                <div id="mobile-categories" class="hidden pl-4 mt-1">
                    <a href="men.html" class="block py-2 text-dark hover:text-primary">Men</a>
                    <a href="women.html" class="block py-2 text-dark hover:text-primary">Women</a>
                    <a href="kids.html" class="block py-2 text-dark hover:text-primary">Kids</a>
                </div>
            </div>
            <a href="readytoship.html" class="block py-2 px-2 text-dark font-medium hover:bg-gray-100 rounded">Ready to Ship</a>
            <a href="help.html" class="block py-2 px-2 text-dark font-medium hover:bg-gray-100 rounded">Help</a>
            <?php if ($isLoggedIn): ?>
                <div class="border-t border-gray-200 mt-2 pt-2">
                    <a href="profile.php" class="block py-2 px-2 text-dark font-medium hover:bg-gray-100 rounded">My Profile</a>
                    <a href="logout.php" class="block py-2 px-2 text-dark font-medium hover:bg-gray-100 rounded">Logout</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Welcome Message for Logged-in Users -->
    <?php if ($isLoggedIn): ?>
    <div class="bg-accent/20 text-dark py-2 px-4 text-center">
        Welcome back, <?= htmlspecialchars($userName) ?>! Enjoy your shopping experience.
    </div>
    <?php endif; ?>

    <!-- Hero Section -->
    <section class="hero-section bg-primary text-white py-16 md:py-24">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Premium Clothing Collections</h1>
            <p class="text-xl md:text-2xl mb-8 max-w-2xl mx-auto">Discover timeless elegance with our exclusive range of handcrafted apparel</p>
            <a href="shop.html" class="inline-block bg-white text-primary font-bold py-3 px-8 rounded-full hover:bg-gray-100 transition duration-300 shadow-lg">
                Shop Now <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </section>

    <!-- Main Content -->
    <main class="content py-12 px-4 md:px-6">
        <div class="max-w-7xl mx-auto">
            <!-- Featured Categories -->
            <section class="mb-16">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-dark mb-2">Shop By Category</h2>
                    <div class="w-20 h-1 bg-primary mx-auto"></div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <a href="men.html" class="category-card bg-white p-6 rounded-lg shadow-md text-center">
                        <div class="overflow-hidden rounded-md mb-4">
                            <img src="Men Clothes/image2.jpg" alt="Men's Fashion" class="w-full h-48 object-cover hover:scale-105 transition duration-500">
                        </div>
                        <h3 class="text-xl font-bold text-dark mb-2">Men's Collection</h3>
                        <p class="text-gray-600">Premium quality menswear</p>
                        <button class="mt-4 text-primary font-medium hover:underline">Explore <i class="fas fa-arrow-right ml-1"></i></button>
                    </a>
                    
                    <a href="women.html" class="category-card bg-white p-6 rounded-lg shadow-md text-center">
                        <div class="overflow-hidden rounded-md mb-4">
                            <img src="Woman Clothes/image2.jpg" alt="Women's Fashion" class="w-full h-48 object-cover hover:scale-105 transition duration-500">
                        </div>
                        <h3 class="text-xl font-bold text-dark mb-2">Women's Collection</h3>
                        <p class="text-gray-600">Elegant womenswear</p>
                        <button class="mt-4 text-primary font-medium hover:underline">Explore <i class="fas fa-arrow-right ml-1"></i></button>
                    </a>
                    
                    <a href="kids.html" class="category-card bg-white p-6 rounded-lg shadow-md text-center">
                        <div class="overflow-hidden rounded-md mb-4">
                            <img src="Kids Clothes/2.jpeg" alt="Kids Collection" class="w-full h-48 object-cover hover:scale-105 transition duration-500">
                        </div>
                        <h3 class="text-xl font-bold text-dark mb-2">Kid's Collection</h3>
                        <p class="text-gray-600">Adorable outfits for kids</p>
                        <button class="mt-4 text-primary font-medium hover:underline">Explore <i class="fas fa-arrow-right ml-1"></i></button>
                    </a>
                    
                    <a href="newarrivales.html" class="category-card bg-white p-6 rounded-lg shadow-md text-center">
                        <div class="overflow-hidden rounded-md mb-4">
                            <img src="Men Clothes/image1.jpg" alt="New Arrivals" class="w-full h-48 object-cover hover:scale-105 transition duration-500">
                        </div>
                        <h3 class="text-xl font-bold text-dark mb-2">New Arrivals</h3>
                        <p class="text-gray-600">Latest trends in fashion</p>
                        <button class="mt-4 text-primary font-medium hover:underline">Explore <i class="fas fa-arrow-right ml-1"></i></button>
                    </a>
                </div>
            </section>

            <!-- Personalized Recommendations (for logged-in users) -->
            <?php if ($isLoggedIn): ?>
            <section class="mb-16">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-dark mb-2">Recommended For You</h2>
                    <div class="w-20 h-1 bg-primary mx-auto"></div>
                    <p class="text-gray-600 mt-2">Personalized selections based on your preferences</p>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Recommended Product 1 -->
                    <div class="bg-white p-6 rounded-lg shadow-md text-center product" data-id="rec1">
                        <div class="overflow-hidden rounded-md mb-4">
                            <img src="Men Clothes/image3.jpg" alt="Recommended Product" class="w-full h-48 object-cover hover:scale-105 transition duration-500">
                        </div>
                        <h3 class="text-xl font-bold text-dark mb-2">Premium Denim Jacket</h3>
                        <p class="text-gray-600 product-price">₹2,499</p>
                        <button class="mt-4 bg-primary text-white py-2 px-6 rounded-full hover:bg-secondary transition add-to-cart">
                            Add to Cart
                        </button>
                    </div>
                    
                    <!-- Recommended Product 2 -->
                    <div class="bg-white p-6 rounded-lg shadow-md text-center product" data-id="rec2">
                        <div class="overflow-hidden rounded-md mb-4">
                            <img src="Woman Clothes/image3.jpg" alt="Recommended Product" class="w-full h-48 object-cover hover:scale-105 transition duration-500">
                        </div>
                        <h3 class="text-xl font-bold text-dark mb-2">Elegant Silk Dress</h3>
                        <p class="text-gray-600 product-price">₹3,299</p>
                        <button class="mt-4 bg-primary text-white py-2 px-6 rounded-full hover:bg-secondary transition add-to-cart">
                            Add to Cart
                        </button>
                    </div>
                    
                    <!-- Recommended Product 3 -->
                    <div class="bg-white p-6 rounded-lg shadow-md text-center product" data-id="rec3">
                        <div class="overflow-hidden rounded-md mb-4">
                            <img src="Kids Clothes/3.jpeg" alt="Recommended Product" class="w-full h-48 object-cover hover:scale-105 transition duration-500">
                        </div>
                        <h3 class="text-xl font-bold text-dark mb-2">Kids Cotton Set</h3>
                        <p class="text-gray-600 product-price">₹1,299</p>
                        <button class="mt-4 bg-primary text-white py-2 px-6 rounded-full hover:bg-secondary transition add-to-cart">
                            Add to Cart
                        </button>
                    </div>
                    
                    <!-- Recommended Product 4 -->
                    <div class="bg-white p-6 rounded-lg shadow-md text-center product" data-id="rec4">
                        <div class="overflow-hidden rounded-md mb-4">
                            <img src="Men Clothes/image4.jpg" alt="Recommended Product" class="w-full h-48 object-cover hover:scale-105 transition duration-500">
                        </div>
                        <h3 class="text-xl font-bold text-dark mb-2">Casual Linen Shirt</h3>
                        <p class="text-gray-600 product-price">₹1,599</p>
                        <button class="mt-4 bg-primary text-white py-2 px-6 rounded-full hover:bg-secondary transition add-to-cart">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </section>
            <?php endif; ?>

            <!-- Call to Action -->
            <section class="bg-primary text-white rounded-xl p-8 md:p-12 text-center mb-16">
                <h2 class="text-2xl md:text-3xl font-bold mb-4">Ready to Elevate Your Wardrobe?</h2>
                <p class="text-lg mb-6 max-w-2xl mx-auto">Sign up for our newsletter and get 15% off your first order plus exclusive access to new arrivals.</p>
                <form class="max-w-md mx-auto flex">
                    <input type="email" placeholder="Your email address" class="flex-grow py-3 px-4 rounded-l-full focus:outline-none text-dark">
                    <button type="submit" class="bg-dark text-white py-3 px-6 rounded-r-full hover:bg-gray-800 transition duration-300">
                        Subscribe
                    </button>
                </form>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-tshirt text-primary mr-2"></i> Frontier Phagwara
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
                
                <!-- In the Customer Service section of the footer -->
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

    <!-- Cart Notification -->
    <div id="cart-notification" class="cart-notification hidden">
        <i class="fas fa-check-circle mr-2"></i>
        <span id="notification-message">Item added to cart</span>
    </div>

    <!-- Mobile Menu Toggle Script -->
    <script>
        // Mobile menu toggle
        const menuBtn = document.querySelector('.menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileCategoriesToggle = document.getElementById('mobile-categories-toggle');
        const mobileCategories = document.getElementById('mobile-categories');

        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        mobileCategoriesToggle.addEventListener('click', () => {
            mobileCategories.classList.toggle('hidden');
            const icon = mobileCategoriesToggle.querySelector('i');
            icon.classList.toggle('fa-chevron-down');
            icon.classList.toggle('fa-chevron-up');
        });

        // Cart functionality
        let cart = {
            items: [],
            totalItems: 0,
            totalPrice: 0,

            // Initialize cart from localStorage
            init() {
                const savedCart = localStorage.getItem('cart');
                if (savedCart) {
                    const parsedCart = JSON.parse(savedCart);
                    this.items = parsedCart.items || [];
                    this.totalItems = parsedCart.totalItems || 0;
                    this.totalPrice = parsedCart.totalPrice || 0;
                }
                this.updateCartUI();
            },

            // Add item to cart
            addItem(product) {
                // Check if item already exists in cart
                const existingItem = this.items.find(item => item.id === product.id);
                
                if (existingItem) {
                    existingItem.quantity += 1;
                } else {
                    product.quantity = 1;
                    this.items.push(product);
                }
                
                this.totalItems += 1;
                this.totalPrice += product.price;
                
                this.saveCart();
                this.updateCartUI();
                
                // Show notification
                this.showNotification(`${product.name} added to cart`);
            },
            
            // Show notification
            showNotification(message) {
                const notification = document.getElementById('cart-notification');
                const messageElement = document.getElementById('notification-message');
                
                messageElement.textContent = message;
                notification.classList.remove('hidden');
                notification.classList.add('show');
                
                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        notification.classList.add('hidden');
                    }, 300);
                }, 3000);
            },

            // Save cart to localStorage
            saveCart() {
                localStorage.setItem('cart', JSON.stringify({
                    items: this.items,
                    totalItems: this.totalItems,
                    totalPrice: this.totalPrice
                }));
            },

            // Update cart UI (cart icon count)
            updateCartUI() {
                const cartCountElements = document.querySelectorAll('.cart-count');
                cartCountElements.forEach(element => {
                    element.textContent = this.totalItems;
                    if (this.totalItems > 0) {
                        element.classList.add('animate-pulse');
                        setTimeout(() => {
                            element.classList.remove('animate-pulse');
                        }, 500);
                    }
                });
            }
        };

        // Initialize cart when page loads
        document.addEventListener('DOMContentLoaded', () => {
            cart.init();
            
            // Set up event listeners for all "Add to Cart" buttons
            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', (e) => {
                    const productElement = e.target.closest('.product');
                    if (productElement) {
                        // Extract price by removing ₹ and commas, then converting to number
                        const priceText = productElement.querySelector('.product-price').textContent;
                        const price = parseFloat(priceText.replace('₹', '').replace(',', ''));
                        
                        const product = {
                            id: productElement.dataset.id,
                            name: productElement.querySelector('h3').textContent,
                            price: price,
                            image: productElement.querySelector('img').src
                        };
                        cart.addItem(product);
                        
                        // Visual feedback
                        const originalText = button.innerHTML;
                        button.innerHTML = '<i class="fas fa-check mr-1"></i> Added';
                        button.classList.remove('bg-primary', 'hover:bg-secondary');
                        button.classList.add('bg-green-500', 'hover:bg-green-600');
                        
                        // Reset button after 1.5 seconds
                        setTimeout(() => {
                            button.innerHTML = originalText;
                            button.classList.remove('bg-green-500', 'hover:bg-green-600');
                            button.classList.add('bg-primary', 'hover:bg-secondary');
                        }, 1500);
                    }
                });
            });
        });
    </script>
</body>
</html>