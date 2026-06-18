
-- RosethriftClothingStore - 


CREATE DATABASE IF NOT EXISTS `ClothingStore`;
USE `ClothingStore`;

-- ----------------------------------------------------------------
-- tblAdmin
-- ----------------------------------------------------------------
DROP TABLE IF EXISTS `tblAdmin`;
CREATE TABLE `tblAdmin` (
  `adminID`    INT          NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(100) NOT NULL,
  `username`   VARCHAR(50)  NOT NULL UNIQUE,
  `email`      VARCHAR(100) NOT NULL UNIQUE,
  `password`   VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`adminID`)
);
-- All passwords: admin123  (md5: 0192023a7bbd73250516f069df18b500)
INSERT INTO `tblAdmin` (`name`,`username`,`email`,`password`) VALUES
('Administrator',      'admin',       'admin@rosethrift.co.za',       '0192023a7bbd73250516f069df18b500'),
('Sipho Dlamini',      'sipho_d',     'sipho@rosethrift.co.za',       '0192023a7bbd73250516f069df18b500'),
('Nomsa Khumalo',      'nomsa_k',     'nomsa@rosethrift.co.za',       '0192023a7bbd73250516f069df18b500'),
('Tebogo Sithole',     'tebogo_s',    'tebogo@rosethrift.co.za',      '0192023a7bbd73250516f069df18b500'),
('Zanele Mokoena',     'zanele_m',    'zanele@rosethrift.co.za',      '0192023a7bbd73250516f069df18b500'),
('Bongani Nkosi',      'bongani_n',   'bongani@rosethrift.co.za',     '0192023a7bbd73250516f069df18b500'),
('Ayanda Cele',        'ayanda_c',    'ayanda@rosethrift.co.za',      '0192023a7bbd73250516f069df18b500'),
('Lungelo Zulu',       'lungelo_z',   'lungelo@rosethrift.co.za',     '0192023a7bbd73250516f069df18b500'),
('Precious Ndlovu',    'precious_n',  'precious@rosethrift.co.za',    '0192023a7bbd73250516f069df18b500'),
('Thabo Mthembu',      'thabo_m',     'thabo@rosethrift.co.za',       '0192023a7bbd73250516f069df18b500'),
('Lindiwe Shabalala',  'lindiwe_s',   'lindiwe@rosethrift.co.za',     '0192023a7bbd73250516f069df18b500'),
('Mduduzi Hadebe',     'mduduzi_h',   'mduduzi@rosethrift.co.za',     '0192023a7bbd73250516f069df18b500'),
('Nokwanda Buthelezi', 'nokwanda_b',  'nokwanda@rosethrift.co.za',    '0192023a7bbd73250516f069df18b500'),
('Sandile Mhlongo',    'sandile_m',   'sandile@rosethrift.co.za',     '0192023a7bbd73250516f069df18b500'),
('Ntombifuthi Gumede', 'ntombi_g',    'ntombi@rosethrift.co.za',      '0192023a7bbd73250516f069df18b500'),
('Siyabonga Mthethwa', 'siya_m',      'siya@rosethrift.co.za',        '0192023a7bbd73250516f069df18b500'),
('Hlengiwe Majola',    'hlengiwe_m',  'hlengiwe@rosethrift.co.za',    '0192023a7bbd73250516f069df18b500'),
('Mandla Ngcobo',      'mandla_n',    'mandla@rosethrift.co.za',      '0192023a7bbd73250516f069df18b500'),
('Ntombi Zwane',       'ntombi_z',    'ntombi_z@rosethrift.co.za',    '0192023a7bbd73250516f069df18b500'),
('Sibusiso Mkhize',    'sibusiso_m',  'sibusiso@rosethrift.co.za',    '0192023a7bbd73250516f069df18b500'),
('Khanyisile Dube',    'khanyisile_d','khanyisile@rosethrift.co.za',  '0192023a7bbd73250516f069df18b500'),
('Ntokozo Ntuli',      'ntokozo_n',   'ntokozo@rosethrift.co.za',     '0192023a7bbd73250516f069df18b500'),
('Simphiwe Khoza',     'simphiwe_k',  'simphiwe@rosethrift.co.za',    '0192023a7bbd73250516f069df18b500'),
('Gcinile Myeni',      'gcinile_m',   'gcinile@rosethrift.co.za',     '0192023a7bbd73250516f069df18b500'),
('Jabulani Ngema',     'jabulani_n',  'jabulani@rosethrift.co.za',    '0192023a7bbd73250516f069df18b500'),
('Ntombizodwa Mthwa',  'ntombizodwa', 'ntombizodwa@rosethrift.co.za', '0192023a7bbd73250516f069df18b500'),
('Mfanafuthi Ndaba',   'mfanafuthi_n','mfanafuthi@rosethrift.co.za',  '0192023a7bbd73250516f069df18b500'),
('Nobuhle Msweli',     'nobuhle_m',   'nobuhle@rosethrift.co.za',     '0192023a7bbd73250516f069df18b500'),
('Sthembiso Radebe',   'sthembiso_r', 'sthembiso@rosethrift.co.za',   '0192023a7bbd73250516f069df18b500'),
('Nompumelelo Mhlongo','nompumelelo_m','nompumelelo@rosethrift.co.za', '0192023a7bbd73250516f069df18b500');

