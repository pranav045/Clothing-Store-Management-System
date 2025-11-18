<?php
include("database.php");
session_start();
$msg = '';

// Check for successful registration redirect
if (isset($_SESSION['registration_success'])) {
    $msg = "Registration successful! Please log in.";
    unset($_SESSION['registration_success']);
}

if(isset($_POST['Login'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $select1 = "SELECT * FROM `users` WHERE email='$email'";
    $select_user = mysqli_query($conn, $select1);

    if(mysqli_num_rows($select_user) > 0) {
        $row1 = mysqli_fetch_assoc($select_user);
        // Verify password
        if(password_verify($password, $row1['CreatePassword'])) {
            $_SESSION['user_id'] = $row1['id'];
            $_SESSION['user_name'] = $row1['Name'];
            
            // Set a cookie for "Remember me" functionality
            if (isset($_POST['remember'])) {
                setcookie('fashion_email', $email, time() + (86400 * 30), "/"); // 30 days
                setcookie('fashion_remember', '1', time() + (86400 * 30), "/");
            }
            
            header("Location: main.php");
            exit();
        } else {
            $msg = "Invalid password!";
        }
    } else {
        $msg = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Fashion Frontier</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-shadow {
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-300 flex items-center justify-center min-h-screen p-4">
    <div class="max-w-6xl w-full">
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl overflow-hidden card-shadow md:flex">
            <div class="hidden md:block md:w-1/2 gradient-bg p-12 text-white">
                <div class="h-full flex flex-col justify-center">
                    <h2 class="text-4xl font-bold mb-6 animate__animated animate__fadeIn">Welcome Back!</h2>
                    <p class="text-lg mb-8">Login to access your personalized fashion dashboard and continue your style journey.</p>
                    
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                                <i class="fas fa-heart text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold">Your Wishlist</h3>
                                <p class="text-sm opacity-80">Save your favorite items</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                                <i class="fas fa-bell text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold">Notifications</h3>
                                <p class="text-sm opacity-80">Get alerts on new arrivals</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                                <i class="fas fa-tag text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold">Exclusive Deals</h3>
                                <p class="text-sm opacity-80">Member-only discounts</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:w-1/2 p-8 md:p-12">
                <div class="text-center mb-2">
                    <img src="Logo.jpeg" alt="Logo" class="h-12 mx-auto mb-4">
                    <h1 class="text-3xl font-bold text-gray-800">Sign In</h1>
                    <p class="text-gray-600">Access your Fashion World account</p>
                </div>
                
                <?php if ($msg): ?>
                    <div class="mb-4 p-3 <?php echo strpos($msg, 'successful') !== false ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?> rounded-md animate__animated animate__fadeIn">
                        <i class="fas <?php echo strpos($msg, 'successful') !== false ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-2"></i><?= htmlspecialchars($msg) ?>
                    </div>
                <?php endif; ?>

                <form id="loginForm" method="post" class="space-y-4">
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-3 text-gray-400"></i>
                            <input type="email" id="email" name="email" placeholder="Enter your email" 
                                   value="<?php echo isset($_COOKIE['fashion_email']) ? htmlspecialchars($_COOKIE['fashion_email']) : ''; ?>"
                                   class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                        </div>
                    </div>
                    
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-3 text-gray-400"></i>
                            <input type="password" id="password" name="password" placeholder="Enter password" 
                                   class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                            <i class="fas fa-eye-slash absolute right-3 top-3 text-gray-400 cursor-pointer" id="togglePassword"></i>
                        </div>
                        <div class="text-right mt-1">
                            <a href="reset_password.php" class="text-sm text-purple-600 hover:underline">Forgot password?</a>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" id="remember" name="remember" 
                                   <?php echo isset($_COOKIE['fashion_remember']) ? 'checked' : ''; ?>
                                   class="w-4 h-4 accent-purple-600 rounded">
                            <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full py-3 gradient-bg text-white rounded-lg font-semibold hover:opacity-90 transition duration-300 shadow-md hover:shadow-lg flex items-center justify-center" name="Login" value="login">
                        <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                    </button>
                    
                    <div class="text-center text-sm text-gray-500">or sign in with</div>
                    
                    <div class="flex justify-center space-x-4">
                        <button type="button" class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition">
                            <i class="fab fa-facebook-f"></i>
                        </button>
                        <button type="button" class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center hover:bg-red-700 transition">
                            <i class="fab fa-google"></i>
                        </button>
                        <button type="button" class="w-12 h-12 rounded-full bg-black text-white flex items-center justify-center hover:bg-gray-800 transition">
                            <i class="fab fa-apple"></i>
                        </button>
                    </div>
                    
                    <div class="text-center text-sm text-gray-600 pt-4">
                        Don't have an account? <a href="createaccount.php" class="text-purple-600 font-semibold hover:underline">Sign up</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Password toggle functionality
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
            this.classList.toggle('fa-eye');
        });
        
        // Form validation
        document.getElementById("loginForm").addEventListener("submit", function(event) {
            let email = document.getElementById("email").value.trim();
            let password = document.getElementById("password").value.trim();

            if (email === "" || password === "") {
                event.preventDefault();
                alert("Please fill out all fields.");
            }
        });
        
        // Add animation to form elements
        document.addEventListener('DOMContentLoaded', function() {
            const formElements = document.querySelectorAll('#loginForm input, #loginForm button');
            formElements.forEach((element, index) => {
                element.classList.add('animate__animated', 'animate__fadeInUp');
                element.style.setProperty('--animate-duration', `${0.3 + (index * 0.1)}s`);
            });
        });
    </script>
</body>
</html>