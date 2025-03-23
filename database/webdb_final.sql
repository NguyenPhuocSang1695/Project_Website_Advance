-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2025 at 11:13 AM
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
-- Database: `webdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `CartID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(255) NOT NULL,
  `CreateAt` datetime NOT NULL,
  `Description` text DEFAULT NULL,
  `CategoryParent` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CategoryID`, `CategoryName`, `CreateAt`, `Description`, `CategoryParent`) VALUES
(1, 'Cây văn phòng', '0000-00-00 00:00:00', 'Các loại cây trang trí văn phòng, giúp thanh lọc không khí.', NULL),
(2, 'Cây dưới nước', '0000-00-00 00:00:00', 'Các loại cây sống trong nước, phù hợp với bể thủy sinh.', NULL),
(3, 'Cây dễ chăm', '0000-00-00 00:00:00', 'Những loại cây dễ trồng, ít cần chăm sóc.', NULL),
(4, 'Cây để bàn', '0000-00-00 00:00:00', 'Những loại cây nhỏ gọn, phù hợp đặt trên bàn làm việc.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE `orderdetails` (
  `OrderDetailID` int(11) NOT NULL,
  `OrderID` int(11) DEFAULT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `UnitPrice` decimal(10,2) NOT NULL,
  `TotalPrice` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `TotalAmount` decimal(10,0) NOT NULL,
  `OrderStatus` enum('pending','processing','shipped','completed','canceled') DEFAULT 'pending',
  `PaymentMethod` enum('cod','credit card','bank transfer') NOT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(255) NOT NULL,
  `CategoryID` int(11) DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  `StockQuantity` int(11) NOT NULL,
  `DescriptionBrief` text NOT NULL,
  `DescriptionDetail` text NOT NULL,
  `ImageURL` varchar(255) NOT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ProductID`, `ProductName`, `CategoryID`, `Price`, `StockQuantity`, `DescriptionBrief`, `DescriptionDetail`, `ImageURL`, `CreatedAt`) VALUES