-- ----------------------------------------------------------------
-- tblUser  (Part 1 - User Table)
-- ----------------------------------------------------------------
DROP TABLE IF EXISTS `tblUser`;
CREATE TABLE `tblUser` (
  `userID`     INT          NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(100) NOT NULL,
  `surname`    VARCHAR(100) DEFAULT '',
  `username`   VARCHAR(50)  NOT NULL UNIQUE,
  `email`      VARCHAR(100) NOT NULL UNIQUE,
  `phone`      VARCHAR(20)  DEFAULT '',
  `password`   VARCHAR(255) NOT NULL,
  `userType`   VARCHAR(20)  DEFAULT 'buyer',
  `status`     VARCHAR(20)  DEFAULT 'Pending',
  `created_at` TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`userID`)
);
-- All passwords: password123  (md5: 482c811da5d5b4bc6d497ffa98491e38)
INSERT INTO `tblUser` (`name`,`surname`,`username`,`email`,`phone`,`password`,`userType`,`status`) VALUES
('Lerato',      'Nxumalo',   'lerato_n',    'lerato@example.com',    '072 111 2233', '482c811da5d5b4bc6d497ffa98491e38', 'buyer',  'Verified'),
('Tiisetso',    'Mokone',    'tiisetso_m',  'tiisetso@example.com',  '083 444 5566', '482c811da5d5b4bc6d497ffa98491e38', 'seller', 'Verified'),
('Anele',       'Nkosi',     'anele_n',     'anele@example.com',     '061 777 8899', '482c811da5d5b4bc6d497ffa98491e38', 'seller', 'Verified'),
('Thabiso',     'Morebodi',  'thabiso_m',   'thabiso@example.com',   '072 333 4455', '482c811da5d5b4bc6d497ffa98491e38', 'buyer',  'Pending'),
('Nomvula',     'Dlamini',   'nomvula_d',   'nomvula@example.com',   '081 222 3344', '482c811da5d5b4bc6d497ffa98491e38', 'buyer',  'Verified'),
('Siphamandla', 'Cele',      'sipham_c',    'sipham@example.com',    '073 555 6677', '482c811da5d5b4bc6d497ffa98491e38', 'seller', 'Verified'),
('Ayanda',      'Zungu',     'ayanda_z',    'ayanda@example.com',    '083 666 7788', '482c811da5d5b4bc6d497ffa98491e38', 'buyer',  'Verified'),
('Blessing',    'Moyo',      'blessing_m',  'blessing@example.com',  '072 777 8899', '482c811da5d5b4bc6d497ffa98491e38', 'buyer',  'Verified'),
('Lungelo',     'Hadebe',    'lungelo_h',   'lungelo@example.com',   '061 888 9900', '482c811da5d5b4bc6d497ffa98491e38', 'seller', 'Verified'),
('Precious',    'Mthembu',   'precious_m',  'precious@example.com',  '083 999 0011', '482c811da5d5b4bc6d497ffa98491e38', 'buyer',  'Pending'),
('Sandile',     'Buthelezi', 'sandile_b',   'sandile@example.com',   '072 100 2200', '482c811da5d5b4bc6d497ffa98491e38', 'buyer',  'Verified'),
('Nokwanda',    'Gumede',    'nokwanda_g',  'nokwanda@example.com',  '081 200 3300', '482c811da5d5b4bc6d497ffa98491e38', 'seller', 'Verified'),
('Mduduzi',     'Mhlongo',   'mduduzi_m',   'mduduzi@example.com',   '073 300 4400', '482c811da5d5b4bc6d497ffa98491e38', 'buyer',  'Verified'),
('Hlengiwe',    'Shabalala',  'hlengiwe_s', 'hlengiwe@example.com',  '083 400 5500', '482c811da5d5b4bc6d497ffa98491e38', 'buyer',  'Pending'),
('Jabulani',    'Ngcobo',    'jabulani_n',  'jabulani@example.com',  '072 500 6600', '482c811da5d5b4bc6d497ffa98491e38', 'seller', 'Verified'),
('Lindiwe',     'Majola',    'lindiwe_m',   'lindiwe@example.com',   '061 600 7700', '482c811da5d5b4bc6d497ffa98491e38', 'buyer',  'Verified'),
('Sibusiso',    'Ntuli',     'sibusiso_n',  'sibusiso@example.com',  '081 700 8800', '482c811da5d5b4bc6d497ffa98491e38', 'buyer',  'Verified'),
('Zanele',      'Mkhize',    'zanele_mk',   'zanele@example.com',    '073 800 9900', '482c811da5d5b4bc6d497ffa98491e38', 'seller', 'Verified'),
('Thabo',       'Radebe',    'thabo_r',     'thabo@example.com',     '083 900 0011', '482c811da5d5b4bc6d497ffa98491e38', 'buyer',  'Pending'),
('Ntombi',      'Zwane',     'ntombi_z',    'ntombi@example.com',    '072 010 1122', '482c811da5d5b4bc6d497ffa98491e38', 'buyer',  'Verified'),
('Khanyisile',  'Dube',      'khanyisile_d','khanyisile@example.com','081 020 2233', '482c811da5d5b4bc6d497ffa98491e38', 'seller', 'Verified'),
('Simphiwe',    'Khoza',     'simphiwe_k',  'simphiwe@example.com',  '073 030 3344', '482c811da5d5b4bc6d497ffa98491e38', 'buyer',  'Verified'),
('Gcinile',     'Myeni',     'gcinile_m',   'gcinile@example.com',   '083 040 4455', '482c811da5d5b4bc6d497ffa98491e38', 'buyer',  'Pending'),
('Ntokozo',     'Ngema',     'ntokozo_n',   'ntokozo@example.com',   '072 050 5566', '482c811da5d5b4bc6d497ffa98491e38', 'buyer',  'Verified'),
('Bongani',     'Msweli',    'bongani_ms',  'bongani@example.com',   '061 060 6677', '482c811da5d5b4bc6d497ffa98491e38', 'seller', 'Verified'),
('Nobuhle',     'Ndaba',     'nobuhle_n',   'nobuhle@example.com',   '081 070 7788', '482c811da5d5b4bc6d497ffa98491e38', 'buyer',  'Verified'),
('Mandla',      'Mthwa',     'mandla_m',    'mandla@example.com',    '073 080 8899', '482c811da5d5b4bc6d497ffa98491e38', 'buyer',  'Verified'),
('Sthembiso',   'Radebe',    'sthembiso_r', 'sthembiso@example.com', '061 090 9900', '482c811da5d5b4bc6d497ffa98491e38', 'seller', 'Verified'),
('Nompumelelo', 'Mhlongo',   'nompum_m',    'nompum@example.com',    '083 091 0011', '482c811da5d5b4bc6d497ffa98491e38', 'buyer',  'Verified'),
('Mbuso',       'Sithole',   'mbuso_s',     'mbuso@example.com',     '072 092 1122', '482c811da5d5b4bc6d497ffa98491e38', 'buyer',  'Pending');

