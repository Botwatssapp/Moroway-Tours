-- Additive bootstrap matching the project's schema definitions. No data removal.
CREATE TABLE IF NOT EXISTS users (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL,
 email VARCHAR(255) NOT NULL UNIQUE, email_verified_at TIMESTAMP NULL,
 password VARCHAR(255) NOT NULL, remember_token VARCHAR(100) NULL,
 created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL, is_admin TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS password_reset_tokens (
 email VARCHAR(255) PRIMARY KEY, token VARCHAR(255) NOT NULL, created_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS sessions (
 id VARCHAR(255) PRIMARY KEY, user_id BIGINT UNSIGNED NULL, ip_address VARCHAR(45) NULL,
 user_agent TEXT NULL, payload LONGTEXT NOT NULL, last_activity INT NOT NULL,
 INDEX sessions_user_id_index(user_id), INDEX sessions_last_activity_index(last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS cache (
 `key` VARCHAR(255) PRIMARY KEY, value MEDIUMTEXT NOT NULL, expiration INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS cache_locks (
 `key` VARCHAR(255) PRIMARY KEY, owner VARCHAR(255) NOT NULL, expiration INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS jobs (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, queue VARCHAR(255) NOT NULL, payload LONGTEXT NOT NULL,
 attempts TINYINT UNSIGNED NOT NULL, reserved_at INT UNSIGNED NULL, available_at INT UNSIGNED NOT NULL,
 created_at INT UNSIGNED NOT NULL, INDEX jobs_queue_index(queue)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS job_batches (
 id VARCHAR(255) PRIMARY KEY, name VARCHAR(255) NOT NULL, total_jobs INT NOT NULL,
 pending_jobs INT NOT NULL, failed_jobs INT NOT NULL, failed_job_ids LONGTEXT NOT NULL,
 options MEDIUMTEXT NULL, cancelled_at INT NULL, created_at INT NOT NULL, finished_at INT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS failed_jobs (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, uuid VARCHAR(255) NOT NULL UNIQUE,
 connection TEXT NOT NULL, queue TEXT NOT NULL, payload LONGTEXT NOT NULL, exception LONGTEXT NOT NULL,
 failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS destinations (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, slug VARCHAR(255) NOT NULL UNIQUE,
 name VARCHAR(255) NOT NULL, description JSON NULL, image VARCHAR(255) NULL,
 created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS tours (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, destination_id BIGINT UNSIGNED NULL,
 slug VARCHAR(255) NOT NULL UNIQUE, title JSON NOT NULL, description JSON NOT NULL,
 start_city VARCHAR(255) NOT NULL, end_city VARCHAR(255) NOT NULL, duration_days SMALLINT UNSIGNED NOT NULL,
 kind VARCHAR(255) NOT NULL, content JSON NULL, published TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
 INDEX tours_start_city_index(start_city), INDEX tours_duration_days_index(duration_days),
 INDEX tours_kind_index(kind), INDEX tours_published_index(published),
 CONSTRAINT tours_destination_id_foreign FOREIGN KEY(destination_id) REFERENCES destinations(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS tour_images (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, tour_id BIGINT UNSIGNED NOT NULL,
 path VARCHAR(255) NOT NULL, alt VARCHAR(255) NOT NULL, credit VARCHAR(255) NULL, credit_url VARCHAR(255) NULL,
 position SMALLINT UNSIGNED NOT NULL DEFAULT 0, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
 CONSTRAINT tour_images_tour_id_foreign FOREIGN KEY(tour_id) REFERENCES tours(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS tour_packages (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, tour_id BIGINT UNSIGNED NOT NULL,
 name VARCHAR(255) NOT NULL, description TEXT NULL, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
 CONSTRAINT tour_packages_tour_id_foreign FOREIGN KEY(tour_id) REFERENCES tours(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS tour_availabilities (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, tour_id BIGINT UNSIGNED NOT NULL,
 date DATE NOT NULL, capacity SMALLINT UNSIGNED NOT NULL, available TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
 UNIQUE KEY tour_availabilities_tour_id_date_unique(tour_id,date),
 CONSTRAINT tour_availabilities_tour_id_foreign FOREIGN KEY(tour_id) REFERENCES tours(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS bookings (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, reference CHAR(36) NOT NULL UNIQUE,
 tour_id BIGINT UNSIGNED NULL, tour_package_id BIGINT UNSIGNED NULL,
 name VARCHAR(100) NOT NULL, email VARCHAR(255) NOT NULL, whatsapp VARCHAR(30) NOT NULL,
 travel_date DATE NOT NULL, people SMALLINT UNSIGNED NOT NULL, pack VARCHAR(100) NOT NULL,
 request_type VARCHAR(255) NOT NULL DEFAULT 'tour', duration SMALLINT UNSIGNED NULL,
 options JSON NULL, message TEXT NULL,
 status ENUM('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
 final_quote DECIMAL(12,2) NULL, currency VARCHAR(3) NOT NULL DEFAULT 'EUR',
 created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
 INDEX bookings_travel_date_index(travel_date), INDEX bookings_status_index(status),
 CONSTRAINT bookings_tour_id_foreign FOREIGN KEY(tour_id) REFERENCES tours(id) ON DELETE RESTRICT,
 CONSTRAINT bookings_tour_package_id_foreign FOREIGN KEY(tour_package_id) REFERENCES tour_packages(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS contacts (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, email VARCHAR(255) NOT NULL,
 message TEXT NOT NULL, handled TINYINT(1) NOT NULL DEFAULT 0, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS reviews (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, tour_id BIGINT UNSIGNED NULL,
 name VARCHAR(100) NOT NULL, text JSON NOT NULL, rating TINYINT UNSIGNED NOT NULL,
 is_demo TINYINT(1) NOT NULL DEFAULT 1, published TINYINT(1) NOT NULL DEFAULT 0,
 created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
 CONSTRAINT reviews_tour_id_foreign FOREIGN KEY(tour_id) REFERENCES tours(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS site_settings (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, `key` VARCHAR(255) NOT NULL UNIQUE,
 value TEXT NULL, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS personal_access_tokens (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, tokenable_type VARCHAR(255) NOT NULL,
 tokenable_id BIGINT UNSIGNED NOT NULL, name TEXT NOT NULL, token VARCHAR(64) NOT NULL UNIQUE,
 abilities TEXT NULL, last_used_at TIMESTAMP NULL, expires_at TIMESTAMP NULL,
 created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
 INDEX personal_access_tokens_tokenable_type_tokenable_id_index(tokenable_type,tokenable_id),
 INDEX personal_access_tokens_expires_at_index(expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
