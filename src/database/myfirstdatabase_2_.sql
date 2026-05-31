-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2026 at 12:48 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myfirstdatabase(2)`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `discount_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `discount_percent` int(11) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `created_by_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `total_price` decimal(10,2) NOT NULL,
  `shipping_street` varchar(255) DEFAULT NULL,
  `shipping_city` varchar(100) DEFAULT NULL,
  `shipping_country` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `status`, `total_price`, `shipping_street`, `shipping_city`, `shipping_country`, `created_at`) VALUES
(1, 4, 'pending', 99.99, 'Rruga Myslym Shyri 10', 'Tirana', 'Albania', '2026-05-10 20:51:04');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount_percent` int(11) DEFAULT 0,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `category`, `description`, `price`, `stock`, `image`) VALUES
(1, 'Hydrating Facial Cleanser', 'Cleansers', 'Gentle hydrating facial cleanser designed to remove impurities while keeping skin soft and moisturized.', 14.99, 50, 'assets/images/pexels-monirathnak-20384787.jpg'),
(2, 'Foaming Facial Cleanser', 'Cleansers', 'Refreshing foaming cleanser that deeply cleans pores and removes excess oil.', 15.99, 40, 'assets/images/pexels-misolo-cosmetic-2588316-11179695.jpg'),
(3, 'Ultra Shield SPF 50', 'Suncream', 'Lightweight SPF 50 sunscreen offering strong UV protection with a non-greasy finish.', 19.99, 35, 'assets/images/pexels-betul-ustun-236006735-16615430.jpg'),
(4, 'Hydrating Overnight Mask', 'Masks', 'Overnight facial mask that deeply hydrates and revitalizes tired skin while you sleep.', 18.99, 25, 'assets/images/pexels-tiger-lily-8260622.jpg'),
(5, 'Drunk Elephant Milki Micellar Cream Cleanser', 'Cleansers', 'A gentle cream cleanser that melts away makeup and impurities while leaving skin soft and balanced. Suitable for all skin types.', 38.00, 50, 'assets/images/imazhe_produkte/CC1.webp'),
(6, 'Pai Middlemist Seven Camellia & Rose Cream Cleanser', 'Cleansers', 'A dual-action cream cleanser with organic cloth, perfect for sensitive skin. Removes makeup gently while nourishing the skin barrier.', 42.00, 40, 'assets/images/imazhe_produkte/CC2.jpg'),
(7, 'CeraVe Hydrating Cream-to-Foam Cleanser', 'Cleansers', 'Transforms from a rich cream to a luxurious foam, removing makeup and cleansing skin without disrupting the protective barrier. Contains ceramides and hyaluronic acid.', 18.00, 100, 'assets/images/imazhe_produkte/CC3.jpg'),
(8, 'Charlotte Tilbury Goddess Cleansing Ritual Set', 'Cleansers', 'A luxurious two-step cleansing ritual featuring a vitamin-rich cleansing balm and purifying charcoal cleanser for radiant, goddess-worthy skin.', 65.00, 30, 'assets/images/imazhe_produkte/foamc.1.avif'),
(9, 'Charlotte Tilbury Goddess Foam Cleanser I', 'Cleansers', 'Step one of the ritual — a brightening foam that dissolves impurities and preps skin for deeper cleansing. Leaves skin glowing and refreshed.', 35.00, 45, 'assets/images/imazhe_produkte/foamc.2.avif'),
(10, 'Charlotte Tilbury Goddess Charcoal Cleanser II', 'Cleansers', 'Step two of the ritual — a purifying charcoal formula that deep-cleans pores and removes stubborn residue for perfectly clean skin.', 35.00, 45, 'assets/images/imazhe_produkte/foamc.3.avif'),
(11, 'Paula\'s Choice CLEAR Pore Normalizing Cleanser', 'Cleansers', 'A gentle gel cleanser specifically formulated for blemish-prone skin. Removes excess oil and impurities without over-drying or irritating.', 19.00, 80, 'assets/images/imazhe_produkte/gelc.1.avif'),
(12, 'Paula\'s Choice CLEAR Gel Cleanser Refill', 'Cleansers', 'Refill size of the bestselling pore normalizing gel cleanser. Salicylic acid gently exfoliates inside pores to reduce breakouts and blackheads.', 19.00, 60, 'assets/images/imazhe_produkte/gelc.2.avif'),
(13, 'Paula\'s Choice CLEAR Foaming Cleanser Travel Size', 'Cleansers', 'Travel-friendly version of the cult pore normalizing cleanser. Perfect for on-the-go cleansing without clogging pores or causing irritation.', 10.00, 70, 'assets/images/imazhe_produkte/gelc.3.avif'),
(14, 'Paula\'s Choice CLEAR Daily Skin Clearing Treatment', 'Cleansers', 'A targeted treatment cleanser that works on active blemishes while gently purifying the skin. Leaves skin clean, calm, and clear.', 19.00, 55, 'assets/images/imazhe_produkte/gelc.4.avif'),
(15, 'Kate Somerville EradiKate Daily Foaming Cleanser', 'Cleansers', 'A powerful yet gentle daily foaming cleanser that targets blemishes while thoroughly removing dirt and oil. Leaves skin fresh and refined.', 28.00, 50, 'assets/images/imazhe_produkte/img.4.avif'),
(16, 'Sioris Cleanse Me Softly Milk Cleanser', 'Cleansers', 'A mild milk cleanser with fermented ingredients that gently purifies skin while maintaining its natural moisture balance. Ideal for sensitive skin.', 32.00, 40, 'assets/images/imazhe_produkte/Mc1.jpg'),
(17, 'Make p:rem Safe Me Relief Moisture Cleansing Milk', 'Cleansers', 'A pH 5.5 balanced cleansing milk that softly removes impurities while locking in moisture. Leaves skin feeling clean, soft, and comfortable.', 27.00, 45, 'assets/images/imazhe_produkte/Mc2.jpg'),
(18, 'Dewytree Hi Amino All Cleansing Milk', 'Cleansers', 'Amino acid-based cleansing milk that thoroughly yet gently cleanses skin. Strengthens the skin barrier while removing makeup and daily grime.', 24.00, 50, 'assets/images/imazhe_produkte/mc3.jpg'),
(19, 'Nuse Blueberry Cleansing Milk', 'Cleansers', 'A soft cleansing milk enriched with blueberry extracts and antioxidants. Melts away impurities while leaving skin nourished and dewy.', 22.00, 35, 'assets/images/imazhe_produkte/mc4.jpg'),
(20, 'Bioderma Sensibio H2O Micellar Water', 'Cleansers', 'The iconic French pharmacy micellar water for sensitive skin. Gently yet effectively removes makeup and cleanses without rinsing. Dermatologist recommended.', 15.00, 120, 'assets/images/imazhe_produkte/mw1.jpg'),
(21, 'Garnier SkinActive Micellar Water with Vitamin C', 'Cleansers', 'All-in-one micellar water with Vitamin C that removes makeup, cleanses, and adds a brightening glow in one step. No harsh rubbing needed.', 12.00, 100, 'assets/images/imazhe_produkte/mw2.webp'),
(22, 'La Roche-Posay Micellar Water Ultra', 'Cleansers', 'Ultra-gentle micellar cleansing water formulated for sensitive skin. Removes makeup and impurities effectively with no rinse required.', 20.00, 90, 'assets/images/imazhe_produkte/mw3.jpg'),
(23, 'Vichy Pureté Thermale Micellar Water', 'Cleansers', 'Enriched with Vichy thermal water, this micellar solution gently removes makeup and soothes skin simultaneously. Suitable for all skin types including sensitive.', 18.00, 75, 'assets/images/imazhe_produkte/mw4.jpg'),
(24, 'Simple Kind to Skin Micellar Cleansing Water', 'Cleansers', 'A no-rinse micellar water free from perfume, artificial colours and harsh chemicals. Gently cleanses and removes makeup in seconds.', 10.00, 110, 'assets/images/imazhe_produkte/mw5.jpg'),
(25, 'Sulwhasoo Gentle Cleansing Oil', 'Cleansers', 'A luxurious Korean cleansing oil duo that deeply dissolves makeup and impurities while leaving skin silky and hydrated. First step of a double cleanse.', 55.00, 35, 'assets/images/imazhe_produkte/oc.jpg'),
(26, 'iUNIK Calendula Complete Cleansing Oil', 'Cleansers', 'A soothing calendula-infused cleansing oil that gently melts away sunscreen, makeup and sebum. Leaves skin clean without any greasy residue.', 22.00, 60, 'assets/images/imazhe_produkte/oc2.jpg'),
(27, 'Anua Heartleaf Pore Control Cleansing Oil', 'Cleansers', 'Lightweight cleansing oil with heartleaf extract that unclogs pores while dissolving impurities. Rinses clean without stripping the skin.', 25.00, 55, 'assets/images/imazhe_produkte/oc3.jpg'),
(28, 'DHC Deep Cleansing Oil', 'Cleansers', 'Japan\'s #1 cleansing oil. This olive oil-based formula melts away even waterproof makeup, sunscreen and excess sebum. A cult classic for a reason.', 28.00, 80, 'assets/images/imazhe_produkte/oc4.webp'),
(29, 'Neutrogena Ultra Sheer Dry-Touch Sunscreen SPF 70', 'Suncream', 'Dermatologist-recommended lightweight sunscreen with broad spectrum SPF 70. Dries to a clean, non-greasy matte finish. Water resistant for up to 80 minutes.', 16.00, 90, 'assets/images/imazhe_produkte/cs1.avif'),
(30, 'SkinCeuticals Daily Brightening UV Defense Sunscreen SPF 30', 'Suncream', 'A brightening mineral sunscreen that evens skin tone while providing broad spectrum SPF 30 protection. Lightweight lotion finish suitable for daily wear.', 47.00, 40, 'assets/images/imazhe_produkte/cs2.webp'),
(31, 'Sunday Riley Light Hearted Broad Spectrum SPF 30', 'Suncream', 'A fun, lightweight sunscreen with zinc oxide, turmeric, and blue light defense. Broad spectrum SPF 30 in a smooth, skin-loving formula.', 55.00, 35, 'assets/images/imazhe_produkte/cs3.jpeg'),
(32, 'EltaMD UV Clear Tinted Broad-Spectrum SPF 46', 'Suncream', 'A tinted facial sunscreen ideal for acne-prone and hyperpigmentation-prone skin. Contains niacinamide and transparent zinc oxide for a clear, calm complexion.', 42.00, 50, 'assets/images/imazhe_produkte/cs4.jpg'),
(33, 'La Roche-Posay Anthelios Melt-In Milk Sunscreen SPF 60', 'Suncream', 'A fast-absorbing, velvety milk sunscreen with broad spectrum SPF 60. Water resistant, fragrance-free, and suitable for sensitive skin. A French pharmacy favourite.', 38.00, 65, 'assets/images/imazhe_produkte/cs5.jpeg'),
(34, 'Dr. Dennis Gross Lightweight Wrinkle Defense Sunscreen SPF 30', 'Suncream', 'A multi-tasking sunscreen that provides SPF 30 protection while targeting fine lines and wrinkles. Lightweight, fast-absorbing formula for daily anti-aging defense.', 48.00, 30, 'assets/images/imazhe_produkte/ps1.webp'),
(35, 'Neutrogena Sheer Zinc Face Mineral Sunscreen SPF 50', 'Suncream', 'A 100% mineral active, hypoallergenic sunscreen with pure zinc oxide. Oil-free, broad spectrum SPF 50 protection that is gentle on sensitive skin.', 18.00, 85, 'assets/images/imazhe_produkte/ps2.jpg'),
(36, 'Blue Lizard Sensitive Mineral Sunscreen SPF 50+', 'Suncream', 'Australian-formula broad spectrum SPF 50+ mineral sunscreen for sensitive skin. Smart Cap technology turns pink in UV light — a trusted sun protection staple.', 20.00, 70, 'assets/images/imazhe_produkte/ps3.avif'),
(37, 'CeraVe Hydrating Mineral Sunscreen SPF 50', 'Suncream', 'A lightweight, non-greasy mineral sunscreen with SPF 50, 3 essential ceramides, and hyaluronic acid. Protects and hydrates without leaving a white cast.', 22.00, 95, 'assets/images/imazhe_produkte/ps4.webp'),
(38, 'Skinthou Centella Pore Minimizing Quick Clay Stick Mask', 'Masks', 'An innovative clay mask in stick form with centella asiatica. Minimizes pores, absorbs excess oil and calms redness in just 10 minutes. No mess, no fuss.', 28.00, 45, 'assets/images/imazhe_produkte/cm1.webp'),
(39, 'Kiehl\'s Rare Earth Deep Pore Cleansing Masque', 'Masks', 'A deep-cleansing clay mask with Amazonian white clay that intensively purifies pores, removes impurities, and controls excess oil. Leaves skin looking matte and refined.', 42.00, 50, 'assets/images/imazhe_produkte/cm2.webp'),
(40, 'Freeman Purifying Avocado & Oatmeal Clay Mask', 'Masks', 'A nourishing clay mask combining purifying avocado with soothing oatmeal. Deep cleanses pores and nourishes skin for a fresh, healthy glow in just 10 minutes.', 8.00, 100, 'assets/images/imazhe_produkte/cm3.jpeg'),
(41, 'Freeman Clearing Sweet Tea & Lemon Peel-Off Clay Mask', 'Masks', 'A brightening peel-off clay mask with sweet tea and lemon extracts. Instantly removes impurities, tones skin, and reveals a more radiant complexion.', 4.00, 150, 'assets/images/imazhe_produkte/peel1.jpg'),
(42, 'Boscia Luminizing Black Charcoal Peel-Off Mask', 'Masks', 'A cult-favourite activated charcoal peel-off mask that draws out deep-seated impurities and unclogs pores. Reveals luminous, visibly clearer skin after just one use.', 34.00, 45, 'assets/images/imazhe_produkte/peel2.jpg'),
(43, 'Shiseido Waso Purifying Peel-Off Mask', 'Masks', 'A gentle peel-off mask with carrot extract that removes dead skin cells and surface impurities. Leaves skin feeling baby-smooth, fresh, and renewed.', 38.00, 40, 'assets/images/imazhe_produkte/peel3.jpg'),
(44, 'Mediheal Tea Tree Essential Blemish Control Mask', 'Masks', 'A K-beauty staple sheet mask with tea tree extract that soothes blemishes and controls excess sebum. Leaves skin calm, clear, and refreshed after just 20 minutes.', 3.00, 200, 'assets/images/imazhe_produkte/sheet1.webp'),
(45, 'Biodance Bio-Collagen Real Deep Mask', 'Masks', 'A hydrogel sheet mask packed with bio-collagen that plumps, firms and brightens skin overnight. Dissolves completely — no rinse needed. A viral TikTok favourite.', 7.00, 150, 'assets/images/imazhe_produkte/sheet2.jpeg'),
(46, 'Abib Mild Acidic pH Sheet Mask Aqua Fit', 'Masks', 'A pH-balancing sheet mask that restores the skin\'s natural acid mantle while delivering intense hydration. Leaves skin bouncy, calm, and perfectly prepped.', 5.00, 180, 'assets/images/imazhe_produkte/sheet3.webp'),
(47, 'Laneige Bouncy & Firm Sleeping Mask', 'Masks', 'An overnight sleeping mask with hyaluronic acid and Moisture Wrap technology that intensely hydrates and firms skin while you sleep. Wake up to plumper, bouncier skin.', 30.00, 60, 'assets/images/imazhe_produkte/sleep1.jpg'),
(48, 'Laneige Water Sleeping Mask', 'Masks', 'The iconic overnight gel mask that locks in moisture and delivers a surge of hydration while you sleep. Infused with sleep-promoting scent and mineral water.', 30.00, 65, 'assets/images/imazhe_produkte/sleep2.jpg'),
(49, 'Laneige Cica Sleeping Mask', 'Masks', 'A calming overnight sleeping mask with centella asiatica that soothes irritated skin and restores the skin barrier while you sleep. Wake up to calm, recovered skin.', 30.00, 55, 'assets/images/imazhe_produkte/sleep3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `rate_limits`
--

CREATE TABLE `rate_limits` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `request_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rate_limits`
--

INSERT INTO `rate_limits` (`id`, `ip_address`, `request_time`) VALUES
(25, '::1', 1778597815);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT curtime(),
  `email` varchar(255) NOT NULL,
  `role` enum('customer','admin') NOT NULL DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `pwd`, `created_at`, `email`, `role`) VALUES