-- ----------------------------------------------------------------
-- tblClothes  (Part 1 - Clothes Table)
-- ----------------------------------------------------------------
DROP TABLE IF EXISTS `tblClothes`;
CREATE TABLE `tblClothes` (
  `itemID`        INT           NOT NULL AUTO_INCREMENT,
  `itemName`      VARCHAR(150)  NOT NULL,
  `brand`         VARCHAR(100)  DEFAULT '',
  `description`   TEXT          DEFAULT '',
  `category`      VARCHAR(50)   DEFAULT '',
  `conditionItem` VARCHAR(30)   DEFAULT '',
  `size`          VARCHAR(10)   DEFAULT '',
  `colour`        VARCHAR(50)   DEFAULT '',
  `price`         DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `image`         VARCHAR(255)  DEFAULT 'images/plain-white-tee.png',
  `quantity`      INT           NOT NULL DEFAULT 1,
  `sellerID`      INT           NOT NULL,
  `status`        VARCHAR(20)   DEFAULT 'Available',
  `created_at`    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`itemID`),
  FOREIGN KEY (`sellerID`) REFERENCES `tblUser`(`userID`) ON DELETE CASCADE
);

INSERT INTO `tblClothes`
  (`itemName`,`brand`,`description`,`category`,`conditionItem`,`size`,`colour`,`price`,`image`,`quantity`,`sellerID`,`status`)
