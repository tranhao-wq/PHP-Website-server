# Online Shoe Store Website

A full-featured e-commerce website for shoes built with PHP, MySQL, JavaScript, and Bootstrap.
## Pics
<img width="1684" height="823" alt="image" src="https://github.com/user-attachments/assets/d5344c39-1342-4254-b5e2-27b05573ce0c" />
<img width="1684" height="819" alt="image" src="https://github.com/user-attachments/assets/2bf095e0-1861-4166-84af-6a625e19cafd" />
<img width="1682" height="819" alt="image" src="https://github.com/user-attachments/assets/f5fe526d-0d52-42e8-8c9d-8414bfd17846" />
<img width="1066" height="820" alt="image" src="https://github.com/user-attachments/assets/feeb1e4e-cc98-45a9-971b-48c0eb0f1ca4" />
<img width="1299" height="551" alt="image" src="https://github.com/user-attachments/assets/c605a262-b725-4bea-8e11-e4817b9d5aa1" />
<img width="1682" height="808" alt="image" src="https://github.com/user-attachments/assets/ef3270cd-1ee1-44f8-a208-0fbff1ae3223" />
<img width="1656" height="808" alt="image" src="https://github.com/user-attachments/assets/77c32d86-4653-4517-8d58-51de863cba26" />
<img width="1656" height="806" alt="image" src="https://github.com/user-attachments/assets/cded5e04-634b-442a-883d-07e17fb361eb" />
<img width="1656" height="803" alt="image" src="https://github.com/user-attachments/assets/4027b2e8-9510-4cea-8a27-2072773a0d6e" />
<img width="1642" height="803" alt="image" src="https://github.com/user-attachments/assets/d39dc189-7bb4-45c4-85cd-61b341a952b1" />
<img width="1642" height="796" alt="image" src="https://github.com/user-attachments/assets/91d7da1c-21cd-4a10-8e6a-728369b43ce7" />

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
