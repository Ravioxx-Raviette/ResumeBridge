# ResumeBridge

ResumeBridge is an AI-powered career counseling platform designed to bridge the gap between candidate experience and future career opportunities. By leveraging advanced Large Language Models (LLMs), the platform analyzes uploaded resumes or pasted text to intelligently recommend the top job roles a candidate is best suited for, providing skill alignments and actionable tips to land the job.

---

## Key Features

*   **AI-Powered Analysis:** Utilizes the OpenRouter/Qwen API to parse resumes and generate accurate job matches.
*   **Flexible Input:** Supports both raw text pasting and document uploads (.pdf, .txt, .docx).
*   **Smart Matching:** Provides a personalized career summary, top 3 job recommendations, skill alignment metrics, and targeted interview tips.
*   **User Authentication:** Secure registration and login systems allow users to save and protect their profile data.
*   **History Dashboard:** Automatically tracks past analyses, allowing users to review their historical matched jobs and AI feedback at any time.
*   **Responsive UI:** Designed with a modern, clean, and interactive Bento-box layout using customized CSS.

---

## Tech Stack

| Component | Technology | Role |
| :--- | :--- | :--- |
| **Frontend** | HTML5, CSS3, Vanilla JavaScript | Responsive layout, interactive forms, and UI rendering |
| **Backend** | PHP (PDO) | Secure application logic and session handling |
| **Database** | MySQL | Relational data management for user accounts and scan history |
| **AI Integration** | OpenRouter API / Qwen (cURL) | Context processing and natural language job matching |
| **Security Architecture** | Environment Variables, PHP Password Hashing | Secure configuration management and SQL injection prevention |

---

## System Architecture

The application handles requests locally and interacts with the AI model securely through server-side logic:

```text
[Frontend UI (Bento-box Layout)]
                 │
                 │ (Resume Text/File Upload)
                 ▼
[Backend Logic (PHP PDO)] ──► [MySQL Database (Auth & History)]
                 │
                 │ (Secure cURL Request)
                 ▼
[AI Engine (OpenRouter / Qwen API)]
                 │
                 ▼
[Structured Response (Job Matches, Skills, Tips)] ──► [Rendered UI Dashboard]
