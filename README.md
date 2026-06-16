# ResumeBridge 

ResumeBridge is an AI-powered career counseling platform that bridges the gap between your experience and your next opportunity. By leveraging advanced Large Language Models (LLMs), the platform analyzes uploaded resumes and intelligently recommends the top job roles a candidate is best suited for, complete with matching skills and actionable tips to land the job.

<img width="1898" height="840" alt="res" src="https://github.com/user-attachments/assets/d61fa7f9-2dab-443e-8f09-333e8d87a158" />
<img width="1896" height="927" alt="ResumeBridge" src="https://github.com/user-attachments/assets/bf27ab74-c37d-4322-a4f9-c2ffce61017a" />


## ✨ Features

* **AI-Powered Analysis:** Utilizes the OpenRouter API to parse resumes and generate highly accurate job matches.
* **Flexible Input:** Supports raw text pasting and document uploads (.pdf, .txt, .docx).
* **Smart Matching:** Provides a personalized career summary, top 3 job recommendations, skill alignment, and interview tips.
* **User Authentication:** Secure login and registration system for users to save their data.
* **History Dashboard:** Automatically saves past analyses, allowing users to review their matched jobs and AI feedback at any time.
* **Responsive UI:** A modern, clean, and interactive user interface built with customized CSS and a Bento-box layout.

## 🛠️ Tech Stack

* **Frontend:** HTML5, CSS3, Vanilla JavaScript
* **Backend:** PHP (PDO for secure database interactions)
* **Database:** MySQL
* **AI Integration:** OpenRouter API (cURL)
* **Security:** Environment variables (`.env`) for API keys, secure password hashing, and prepared SQL statements to prevent injection.

## 🚀 Getting Started

Follow these instructions to set up ResumeBridge on your local machine using XAMPP or any standard Apache/MySQL environment.

### Prerequisites
* PHP >= 7.4
* MySQL / MariaDB
* Composer (Optional, if adding future PHP dependencies)
* An OpenRouter API Key

### Installation

1. **Clone the repository**
   ```bash
   git clone [https://github.com/BlackKaiser1121/ResumeBridge.git](https://github.com/BlackKaiser1121/ResumeBridge.git)
   cd ResumeBridge


# SETUP GUIDE

## Requirements
- XAMPP (Apache + MySQL + PHP 7.4+)
- A Qwen API key (from https://dashscope.aliyuncs.com)

## Installation Steps

### 1. Copy the project
Place the `resumebridge` folder inside your XAMPP `htdocs` directory:
```
C:\xampp\htdocs\resumebridge\
```

### 2. Import the database
1. Start XAMPP (Apache + MySQL)
2. Open your browser → go to `http://localhost/phpmyadmin`
3. Click **New** → create a database named `resumebridge`
4. Select the `resumebridge` database → click **Import**
5. Choose the file `resumebridge.sql` → click **Go**

### 3. Configure API key
Open `config/config.php` and replace:
```php
define('QWEN_API_KEY', 'YOUR_QWEN_API_KEY_HERE');
```
With your actual Qwen API key from DashScope.

### 4. Run the app
Open your browser and go to:
```
http://localhost/resumebridge/
```

## File Structure
```
resumebridge/
├── index.php          ← Login / Register page
├── logout.php         ← Logout handler
├── resumebridge.sql   ← Database schema (import this)
├── config/
│   └── config.php     ← DB credentials + API key
├── includes/
│   ├── auth.php       ← Session management
│   ├── header.php     ← Shared sidebar/nav
│   └── footer.php     ← Closing HTML
├── pages/
│   ├── dashboard.php  ← Main dashboard
│   ├── analyzer.php   ← AI Resume Analyzer
│   └── history.php    ← Scan history
└── uploads/           ← (for file uploads)
```

## Default Login
Register a new account on the login page — no default admin needed.

## Notes
- For best resume parsing, paste text directly (PDF text extraction is basic)
- The AI model used is `qwen-plus` by default (change in config.php)
- All data is stored per-user in MySQL
