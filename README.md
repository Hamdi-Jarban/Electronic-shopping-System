# نظام التسوق الإلكتروني

تطبيق متجر مبني على Laravel 12 وPHP 8.2 أو أحدث، مع واجهة Blade وVite، ويستخدم SQLite افتراضياً.

## تشغيل محلي

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm ci
npm run build
php artisan storage:link
php artisan serve
```

للتطوير مع Vite استخدم `npm run dev` في نافذة أخرى. بعد ضبط قاعدة البيانات، يمكن تشغيل `php artisan db:seed` لإضافة بيانات تجريبية؛ المستخدمون التجريبيون يُنشؤون بدور العميل فقط وكلمة مرورهم التجريبية `password`، لذلك لا تستخدم هذه البيانات في بيئة إنتاج.

## صلاحيات لوحة الإدارة

تتطلب المسارات `/admin` دورًا اسمه `admin` بالإضافة إلى تسجيل الدخول. لم يُنشأ حساب مدير افتراضيًا. بعد إنشاء حساب موثوق ودور `admin`، اربطهما يدويًا عبر Tinker:

```bash
php artisan tinker
```

```php
$user = App\Models\User::where('email', 'admin@example.com')->firstOrFail();
$role = App\Models\Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'مدير النظام']);
$user->roles()->syncWithoutDetaching([$role->id]);
```

لا تستخدم الحسابات وكلمات المرور التجريبية خارج بيئة التطوير.

## الاختبارات

```bash
php artisan test
```

يتضمن المشروع اختبارات حماية الإدارة، إضافة إلى اختبارات Laravel الأساسية.
