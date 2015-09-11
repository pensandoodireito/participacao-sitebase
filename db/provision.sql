UPDATE wp_posts SET guid = REPLACE(guid, 'https', 'http') WHERE post_type IN ('debate','destaque','event','location','nav_menu_item','page','parceiro','post','publicacao');
UPDATE wp_posts SET guid = REPLACE(guid, 'pensando.mj.gov.br', 'localhost') WHERE post_type IN ('debate','destaque','event','location','nav_menu_item','page','parceiro','post','publicacao');
UPDATE wp_2_posts SET guid = REPLACE(guid, 'https', 'http') WHERE post_type IN ('debate','destaque','event','location','nav_menu_item','page','parceiro','post','publicacao');
UPDATE wp_2_posts SET guid = REPLACE(guid, 'pensando.mj.gov.br', 'localhost') WHERE post_type IN ('debate','destaque','event','location','nav_menu_item','page','parceiro','post','publicacao');
UPDATE wp_3_posts SET guid = REPLACE(guid, 'https', 'http') WHERE post_type IN ('debate','destaque','event','location','nav_menu_item','page','parceiro','post','publicacao');
UPDATE wp_3_posts SET guid = REPLACE(guid, 'pensando.mj.gov.br', 'localhost') WHERE post_type IN ('debate','destaque','event','location','nav_menu_item','page','parceiro','post','publicacao');
UPDATE wp_4_posts SET guid = REPLACE(guid, 'https', 'http') WHERE post_type IN ('debate','destaque','event','location','nav_menu_item','page','parceiro','post','publicacao');
UPDATE wp_4_posts SET guid = REPLACE(guid, 'pensando.mj.gov.br', 'localhost') WHERE post_type IN ('debate','destaque','event','location','nav_menu_item','page','parceiro','post','publicacao');
UPDATE wp_5_posts SET guid = REPLACE(guid, 'https', 'http') WHERE post_type IN ('debate','destaque','event','location','nav_menu_item','page','parceiro','post','publicacao');
UPDATE wp_5_posts SET guid = REPLACE(guid, 'pensando.mj.gov.br', 'localhost') WHERE post_type IN ('debate','destaque','event','location','nav_menu_item','page','parceiro','post','publicacao');
UPDATE wp_site SET domain = 'localhost';
UPDATE wp_blogs SET domain = 'localhost';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'root';
UPDATE wp_options SET option_value = 'http://localhost' WHERE option_name = 'siteurl';
UPDATE wp_options SET option_value = 'http://localhost' WHERE option_name = 'home';
FLUSH PRIVILEGES;


