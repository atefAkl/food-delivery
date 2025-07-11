<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\AdminProfile;
use App\Models\SocialLink;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * تشغيل بذرة قاعدة البيانات.
     */
    public function run(): void
    {
        // بيانات المشرفين
        $admins = [
            [
                'name' => 'أحمد محمد',
                'email' => 'admin@fooddelivery.com',
                'password' => 'password123',
                'profile' => [
                    'phone_number' => '0123456789',
                    'position' => 'المدير العام',
                    'bio' => 'مدير عام تطبيق توصيل الطعام مع خبرة أكثر من 10 سنوات في مجال إدارة المطاعم وخدمات التوصيل.',
                    'gender' => 'male',
                ],
                'social_links' => [
                    ['label' => 'facebook', 'link' => 'https://facebook.com/admin1', 'icon' => 'fa fa-facebook'],
                    ['label' => 'twitter', 'link' => 'https://twitter.com/admin1', 'icon' => 'fa fa-twitter'],
                    ['label' => 'linkedin', 'link' => 'https://linkedin.com/in/admin1', 'icon' => 'fa fa-linkedin'],
                ]
            ],
            [
                'name' => 'سارة علي',
                'email' => 'sara@fooddelivery.com',
                'password' => 'password123',
                'profile' => [
                    'phone_number' => '0123456788',
                    'position' => 'مديرة العمليات',
                    'bio' => 'مديرة العمليات في تطبيق توصيل الطعام مع خبرة في إدارة العمليات اللوجستية وخدمة العملاء.',
                    'gender' => 'female',
                    'birth_date' => '1990-05-15',
                ],
                'social_links' => [
                    ['label' => 'facebook', 'link' => 'https://facebook.com/admin2', 'icon' => 'fa fa-facebook'],
                    ['label' => 'twitter', 'link' => 'https://twitter.com/admin2', 'icon' => 'fa fa-twitter'],
                    ['label' => 'linkedin', 'link' => 'https://linkedin.com/in/admin2', 'icon' => 'fa fa-linkedin'],
                ]
            ],
            [
                'name' => 'محمد خالد',
                'email' => 'mohamed@fooddelivery.com',
                'password' => 'password123',
                'profile' => [
                    'phone_number' => '0123456787',
                    'position' => 'مدير التسويق',
                    'bio' => 'مدير التسويق في تطبيق توصيل الطعام مع خبرة في التسويق الرقمي ووسائل التواصل الاجتماعي.',
                    'gender' => 'male',
                    'birth_date' => '1992-08-20',
                    'address' => 'القاهرة، مصر',
                ],
                'social_links' => [
                    ['label' => 'facebook', 'link' => 'https://facebook.com/admin3', 'icon' => 'fa fa-facebook'],
                    ['label' => 'twitter', 'link' => 'https://twitter.com/admin3', 'icon' => 'fa fa-twitter'],
                    ['label' => 'linkedin', 'link' => 'https://linkedin.com/in/admin3', 'icon' => 'fa fa-linkedin'],
                ]
            ]
        ];
        
        // إنشاء أو تحديث المشرفين وملفاتهم الشخصية
        foreach ($admins as $adminData) {
            // البحث عن المشرف بواسطة البريد الإلكتروني
            $admin = Admin::firstOrCreate(
                ['email' => $adminData['email']],
                [
                    'name' => $adminData['name'],
                    'password' => Hash::make($adminData['password']),
                ]
            );
            
            // التحقق من وجود ملف شخصي للمشرف
            $profile = AdminProfile::firstOrNew(['admin_id' => $admin->id]);
            
            // تحديث بيانات الملف الشخصي
            $profile->phone_number = $adminData['profile']['phone_number'] ?? null;
            $profile->position = $adminData['profile']['position'] ?? null;
            $profile->bio = $adminData['profile']['bio'] ?? null;
            $profile->gender = $adminData['profile']['gender'] ?? null;
            $profile->birth_date = $adminData['profile']['birth_date'] ?? null;
            $profile->address = $adminData['profile']['address'] ?? null;
            
            // حفظ الملف الشخصي
            $profile->save();
            
            // إضافة روابط التواصل الاجتماعي
            if (isset($adminData['social_links'])) {
                // حذف الروابط القديمة إذا وجدت
                SocialLink::where('admin_id', $admin->id)->delete();
                
                // إضافة الروابط الجديدة
                foreach ($adminData['social_links'] as $linkData) {
                    SocialLink::create([
                        'admin_id' => $admin->id,
                        'label' => $linkData['label'],
                        'link' => $linkData['link'],
                        'icon' => $linkData['icon'],
                    ]);
                }
            }
            
            $this->command->info("تم إنشاء/تحديث المشرف: {$admin->name} مع ملفه الشخصي وروابط التواصل الاجتماعي");
        }
    }
}
