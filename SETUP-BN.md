# MCTSC Laravel Website — XAMPP সেটআপ

## অন্তর্ভুক্ত সুবিধা

- পুরোনো forest-green/gold ডিজাইনের responsive public website; বিদ্যমান logo, campus ও classroom ছবি পুনর্ব্যবহার করা হয়েছে।
- Laravel 12 + MySQL CMS; seed করা course, project, notice, event ও gallery content admin থেকে যোগ/সম্পাদনা/মুছতে পারবেন।
- নমুনা dashboard-এর কাছাকাছি সবুজ sidebar, summary cards, Recent Updates, Quick Actions ও Course Management layout।
- Admin login, rate-limited public application/contact forms, application inbox/status update, contact inbox ও institution settings।
- Upload করা ছবি `storage/app/public`-এ থাকে; Laravel validation সর্বোচ্চ 4 MB image গ্রহণ করে।

## প্রথমবার চালু করা

1. XAMPP Control Panel-এ **Apache** এবং **MySQL** Start করুন। phpMyAdmin খুলুন: `http://localhost:8080/phpmyadmin/`।
2. phpMyAdmin-এ `mnctsc_website` নামে নতুন database তৈরি করুন; collation হিসেবে `utf8mb4_unicode_ci` বাছুন। বিদ্যমান অন্য database ব্যবহার/পরিবর্তন করার প্রয়োজন নেই।
3. PowerShell-এ প্রকল্প ফোল্ডারে যান:

   ```powershell
   cd C:\xampp\htdocs\MNCTSC
   ```

4. `.env`-এ database access দেখুন। XAMPP-এর ডিফল্ট হলে `DB_HOST=127.0.0.1`, `DB_PORT=3306`, `DB_DATABASE=mnctsc_website`, `DB_USERNAME=root`, `DB_PASSWORD=` থাকবে। আপনার MySQL root password থাকলে `DB_PASSWORD`-এ দিন।
5. migration, sample data এবং upload storage link তৈরি করুন:

   ```powershell
   php artisan config:clear
   php artisan migrate --seed
   php artisan storage:link
   ```

6. Apache-এর default `htdocs` DocumentRoot হলে সাইট খুলুন:
   - Website: `http://localhost:8080/MNCTSC/public/`
   - Admin: `http://localhost:8080/MNCTSC/public/admin/login`

   সার্ভার Apache-এ অন্য port-এ চললে URL-এ `8080` বদলান। চাইলে Apache VirtualHost-এর DocumentRoot `C:/xampp/htdocs/MNCTSC/public` করতে পারেন; তখন `.env`-এর `APP_URL`-ও আপনার site URL অনুযায়ী পরিবর্তন করুন।

## Admin sign-in

প্রাথমিক local/demo login (প্রথমবার `php artisan db:seed` চালালে):

- Email: `admin@mctsc.edu.bd`
- Password: `ChangeMe123!`

**প্রথম প্রবেশেই `.env`-এর `ADMIN_PASSWORD` বদলে `php artisan db:seed` আবার চালান।** এটি স্থানীয় ডেমো credential; production/public server-এ এই password বা `APP_DEBUG=true` রেখে deploy করবেন না। `.env` কখনো public repository-তে commit করবেন না।

## ডেটাবেস ও নমুনা তথ্য

Migration নতুন `mnctsc_website` database-এই টেবিল তৈরি করে। `DatabaseSeeder`-এ বর্তমানে static site-এ পাওয়া ছয়টি কোর্স, NHRDF/ECHO/ASSET project, sample notices/events/gallery এবং sample address/phone/email আছে। এগুলো যাচাইযোগ্য চূড়ান্ত তথ্য নয়। Admin-এর **Settings**-এ সঠিক ঠিকানা, ফোন, email, প্রতিষ্ঠান-পরিচিতি ও hero copy দিন; Courses/Projects/Notices/Events/Gallery-তে sample content যাচাই/সংশোধন করুন। প্রতিষ্ঠানের Word profile ফাইলটি এই working folder বা পাওয়া Downloads-এ ছিল না, তাই profile থেকে অযাচাইকৃত তথ্য আমদানি করা হয়নি।

## দরকারি কমান্ড

```powershell
php artisan test                 # in-memory SQLite feature tests
php artisan route:list           # route তালিকা
php artisan migrate --seed       # migration + initial sample records
php artisan db:seed              # admin/sample records পুনরায় seed
php artisan storage:link         # uploaded image link
```

Feature tests SQLite in-memory database-এ চলে; XAMPP MySQL-কে test-এর সময় touch করে না। `.work/legacy-static/`-এ conversion-ээс আগের `index.html`, `styles.css`, `script.js` backup আছে।
