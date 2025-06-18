# Arm Wrestling E-commerce PHP Web Application

This is a custom-built arm wrestling e-commerce website developed with **vanilla PHP**, implementing a **MVC structure**. The application supports category-based product browsing, cart management, seller order and products management, and more.

---

## Features Overview

### User Functionality

- **User Authentication**  
  Users can register, log in, and maintain authenticated sessions using PHP sessions.

- **Two Roles: User and Seller**  
  The system supports two distinct roles:
  - **User:** Regular customer
  - **Seller:** Authorized to manage orders and products

- **Category-Based Product Browsing**  
  Products are listed by category (e.g., _clothing_, _equipment_), and include details such as name, price, size, availability, image  and description.

- **Pagination**  
  Products are paginated.

- **Shopping Cart**  
  Users can:
  - Add products by size
  - Adjust quantities (AJAX)
  - See real-time total price updates (AJAX)
  - Remove items
  - Proceed to checkout.

- **Order Summary & Checkout**  
  Users can review the order and proceed to complete the purchase.

- **Profile Management**  
  Users have a profile page where they can update their personal information.

---

### Seller Functionality

- **Order Management**
  - View all customer orders
  - Change order status (e.g., `processing`, `shipped`) using dropdown menus
  - View full order details using Bootstrap 5 modals

- **Product Management**
  - **CRUD functionality**
    - Add new products via Bootstrap modals
    - Edit existing products using modals prefilled with AJAX
    - Change product status: `draft`, `published`, or `deleted`
    - View detailed product info and available inventory

---

## Technologies Used

- vanilla object-oriented PHP
- HTML
- Bootstrap 5
- JavaScipt (+AJAX)
- MySQL

---


## Key Implementation Notes

- **Front Controller:** `public/index.php` routes requests using query parameters (e.g., `?target=product&action=index`).
- **Singleton Pattern** – Used for managing a single instance of the PDO connection across the application
- **Security:** Sensitive files are stored outside `/public`. Seller-only routes are protected via session-based role checks.
- **Responsive Design:** The application uses Bootstrap 5 to support both mobile and desktop devices.

---

## Setup Instructions

1. Clone the repository
2. Import the SQL schema into your MySQL database (exported_db_02.sql)
3. Create a configuration file `config.php` (by renaming the provided example config and replacing the placeholder values) for DB credentials

---
