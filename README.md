ওকে 👍 তাহলে Bootstrap বাদ দিয়ে TailwindCSS ব্যবহার করা হয়েছে সেটা আপডেট করে দিচ্ছি। বাকিটা একই থাকবে।

---

# 📘 Updated README.md (with Tailwind)

````markdown
# 🏢 Automated Property Management System

A **comprehensive rental & property management solution** built with **Laravel + Blade + TailwindCSS**.  
The system is designed to handle property rentals, leases, payments, utilities, tax management, and sales transactions under one platform.  

It enables **Admins, Owners, Tenants, and other stakeholders** to efficiently manage properties, rental agreements, maintenance, payments, and reports.

---

## 🔹 Current Scope
✔ Authentication (Admin, Owner, Tenant)  
✔ Property Management (CRUD, Units, Facilities, Gallery)  
✔ Tenant Management (Registration, Assign to unit, Rental history)  
✔ Lease & Agreement Module (create, renew, terminate)  
✔ Client Portal with inquiry & booking system  

---

## 🔹 Pending / Future Enhancements
⏳ Rent Collection & Payment Tracking  
⏳ Maintenance Request Tracking  
⏳ Tax & Utility Bill Management  
⏳ Reports & Analytics (Basic) 
⏳ Agent & Buyer Module (profile, commission, sales leads)  
⏳ Real Estate Sales Management (property sales, contracts, milestones)  
⏳ CRM & Lead Management (buyer inquiry, follow-up, conversion)  
⏳ Online Payment Gateway (Stripe/SSLCommerz)  
⏳ Email/SMS Notification System  
⏳ Multi-language Support (English/Bangla)  
⏳ Document Storage on Cloud (S3/Drive)  

---

## 🔹 Core Modules (Implemented + Planned)

### 1. Authentication & User Management
- Multi-role login (Admin, Owner, Tenant, Agent, Buyer)  
- Profile & document management  
- Role-based dashboard access  

### 2. Property Management
- Property CRUD (Add/Edit/Delete)  
- Unit/Flat/Room management  
- Facilities (parking, utilities, etc.)  
- Gallery & image upload  
- Property legal document submission & admin approval  
- Ownership history tracking  

### 3. Tax & Utility Management
- Property tax records & receipts  
- Annual tax submission tracking  
- Utility bills (electricity, water, gas, internet)  
- Bill payment + receipt upload (Owner)  
- Admin verification  

### 4. Tenant & Lease Management
- Tenant registration & approval  
- Assign tenant to property/unit  
- Rental history (agreements, payments)  
- Lease agreements (create, renew, terminate)  
- Agreement document upload  

### 5. Rent & Payment
- Rent collection & tracking  
- Payment history records  
- Invoice generation (Blade/PDF)  
- Overdue reminders  

### 6. Maintenance Requests
- Tenant submits request  
- Status tracking (Pending/In Progress/Completed)  
- Maintenance cost logging  

### 7. Reports & Analytics
- Income & Expense Reports  
- Occupancy Reports  
- Tenant Payment Compliance  
- Property Tax & Bill Reports  
- Owner-wise Liability Summary  

---

## 🛠 Tech Stack

### Frontend (UI Layer)
- Blade Templates (Laravel default)  
- TailwindCSS (Responsive UI)  
- Vanilla JavaScript / Alpine.js (for interactivity)  
- Font Awesome / Heroicons  

### Backend (Application Layer)
- Laravel 9.52.20  
- PHP 8.0.30  
- Laravel Breeze / UI (Authentication)  
- Spatie Roles & Permissions (RBAC)  
- Laravel Sanctum (API auth for portal/app)  
- Laravel Excel (Export/Import data)  
- Laravel Notifications (Email/SMS alerts)  
- Spatie Activity Log (Audit log)  

### Database
- MySQL (`hybrid_property_system` DB for testing)  
- Eloquent ORM  
- Pivot tables for many-to-many relations (e.g. Agents ↔ Properties, Buyers ↔ Interests)  
- Migration & Seeder support  

### Utilities & Tools
- Composer (Dependency Manager)  
- NPM (JS/CSS build, Tailwind compiling)  
- Artisan CLI  
- Vite (Asset bundler for Tailwind/JS)  
- Git (Version Control)  
- Debugging: Laravel Debugbar, Telescope (dev only)  
- Horizon (for queues, if notifications added)  

---

## ⚙️ Installation & Setup

### 🔹 Clone & Install
```bash
git clone https://github.com/rubaiyat07/hybrid-property-system.git
cd hybrid-property-system
composer install
npm install && npm run dev
cp .env.example .env
php artisan key:generate
````

### 🔹 Database

```bash
php artisan migrate --seed
```

### 🔹 Run Server

```bash
php artisan serve
```

---

## 📜 License

This project is licensed under the **MIT License**.

---

## 👨‍💻 Author

**Rubaiyat Afreen**
📧 Email: [rubaiyat97wd@gmail.com](mailto:rubaiyat97wd@gmail.com)
🌐 GitHub: [rubaiyat07](https://github.com/rubaiyat07)

```

---
