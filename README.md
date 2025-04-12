---

## 🧠 AVER-WEB – Project Summary (April 2025)

---

### ✅ Project Goal
Aver-Web is a **modern, AI-powered personal assistant platform** designed with:
- **ChatGPT-style interface**
- **Memory Vault system**
- **Chat History system**
- File upload & inline content preview
- **Dark/Light hybrid theming**
- Designed for **user customization**, future **expansion with a storefront**, and **tight authentication control**

---

## 🛠️ Core Features

### 1. 💬 AI Chat System (chat.php)
- Live streamed responses using `Ollama` via `/api/chat.php`
- Markdown rendering via **Marked.js**
- Syntax highlighting via **Prism.js**
- Copy button support for code blocks
- Animated typing dots (`...`) while AI responds
- **Auto-save to Chat History**
- Upload support: `.js`, `.php`, `.txt`, images (view-only), etc.
- Web Search toggle support (`/api/web_search.php`)

### 2. 📜 Chat History Sidebar
- Shows previous chats (title + timestamp)
- Grouped by: Today, Yesterday, Older
- Functions: Load, Rename, Delete, Search
- Toggle open/close via `square-toggle.svg` button (outside sidebar)

### 3. 🧠 Memory Vault Sidebar
- Display, edit, delete user memories
- Memory categories, titles, and content
- Triggered by AI or manually by user
- Toggle open/close via `square-toggle.svg` button
- Includes memory modal for saving/editing (`memory-modal.php`)
- Data saved via `memory_handler.php`

### 4. ⬆️ File Upload System
- Upload limit: 20 files per user (auto-expire after 24h)
- Supported: Text/code/images
- Modal shows files with time-left & delete options
- Backed by `upload_handler.php` and `uploads` table
- UI tied to Alpine `$store.uploads`

### 5. 👤 Auth System
- Modal-based Login/Register/Profile forms (Alpine)
- Auth handled via `auth_handler.php`
- Session-based auth stored in `session.php`
- Profile info saved via `profile_handler.php` (nickname, country, bio, password change)
- Protected routes using `isLoggedIn()` function

### 6. 🌗 Dark/Light Theme (Hybrid System)
- Default: Dark for guests
- Users: Stored in DB, synced with frontend (via `theme_handler.php`)
- Toggle switch in `footer.php`
- Controlled via localStorage + body `data-logged-in`
- Tailwind's `darkMode: 'class'` system used globally

---

## 🧩 Logic & Tech Stack

### 🔧 Backend (PHP)
- Modular structure in `/includes`
- Secure DB access using **PDO**
- All handlers: `auth_handler.php`, `chat_handler.php`, `memory_handler.php`, `upload_handler.php`, `profile_handler.php`, `theme_handler.php`
- Follows POST-only pattern for AJAX endpoints
- `config.php` stores DB creds & constants

### 🧠 Frontend (JS + Tailwind + Alpine)
- **Alpine.js v3** for all reactive UI states (modals, sidebar, uploads, theme)
- **Tailwind CSS** (custom config) with extended animations, colors, and themes
- `main.js`: Auth modal logic, profile loading, theme logic, and reusable form handler
- `chat.js`: Handles chat rendering, streaming, animated dots, copy logic, and saving

---

## 🗺️ Project Plan – Phased Structure

### ✅ Phase 1 – DONE
- Full chat page
- Auth system & modals
- Live AI streaming with Markdown + Code
- File upload & preview
- Tailwind dark/light theme toggle
- Full mobile/desktop responsive layout

### ✅ Phase 2 – DONE
- Chat History & Memory Vault UI
- Modal handling
- Search, delete, and edit logic
- Uploads modal

### 🔄 Phase 3 – IN PROGRESS
- Advanced memory injection (context-aware memory suggestions to AI)
- Tag-based priority & memory metadata
- Smart prompt injection using `memory_fetcher.php`

### 🔜 Phase 4 – NEXT
- Style consistency pass (modals, buttons, error/success alerts)
- Dark/light adaptive icons & buttons
- Animated transitions for chat elements
- Tiny performance boost: lazy load, debounce input, etc.

### 🔮 Phase 5 – FUTURE
- News feed & announcement system
- Custom widget/slider framework
- Admin dashboard for managing uploads, chat logs, memory, themes
- API key system for Pro users (with limits)

---

## 🎨 Visual Style

- Based on ChatGPT's layout (centered, spacious, clean)
- TailwindCSS driven with dark/light theme
- Custom `style.css` uses:
  - Custom animations (`fadeIn`, `pulseFast`)
  - Rounded buttons (`btn`, `xl`, `2xl`)
  - Shadow and layout enhancements (`soft`, `chat`)
- Icons: **Tabler Icons (outline)** – consistent style across all UI
- Typography: **Inter** font (fallback to system UI)

---

## 🔐 Security & Best Practices

- Session-based auth with secure `session_start()` early
- No client-side secrets
- File uploads: 24h auto-expire, type restrictions, size control
- Auth validation required for all handlers

---

## ✅ Project Status (On going bug fixes and more)

