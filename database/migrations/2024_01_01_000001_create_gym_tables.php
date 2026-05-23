<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * REPLACES the default Laravel users migration.
 * Delete the original 0001_01_01_000000_create_users_table.php
 * and keep only this file for the core schema.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. BRANCHES
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('address', 255);
            $table->string('city', 80);
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('manager_name', 100)->nullable();
            $table->date('opened_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. ROLES
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });

        // 3. USERS (extended with role_id and branch_id)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('name', 100);
            $table->string('email', 150)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // Sessions table (required by Laravel session driver)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // 4. TRAINERS
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users');
            $table->foreignId('branch_id')->constrained('branches');
            $table->string('first_name', 60);
            $table->string('last_name', 60);
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->date('birthdate');
            $table->string('phone', 20);
            $table->string('address', 255)->nullable();
            $table->string('specialization', 150)->nullable();
            $table->string('certification', 200)->nullable();
            $table->unsignedTinyInteger('experience_years')->default(0);
            $table->date('hire_date');
            $table->decimal('salary', 10, 2)->nullable();
            $table->enum('status', ['Active', 'On Leave', 'Resigned'])->default('Active');
            $table->string('profile_photo', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 5. MEMBERSHIP PLANS
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->unsignedSmallInteger('duration_days');
            $table->decimal('price', 10, 2);
            $table->text('description')->nullable();
            $table->text('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 6. CUSTOMERS
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->nullOnDelete();
            $table->foreignId('branch_id')->constrained('branches');
            $table->string('first_name', 60);
            $table->string('last_name', 60);
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->date('birthdate');
            $table->string('phone', 20);
            $table->string('email', 150)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('city', 80)->nullable();
            $table->string('emergency_contact_name', 100)->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->enum('blood_type', ['A+','A-','B+','B-','AB+','AB-','O+','O-','Unknown'])->default('Unknown');
            $table->text('health_conditions')->nullable();
            $table->string('profile_photo', 255)->nullable();
            $table->enum('status', ['Active', 'Inactive', 'Suspended'])->default('Active');
            $table->timestamps();
            $table->softDeletes();
        });

        // 7. MEMBERSHIPS
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('membership_plan_id')->constrained('membership_plans');
            $table->date('start_date');
            $table->date('expiration_date');
            $table->enum('status', ['Active', 'Expired', 'Cancelled', 'Frozen'])->default('Active');
            $table->unsignedSmallInteger('frozen_days')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 8. PAYMENTS
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membership_id')->constrained('memberships');
            $table->foreignId('customer_id')->constrained('customers');
            $table->decimal('amount', 10, 2);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('total_paid', 10, 2);
            $table->enum('payment_method', ['Cash', 'GCash', 'Credit Card', 'Bank Transfer', 'PayMaya']);
            $table->dateTime('payment_date');
            $table->string('reference_no', 100)->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['Paid', 'Pending', 'Refunded'])->default('Paid');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 9. TRAINER ASSIGNMENTS
        Schema::create('trainer_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_id')->constrained('trainers');
            $table->foreignId('customer_id')->constrained('customers');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->unsignedTinyInteger('sessions_total')->default(0);
            $table->unsignedTinyInteger('sessions_done')->default(0);
            $table->text('notes')->nullable();
            $table->enum('status', ['Active', 'Completed', 'Cancelled'])->default('Active');
            $table->timestamps();
        });

        // 10. EQUIPMENT CATEGORIES
        Schema::create('equipment_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });

        // 11. EQUIPMENT
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('equipment_categories');
            $table->foreignId('branch_id')->constrained('branches');
            $table->string('name', 150);
            $table->string('brand', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('serial_number', 100)->unique()->nullable();
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->enum('condition', ['New', 'Good', 'Fair', 'Needs Repair', 'Retired'])->default('Good');
            $table->date('last_maintained')->nullable();
            $table->date('next_maintenance')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 12. MAINTENANCE LOGS
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipment');
            $table->string('performed_by', 100)->nullable();
            $table->date('maintenance_date');
            $table->text('description')->nullable();
            $table->decimal('cost', 10, 2)->default(0.00);
            $table->date('next_schedule')->nullable();
            $table->timestamps();
        });

        // 13. CLASSES
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_id')->constrained('trainers');
            $table->foreignId('branch_id')->constrained('branches');
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('schedule_day', 50); // stored as comma-separated e.g. "Mon,Wed,Fri"
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedTinyInteger('max_capacity')->default(20);
            $table->decimal('fee', 10, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 14. CLASS ENROLLMENTS
        Schema::create('class_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes');
            $table->foreignId('customer_id')->constrained('customers');
            $table->timestamp('enrolled_at')->useCurrent();
            $table->enum('status', ['Enrolled', 'Dropped', 'Completed'])->default('Enrolled');
            $table->unique(['class_id', 'customer_id']);
        });

        // 15. ATTENDANCE
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('branch_id')->constrained('branches');
            $table->dateTime('check_in');
            $table->dateTime('check_out')->nullable();
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 16. ANNOUNCEMENTS
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('posted_by')->constrained('users');
            $table->string('title', 200);
            $table->text('body');
            $table->dateTime('published_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Drop in reverse order to respect foreign keys
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('attendance');
        Schema::dropIfExists('class_enrollments');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('maintenance_logs');
        Schema::dropIfExists('equipment');
        Schema::dropIfExists('equipment_categories');
        Schema::dropIfExists('trainer_assignments');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('memberships');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('membership_plans');
        Schema::dropIfExists('trainers');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('branches');
    }
};
