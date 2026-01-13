-- ========================================
-- Database Tables for DocumentTraits, LoggerTrait, and SignatoryTrait
-- ========================================

-- Drop tables if they exist (in reverse order due to foreign keys)
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `action_logs`;
DROP TABLE IF EXISTS `set_signatory`;
DROP TABLE IF EXISTS `signatory_action`;
DROP TABLE IF EXISTS `students`;
DROP TABLE IF EXISTS `users`;

-- ========================================
-- 1. USERS TABLE
-- ========================================
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(255) NOT NULL,
    `remember_token` VARCHAR(100) NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `users_email_index` (`email`),
    INDEX `users_role_index` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- 2. STUDENTS TABLE
-- ========================================
CREATE TABLE `students` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` VARCHAR(255) NOT NULL UNIQUE,
    `course_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `department_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `school_year_and_semester_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `first_name` VARCHAR(255) NOT NULL,
    `middle_name` VARCHAR(255) NULL DEFAULT NULL,
    `last_name` VARCHAR(255) NOT NULL,
    `suffix` VARCHAR(255) NULL DEFAULT NULL,
    `gender` VARCHAR(255) NULL DEFAULT NULL,
    `date_of_birth` DATE NULL DEFAULT NULL,
    `age` INT NULL DEFAULT NULL,
    `address` TEXT NULL DEFAULT NULL,
    `profile_image` VARCHAR(255) NULL DEFAULT NULL,
    `student_id_image` VARCHAR(255) NULL DEFAULT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `status` VARCHAR(255) NOT NULL,
    `remember_token` VARCHAR(100) NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `students_student_id_index` (`student_id`),
    INDEX `students_email_index` (`email`),
    INDEX `students_course_id_index` (`course_id`),
    INDEX `students_department_id_index` (`department_id`),
    INDEX `students_school_year_and_semester_id_index` (`school_year_and_semester_id`),
    INDEX `students_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- 3. SIGNATORY_ACTION TABLE
-- ========================================
CREATE TABLE `signatory_action` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `action_name` VARCHAR(255) NOT NULL,
    `status` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `signatory_action_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- 4. SET_SIGNATORY TABLE
-- ========================================
CREATE TABLE `set_signatory` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `users_id` BIGINT UNSIGNED NOT NULL,
    `position` VARCHAR(255) NULL DEFAULT NULL,
    `academic_suffix` VARCHAR(255) NULL DEFAULT NULL,
    `signatory_action_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `status` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `set_signatory_users_id_index` (`users_id`),
    INDEX `set_signatory_signatory_action_id_index` (`signatory_action_id`),
    INDEX `set_signatory_status_index` (`status`),
    CONSTRAINT `set_signatory_signatory_action_id_foreign` 
        FOREIGN KEY (`signatory_action_id`) REFERENCES `signatory_action` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- 5. ACTION_LOGS TABLE
-- ========================================
CREATE TABLE `action_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `document_type` VARCHAR(255) NULL DEFAULT NULL,
    `document_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `created_by` BIGINT UNSIGNED NULL DEFAULT NULL,
    `action` VARCHAR(255) NOT NULL,
    `details` TEXT NULL DEFAULT NULL,
    `remarks` TEXT NULL DEFAULT NULL,
    `trackable_type` VARCHAR(255) NULL DEFAULT NULL,
    `trackable_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `ip_address` VARCHAR(255) NULL DEFAULT NULL,
    `user_agent` VARCHAR(255) NULL DEFAULT NULL,
    `location` VARCHAR(255) NULL DEFAULT NULL,
    `batch_uuid` VARCHAR(255) NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `action_logs_document_type_document_id_index` (`document_type`, `document_id`),
    INDEX `action_logs_trackable_type_trackable_id_index` (`trackable_type`, `trackable_id`),
    INDEX `action_logs_user_id_index` (`user_id`),
    INDEX `action_logs_action_index` (`action`),
    INDEX `action_logs_batch_uuid_index` (`batch_uuid`),
    INDEX `action_logs_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- 6. NOTIFICATIONS TABLE
-- ========================================
CREATE TABLE `notifications` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `type` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `signatory_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `release_type` VARCHAR(255) NULL DEFAULT NULL,
    `status` VARCHAR(255) NOT NULL DEFAULT 'pending',
    `message` TEXT NOT NULL,
    `title` VARCHAR(255) NULL DEFAULT NULL,
    `icon` VARCHAR(255) NULL DEFAULT NULL,
    `icon_color` VARCHAR(255) NULL DEFAULT NULL,
    `url` VARCHAR(255) NULL DEFAULT NULL,
    `pdf_url` VARCHAR(255) NULL DEFAULT NULL,
    `documentable_type` VARCHAR(255) NULL DEFAULT NULL,
    `documentable_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `notifiable_type` VARCHAR(255) NULL DEFAULT NULL,
    `notifiable_id` VARCHAR(255) NULL DEFAULT NULL,
    `data` JSON NULL DEFAULT NULL,
    `view_data` JSON NULL DEFAULT NULL,
    `read_at` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `notifications_user_id_index` (`user_id`),
    INDEX `notifications_signatory_id_index` (`signatory_id`),
    INDEX `notifications_status_index` (`status`),
    INDEX `notifications_documentable_type_documentable_id_index` (`documentable_type`, `documentable_id`),
    INDEX `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`, `notifiable_id`),
    INDEX `notifications_read_at_index` (`read_at`),
    INDEX `notifications_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- SAMPLE DATA INSERTION
-- ========================================

-- Insert sample signatory actions
INSERT INTO `signatory_action` (`action_name`, `status`) VALUES
('Prepared by', 'active'),
('Reviewed by', 'active'),
('Approved by', 'active'),
('Noted by', 'active'),
('Checked by', 'active');

-- Insert sample users (admin)
INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Admin User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Department Head', 'depthead@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff'),
('Dean', 'dean@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff');

-- Insert sample students
INSERT INTO `students` (`student_id`, `first_name`, `middle_name`, `last_name`, `email`, `password`, `status`) VALUES
('2024-0001', 'John', 'Michael', 'Doe', 'john.doe@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active'),
('2024-0002', 'Jane', 'Marie', 'Smith', 'jane.smith@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active'),
('2024-0003', 'Bob', 'James', 'Johnson', 'bob.johnson@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active');

-- ========================================
-- USAGE EXAMPLES
-- ========================================

/*
-- Example 1: Create a signatory assignment
INSERT INTO `set_signatory` (
    `users_id`, `position`, `academic_suffix`, `signatory_action_id`, 
    `status`
) VALUES (
    1, 'Department Head', 'PhD', 1, 'pending'
);

-- Example 2: Log an action
INSERT INTO `action_logs` (
    `document_type`, `document_id`, `user_id`, `action`, `details`, 
    `trackable_type`, `trackable_id`
) VALUES (
    'App\\Models\\Document', 1, 1, 'created', 'Document created', 
    'App\\Models\\Document', 1
);

-- Example 3: Create a notification
INSERT INTO `notifications` (
    `type`, `user_id`, `signatory_id`, `release_type`, `status`, 
    `message`, `title`, `documentable_type`, `documentable_id`
) VALUES (
    'App\\Notifications\\DocumentNotification', 1, 1, 'trail_release', 
    'pending', 'You have been assigned as a signatory', 'Document Signatory Assignment',
    'App\\Models\\Document', 1
);
*/
