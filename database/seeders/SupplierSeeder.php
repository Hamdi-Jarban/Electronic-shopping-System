<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            ['company_name' => 'شركة التوريد الأولى', 'contact_person' => 'أحمد محمد', 'email' => 'supplier1@example.com', 'phone' => '0500000001', 'rating_avg' => 4.5],
            ['company_name' => 'مؤسسة السلع الممتازة', 'contact_person' => 'خالد علي', 'email' => 'supplier2@example.com', 'phone' => '0500000002', 'rating_avg' => 4.0],
            ['company_name' => 'شركة الواردات', 'contact_person' => 'محمد حسن', 'email' => 'supplier3@example.com', 'phone' => '0500000003', 'rating_avg' => 3.5],
            ['company_name' => 'مؤسسة التوزيع', 'contact_person' => 'عمر حسين', 'email' => 'supplier4@example.com', 'phone' => '0500000004', 'rating_avg' => 4.2],
            ['company_name' => 'شركة المواد الغذائية', 'contact_person' => 'علي إبراهيم', 'email' => 'supplier5@example.com', 'phone' => '0500000005', 'rating_avg' => 4.8],
            ['company_name' => 'مؤسسة الإلكترونيات', 'contact_person' => 'حسن يوسف', 'email' => 'supplier6@example.com', 'phone' => '0500000006', 'rating_avg' => 3.9],
            ['company_name' => 'شركة الملابس العصرية', 'contact_person' => 'إبراهيم سعيد', 'email' => 'supplier7@example.com', 'phone' => '0500000007', 'rating_avg' => 4.1],
            ['company_name' => 'مؤسسة التنظيف', 'contact_person' => 'سعيد خالد', 'email' => 'supplier8@example.com', 'phone' => '0500000008', 'rating_avg' => 3.8],
            ['company_name' => 'شركة الأثاث المنزلي', 'contact_person' => 'يوسف عمر', 'email' => 'supplier9@example.com', 'phone' => '0500000009', 'rating_avg' => 4.3],
            ['company_name' => 'مؤسسة الألعاب', 'contact_person' => 'محمد أحمد', 'email' => 'supplier10@example.com', 'phone' => '0500000010', 'rating_avg' => 4.6],
            ['company_name' => 'شركة المشروبات', 'contact_person' => 'خالد محمد', 'email' => 'supplier11@example.com', 'phone' => '0500000011', 'rating_avg' => 4.0],
            ['company_name' => 'مؤسسة المخبوزات', 'contact_person' => 'علي حسن', 'email' => 'supplier12@example.com', 'phone' => '0500000012', 'rating_avg' => 4.4],
            ['company_name' => 'شركة الحلويات', 'contact_person' => 'حسن علي', 'email' => 'supplier13@example.com', 'phone' => '0500000013', 'rating_avg' => 4.7],
            ['company_name' => 'مؤسسة الألبان', 'contact_person' => 'إبراهيم محمد', 'email' => 'supplier14@example.com', 'phone' => '0500000014', 'rating_avg' => 4.2],
            ['company_name' => 'شركة المعلبات', 'contact_person' => 'سعيد إبراهيم', 'email' => 'supplier15@example.com', 'phone' => '0500000015', 'rating_avg' => 3.7],
            ['company_name' => 'مؤسسة العناية الشخصية', 'contact_person' => 'يوسف خالد', 'email' => 'supplier16@example.com', 'phone' => '0500000016', 'rating_avg' => 4.5],
            ['company_name' => 'شركة المنظفات', 'contact_person' => 'محمد يوسف', 'email' => 'supplier17@example.com', 'phone' => '0500000017', 'rating_avg' => 3.9],
            ['company_name' => 'مؤسسة الكاميرات', 'contact_person' => 'خالد سعيد', 'email' => 'supplier18@example.com', 'phone' => '0500000018', 'rating_avg' => 4.1],
            ['company_name' => 'شركة السماعات', 'contact_person' => 'علي خالد', 'email' => 'supplier19@example.com', 'phone' => '0500000019', 'rating_avg' => 4.3],
            ['company_name' => 'مؤسسة الحواسيب', 'contact_person' => 'حسن محمد', 'email' => 'supplier20@example.com', 'phone' => '0500000020', 'rating_avg' => 4.6],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        $this->command->info('✓ تم إنشاء ' . Supplier::count() . ' مورد');
    }
}
