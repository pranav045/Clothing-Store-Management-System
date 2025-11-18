// cart.js
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
                    size: productElement.querySelector('.product-size').textContent,
                    image: productElement.querySelector('img').src
                };
                cart.addItem(product);
                
                // Visual feedback
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check mr-1"></i> Added';
                button.classList.remove('bg-pink-500', 'hover:bg-pink-600');
                button.classList.add('bg-green-500', 'hover:bg-green-600');
                
                // Reset button after 1.5 seconds
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('bg-green-500', 'hover:bg-green-600');
                    button.classList.add('bg-pink-500', 'hover:bg-pink-600');
                }, 1500);
            }
        });
    });
});