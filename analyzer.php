</main>
</body>
</html>
from openai import OpenAI

client = OpenAI(
  base_url = "https://integrate.api.nvidia.com/v1",
  api_key = "Censor"
)

completion = client.chat.completions.create(
  model="deepseek-ai/deepseek-v3.2",
  messages=[{"role":"user","content":""}],
  temperature=1,
  top_p=0.95,
  max_tokens=8192,
  extra_body={"chat_template_kwargs": {"thinking":True}},
  stream=True
)

for chunk in completion:
  if not getattr(chunk, "choices", None):
    continue
  reasoning = getattr(chunk.choices[0].delta, "reasoning_content", None)
  if reasoning:
    print(reasoning, end="")
  if chunk.choices and chunk.choices[0].delta.content is not None:
    print(chunk.choices[0].delta.content, end="")
  

John Michael Santos
Email: johnsantos@email.com | Phone: 09171234567
GitHub: github.com/johnsantos | LinkedIn: linkedin.com/in/johnsantos
Location: Cavite, Philippines

EDUCATION
Bachelor of Science in Computer Science
Lyceum of the Philippines University – Cavite | 2020 – 2024
GPA: 1.75

TECHNICAL SKILLS
Languages: Java, PHP, Python, JavaScript, HTML, CSS
Databases: MySQL, Firebase Firestore
Frameworks & Tools: Android Studio, XAMPP, Git, GitHub, REST APIs
Other: Object-Oriented Programming, MVC Architecture, Agile/Scrum

WORK EXPERIENCE
Web Development Intern
TechSolutions PH, General Trias, Cavite | June 2023 – August 2023
- Built and maintained internal web tools using PHP and MySQL
- Assisted in designing responsive UI using HTML/CSS and Bootstrap
- Collaborated with senior developers using Git version control
- Documented system processes and created user manuals

PROJECTS
Student Information System (Capstone Project) | 2024
- Developed a full-stack student portal using PHP, MySQL, and JavaScript
- Implemented user authentication, CRUD operations, and PDF report generation
- Deployed locally using XAMPP for demo and testing

Job Finder App (AI-Integrated System) | 2024
- Built an Android app using Java and Firebase
- Integrated Qwen AI API for resume analysis and job matching
- Designed UI wireframes and implemented core modules

Inventory Management System | 2023
- Created a web-based inventory system using PHP and MySQL
- Features included stock tracking, low-stock alerts, and sales reports

CERTIFICATIONS
- DICT ICT Literacy Certificate | 2023
- Coursera: Python for Everybody | 2022

SOFT SKILLS
Team collaboration, problem-solving, attention to detail, fast learner