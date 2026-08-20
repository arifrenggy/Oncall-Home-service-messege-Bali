# Product Requirement Document (PRD) & Design Spec
## Oncall & home service message Website (PHP + MySQL)

**Date:** 2026-08-20  
**Status:** Approved  
**Language:** English (Client-facing), Indonesian (Internal/Admin)  
**Tech Stack:** PHP 7.4+, MySQL 5.7+, Tailwind CSS, cPanel / Shared Hosting

---

## 1. Executive Summary
This project aims to build a high-performance, SEO-optimized, secure, and visually appealing business profile website for a home service massage (pijat panggilan) business in Bali. The website targets foreign tourists and expats. All client-facing copy is in English.

The architecture is transitioned to a full **PHP + MySQL** relational database structure. The client-facing homepage (`index.php`) is rendered server-side (SSR) for maximum SEO and Google Ads crawling efficiency. Content management is handled via a secure, session-protected **PHP Admin Dashboard** (`admin/index.php`) that connects directly to the MySQL database to execute CRUD queries using secure PDO Prepared Statements, and supports image file uploads directly to the server.

---

## 2. Target Audience
*   **Demographics:** Foreign tourists, holidaymakers, and expatriates living in or visiting Bali.
*   **Needs:** Premium on-call spa and massage services delivered directly to their villa, hotel, or residence with transparency and speed.
*   **Key Friction Points to Solve:** Secure booking flow, clear mobile UI, pricing trust, and seamless booking redirection to WhatsApp.

---

## 3. UI/UX & Design Requirements
*   **Mobile-First Design:** Optimized for smartphones (responsive layout), as most tourists browse on mobile.
*   **Color Palette (Green Theme):**
    *   **Primary Color:** Rich tropical greens (representing nature, serenity, wellness).
    *   **Secondary/Accent Colors:** Soft gold, warm beige, and clean white to convey a premium, clean, and luxury spa vibe.
*   **Typography:** Elegant serif fonts for headings (spa vibe) and clean sans-serif fonts for readable body copy.
*   **Key Sections on Homepage:**
    1.  **Header:** Logo and quick navigation links.
    2.  **Hero Banner:** Catchy tagline, background image, and a "Book Now" CTA.
    3.  **Why Choose Us:** Certified therapists, premium natural oils, high hygiene standards, and zero extra transport fees.
    4.  **Massage Menu (Services list):** Clear cards with images, descriptions, durations, pricing, and direct WhatsApp booking CTAs.
    5.  **How It Works:** 3-step booking explanation.
    6.  **Service Area Coverage:** Clear list of serviced regions in Bali + Google Maps embed.
    7.  **FAQ Section:** Collapsible accordion for common client inquiries.
    8.  **Footer:** Direct contact details, operating hours, and social media links.

---

## 4. Service Area Coverage
The service coverage areas displayed on the website include:
*   Pecatu, Uluwatu, Nusa Dua
*   Kuta, Seminyak, Canggu (including Pererenan)
*   Tanah Lot, Tabanan
*   Gianyar, Ubud

---

## 5. Functional Requirements
### A. Homepage (Server-Side Rendered PHP)
*   `index.php` loads all configuration settings, massage services, duration options, areas, and FAQs directly from the MySQL database using PHP PDO on page load.
*   Outputs fully formed HTML, meaning no waiting for client-side JS fetches, leading to near-instant loading speeds and excellent SEO indexing.

### B. MySQL Database Schema
The database consists of 5 tables:
1.  `settings`: Key-value configuration storage.
2.  `services`: Storage for each service type.
3.  `service_options`: Price tiers per service (mapped by foreign key to `services`).
4.  `areas`: List of active service locations.
5.  `faqs`: FAQs database.

### C. PHP Admin Dashboard
*   Protected by PHP Session authentication.
*   Full visual panels to update General settings, list/add/edit/delete services (with dynamic price rows and service image uploading directly to `assets/images/`), edit service areas, and manage FAQs.
*   Uses SQL Parameterized Queries (Prepared Statements) to ensure complete protection against SQL Injection.

### D. WhatsApp Booking Integration
*   Clicking a service book button launches WhatsApp with a pre-filled template message populated with selected service title, chosen duration, and price tier:
    ```text
    Hi, I would like to book a [Service Name] ([Duration] - [Price]). Here are my details:
    - Date & Time: 
    - Address (Hotel/Villa/Home): 
    - Number of People: 
    Please confirm my booking. Thank you!
    ```

---

## 6. Technical Architecture & Database Diagram
```mermaid
erDiagram
    settings {
        varchar setting_key PK
        text setting_value
    }
    services {
        int id PK, AUTO_INCREMENT
        varchar service_id
        varchar title
        text description
        varchar image_path
        tinyint featured
    }
    service_options {
        int id PK, AUTO_INCREMENT
        int service_ref FK
        varchar duration
        varchar price
    }
    areas {
        int id PK, AUTO_INCREMENT
        varchar area_name
    }
    faqs {
        int id PK, AUTO_INCREMENT
        text question
        text answer
    }

    services ||--o{ service_options : "has many options"
```
### Database Initialization Script (`schema.sql`)
A SQL script containing the table structures and pre-populated seed data is created so the user can easily import it into their phpMyAdmin panel.