VALUES
-- TOPS
('Vintage Graphic Tee',
 'Thrift',
 'Cool vintage car graphic tee. Oversized fit. Great condition, no stains.',
 'Tops','Good','M','White',85.00,'images/vintage-graphic-tee.png',3,2,'Available'),

('Plain White Oversized Tee',
 'Uniqlo',
 'Clean plain white cotton tee. Perfect everyday basic, barely worn.',
 'Tops','New','L','White',70.00,'images/plain-white-tee.png',5,3,'Available'),

('FUBU Plaid Short Sleeve Shirt',
 'FUBU',
 'Classic FUBU blue plaid button-up shirt. XL size, great condition.',
 'Tops','Good','XL','Blue',110.00,'images/fubu-plaid-shirt.png',2,2,'Available'),

-- JACKETS / HOODIES
('Nike Maroon Oversized Hoodie',
 'Nike',
 'Burgundy/maroon Nike fleece hoodie. Oversized fit, super soft inside.',
 'Tops','Good','M','Maroon',320.00,'images/nike-maroon-hoodie.png',2,3,'Available'),

('Nike Red Graphic Hoodie',
 'Nike',
 'Bold red Nike hoodie with custom bat graphic cutout detail. Rare piece.',
 'Tops','Good','L','Red',450.00,'images/nike-red-hoodie.png',1,2,'Available'),

('Columbia Brown Leather Jacket',
 'Columbia',
 'Genuine leather Columbia jacket with plaid lining. Warm and classic.',
 'Jackets','Good','L','Brown',550.00,'images/columbia-leather-jacket.png',1,3,'Available'),

('Beige Puffer Hooded Jacket',
 'Generic',
 'Washed beige puffer jacket with embroidery detail. Streetwear style.',
 'Jackets','Good','M','Beige',380.00,'images/beige-puffer-jacket.png',2,2,'Available'),

-- DRESSES
('Brown Bodycon Midi Dress',
 'Generic',
 'Chocolate brown square-neck bodycon dress. Elegant and fitted silhouette.',
 'Dresses','New','S','Brown',260.00,'images/brown-midi-dress.png',2,3,'Available'),

-- BOTTOMS
('Levis High-Waist Skinny Jeans',
 'Levis',
 'Dark blue high-waisted skinny jeans. Raw hem detail at the ankle.',
 'Bottoms','Good','28','Dark Blue',220.00,'images/levis-skinny-jeans.png',3,2,'Available'),

('Black Wide-Leg Trousers',
 'Generic',
 'Smart black wide-leg trousers. Great for formal or casual looks.',
 'Bottoms','New','32','Black',180.00,'images/black-wide-trousers.png',4,3,'Available'),

