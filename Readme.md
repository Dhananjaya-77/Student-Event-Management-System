# 🎓 EduSphere Pro — Advanced Student Information & Campus Management System

EduSphere Pro is an enterprise-grade, comprehensive **Student Information System (SIS)** and **Campus Event Management System (EMS)** designed for universities, colleges, and academic institutions.

Built with responsive HTML5, modern CSS3 variables, pure vector SVG generators, print-ready document layouts, and client-side persistent storage.

---

## 🚀 Quick Launch

The platform is running and accessible locally:
```bash
# To run the local server
python3 -m http.server 8000
```
Open your browser and navigate to:
👉 **[http://localhost:8000](http://localhost:8000)** (auto-redirects to `eventhub.html`)

---

## 👥 Instant 1-Click Role Accounts

Use the instant role switcher in the top navigation bar or on the sign-in screen:
- **Amal Fernando** (`student1` / `student123`): Year 3 B.Sc. Computer Science · STU001 · CGPA 3.82 · Dean's List
- **Nimali Rathnayake** (`student2` / `student123`): Year 2 BBA Business Administration · STU002 · CGPA 3.68
- **Kasun Bandara** (`student3` / `student123`): Year 4 B.Sc. Engineering · STU003 · CGPA 3.89
- **Dr. Kamal Silva** (`dr.silva` / `faculty123`): Head of Computer Science & Associate Professor
- **Prof. Nimali Perera** (`prof.perera` / `faculty123`): Dean, Faculty of Engineering
- **Dean Marcus Vance** (`admin` / `admin123`): Chief University Registrar & Dean of Students

---

## ✨ Comprehensive Functional Modules

### 1. 📜 Academics, GPA & Official Transcripts
- **Cumulative CGPA & Semester GPA**: Term-by-term credit breakdowns with letter grade points (A+, A, B, etc.).
- **Degree Audit**: Visual graduation progress tracker (e.g. 88 / 120 credits completed).
- **Official Certified Academic Transcript**: Formal university document with institutional seal, Registrar signature, verification hash, and print-to-PDF layout (`Ctrl/Cmd + P`).
- **Interactive CGPA Target Calculator**: Forecasts the required GPA in remaining semesters to achieve target graduation honors.

### 2. 📚 Course Catalog & Add/Drop Enrolment
- **Searchable Course Catalog**: Filter by department (Computer Science, Engineering, Business, etc.).
- **Real-Time Add/Drop**: Live course registration with seat capacity tracking and maximum credit overload check (max 18 credits/term).
- **Course Syllabus Viewer**: Course learning outcomes, recommended textbooks, and grade weight breakdowns.

### 3. 🗓️ Weekly Academic Timetable Matrix
- **5-Day Class Schedule Grid**: Color-coded lecture and lab blocks mapped across Monday to Friday (08:00 - 18:00).
- **Automatic Schedule Conflict Detector**: Alerts if two lectures overlap.
- **Export to Calendar (.ICS)**: Generates and downloads RFC 5545 standard `.ics` calendar files for Apple Calendar, Google Calendar, and Outlook.

### 4. 📋 Academic Attendance & Leave Management
- **Course-by-Course Attendance**: Real-time attendance percentages against the mandatory 75% examination threshold.
- **At-Risk Alerts**: Automated red warning badges for courses below the 75% policy limit.
- **Leave Application System**: Submit medical excuses and absence justifications with dates and reason categories.
- **Dean / Faculty Review**: Approving a leave automatically excuses the absence and restores attendance percentages!

### 5. 📝 Examinations & Hall Admission Tickets
- **Examination Timetable**: Exam dates, morning/afternoon time slots, examination halls, desk numbers, and assigned chief invigilators.
- **Exam Countdown Clock**: Highlights the upcoming scheduled paper.
- **Official Examination Hall Ticket / Admit Pass**: Generates authenticated candidate admission cards complete with student photo placeholder, barcode, seal, and invigilator signature line.

### 6. 💳 Student Tuition, Fees & Financial Ledger
- **Semester Billing**: Itemized fees (Tuition, Lab Kits, Campus Wi-Fi, Health Insurance, Athletics).
- **Scholarship Grants**: Tracks merit waivers (e.g., Dean's Academic Merit 25% waiver).
- **Online Payment Gateway Simulator**: Settle tuition fees instantly via Credit/Debit Card, Campus e-Wallet, or Bank Transfer.
- **Official Fee Receipt & Statement**: Printable invoice and payment receipt signed by the University Bursar.

### 7. 🏠 Hostel & Residential Housing Hub
- **Dorm Allocation**: Residence Hall, Block, Room number, Room type, Roommate details, and Resident Assistant (RA) contact.
- **Dorm Maintenance Ticket System**: Submit work orders (Plumbing, Air Conditioning/HVAC, Wi-Fi, Electrical, Furniture) with urgency levels and track resolution status in real time.

### 8. 📖 Campus Digital Library
- **Searchable Book Catalog**: Title, author, category, ISBN, shelf location, and real-time available copy counts.
- **Active Loans Management**: Borrowed books tracker with return due dates and overdue fine calculator.
- **1-Click Actions**: Borrow/reserve books and renew active loans (+14 days).

### 9. 💼 Career Services & Campus Placements
- **Exclusive Job Board**: Internships and graduate roles from top corporate partners (Google Cloud, Sysco LABS, WSO2, IFS).
- **Student Profile Application**: 1-click application submission with academic dossier.
- **Application Tracker**: Follow stages from `Applied` to `Resume Shortlisted`, `Interview Scheduled`, and `Offer Extended`.

### 10. 🩺 Student Health & Counseling Center
- **Confidential Electronic Health Record (EHR)**: Blood type, known allergies, chronic conditions, emergency contact, and vaccination records.
- **Appointment Booking**: Schedule appointments with campus physicians, mental health counselors, or sports physiotherapists.

### 11. 🪪 Digital Student Smart ID Card
- **Interactive 3D Card**: Click to flip between front and back with 3D animation.
- **Front View**: University Crest, photo, student registration number, major, faculty, hologram security badge, smart NFC chip, and barcode.
- **Back View**: Magnetic swipe stripe, emergency security hotlines, and library barcode.
- **Printable ID Badge**: Formatted for wallet-sized card printing.

### 12. 🎟️ Campus Events & Ticketing (EventHub Core)
- **All Events Directory**: Filter by Workshop, Hackathon, Sports, Seminar, Cultural festival.
- **Digital Vector QR Event Pass**: Generates pure vector SVG QR tickets for venue entry.
- **Certificate of Participation**: Gold-bordered credential verification document with university crest.
- **Event Rating & Feedback**: 5-star review system with comments.

### 13. 🤝 Clubs, Societies & Volunteer Hub
- **Clubs Directory & Charters**: Browse student organizations, join clubs, or charter new societies.
- **Volunteer Service Ledger**: Apply for event operations roles and track official community leadership hours.

### 14. 👨‍🏫 Faculty & Administration Portal
- **Student Information Directory**: Searchable institutional database with academic dossiers and GPA records.
- **Faculty Grade Submission**: Assign and update official grades (A+, A, B, etc.) and attendance records.
- **Administrative Approvals Queue**: Review pending event proposals and student medical leave requests.
- **Live QR Scanner Check-In**: Optical sensor simulation for validating student passes at event gates.
- **Budget & Ledger**: Event budget allocations, expense tracking, and AI budget optimization.
- **Reports & Analytics**: Student engagement leaderboards, faculty enrollment distributions, and printable executive summaries.

### 15. 🤖 AI Academic Copilot & Early Warning Advisory
- **Academic Early Warning Engine**: Computes risk tiers (Low, Moderate, Critical) based on attendance rates, GPA trajectory, and extracurricular burnout.
- **Personalized Weekly Study Planner**: Formulates tailored study routines based on enrolled coursework.
- **Career & Elective Pathway Advisor**: Recommends strategic electives to target specific industry careers.

---

## 🌓 Dark Mode & Light Mode Theme Support

Both the **Classic Theme (`event-management/`)** and **Modern SIS Dashboard (`eventhub.html`)** include complete, responsive Dark and Light Mode support:
- **Instant Persistence**: Theme choice (`dark` / `light`) is saved in `localStorage` and shared across both portals with zero page flicker (FOUC).
- **System Preference Detection**: Automatically adheres to your operating system's `prefers-color-scheme` if no custom preference is set.
- **Floating & Header Toggles**: Easily toggle theme anywhere via the floating bottom-right button or header action buttons.
- **Accessible WCAG Contrast**: Meticulously tuned colors, surfaces, borders, and input fields ensuring optimal legibility and contrast in both modes.
# Dhananjaya-77-Student-Event-Management-System
# Dhananjaya-77-Student-Event-Management-System
