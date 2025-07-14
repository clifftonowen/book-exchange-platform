# 📚 P2P Book Exchange Platform: Giving Books a Second Life (Proof of Concept)

## 📖 Project Overview

In an increasingly digital world, many physical books end up unread on shelves. This project is a Proof of Concept (POC) for a Peer-to-Peer (P2P) Book Exchange Platform aimed at giving these neglected books a second life. It connects readers, promotes a culture of sharing and sustainability, and reduces waste by facilitating easy book exchange.

This web application demonstrates a seamless user journey from listing a book to completing an exchange, incorporating key community features and a touch of innovation to highlight its market potential.

---

## ✨ Key Features Implemented (POC Scope)

1. **User Authentication & Profiles**
   - Secure User Registration and Login.
   - Basic User Profiles displaying join date, average rating, and exchange history.

2. **Book Management (CRUD)**
   - List books with title, author, genre, condition, and cover image.
   - Book Details Page for full information.
   - "My Books" page for users to manage their listings.
   - Edit and delete owned books.

3. **Book Discovery & Search**
   - Dashboard with available books (excludes the user’s own).
   - Search by title or author.
   - Guests can browse but not exchange.

4. **Core Exchange Mechanism**
   - Send exchange requests.
   - View/manage incoming requests.
   - Accept/reject requests.
   - Mark exchanges as completed.

5. **Basic Messaging System**
   - In-app messaging between exchange parties.

6. **Ratings & Reviews**
   - Leave star ratings and comments after completed exchanges.
   - Public user profiles display average ratings and reviews.

7. **Wishlist System**
   - Add books to a personal wishlist.
   - "My Wishlist" page to manage items.

8. **AI-Powered Recommendations**
   - "Recommended for You" section using OpenAI to suggest books based on listed genres.

---

## ⚙️ Technology Stack

- **Backend:** Laravel 11 (PHP)
- **Frontend:** Laravel Blade + Alpine.js
- **Styling:** Tailwind CSS
- **Database:** SQLite (for development; scalable to MySQL/PostgreSQL)
- **Local Dev Tools:** Laravel Herd / Laragon
- **External API:** OpenAI API for recommendations
- **Package Managers:** Composer, npm

---

## 🚀 Setup Instructions

### 1. Prerequisites
- Git: [https://git-scm.com/downloads](https://git-scm.com/downloads)
- PHP 8.2+: via Laravel Herd / Laragon
- Composer: [https://getcomposer.org/download/](https://getcomposer.org/download/)
- Node.js + npm: [https://nodejs.org/en/download/](https://nodejs.org/en/download/)

### 2. Clone Repository
```bash
git clone https://github.com/clifftonowen/book-exchange-platform.git
cd book-exchange-platform
```

### 3. Install PHP Dependencies
```bash
composer install
```

### 4. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```
- Edit `.env`:
  - Change `DB_CONNECTION=mysql` to `DB_CONNECTION=sqlite`.
  - Comment/delete other `DB_*` lines.
  - Set: `OPENAI_API_KEY=YOUR_OPENAI_API_KEY`

### 5. Create SQLite Database
```bash
touch database/database.sqlite
```

### 6. Run Migrations
```bash
php artisan migrate
```

### 7. Storage Link for Images
```bash
php artisan storage:link
```

### 8. Install JS Dependencies & Compile Assets
```bash
npm install
npm run dev
```

### 9. Run Development Server
- Terminal 1:
```bash
php artisan serve
```
- Terminal 2:
```bash
npm run dev
```

### 10. Access the App
Visit:  
```
http://127.0.0.1:8000
```

---

## 📝 Decisions Worth Noting

- **Tech Stack:** Laravel + Blade + Tailwind CSS + SQLite for rapid development.
- **SQLite:** Chosen for simplicity in dev; easy to migrate to MySQL/PostgreSQL.
- **Cascade Deletes:** Ensures cleanup on deletions with `onDelete('cascade')`.
- **Separate "My Books" & Dashboard:** Encourages discovery.
- **AI Recommendations:** Lightweight via OpenAI without deep data modeling.
- **Guest Browsing:** Non-logged-in users can explore available books.

---

## 🗺️ Future Project Plan & Milestones

### Milestone 1: MVP Refinement
- Notifications for exchanges/messages/wishlist matches.
- Personalized activity feed.
- Enhanced search & filtering.
- Richer user profiles: exchange counts, response time.
- More book details via Google Books API.
- UI/UX polishing and mobile responsiveness.

### Milestone 2: Advanced Features
- Location-based matching.
- ISBN autofill via Google Books API.
- Wishlist matching notifications.
- Community forums/groups.
- Admin dashboard for moderation.

### Milestone 3: Production Readiness
- Database migration to MySQL/PostgreSQL.
- Deployment via Docker, CI/CD pipelines.
- Unit, feature, browser tests.
- Performance optimizations.
- Security hardening: input sanitization, rate limiting.
- Integrations: email/SMS for notifications.

---

Thank you for checking out this POC! 🚀