(1, 'Cây thường xuân', 2, 160000.00, 10, 'Cây leo tường đẹp', '', '/Project_Website_Advance/assets/images/CAY1.jpg', '2025-03-23 17:03:33'),
(2, 'Cây lá sọc dưa hấu', 2, 85000.00, 10, 'Lá sọc độc đáo', '', '/Project_Website_Advance/assets/images/CAY2.jpg', '2025-03-23 17:03:33'),
(3, 'Cây cẩm nhung', 4, 150000.00, 10, 'Lá có vân đẹp', '', '/Project_Website_Advance/assets/images/CAY3.jpg', '2025-03-23 17:03:33'),
(4, 'Cây lan ý', 1, 180000.00, 10, 'Cây mang ý nghĩa hòa bình', '', '/Project_Website_Advance/assets/images/CAY4.jpg', '2025-03-23 17:03:33'),
(5, 'Cây phát tài núi', 1, 1950000.00, 10, 'Mang lại tài lộc, may mắn', '', '/Project_Website_Advance/assets/images/CAY5.jpg', '2025-03-23 17:03:33'),
(6, 'Cây kim ngân', 4, 100000.00, 10, 'Cây phong thủy hút tài lộc', '', '/Project_Website_Advance/assets/images/CAY6.jpg', '2025-03-23 17:03:33'),
(7, 'Trầu bà lá xanh', 1, 200000.00, 10, 'Dễ sống, thanh lọc không khí', '', '/Project_Website_Advance/assets/images/CAY7.jpg', '2025-03-23 17:03:33'),
(8, 'Cây dây nhện', 3, 35000.00, 10, 'Cây nhỏ, dễ chăm', '', '/Project_Website_Advance/assets/images/CAY8.jpg', '2025-03-23 17:03:33'),
(9, 'Cây trầu bà đỏ', 2, 300000.00, 10, 'Lá có màu đỏ bắt mắt', '', '/Project_Website_Advance/assets/images/CAY9.jpg', '2025-03-23 17:03:33'),
(10, 'Lưỡi hổ vàng', 3, 200000.00, 10, 'Hấp thụ khí độc', '', '/Project_Website_Advance/assets/images/CAY10.jpg', '2025-03-23 17:03:33'),
(11, 'Cây Thiết Mộc Lan', 1, 1000000.00, 10, 'Biểu tượng cho sự thịnh vượng', '', '/Project_Website_Advance/assets/images/CAY11.jpg', '2025-03-23 17:03:33'),
(12, 'Cây trầu bà vàng', 2, 200000.00, 10, 'Lá vàng sang trọng', '', '/Project_Website_Advance/assets/images/CAY12.jpg', '2025-03-23 17:03:33'),
(13, 'Thiết mộc lan sọc vàng', 3, 500000.00, 10, 'Cây phong thủy đẹp, dễ trồng', '', '/Project_Website_Advance/assets/images/CAY13.jpg', '2025-03-23 17:04:17'),
(14, 'Cây lưỡi hổ xanh', 3, 220000.00, 10, 'Lá xanh bắt mắt', '', '/Project_Website_Advance/assets/images/CAY14.jpg', '2025-03-23 17:03:33'),
(16, 'Vạn lộc đỏ', 4, 300000.00, 10, 'Lá đỏ đẹp', '', '/Project_Website_Advance/assets/images/CAY16.jpg', '2025-03-23 17:03:33'),
(17, 'Cây tróc bạc', 2, 180000.00, 10, 'Màu sắc đặc biệt', '', '/Project_Website_Advance/assets/images/CAY17.jpg', '2025-03-23 17:03:33'),
(18, 'Lưỡi hổ búp sen', 3, 45000.00, 10, 'Cây nhỏ, dễ chăm', '', '/Project_Website_Advance/assets/images/CAY18.jpg', '2025-03-23 17:03:33'),
(19, 'Cây Pachira aquatica', 4, 250000.00, 10, 'Cây phong thủy tốt', '', '/Project_Website_Advance/assets/images/CAY19.jpg', '2025-03-23 17:03:33'),
(20, 'Cây môn Hồng', 4, 100000.00, 10, 'Màu sắc nổi bật', '', '/Project_Website_Advance/assets/images/CAY20.jpg', '2025-03-23 17:03:33'),
(22, 'Cây trúc Nhật', 1, 250000.00, 10, 'Nhẹ nhàng, thanh thoát', '', '/Project_Website_Advance/assets/images/CAY22.jpg', '2025-03-23 17:03:33'),
(23, 'Cây cọ Nhật', 1, 650000.00, 10, 'Sang trọng, dễ chăm', '', '/Project_Website_Advance/assets/images/CAY23.jpg', '2025-03-23 17:03:33'),
(24, 'Cây kim tiền', 1, 450000.00, 10, 'Cây phong thủy hút tài lộc', '', '/Project_Website_Advance/assets/images/CAY24.jpg', '2025-03-23 17:03:33'),
(25, 'Cây ngọc ngân', 1, 300000.00, 10, 'Lá đẹp, phù hợp để trong nhà', '', '/Project_Website_Advance/assets/images/CAY25.jpg', '2025-03-23 17:03:33'),
(26, 'Cây rong la hán', 2, 90000.00, 10, 'Dễ trồng trong bể cá', '', '/Project_Website_Advance/assets/images/CAY26.jpg', '2025-03-23 17:03:33'),
(27, 'Cây bèo Nhật', 2, 75000.00, 10, 'Phù hợp bể thủy sinh', '', '/Project_Website_Advance/assets/images/CAY27.jpg', '2025-03-23 17:03:33'),
(28, 'Cây dương xỉ nước', 2, 110000.00, 10, 'Thích hợp bể cá cảnh', '', '/Project_Website_Advance/assets/images/CAY28.jpg', '2025-03-23 17:03:33'),
(29, 'Cây thủy sinh cỏ thìa', 2, 140000.00, 10, 'Tạo thảm xanh đẹp', '', '/Project_Website_Advance/assets/images/CAY29.jpg', '2025-03-23 17:03:33'),
(30, 'Cây thủy cúc', 2, 100000.00, 10, 'Cây thủy sinh đẹp', '', '/Project_Website_Advance/assets/images/CAY30.jpg', '2025-03-23 17:03:33'),
(31, 'Cây cau tiểu trâm', 3, 70000.00, 10, 'Cây xanh nhỏ, thanh lọc không khí', '', '/Project_Website_Advance/assets/images/CAY31.jpg', '2025-03-23 17:04:17'),
(32, 'Cây phú quý', 3, 320000.00, 10, 'Cây mang lại tài lộc, sức khỏe', '', '/Project_Website_Advance/assets/images/CAY32.jpg', '2025-03-23 17:04:17'),
(33, 'Cây vạn niên thanh', 3, 250000.00, 10, 'Cây xanh mát, dễ chăm sóc', '', '/Project_Website_Advance/assets/images/CAY33.jpg', '2025-03-23 17:04:17'),
(34, 'Cây sen đá', 3, 90000.00, 10, 'Cây nhỏ gọn, trang trí bàn làm việc', '', '/Project_Website_Advance/assets/images/CAY34.jpg', '2025-03-23 17:04:17'),
(35, 'Cây cỏ lan chi', 3, 50000.00, 10, 'Cây dễ chăm, thích hợp trong nhà', '', '/Project_Website_Advance/assets/images/CAY35.jpg', '2025-03-23 17:04:17'),
(36, 'Cây bonsai sam hương', 4, 700000.00, 10, 'Cây bonsai mini đẹp', '', '/Project_Website_Advance/assets/images/CAY36.jpg', '2025-03-23 17:03:33'),
(37, 'Cây bạch mã hoàng tử', 4, 270000.00, 10, 'Tán lá đẹp, sang trọng', '', '/Project_Website_Advance/assets/images/CAY37.jpg', '2025-03-23 17:03:33'),
(38, 'Cây hạnh phúc mini', 4, 400000.00, 10, 'Mang lại niềm vui', '', '/Project_Website_Advance/assets/images/CAY38.jpg', '2025-03-23 17:03:33'),
(39, 'Cây tùng bồng lai', 4, 350000.00, 10, 'Cây bonsai để bàn', '', '/Project_Website_Advance/assets/images/CAY39.jpg', '2025-03-23 17:03:33'),
(40, 'Cây trường sinh', 4, 200000.00, 10, 'Lá xanh quanh năm', '', '/Project_Website_Advance/assets/images/CAY40.jpg', '2025-03-23 17:03:33'),
(41, 'Cây bàng Singapore', 1, 400000.00, 10, 'Cây có lá to, tạo điểm nhấn', '', '/Project_Website_Advance/assets/images/CAY41.jpg', '2025-03-23 17:03:33');

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `ShipmentID` int(11) NOT NULL,
  `OrderID` int(11) DEFAULT NULL,
  `Carrier` varchar(255) DEFAULT NULL,
  `TrackingNumber` varchar(255) DEFAULT NULL,
  `Status` enum('pending','in transit','delivered','returned','failed') DEFAULT 'pending',
  `EstimatedDeliveryDate` datetime DEFAULT NULL,
  `ActualDeliveryDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `UserName` varchar(255) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `Address` varchar(500) NOT NULL,
  `Province` varchar(255) NOT NULL,
  `District` varchar(255) NOT NULL,
  `Ward` varchar(255) NOT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp(),
  `Role` enum('User','Admin') DEFAULT 'User',
  `Phone` varchar(15) NOT NULL,
  `Status` enum('Active','Blocked') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`CartID`),
  ADD UNIQUE KEY `CartID` (`CartID`),
  ADD KEY `ProductID` (`ProductID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`),
  ADD UNIQUE KEY `CategoryID` (`CategoryID`),
  ADD KEY `CategoryParent` (`CategoryParent`);

--
-- Indexes for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`OrderDetailID`),
  ADD UNIQUE KEY `OrderDetailID` (`OrderDetailID`),
  ADD KEY `ProductID` (`ProductID`),
  ADD KEY `OrderID` (`OrderID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD UNIQUE KEY `OrderID` (`OrderID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`),
  ADD UNIQUE KEY `ProductID` (`ProductID`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`ShipmentID`),
  ADD UNIQUE KEY `ShipmentID` (`ShipmentID`),
  ADD UNIQUE KEY `TrackingNumber` (`TrackingNumber`),
  ADD KEY `OrderID` (`OrderID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `UserID` (`UserID`),
  ADD UNIQUE KEY `UserName` (`UserName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `CartID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orderdetails`
--
ALTER TABLE `orderdetails`
  MODIFY `OrderDetailID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `shipments`
--
ALTER TABLE `shipments`
  MODIFY `ShipmentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`CategoryParent`) REFERENCES `categories` (`CategoryID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `orderdetails_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `orderdetails_ibfk_2` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON UPDATE NO ACTION;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `categories` (`CategoryID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `shipments_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
