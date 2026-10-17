# Mini eCitizen - Public Service Request System

A web-based public service request system modeled after eCitizen portals, allowing citizens to register, submit service requests, track application status, and enabling administrators to review, manage, and analyze incoming cases.

---

## 🚀 Features

### 👤 Citizen Module
* **Secure Authentication:** Register and log in with password hashing (`password_hash`).
* **Service Requests:** Submit applications by selecting from available public services and providing description details.
* **Tracking System:** Automatically generates unique tracking numbers (`ECIT-XXXXXXXX`) and displays real-time application statuses (`Pending`, `Under Review`, `Approved`, `Rejected`).

### 🛠️ Admin Module
* **Control Panel:** Review all citizen applications with user details and tracking numbers.
* **Status Management:** Dynamically update application statuses via inline admin action controls.
* **Basic Analytics:** Quantitative summary metric cards displaying total applications alongside a breakdown of Pending, Under Review, Approved, and Rejected cases.

---

## 🛠️ Tech Stack
* **Backend:** PHP (PDO for secure database queries)
* **Frontend:** Bootstrap 5, FontAwesome Icons
* **Database:** MySQL (Relational schema with Foreign Keys)
* **Server Environment:** XAMPP (Apache)

---

## ⚙️ Local Setup Instructions

1. **Clone or Download the Repository**
   Place your project folder inside your local XAMPP root directory:
   ```text
   C:\xampp\htdocs\mini-ecitizen\
