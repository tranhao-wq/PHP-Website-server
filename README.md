# Online Shoe Store Website

A full-featured e-commerce website for shoes built with PHP, MySQL, JavaScript, and Bootstrap.

## 🌟 Features

### Customer Features
- User Authentication (Register/Login/Logout)
- Browse Products with Categories
- Product Details View
- Shopping Cart Management
- Checkout Process
- Order History
- User Profile Management

### Admin Features
- Secure Admin Dashboard
- Product Management (Add/Edit/Delete)
- Order Management
- User Management
- Admin Authentication

## 📂 Project Structure

```
Sources WebSite Bán Giày Trực Tuyến/
├── index.php                 # Home page
├── products.php             # Products listing
├── product-detail.php      # Single product view
├── cart.php                # Shopping cart
├── checkout.php            # Checkout process
├── login.php              # User login
├── register.php           # User registration
├── profile.php           # User profile
├── my-orders.php         # Order history
├── admin/                # Admin section
│   ├── dashboard.php    # Admin dashboard
│   ├── products.php     # Product management
│   ├── orders.php      # Order management
│   ├── users.php       # User management
│   └── ...
├── assets/             # Static assets
│   ├── css/           # Stylesheets
│   ├── js/            # JavaScript files
│   └── image/         # Product images
├── includes/          # PHP includes
│   ├── config.php    # Database configuration
│   ├── functions.php # Helper functions
│   ├── header.php    # Common header
│   └── footer.php    # Common footer
└── database/         # Database schema
    └── thuadmin.sql  # SQL dump file
```

## 🚀 Technologies Used

- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap
- **Backend**: PHP
- **Database**: MySQL
- **Other Tools**: AJAX for dynamic content loading

## ⚙️ Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/tranhao-wq/PHP-Website-server.git
   ```

2. Import the database:
   - Create a new MySQL database
   - Import `database/thuadmin.sql`

3. Configure the database connection:
   - Open `includes/config.php`
   - Update the database credentials

4. Set up your web server:
   - Configure your web server (Apache/Nginx) to serve the project
   - Ensure PHP and MySQL are installed and running

5. Access the website:
   - Open your browser and navigate to the project URL
   - Admin panel can be accessed at `/admin/admin-login.php`

## 👥 User Types

### Customer
- Browse and search products
- Add items to cart
- Place orders
- View order history
- Manage profile

### Administrator
- Manage product inventory
- Process orders
- Manage user accounts
- View sales statistics

## 🔒 Security Features

- Password Hashing
- Session Management
- Input Validation
- SQL Injection Prevention
- XSS Protection

## 📝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 👨‍💻 Authors

- Group 1 - Online Shoe Store Website Team