-- SHOES
('New Balance 530 White Sneakers',
 'New Balance',
 'White and navy New Balance 530 running sneakers. Lightly used.',
 'Shoes','Good','8','White',350.00,'images/new-balance-sneakers.png',1,2,'Available'),

('Timberland Platform Wheat Boots',
 'Timberland',
 'Wheat Timberland boots on a platform sole. Great condition.',
 'Shoes','Good','6','Wheat/Tan',480.00,'images/timberland-boots.png',1,3,'Available'),

('Black Patent Slingback Heels',
 'Generic',
 'Sleek black patent leather slingback stiletto heels with gold buckle.',
 'Shoes','New','5','Black',290.00,'images/black-heels.png',2,2,'Available'),

-- ACCESSORIES
('Gold Designer Bracelets Set',
 'Generic',
 'Set of 4 gold-tone bracelets including nail bangle, love bangle, YSL charm and tennis bracelet.',
 'Accessories','Good','One Size','Gold',150.00,'images/gold-bracelets.png',3,3,'Available'),

('Gold Frame Square Sunglasses',
 'Generic',
 'Retro gold metal frame sunglasses with brown tinted lenses.',
 'Accessories','Good','One Size','Gold',95.00,'images/gold-sunglasses.png',4,2,'Available'),

('Nike Beige Backpack',
 'Nike',
 'Tan/beige Nike Elemental backpack. Large Nike logo print. Excellent condition.',
 'Accessories','Good','One Size','Beige',230.00,'images/nike-beige-backpack.png',2,3,'Available'),

('Louis Vuitton Black Tote Bag',
 'Louis Vuitton',
 'Black LV Epi leather Neverfull tote with matching pouch. Luxury pre-loved.',
 'Accessories','Good','One Size','Black',1200.00,'images/lv-black-tote.png',1,2,'Available'),

-- ADDITIONAL ITEMS (to reach 30 total)
('Adidas Black Track Pants',
 'Adidas',
 'Classic black Adidas track pants with white stripes. Comfortable and stylish.',
 'Bottoms','Good','M','Black',160.00,'images/plain-white-tee.png',3,3,'Available'),

('H&M Floral Wrap Dress',
 'H&M',
 'Pretty floral wrap midi dress. Light and flowy for summer.',
 'Dresses','New','S','Floral',190.00,'images/brown-midi-dress.png',2,2,'Available'),

('Levi 501 Straight Jeans',
 'Levis',
 'Classic Levi 501 straight-leg jeans in medium wash. Timeless cut.',
 'Bottoms','Good','30','Medium Blue',270.00,'images/levis-skinny-jeans.png',2,3,'Available'),

('Zara Cream Blazer',
 'Zara',
 'Structured cream blazer with gold button detail. Perfect for smart casual looks.',
 'Jackets','New','S','Cream',350.00,'images/zara-cream-blazer.png',2,2,'Available'),

('Converse All Star High Tops',
 'Converse',
 'Black high-top Converse Chuck Taylor. Lightly worn, still very clean.',
 'Shoes','Good','7','Black',220.00,'images/new-balance-sneakers.png',2,3,'Available'),

('Polo Ralph Lauren Shirt',
 'Polo Ralph Lauren',
 'Classic white Polo Ralph Lauren short-sleeve shirt with logo. Size L.',
 'Tops','Good','L','White',200.00,'images/plain-white-tee.png',2,2,'Available'),

('Puma Running Shorts',
 'Puma',
 'Black Puma dry-cell running shorts. Lightweight and breathable.',
 'Bottoms','New','M','Black',95.00,'images/plain-white-tee.png',4,3,'Available'),

('Woolworths Knit Cardigan',
 'Woolworths',
 'Cosy grey knitted button-up cardigan from Woolworths. Barely worn.',
 'Tops','New','M','Grey',180.00,'images/nike-maroon-hoodie.png',3,2,'Available'),

('Block Heel Ankle Boots',
 'Generic',
 'Brown block-heel ankle boots with side zip. Comfortable everyday heel.',
 'Shoes','Good','6','Brown',310.00,'images/timberland-boots.png',2,3,'Available'),

