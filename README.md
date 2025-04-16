# 🧠 Aver-Web

Aver-Web is a **modern, AI-powered personal assistant platform** with a ChatGPT-style interface, memory vault, chat history, file uploads, and robust authentication.  
Built with PHP, Alpine.js, Tailwind CSS, and Docker, Aver-Web is designed for extensibility, security, and a beautiful user experience.

---

## 🚀 Project Summary

- **Repository:** [Averdrity/Aver-Web-Docker](https://github.com/Averdrity/Aver-Web-Docker)
- **Purpose:** AI chat application with persistent memory, chat history, file uploads, and user customization.
- **Tech Stack:** PHP (backend), Alpine.js (frontend), Tailwind CSS (styling), Docker (containerization)
- **Icons:** [Tabler Icons](https://tabler.io/icons) (outline style)

---

## 🏆 Core Features

### 💬 AI Chat System
- **Live streamed AI responses** (Ollama backend, token-by-token)
- **Markdown rendering** (Marked.js) & **syntax highlighting** (Prism.js)
- **Copy button** for code blocks
- **Animated typing indicator** while AI responds
- **Auto-save to Chat History** (with auto-title)
- **Web Search toggle** (injects web results into prompt)
- **File upload support**: `.js`, `.php`, `.txt`, images (view-only), etc.
- **Stop stream** button for AI responses

### 📜 Chat History Sidebar
- **Grouped by**: Today, Yesterday, Older, by Month
- **Search, rename, delete, and load chats**
- **3-dot menu** for chat actions
- **Sidebar toggle** (animated, Tabler icon)
- **Dynamic updates** (auto-refresh on change)

### 🧠 Memory Vault Sidebar
- **Display, edit, delete** user memories
- **Categories, tags, favorite flag**
- **Search/filter** by tag/category/favorite
- **Pattern detection**: auto-save facts from chat
- **Add/Edit via modal** (multi-tag support)
- **Sidebar toggle** (animated, Tabler icon)

### ⬆️ File Upload System
- **Upload limit**: 20 files/user, auto-expire after 24h
- **Supported types**: text/code/images
- **Modal**: shows files, time-left, delete, preview
- **Upload progress** feedback
- **File preview** (inline for images/text)
- **Security**: type/size validation

### 👤 Auth System
- **Modal-based** Login/Register/Profile forms (Alpine.js)
- **Session-based** authentication (secure, early session_start)
- **Profile info**: nickname, country, bio, password change
- **Protected routes** using `isLoggedIn()`
- **Admin role** support (future)

### 🌗 Dark/Light Theme (Hybrid)
- **Default: Dark for guests**
- **User preference**: stored in DB, synced with frontend
- **Theme toggle** in footer (syncs with backend/localStorage)
- **System theme detection** (auto)
- **Smooth transitions** and full coverage

---

## 🧩 Logic & Tech Stack

### 🔧 Backend (PHP)
- Modular `/includes` structure for all handlers
- **PDO** for secure DB access
- **AJAX endpoints**: `auth_handler.php`, `chat_handler.php`, `memory_handler.php`, `upload_handler.php`, `profile_handler.php`, `theme_handler.php`
- **Memory injection**: context-aware, tag-based, and pattern-based
- **Audit/log tables** and analytics hooks (future)
- **Configurable** via `config.php` and environment variables

### 🧠 Frontend (Alpine.js + Tailwind)
- **Alpine.js v3** for all reactive UI states (modals, sidebar, uploads, theme)
- **Tailwind CSS** (custom config) with extended animations, colors, and themes
- **main.js**: Auth modal logic, profile loading, theme logic, and reusable form handler
- **chat.js**: Handles chat rendering, streaming, animated dots, copy logic, and saving
- **ARIA & accessibility**: Focus management, keyboard navigation, screen reader support

### 🐳 DevOps
- **Docker Compose**: PHP/Apache, MySQL, phpMyAdmin, Ollama (AI backend)
- **Dockerfile**: Installs PHP extensions, Composer, Node.js/NPM for Tailwind build
- **Persistent volumes** for uploads
- **Healthchecks** and environment-based configs
- **Security headers** and static asset caching (Apache)

---

## 📅 Project Roadmap & Checklist

### ✅ Phase 1 – Core Platform
- [x] Full chat page with live AI streaming
- [x] Auth system & modals
- [x] File upload & preview
- [x] Tailwind dark/light theme toggle
- [x] Mobile/desktop responsive layout

### ✅ Phase 2 – Productivity Features
- [x] Chat History & Memory Vault UI
- [x] Modal handling
- [x] Search, delete, and edit logic
- [x] Uploads modal

### 🔄 Phase 3 – Memory Intelligence (In Progress)
- [ ] Advanced memory injection (context-aware, tag-based, priority)
- [ ] Smart prompt injection using `memory_fetcher.php`
- [ ] Pattern-based auto-memory extraction

### 🔜 Phase 4 – UI/UX Polish & Performance
- [ ] Style consistency pass (modals, buttons, alerts)
- [ ] Dark/light adaptive icons & buttons
- [ ] Animated transitions for chat elements
- [ ] Performance boost: lazy load, debounce input, etc.

### 🔮 Phase 5 – Expansion & Admin
- [ ] News feed & announcement system
- [ ] Custom widget/slider framework
- [ ] Admin dashboard (uploads, chat logs, memory, themes)
- [ ] API key system for Pro users (with limits)
- [ ] Storefront, user reviews, GDPR-compliant static pages

---

## 🛡️ Security & Best Practices

- **Session-based auth** with secure `session_start()` early
- **No client-side secrets**
- **File uploads**: 24h auto-expire, type/size restrictions
- **Input validation/sanitization** everywhere
- **Rate limiting** and **brute-force protection** (planned)
- **Security headers** and **error logging** (planned)

---

## 📝 Development & Contribution

- **Modular codebase**: `/src` for app, `/includes` for backend logic, `/assets` for JS/CSS, `/components` for UI
- **Testing & CI/CD**: (Planned) Automated tests and deployment pipeline
- **Documentation**: (Planned) Full developer and user documentation

---

## 🎨 Visual Style

- **ChatGPT-inspired**: Centered, clean, and spacious
- **TailwindCSS**: Custom animations, rounded buttons, shadow, and layout enhancements
- **Tabler Icons (outline)**: Consistent iconography
- **Inter font**: Modern, readable typography

---

## 📋 Full Upgrade Checklist (2025)

- [ ] **Backend**: Tag-based memories, chat sharing, admin roles, news/announcements, audit/log tables, error logging, analytics hooks, RESTful endpoints, password reset, email verification, session security, DB config, etc.
- [ ] **Frontend**: Multi-tag memory modals, chat sharing UI, stop stream button, ARIA/accessibility, About/Terms/Contact, news feed, admin panel UI, style polish, performance, documentation.
- [ ] **DevOps**: Ollama service, persistent uploads, healthchecks, security headers, static asset caching, automated testing, CI/CD, environment configs.

See [Project Board](https://github.com/Averdrity/Aver-Web-Docker/projects) for detailed progress.

---

## 🛠️ Additional Notes

- **Icons**: [Tabler Icons](https://tabler.io/icons) (outline style)
- **Docker**: Containerized for easy deployment and scaling
- **Styling**: Tailwind CSS with custom config and dark/light theming
- **Interactivity**: Alpine.js for all UI state and reactivity

---


## 🧱 Folder Structure

```text
C:\Projects\Aver-Web
/aver-web/apache/000-default.conf
/aver-web/db-data/ (contents hidden)
└── src/
    └── api/
        ├── chat.php
        └── web_search.php
    └── assets/
        └── css/
            ├── style.css
            └── tailwind.css
        └── icons/ (contents hidden)
        └── js/
            ├── chat.js
            └── main.js
        ├── aw_logo_transparent_64x64.png
        └── favicon.ico
    └── auth/
        ├── login.php
        ├── logout.php
        ├── profile.php
        └── register.php
    └── components/
        └── modals/
            ├── login-modal.php
            ├── memory-modal.php
            ├── profile-modal.php
            ├── register-modal.php
            └── uploaded-files-modal.php
        ├── footer.php
        └── header.php
    └── data/
        └── patterns.json
    └── includes/
        ├── auth_handler.php
        ├── chat_handler.php
        ├── config.php
        ├── db.php
        ├── memory_fetcher.php
        ├── memory_handler.php
        ├── pattern_detector.php
        ├── profile_handler.php
        ├── session.php
        ├── theme_handler.php
        └── upload_handler.php
    └── node_modules/ (contents hidden)
    └── public/
    └── uploads/
    ├── .gitignore
    ├── .htaccess
    ├── chat.php
    ├── index.php
    ├── package.json
    ├── package-lock.json
    ├── postcss.config.js
    └── tailwind.config.js
/docker-compose.yml
/Dockerfile
```