(1, 'evakoci', '$2y$12$0woDEP7wsYBqsIs6gwLIvOaDiGwB/Mxx8YZMgjHfo1M9zO9N4.uWa', '2026-05-04 18:57:59', 'etlevakoci5@gmail.com', 'admin'),
(2, 'kleadisha', '$2y$12$aOASXgrO2zUec3dxynHFRODC1s/.zBF/IeBehf09CkoDgd5/GZTUG', '2026-05-04 18:58:25', 'kleadisha1@gmail.com', 'customer'),
(3, 'erila', '$2y$12$i9/LFbLHu6hLWkhtHjvX/OSBHHKV4jEJU/sxvw9u1b3iqJeYtVXJC', '2026-05-05 00:53:19', 'erilatifllari@gmail.com', 'customer'),
(4, 'klevikoci1', '$2y$12$Ckbmn2qchP6MCZcxeWbvgOEh55pGfNSZ.pMg7HNnn7OuJWph43HVe', '2026-05-05 11:19:42', 'klevikoci@gmail.com', 'customer'),
(5, 'juritanushi', '$2y$12$Lib6yCcpG8AxjNWd767Ii.doS4t6ijKgxdo4MPggfqtK8sC27I5HC', '2026-05-05 13:23:50', 'juritanushi@gmail.com', 'customer'),
(8, 'luelaterziu', '$2y$12$fGp6sSACWHHstVEjaPlwSOMF1i98mp3G3.VaRnzk4iYoM/P0wGvHi', '2026-05-17 22:39:51', 'luelaterziu12@gmail.com', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`discount_id`),
  ADD KEY `fk_discounts_product` (`product_id`),
  ADD KEY `fk_discounts_created_by` (`created_by_user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_orders_user` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `fk_order_details_order` (`order_id`),
  ADD KEY `fk_order_details_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `rate_limits`
--
ALTER TABLE `rate_limits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `discount_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `rate_limits`
--
ALTER TABLE `rate_limits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `discounts`
--
ALTER TABLE `discounts`
  ADD CONSTRAINT `fk_discounts_created_by` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_discounts_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_order_details_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_details_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
