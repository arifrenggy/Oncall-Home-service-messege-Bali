-- schema.sql

-- 1. Settings Table
CREATE TABLE IF NOT EXISTS `settings` (
  `setting_key` VARCHAR(50) NOT NULL,
  `setting_value` TEXT NOT NULL,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Services Table
CREATE TABLE IF NOT EXISTS `services` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `service_id` VARCHAR(50) NOT NULL UNIQUE,
  `title` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `featured` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Service Options Table (Prices & Durations)
CREATE TABLE IF NOT EXISTS `service_options` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `service_ref` INT(11) NOT NULL,
  `duration` VARCHAR(30) NOT NULL,
  `price` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`service_ref`) REFERENCES `services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Service Areas Table
CREATE TABLE IF NOT EXISTS `areas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `area_name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. FAQs Table
CREATE TABLE IF NOT EXISTS `faqs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `question` VARCHAR(255) NOT NULL,
  `answer` TEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEED DEFAULT SETTINGS
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('brandName', 'Oncall & home service message'),
('tagline', 'Rejuvenate Your Body & Mind at Your Villa'),
('description', 'Premium on-call massage and spa treatments delivered directly to your villa, hotel, or home in Bali. Certified therapists, organic oils, and pure relaxation.'),
('whatsapp', '6281234567890'),
('instagram', 'https://instagram.com/baligreenoasis'),
('operatingHours', '09:00 AM - 10:00 PM'),
('heroImage', 'assets/images/hero-massage.webp'),
('aboutImage', 'assets/images/about-massage.webp')
ON DUPLICATE KEY UPDATE `setting_value`=VALUES(`setting_value`);

-- SEED SERVICES
INSERT INTO `services` (`id`, `service_id`, `title`, `description`, `image_path`, `featured`) VALUES
(1, 'balinese-massage', 'Balinese Traditional Massage', 'Enjoy the best traditional Balinese massage at your villa, hotel, or home in Canggu, Seminyak, or Kuta. A full-body holistic treatment using premium organic aromatherapy oils.', 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=600', 1),
(2, 'deep-tissue', 'Deep Tissue Massage', 'Our on-call deep tissue massage in Bali focuses on realigning deeper layers of muscles. Highly beneficial for muscle recovery, tension relief, or jet lag recovery after your flight.', 'https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?q=80&w=600', 1),
(3, 'reflexology', 'Foot Reflexology', 'Authentic foot reflexology delivered directly to your place. Applies precise pressure to restore energy flow and relieve tired feet after exploring Ubud or Uluwatu.', 'https://images.unsplash.com/photo-1519699047748-de8e457a634e?q=80&w=600', 0);

-- SEED SERVICE OPTIONS
INSERT INTO `service_options` (`service_ref`, `duration`, `price`) VALUES
(1, '60 Mins', '250,000 IDR'),
(1, '90 Mins', '350,000 IDR'),
(1, '120 Mins', '450,000 IDR'),
(2, '60 Mins', '300,000 IDR'),
(2, '90 Mins', '400,000 IDR'),
(2, '120 Mins', '500,000 IDR'),
(3, '60 Mins', '200,000 IDR'),
(3, '90 Mins', '280,000 IDR');

-- SEED AREAS
INSERT INTO `areas` (`area_name`) VALUES
('Pecatu, Uluwatu, Nusa Dua'),
('Kuta, Seminyak, Canggu (including Pererenan)'),
('Tanah Lot, Tabanan'),
('Gianyar, Ubud');

-- SEED FAQS
INSERT INTO `faqs` (`question`, `answer`) VALUES
('How do I book a massage?', 'Simply select your service on our website, click \'Book on WhatsApp\', fill in your details (date, time, villa/hotel address), and our admin will confirm your booking instantly.'),
('Are there any transport fees?', 'No, all transport costs are included in the menu price for our service coverage areas.'),
('What payment methods do you accept?', 'We accept Cash (IDR) directly to the therapist, Bank Transfers, or Wise payments.');

-- 6. Reviews Table
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `rating` INT NOT NULL,
  `comment` TEXT NOT NULL,
  `status` VARCHAR(20) DEFAULT 'approved',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEED REVIEWS
INSERT INTO `reviews` (`name`, `rating`, `comment`, `status`) VALUES
('Sarah Jenkins', 5, 'Absolutely amazing massage! The therapist arrived at our Seminyak villa on time, brought fresh towels, and the massage was incredibly relaxing after a long flight.', 'approved'),
('Michael Go', 5, 'Highly professional deep tissue massage. Helped so much with my muscle stiffness. Will definitely book again during my stay in Canggu.', 'approved'),
('Emily Watson', 5, 'Best home service spa in Bali! The aromatherapy oils smelled wonderful and the therapists were very polite and skilled. Very easy to book via WhatsApp.', 'approved');