('Silver Chain Necklace Set',
 'Generic',
 'Set of 3 layered silver chain necklaces of different lengths. Trendy.',
 'Accessories','New','One Size','Silver',130.00,'images/gold-bracelets.png',5,2,'Available'),

('Mr Price Denim Shorts',
 'Mr Price',
 'High-waisted light denim shorts with frayed hem. Great summer staple.',
 'Bottoms','Good','28','Light Blue',90.00,'images/levis-skinny-jeans.png',3,3,'Available'),

('Truworths Satin Slip Dress',
 'Truworths',
 'Dusty rose satin slip dress with adjustable straps. Elegant and feminine.',
 'Dresses','New','XS','Rose Pink',240.00,'images/brown-midi-dress.png',2,2,'Available'),

('Fila Vintage Windbreaker',
 'Fila',
 'Navy blue Fila windbreaker jacket with red and white logo. 90s style.',
 'Jackets','Good','L','Navy Blue',290.00,'images/columbia-leather-jacket.png',1,3,'Available');

-- ----------------------------------------------------------------
-- tblOrders  (Part 1 - Orders Table)
-- ----------------------------------------------------------------
DROP TABLE IF EXISTS `tblOrders`;
CREATE TABLE `tblOrders` (
  `orderID`      INT           NOT NULL AUTO_INCREMENT,
  `orderNumber`  VARCHAR(30)   NOT NULL UNIQUE,
  `sessionRef`   VARCHAR(100)  DEFAULT '',
  `userID`       INT           NOT NULL,
  `totalAmount`  DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `status`       VARCHAR(30)   DEFAULT 'Completed',
  `orderDate`    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`orderID`),
  FOREIGN KEY (`userID`) REFERENCES `tblUser`(`userID`) ON DELETE CASCADE
);

INSERT INTO `tblOrders` (`orderNumber`,`sessionRef`,`userID`,`totalAmount`,`status`) VALUES
('ORD-A1B2C3D4','sess_001',1, 85.00,'Completed'),
('ORD-B2C3D4E5','sess_002',1,220.00,'Completed'),
('ORD-C3D4E5F6','sess_003',4,350.00,'Completed'),
('ORD-D4E5F6G7','sess_004',5,110.00,'Completed'),
('ORD-E5F6G7H8','sess_005',7, 70.00,'Completed'),
('ORD-F6G7H8I9','sess_006',8,320.00,'Completed'),
('ORD-G7H8I9J0','sess_007',1,450.00,'Completed'),
('ORD-H8I9J0K1','sess_008',4,260.00,'Completed'),
('ORD-I9J0K1L2','sess_009',5,550.00,'Completed'),
('ORD-J0K1L2M3','sess_010',7,380.00,'Completed'),
('ORD-K1L2M3N4','sess_011',8,150.00,'Completed'),
('ORD-L2M3N4O5','sess_012',1,350.00,'Completed'),
('ORD-M3N4O5P6','sess_013',4,480.00,'Completed'),
('ORD-N4O5P6Q7','sess_014',5,290.00,'Completed'),
('ORD-O5P6Q7R8','sess_015',7, 95.00,'Completed'),
('ORD-P6Q7R8S9','sess_016',8,230.00,'Completed'),
('ORD-Q7R8S9T0','sess_017',1,1200.00,'Completed'),
('ORD-R8S9T0U1','sess_018',4,160.00,'Completed'),
('ORD-S9T0U1V2','sess_019',5,190.00,'Completed'),
('ORD-T0U1V2W3','sess_020',7,270.00,'Completed'),
('ORD-U1V2W3X4','sess_021',8,350.00,'Completed'),
('ORD-V2W3X4Y5','sess_022',1,220.00,'Completed'),
('ORD-W3X4Y5Z6','sess_023',4, 95.00,'Completed'),
('ORD-X4Y5Z6A7','sess_024',5,180.00,'Completed'),
('ORD-Y5Z6A7B8','sess_025',7,310.00,'Completed'),
('ORD-Z6A7B8C9','sess_026',8,130.00,'Completed'),
('ORD-A7B8C9D0','sess_027',1, 90.00,'Completed'),
('ORD-B8C9D0E1','sess_028',4,240.00,'Completed'),
('ORD-C9D0E1F2','sess_029',5,200.00,'Completed'),
('ORD-D0E1F2G3','sess_030',7,290.00,'Completed');

