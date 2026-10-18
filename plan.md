# পরিকল্পনা — MCTSC Laravel Website

- Laravel 12/PHP 8.2 app; XAMPP MySQL-এ পৃথক `mnctsc_website` database ব্যবহার।
- আগের responsive green/gold website, logo, lab/campus imagery সংরক্ষণ; মূল static files `.work/legacy-static/`-এ backup।
- Website content database-backed: courses, projects, notices, events, gallery এবং editable institution/contact settings।
- Screenshot-inspired admin dashboard, login protection, content CRUD, admission inbox/status workflow ও contact inbox।
- Public pages use Blade; Laravel migrations/seeders create schema and initial sample data; PHPUnit uses isolated in-memory SQLite.
- Official profile document অনুপস্থিত; user/admin-এর review না হওয়া পর্যন্ত sample phone/address/events আনুষ্ঠানিক তথ্য নয়।

Setup: `SETUP-BN.md` দেখুন।
