# MCTSC Website + Laravel CMS

প্রতিষ্ঠানের responsive public website এবং নমুনা ছবির অনুরূপ admin dashboard Laravel 12 ও MySQL-এ তৈরি। XAMPP-এ চালানোর ধাপ, sample admin credential এবং তথ্য হালনাগাদের নির্দেশিকা [SETUP-BN.md](SETUP-BN.md)-তে আছে।

- Public website: `/`
- Admin panel: `/admin/login`
- Editable content: courses, projects, notices, events, gallery, institution/contact profile
- Forms: online admissions enquiry and contact inbox
- Test: `php artisan test` (SQLite in-memory; configured XAMPP MySQL touched হয় না)

**প্রথম লগইনের পর demo admin password বদলান।** Contact/phone/address ও প্রতিষ্ঠানের পরিচিতি demo/static content-এর ভিত্তিতে আছে; প্রতিষ্ঠানের আসল Word profile পাওয়া যায়নি।