-- ----------------------------------------------------------------
-- tblOrderLine  (Part 1 - Order Items Table)
-- ----------------------------------------------------------------
DROP TABLE IF EXISTS `tblOrderLine`;
CREATE TABLE `tblOrderLine` (
  `lineID`    INT           NOT NULL AUTO_INCREMENT,
  `orderID`   INT           NOT NULL,
  `itemID`    INT           DEFAULT NULL,
  `itemName`  VARCHAR(150)  NOT NULL,
  `qty`       INT           NOT NULL DEFAULT 1,
  `unitPrice` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `subtotal`  DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`lineID`),
  FOREIGN KEY (`orderID`) REFERENCES `tblOrders`(`orderID`) ON DELETE CASCADE
);

-- ----------------------------------------------------------------
-- tblSalary  (Part 1 - Salary Table)
-- ----------------------------------------------------------------
DROP TABLE IF EXISTS `tblSalary`;
CREATE TABLE `tblSalary` (
  `salaryID`   INT           NOT NULL AUTO_INCREMENT,
  `sellerID`   INT           NOT NULL,
  `amount`     DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `date`       DATE          NOT NULL,
  `note`       VARCHAR(255)  DEFAULT '',
  `created_at` TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`salaryID`),
  FOREIGN KEY (`sellerID`) REFERENCES `tblUser`(`userID`) ON DELETE CASCADE
);

-- ----------------------------------------------------------------
-- tblMessages  (Part 1 - Messages Table)
-- ----------------------------------------------------------------
DROP TABLE IF EXISTS `tblMessages`;
CREATE TABLE `tblMessages` (
  `messageID`  INT          NOT NULL AUTO_INCREMENT,
  `senderID`   INT          NOT NULL,
  `subject`    VARCHAR(200) NOT NULL,
  `body`       TEXT         NOT NULL,
  `reply`      TEXT         DEFAULT NULL,
  `replied`    TINYINT(1)   DEFAULT 0,
  `created_at` TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`messageID`),
  FOREIGN KEY (`senderID`) REFERENCES `tblUser`(`userID`) ON DELETE CASCADE
);

-- ----------------------------------------------------------------
-- tblWishlist  (Part 1 - Favourites/Wishlist Feature)
-- ----------------------------------------------------------------
DROP TABLE IF EXISTS `tblWishlist`;
CREATE TABLE `tblWishlist` (
  `wishlistID` INT       NOT NULL AUTO_INCREMENT,
  `userID`     INT       NOT NULL,
  `itemID`     INT       NOT NULL,
  `saved_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`wishlistID`),
  UNIQUE KEY `unique_wishlist` (`userID`,`itemID`),
  FOREIGN KEY (`userID`) REFERENCES `tblUser`(`userID`) ON DELETE CASCADE,
  FOREIGN KEY (`itemID`) REFERENCES `tblClothes`(`itemID`) ON DELETE CASCADE
);

-- ----------------------------------------------------------------
-- tblSellRequests  (Seller item submission for admin approval)
-- ----------------------------------------------------------------
DROP TABLE IF EXISTS `tblSellRequests`;
CREATE TABLE `tblSellRequests` (
  `requestID`     INT           NOT NULL AUTO_INCREMENT,
  `sellerID`      INT           NOT NULL,
  `itemName`      VARCHAR(150)  NOT NULL,
  `brand`         VARCHAR(100)  DEFAULT '',
  `description`   TEXT          DEFAULT '',
  `price`         DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `size`          VARCHAR(10)   DEFAULT '',
  `category`      VARCHAR(50)   DEFAULT '',
  `conditionItem` VARCHAR(30)   DEFAULT '',
  `colour`        VARCHAR(50)   DEFAULT '',
  `image`         VARCHAR(255)  DEFAULT '',
  `status`        VARCHAR(20)   DEFAULT 'Pending',
  `created_at`    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`requestID`),
  FOREIGN KEY (`sellerID`) REFERENCES `tblUser`(`userID`) ON DELETE CASCADE
);
