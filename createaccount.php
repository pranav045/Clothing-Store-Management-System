<?php
include("database.php");
$msg = '';

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['Name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['CreatePassword'];
    $cpassword = $_POST['ConfirmPassword'];

    if (!isset($_POST['terms'])) {
        $msg = "You must agree to the terms and conditions!";
    } elseif ($password !== $cpassword) {
        $msg = "Passwords do not match!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $select1 = "SELECT * FROM `users` WHERE email = '$email'";
        $select_user = mysqli_query($conn, $select1);

        if (mysqli_num_rows($select_user) > 0) {
            $msg = "User already exists!";
        } else {
            $insert1 = "INSERT INTO `users` (`Name`, `Email`, `CreatePassword`) VALUES ('$name', '$email', '$hashed_password')";
            if (mysqli_query($conn, $insert1)) {
                $_SESSION['registration_success'] = true;
                header('location: login.php');
                exit();
            } else {
                $msg = "Registration failed. Please try again!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Fashion Frontier</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>
<body class="flex flex-col h-screen justify-center items-center bg-gradient-to-br from-gray-100 to-gray-300">
    <div class="relative w-full max-w-4xl mx-4">
        <div class="absolute inset-0 w-full h-full bg-cover bg-center opacity-20 rounded-3xl" style="background-image: url('https://source.unsplash.com/1600x900/?fashion,runway');"></div>
        
        <div class="relative bg-white/90 backdrop-blur-sm shadow-2xl rounded-3xl overflow-hidden md:flex">
            <div class="hidden md:flex md:flex-col md:justify-center md:w-1/2 p-8 bg-gradient-to-br from-indigo-500 to-purple-600">
                <div class="text-white text-center space-y-6">
                    <h2 class="text-4xl font-bold animate__animated animate__fadeIn">Welcome to Fashion Frontier</h2>
                    <p class="text-lg">Join our community of fashion enthusiasts and get access to exclusive deals and trends.</p>
                    <div class="flex justify-center space-x-4">
                        <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center">
                            <i class="fas fa-truck text-2xl"></i>
                        </div>
                        <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center">
                            <i class="fas fa-percent text-2xl"></i>
                        </div>
                        <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center">
                            <i class="fas fa-crown text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:w-1/2 w-full p-8 md:p-10">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-800">Create Account</h1>
                    <a href="login.php" class="text-sm text-blue-600 hover:underline">Already a member?</a>
                </div>
                
                <?php if ($msg): ?>
                    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md animate__animated animate__shakeX">
                        <i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($msg) ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST" class="space-y-4" onsubmit="return validateForm()">
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <div class="relative">
                            <i class="fas fa-user absolute left-3 top-3 text-gray-400"></i>
                            <input type="text" id="name" name="Name" placeholder="Enter your name" 
                                   class="w-full pl-10 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                        </div>
                    </div>
                    
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-3 text-gray-400"></i>
                            <input type="email" id="email" name="email" placeholder="Enter your email" 
                                   class="w-full pl-10 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                        </div>
                    </div>
                    
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-3 text-gray-400"></i>
                            <input type="password" id="password" name="CreatePassword" placeholder="Enter password" 
                                   class="w-full pl-10 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                            <i class="fas fa-eye-slash absolute right-3 top-3 text-gray-400 cursor-pointer" id="togglePassword"></i>
                        </div>
                        <div class="text-xs text-gray-500 mt-1" id="password-strength"></div>
                    </div>
                    
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-3 text-gray-400"></i>
                            <input type="password" id="confirmPassword" name="ConfirmPassword" placeholder="Confirm password" 
                                   class="w-full pl-10 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                            <i class="fas fa-eye-slash absolute right-3 top-3 text-gray-400 cursor-pointer" id="toggleConfirmPassword"></i>
                        </div>
                    </div>

                    <div class="flex items-start space-x-2 pt-3">
                        <input type="checkbox" id="terms" name="terms" class="mt-1 w-4 h-4 accent-purple-600">
                        <label for="terms" class="text-sm text-gray-600">I agree to the <a href="#" class="text-purple-600 hover:underline">terms and conditions</a> and <a href="#" class="text-purple-600 hover:underline">privacy policy</a></label>
                    </div>

                    <button class="w-full py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition duration-300 shadow-md hover:shadow-lg flex items-center justify-center" name="submit">
                        <i class="fas fa-user-plus mr-2"></i> Sign Up
                    </button>
                    
                    <div class="text-center text-sm text-gray-500">or sign up with</div>
                    
                    <div class="flex justify-center space-x-4">
                        <button type="button" class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition">
                            <i class="fab fa-facebook-f"></i>
                        </button>
                        <button type="button" class="w-10 h-10 rounded-full bg-red-600 text-white flex items-center justify-center hover:bg-red-700 transition">
                            <i class="fab fa-google"></i>
                        </button>
                        <button type="button" class="w-10 h-10 rounded-full bg-black text-white flex items-center justify-center hover:bg-gray-800 transition">
                            <i class="fab fa-apple"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Password toggle functionality
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
        const confirmPassword = document.querySelector('#confirmPassword');
        
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
            this.classList.toggle('fa-eye');
        });
        
        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
            this.classList.toggle('fa-eye');
        });
        
        // Password strength indicator
        password.addEventListener('input', function() {
            const strengthText = document.getElementById('password-strength');
            const strength = checkPasswordStrength(this.value);
            
            if (this.value.length === 0) {
                strengthText.textContent = '';
                strengthText.className = 'text-xs text-gray-500 mt-1';
                return;
            }
            
            strengthText.textContent = strength.message;
            strengthText.className = `text-xs mt-1 ${strength.color}`;
        });
        
        function checkPasswordStrength(password) {
            // Check password strength
            const hasUpperCase = /[A-Z]/.test(password);
            const hasLowerCase = /[a-z]/.test(password);
            const hasNumbers = /\d/.test(password);
            const hasSpecialChars = /[!@#$%^&*(),.?":{}|<>]/.test(password);
            const length = password.length;
            
            let strength = 0;
            
            if (length >= 8) strength++;
            if (hasUpperCase) strength++;
            if (hasLowerCase) strength++;
            if (hasNumbers) strength++;
            if (hasSpecialChars) strength++;
            
            if (strength <= 2) {
                return { message: 'Weak password', color: 'text-red-500' };
            } else if (strength <= 4) {
                return { message: 'Moderate password', color: 'text-yellow-500' };
            } else {
                return { message: 'Strong password', color: 'text-green-500' };
            }
        }
        
        function validateForm() {
            let name = document.getElementById("name").value.trim();
            let email = document.getElementById("email").value.trim();
            let password = document.getElementById("password").value.trim();
            let confirmPassword = document.getElementById("confirmPassword").value.trim();
            let terms = document.getElementById("terms").checked;

            if (name === "" || email === "" || password === "" || confirmPassword === "") {
                alert("All fields must be filled out.");
                return false;
            }

            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }
            
            if (!terms) {
                alert("You must agree to the terms and conditions.");
                return false;
            }

            return true;
        }
    </script>
</body>
</html>