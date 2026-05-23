<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        DB::table('roles')->insert([
            ['name' => 'admin',   'description' => 'Full system access'],
            ['name' => 'staff',   'description' => 'Front desk and operations'],
            ['name' => 'trainer', 'description' => 'Fitness trainer / coach'],
            ['name' => 'member',  'description' => 'Gym member / customer'],
        ]);

        // 2. Branches
        DB::table('branches')->insert([
            ['name' => 'FitZone Main',   'address' => '123 Rizal Ave, Poblacion', 'city' => 'Davao City', 'phone' => '082-123-4567', 'email' => 'main@fitzone.com',   'manager_name' => 'Ricardo Santos',  'opened_at' => '2018-01-15'],
            ['name' => 'FitZone Uptown', 'address' => '45 Quimpo Blvd, Matina',   'city' => 'Davao City', 'phone' => '082-234-5678', 'email' => 'uptown@fitzone.com', 'manager_name' => 'Maria Dela Cruz', 'opened_at' => '2020-06-01'],
            ['name' => 'FitZone Tagum',  'address' => '78 Apokon Rd',             'city' => 'Tagum City', 'phone' => '084-345-6789', 'email' => 'tagum@fitzone.com',  'manager_name' => 'Jose Reyes',      'opened_at' => '2021-03-10'],
        ]);

        // 3. Admin & Staff Users (password: "password")
        $hashedPassword = Hash::make('password');
        DB::table('users')->insert([
            ['role_id' => 1, 'branch_id' => 1, 'name' => 'Admin User',     'email' => 'admin@fitzone.com',    'password' => $hashedPassword, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 2, 'branch_id' => 1, 'name' => 'Ana Macaraeg',   'email' => 'ana@fitzone.com',      'password' => $hashedPassword, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 2, 'branch_id' => 2, 'name' => 'Ben Florendo',   'email' => 'ben@fitzone.com',      'password' => $hashedPassword, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 2, 'branch_id' => 3, 'name' => 'Clara Montoya',  'email' => 'clara@fitzone.com',    'password' => $hashedPassword, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 3, 'branch_id' => 1, 'name' => 'Trainer User 1', 'email' => 'trainer1@fitzone.com', 'password' => $hashedPassword, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 3, 'branch_id' => 1, 'name' => 'Trainer User 2', 'email' => 'trainer2@fitzone.com', 'password' => $hashedPassword, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 3, 'branch_id' => 2, 'name' => 'Trainer User 3', 'email' => 'trainer3@fitzone.com', 'password' => $hashedPassword, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 3, 'branch_id' => 2, 'name' => 'Trainer User 4', 'email' => 'trainer4@fitzone.com', 'password' => $hashedPassword, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 3, 'branch_id' => 3, 'name' => 'Trainer User 5', 'email' => 'trainer5@fitzone.com', 'password' => $hashedPassword, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 4. Trainers
        DB::table('trainers')->insert([
            ['user_id' => 5, 'branch_id' => 1, 'first_name' => 'Marco',    'last_name' => 'Villanueva', 'gender' => 'Male',   'birthdate' => '1990-04-12', 'phone' => '09171234501', 'specialization' => 'Bodybuilding, Strength Training', 'certification' => 'NSCA-CPT',    'experience_years' => 8,  'hire_date' => '2018-02-01', 'salary' => 25000.00, 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 6, 'branch_id' => 1, 'first_name' => 'Liza',     'last_name' => 'Domingo',    'gender' => 'Female', 'birthdate' => '1993-07-25', 'phone' => '09171234502', 'specialization' => 'Zumba, Aerobics',                 'certification' => 'Zumba Inc.', 'experience_years' => 5,  'hire_date' => '2019-05-15', 'salary' => 22000.00, 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 7, 'branch_id' => 2, 'first_name' => 'Jerome',   'last_name' => 'Aquino',     'gender' => 'Male',   'birthdate' => '1988-11-03', 'phone' => '09171234503', 'specialization' => 'CrossFit, HIIT',                  'certification' => 'CrossFit L2', 'experience_years' => 10, 'hire_date' => '2020-07-01', 'salary' => 27000.00, 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 8, 'branch_id' => 2, 'first_name' => 'Patricia', 'last_name' => 'Salazar',    'gender' => 'Female', 'birthdate' => '1995-02-18', 'phone' => '09171234504', 'specialization' => 'Yoga, Pilates',                   'certification' => 'RYT-200',    'experience_years' => 4,  'hire_date' => '2021-01-10', 'salary' => 21000.00, 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 9, 'branch_id' => 3, 'first_name' => 'Ryan',     'last_name' => 'Tolentino',  'gender' => 'Male',   'birthdate' => '1991-09-30', 'phone' => '09171234505', 'specialization' => 'Weight Loss, Cardio',             'certification' => 'ACE-CPT',    'experience_years' => 7,  'hire_date' => '2021-04-01', 'salary' => 23000.00, 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 5. Membership Plans
        DB::table('membership_plans')->insert([
            ['name' => 'Day Pass',     'duration_days' => 1,   'price' => 150.00,  'description' => 'Single day access',        'features' => 'Open gym access',                                              'is_active' => 1],
            ['name' => 'Monthly',      'duration_days' => 30,  'price' => 799.00,  'description' => '1-month full access',       'features' => 'Open gym, locker, shower',                                     'is_active' => 1],
            ['name' => 'Quarterly',    'duration_days' => 90,  'price' => 2100.00, 'description' => '3-month full access',       'features' => 'Open gym, locker, shower, 1 free class/month',                 'is_active' => 1],
            ['name' => 'Semi-Annual',  'duration_days' => 180, 'price' => 3800.00, 'description' => '6-month full access',       'features' => 'Open gym, locker, shower, 2 free classes/month',               'is_active' => 1],
            ['name' => 'Annual',       'duration_days' => 365, 'price' => 6500.00, 'description' => '1-year full access',        'features' => 'Open gym, locker, shower, unlimited classes, 1 PT session/month', 'is_active' => 1],
            ['name' => 'Student',      'duration_days' => 30,  'price' => 599.00,  'description' => 'Monthly plan for students', 'features' => 'Open gym, locker (valid student ID required)',                  'is_active' => 1],
            ['name' => 'Couple',       'duration_days' => 30,  'price' => 1299.00, 'description' => 'Monthly plan for 2',        'features' => 'Open gym, locker, shower for 2',                               'is_active' => 1],
            ['name' => 'VIP Annual',   'duration_days' => 365, 'price' => 10000.00,'description' => 'Premium annual membership', 'features' => 'All access, personal trainer 4x/month, supplements discount',   'is_active' => 1],
        ]);

        // 6. Equipment Categories
        DB::table('equipment_categories')->insert([
            ['name' => 'Cardio',       'description' => 'Treadmills, bikes, ellipticals'],
            ['name' => 'Free Weights', 'description' => 'Dumbbells, barbells, kettlebells'],
            ['name' => 'Machines',     'description' => 'Cable machines, leg press, lat pulldown'],
            ['name' => 'Functional',   'description' => 'Battle ropes, TRX, plyo boxes'],
            ['name' => 'Stretching',   'description' => 'Mats, foam rollers, stretching bars'],
            ['name' => 'Accessories',  'description' => 'Gloves, belts, straps, resistance bands'],
        ]);

        // 7. Equipment (sample from SQL)
        DB::table('equipment')->insert([
            ['category_id' => 1, 'branch_id' => 1, 'name' => 'Treadmill',       'brand' => 'Life Fitness', 'model' => 'F3',      'serial_number' => 'SN-TM-001', 'quantity' => 6, 'purchase_date' => '2020-01-10', 'purchase_price' => 85000.00, 'condition' => 'Good', 'last_maintained' => '2024-10-01', 'next_maintenance' => '2025-04-01', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'branch_id' => 1, 'name' => 'Stationary Bike', 'brand' => 'Technogym',   'model' => 'Bike700', 'serial_number' => 'SN-BK-001', 'quantity' => 4, 'purchase_date' => '2020-01-10', 'purchase_price' => 45000.00, 'condition' => 'Good', 'last_maintained' => '2024-10-01', 'next_maintenance' => '2025-04-01', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'branch_id' => 1, 'name' => 'Elliptical',      'brand' => 'Precor',      'model' => 'EFX221',  'serial_number' => 'SN-EL-001', 'quantity' => 3, 'purchase_date' => '2020-03-15', 'purchase_price' => 60000.00, 'condition' => 'Fair', 'last_maintained' => '2024-09-01', 'next_maintenance' => '2025-03-01', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'branch_id' => 1, 'name' => 'Dumbbell Set',    'brand' => 'Ativafit',    'model' => 'HEX',     'serial_number' => 'SN-DB-001', 'quantity' => 1, 'purchase_date' => '2020-01-10', 'purchase_price' => 35000.00, 'condition' => 'Good', 'last_maintained' => '2024-11-01', 'next_maintenance' => '2025-05-01', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'branch_id' => 1, 'name' => 'Cable Machine',   'brand' => 'Hammer',      'model' => 'MG-7802', 'serial_number' => 'SN-CM-001', 'quantity' => 2, 'purchase_date' => '2021-06-01', 'purchase_price' => 95000.00, 'condition' => 'Good', 'last_maintained' => '2024-10-15', 'next_maintenance' => '2025-04-15', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'branch_id' => 2, 'name' => 'Treadmill',       'brand' => 'Life Fitness', 'model' => 'F3',     'serial_number' => 'SN-TM-101', 'quantity' => 4, 'purchase_date' => '2020-07-01', 'purchase_price' => 85000.00, 'condition' => 'Good', 'last_maintained' => '2024-10-01', 'next_maintenance' => '2025-04-01', 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'branch_id' => 3, 'name' => 'Treadmill',       'brand' => 'Life Fitness', 'model' => 'F3',     'serial_number' => 'SN-TM-201', 'quantity' => 3, 'purchase_date' => '2021-04-01', 'purchase_price' => 85000.00, 'condition' => 'Good', 'last_maintained' => '2024-10-01', 'next_maintenance' => '2025-04-01', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 8. Classes
        DB::table('classes')->insert([
            ['trainer_id' => 1, 'branch_id' => 1, 'name' => 'Bodybuilding 101', 'description' => 'Beginner weightlifting program',         'schedule_day' => 'Mon,Wed,Fri',         'start_time' => '06:00:00', 'end_time' => '07:00:00', 'max_capacity' => 15, 'fee' => 200.00, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['trainer_id' => 2, 'branch_id' => 1, 'name' => 'Zumba Fitness',    'description' => 'High-energy dance workout',              'schedule_day' => 'Tue,Thu,Sat',         'start_time' => '07:00:00', 'end_time' => '08:00:00', 'max_capacity' => 25, 'fee' => 0.00,   'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['trainer_id' => 3, 'branch_id' => 2, 'name' => 'CrossFit WOD',     'description' => 'Daily workout of the day',               'schedule_day' => 'Mon,Tue,Wed,Thu,Fri', 'start_time' => '05:30:00', 'end_time' => '06:30:00', 'max_capacity' => 12, 'fee' => 300.00, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['trainer_id' => 4, 'branch_id' => 2, 'name' => 'Morning Yoga',     'description' => 'Relaxing morning yoga flow',             'schedule_day' => 'Mon,Wed,Fri',         'start_time' => '07:00:00', 'end_time' => '08:00:00', 'max_capacity' => 20, 'fee' => 0.00,   'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['trainer_id' => 5, 'branch_id' => 3, 'name' => 'Cardio Blast',     'description' => 'Intense cardio and fat-burning session', 'schedule_day' => 'Tue,Thu,Sat',         'start_time' => '06:00:00', 'end_time' => '07:00:00', 'max_capacity' => 18, 'fee' => 150.00, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