- **Repository**: [Averdrity/Aver-Web-Docker](https://github.com/Averdrity/Aver-Web-Docker)
- **Purpose**: Aver-Web is a PHP-based AI chat application utilizing Alpine.js for interactivity, Tailwind CSS for styling, and Docker for containerization.
- **Icons**: The project uses [Tabler Icons](https://tabler.io/icons) with the "outline" style.

---

## 🔧 Key Fixes and Enhancements (Current Issues)

### 1. **Sidebar Toggle Functionality**

- **Issue**:Sidebars could only be closed via an "×" button within them, with no external toggle to reopen
- **Solution**:Introduced external toggle buttons using the `square-toggle.svg` icon for both left and right sidebars
- **Implementation**:
  - **Left Sidebar Toggle**:
    ```html
    <div class="absolute top-4 left-4 z-20">
      <button @click="$store.sidebar.left = !$store.sidebar.left" class="...">
        <img src="/assets/icons/square-toggle.svg" alt="Toggle Left Sidebar" />
      </button>
    </div>
    ```
  - **Right Sidebar Toggle**:
    ```html
    <div class="absolute top-4 right-4 z-20">
      <button @click="$store.sidebar.right = !$store.sidebar.right" class="...">
        <img src="/assets/icons/square-toggle.svg" alt="Toggle Right Sidebar" />
      </button>
    </div>
    ```
  -These buttons are placed outside the sidebars and remain accessible regardless of the sidebar state

### 2. **Theme Toggle Animation**

- **Issue**:The theme toggle switch lacked smooth animation when switching between light and dark modes
- **Solution**:Enhanced the toggle switch with Tailwind CSS transitions for a smoother user experience
- **Implementation**:
  -Added transition classes to the toggle switch elements
    ```html
    <span class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform peer-checked:translate-x-5"></span>
    ```
  -Ensured the `transition-transform` class is applied for smooth movement

### 3. **Alpine.js Store Initialization**

- **Issue**:Encountered errors like `Alpine Expression Error: $store.modal is undefined`
- **Solution**:Ensured all Alpine.js stores are properly initialized before use
- **Implementation**:
  -Defined the `modal` store during Alpine.js initialization
    ```javascript
    Alpine.store('modal', {
      showMemory: false,
      // other modal states...
    });
    ```
  -Ensured this initialization occurs before any component attempts to access `$store.modal`

### 4. **Tailwind CSS Configuration**

- **Issue**:Custom animations and transitions were not functioning as expected
- **Solution**:Updated `tailwind.config.js` to include necessary customizations
- **Implementation**:
  -Extended the theme with custom colors, border radii, fonts, shadows, animations, and keyframes
  -Added custom transition timing functions
    ```javascript
    transitionTimingFunction: {
      'in-expo': 'cubic-bezier(0.95, 0.05, 0.795, 0.035)',
      'out-expo': 'cubic-bezier(0.19, 1, 0.22, 1)',
    },
    ```
  -Ensured the `content` array includes all relevant file paths for Tailwind to scan

---

## 🛠️ Additional Notes

- **Icons*: The project utilizes [Tabler Icons](https://tabler.io/icons) in the "outline" style for UI elemens.
- **Docker*: Docker is used for containerizing the application, with configurations defined in `Dockerfile` and `docker-compose.ym`.
- **Styling*: Tailwind CSS is the primary styling framework, with custom configurations to suit the application's nees.
- **Interactivity*: Alpine.js manages the application's interactivity, with stores initialized for authentication, uploads, modals, and sidebas.

---

## 🧱 Folder Structure

C:\Projects\Aver-Web
|-- apache
|   |-- 000-default.conf
|-- db-data
|   (contents hidden)
|-- src
|   |-- api
|   |   |-- chat.php
|   |   |-- web_search.php
|   |-- assets
|   |   |-- css
|   |   |   |-- style.css
|   |   |   |-- tailwind.css
|   |   |-- icons
|   |   |   (contents hidden)
|   |   |-- js
|   |   |   |-- chat.js
|   |   |   |-- main.js
|   |   |-- aw_logo_transparent_64x64.png
|   |   |-- favicon.ico
|   |-- auth
|   |   |-- login.php
|   |   |-- logout.php
|   |   |-- profile.php
|   |   |-- register.php
|   |-- components
|   |   |-- modals
|   |   |   |-- login-modal.php
|   |   |   |-- memory-modal.php
|   |   |   |-- profile-modal.php
|   |   |   |-- register-modal.php
|   |   |   |-- uploaded-files-modal.php
|   |   |-- footer.php
|   |   |-- header.php
|   |-- data
|   |   |-- patterns.json
|   |-- includes
|   |   |-- auth_handler.php
|   |   |-- chat_handler.php
|   |   |-- config.php
|   |   |-- db.php
|   |   |-- memory_fetcher.php
|   |   |-- memory_handler.php
|   |   |-- pattern_detector.php
|   |   |-- profile_handler.php
|   |   |-- session.php
|   |   |-- theme_handler.php
|   |   |-- upload_handler.php
|   |-- node_modules
|   |   (contents hidden)
|   |-- public
|   |-- uploads
|   |-- .gitignore
|   |-- .htaccess
|   |-- chat.php
|   |-- index.php
|   |-- package.json
|   |-- package-lock.json
|   |-- postcss.config.js
|   |-- tailwind.config.js
|-- docker-compose.yml
|-- Dockerfile
