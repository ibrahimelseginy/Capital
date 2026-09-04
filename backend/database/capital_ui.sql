CREATE DATABASE IF NOT EXISTS capital_ui
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE capital_ui;

-- Home content is seeded once from config/home-defaults.json by lib/home.php.
CREATE TABLE IF NOT EXISTS home_page_sections (
  section_key VARCHAR(30) NOT NULL PRIMARY KEY,
  content_json MEDIUMTEXT NOT NULL,
  revision INT UNSIGNED NOT NULL DEFAULT 1,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS users (
  id VARCHAR(32) NOT NULL,
  name VARCHAR(160) NOT NULL,
  email VARCHAR(190) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('investor','entrepreneur','admin') NOT NULL,
  country VARCHAR(100) NOT NULL,
  whatsapp VARCHAR(40) NOT NULL DEFAULT '',
  project VARCHAR(190) NOT NULL DEFAULT '',
  investor_type VARCHAR(100) NOT NULL DEFAULT '',
  kyc_status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  is_demo TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL,
  password_updated_at DATETIME NULL,
  kyc_updated_at DATETIME NULL,
  PRIMARY KEY (id),
  UNIQUE KEY users_email_unique (email),
  KEY users_role_index (role),
  KEY users_kyc_index (kyc_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS password_resets (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id VARCHAR(32) NOT NULL,
  token_hash CHAR(64) NOT NULL,
  expires_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY password_resets_token_unique (token_hash),
  KEY password_resets_user_index (user_id),
  CONSTRAINT password_resets_user_fk FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS investor_holdings (
  id VARCHAR(36) NOT NULL,
  user_id VARCHAR(32) NOT NULL,
  name VARCHAR(190) NOT NULL,
  sector VARCHAR(120) NOT NULL,
  invested_amount DECIMAL(15,2) NOT NULL DEFAULT 0,
  current_value DECIMAL(15,2) NOT NULL DEFAULT 0,
  currency CHAR(3) NOT NULL DEFAULT 'USD',
  status ENUM('active','inactive','exited') NOT NULL DEFAULT 'active',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL,
  PRIMARY KEY (id),
  KEY holdings_user_index (user_id),
  CONSTRAINT holdings_user_fk FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS portfolio_history (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id VARCHAR(32) NOT NULL,
  label VARCHAR(40) NOT NULL,
  value DECIMAL(15,2) NOT NULL DEFAULT 0,
  sort_order INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY portfolio_history_user_index (user_id),
  CONSTRAINT portfolio_history_user_fk FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS opportunities (
  id VARCHAR(20) NOT NULL,
  title VARCHAR(190) NOT NULL,
  sector VARCHAR(120) NOT NULL,
  stage VARCHAR(80) NOT NULL,
  target_amount DECIMAL(15,2) NOT NULL DEFAULT 0,
  currency CHAR(3) NOT NULL DEFAULT 'USD',
  status ENUM('available','review','completed') NOT NULL DEFAULT 'review',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS investor_opportunities (
  user_id VARCHAR(32) NOT NULL,
  opportunity_id VARCHAR(20) NOT NULL,
  assigned_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, opportunity_id),
  CONSTRAINT investor_opportunities_user_fk FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT investor_opportunities_opportunity_fk FOREIGN KEY (opportunity_id) REFERENCES opportunities(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS meetings (
  id VARCHAR(36) NOT NULL,
  user_id VARCHAR(32) NOT NULL,
  subject VARCHAR(190) NOT NULL,
  opportunity VARCHAR(190) NOT NULL DEFAULT '',
  scheduled_at DATETIME NOT NULL,
  platform VARCHAR(80) NOT NULL,
  status ENUM('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY meetings_user_date_index (user_id, scheduled_at),
  CONSTRAINT meetings_user_fk FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS activities (
  id VARCHAR(36) NOT NULL,
  user_id VARCHAR(32) NOT NULL,
  text VARCHAR(255) NOT NULL,
  type VARCHAR(40) NOT NULL DEFAULT 'info',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY activities_user_date_index (user_id, created_at),
  CONSTRAINT activities_user_fk FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS pledges (
  id VARCHAR(36) NOT NULL,
  user_id VARCHAR(32) NOT NULL,
  opportunity_id VARCHAR(20) NOT NULL,
  amount DECIMAL(15,2) NOT NULL DEFAULT 0,
  currency CHAR(3) NOT NULL DEFAULT 'USD',
  status ENUM('non_binding','pending','approved','cancelled') NOT NULL DEFAULT 'non_binding',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY pledges_user_index (user_id),
  CONSTRAINT pledges_user_fk FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT pledges_opportunity_fk FOREIGN KEY (opportunity_id) REFERENCES opportunities(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS contracts (
  id VARCHAR(36) NOT NULL, user_id VARCHAR(32) NULL, title VARCHAR(190) NOT NULL,
  status ENUM('draft','review','pending_signature','signed','cancelled') NOT NULL DEFAULT 'draft',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME NULL,
  PRIMARY KEY (id), KEY contracts_user_index (user_id),
  CONSTRAINT contracts_user_fk FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS content_items (
  id VARCHAR(36) NOT NULL, title VARCHAR(190) NOT NULL,
  content_type ENUM('article','news','update') NOT NULL DEFAULT 'article',
  category_label VARCHAR(80) NOT NULL DEFAULT 'مقال', excerpt VARCHAR(700) NOT NULL DEFAULT '',
  reading_time VARCHAR(80) NOT NULL DEFAULT '', cover_image VARCHAR(500) NOT NULL DEFAULT '',
  external_url VARCHAR(500) NOT NULL DEFAULT '', is_featured TINYINT(1) NOT NULL DEFAULT 0,
  sort_order INT NOT NULL DEFAULT 0,
  status ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME NULL, published_at DATETIME NULL,
  PRIMARY KEY (id), KEY content_publish_index (status,sort_order,published_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS events (
  id VARCHAR(36) NOT NULL, title VARCHAR(190) NOT NULL, starts_at DATETIME NOT NULL,
  location VARCHAR(190) NOT NULL DEFAULT '', description VARCHAR(900) NOT NULL DEFAULT '', capacity INT UNSIGNED NULL,
  registered_count INT UNSIGNED NOT NULL DEFAULT 0, registration_url VARCHAR(500) NOT NULL DEFAULT '',
  calendar_url VARCHAR(500) NOT NULL DEFAULT '', sort_order INT NOT NULL DEFAULT 0,
  status ENUM('draft','published','completed','cancelled') NOT NULL DEFAULT 'draft',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME NULL,
  PRIMARY KEY (id), KEY events_publish_index (status,sort_order,starts_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS admin_audit_log (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, admin_user_id VARCHAR(32) NULL,
  action VARCHAR(80) NOT NULL, entity_type VARCHAR(80) NOT NULL, entity_id VARCHAR(64) NOT NULL DEFAULT '',
  details VARCHAR(500) NOT NULL DEFAULT '', created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id), KEY audit_created_index (created_at),
  CONSTRAINT audit_admin_fk FOREIGN KEY (admin_user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS sector_map (
  code CHAR(2) NOT NULL, name VARCHAR(160) NOT NULL, description VARCHAR(500) NOT NULL,
  tags_json TEXT NOT NULL, icon_key VARCHAR(40) NOT NULL DEFAULT 'software',
  sort_order INT NOT NULL DEFAULT 0, is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME NULL,
  PRIMARY KEY (code), KEY sector_map_sort_index (sort_order,is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS site_settings (
  setting_key VARCHAR(80) NOT NULL, setting_value TEXT NOT NULL, updated_at DATETIME NULL,
  PRIMARY KEY (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS success_stories (
  id VARCHAR(36) NOT NULL, sector_label VARCHAR(120) NOT NULL, category_key VARCHAR(40) NOT NULL,
  title VARCHAR(190) NOT NULL, problem_text VARCHAR(700) NOT NULL, solution_text VARCHAR(900) NOT NULL,
  duration VARCHAR(80) NOT NULL, metrics_json TEXT NOT NULL, sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1, created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL,
  PRIMARY KEY (id), KEY success_stories_publish_index (is_active,sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS about_page_items (
  id VARCHAR(36) NOT NULL, section_key VARCHAR(40) NOT NULL, title VARCHAR(190) NOT NULL,
  subtitle VARCHAR(255) NOT NULL DEFAULT '', body VARCHAR(900) NOT NULL DEFAULT '',
  badge_label VARCHAR(80) NOT NULL DEFAULT '', badge_style VARCHAR(20) NOT NULL DEFAULT 'info',
  value_text VARCHAR(80) NOT NULL DEFAULT '', value_suffix VARCHAR(20) NOT NULL DEFAULT '',
  icon_key VARCHAR(40) NOT NULL DEFAULT 'default', primary_url VARCHAR(500) NOT NULL DEFAULT '',
  secondary_url VARCHAR(500) NOT NULL DEFAULT '', sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1, created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL,
  PRIMARY KEY (id), KEY about_items_section_index (section_key,is_active,sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS investor_page_items (
  id VARCHAR(36) NOT NULL, section_key VARCHAR(40) NOT NULL, title VARCHAR(190) NOT NULL,
  subtitle VARCHAR(255) NOT NULL DEFAULT '', body VARCHAR(1000) NOT NULL DEFAULT '',
  badge_label VARCHAR(120) NOT NULL DEFAULT '', badge_style VARCHAR(20) NOT NULL DEFAULT 'info',
  value_text VARCHAR(255) NOT NULL DEFAULT '', value_suffix VARCHAR(255) NOT NULL DEFAULT '',
  icon_key VARCHAR(40) NOT NULL DEFAULT 'default', primary_url VARCHAR(500) NOT NULL DEFAULT '',
  secondary_url VARCHAR(500) NOT NULL DEFAULT '', sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1, created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL,
  PRIMARY KEY (id), KEY investor_items_section_index (section_key,is_active,sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS entrepreneur_page_items (
  id VARCHAR(36) NOT NULL, section_key VARCHAR(40) NOT NULL, title VARCHAR(190) NOT NULL,
  subtitle VARCHAR(255) NOT NULL DEFAULT '', body VARCHAR(1000) NOT NULL DEFAULT '',
  badge_label VARCHAR(120) NOT NULL DEFAULT '', badge_style VARCHAR(20) NOT NULL DEFAULT 'info',
  value_text VARCHAR(255) NOT NULL DEFAULT '', value_suffix VARCHAR(500) NOT NULL DEFAULT '',
  icon_key VARCHAR(40) NOT NULL DEFAULT 'default', primary_url VARCHAR(500) NOT NULL DEFAULT '',
  secondary_url VARCHAR(500) NOT NULL DEFAULT '', sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1, created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL,
  PRIMARY KEY (id), KEY entrepreneur_items_section_index (section_key,is_active,sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO users
  (id,name,email,password,role,country,whatsapp,project,investor_type,kyc_status,is_demo,created_at)
VALUES
  ('eb996bff22b91f3e','مدير النظام','admin@seven-tech.local','$2y$12$0MYnFcviylvBrtXjMqFXOOmZW8rytneHIbmZCoG5tBIyKUHySEkDO','admin','مصر','','','','approved',0,NOW());
