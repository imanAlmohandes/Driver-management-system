 Driver Management System

A complete driver and trips management platform built with Laravel, designed to manage drivers, vehicles, trips, fuel logs, and maintenance with multi-language support.

---

 Features

Admin Panel
- Manage drivers & users
- Manage vehicles with statuses
- Trips scheduling & cancellation
- Fuel logs & maintenance records
- Dashboard statistics with AJAX
- Soft delete & recycle bin
- Notifications system

 Driver Panel
- View assigned vehicle
- Start / complete trips
- Request trip cancellation with reason
- Add fuel & maintenance logs
- Personal dashboard

 Multi Language
- Arabic / English
- Translatable fields using Spatie Translatable

---

ech Stack

- Laravel 12
- MySQL
- Bootstrap 4
- jQuery + AJAX
- Spatie Translatable
- Laravel Notifications
- Soft Deletes

---

 Installation

```
bash
git clone https://github.com/imanAlmohandes/Driver-management-system

cd driv_project
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

---

 Accounts

Admin
email: admin@admin.com
password: password

Driver
email: hope15@example.org
password: password

---

 Project Structure

- Users & Drivers
- Vehicles
- Trips
- Fuel Logs
- Maintenance Logs
- Stations
- Maintenance Companies

---

 Notes

- All statuses are translated
- Validation applied on all forms
- Conflict check for vehicles & drivers
- AJAX filtering & dashboards

---

 Prepared for

 Driver System  
Student: eng.Iman Awni Sabbah  
Supervisor: eng.Ayman Adil Ayyad
