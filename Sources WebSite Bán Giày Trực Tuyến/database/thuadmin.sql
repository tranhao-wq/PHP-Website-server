-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3307
-- Thời gian đã tạo: Th7 07, 2025 lúc 04:53 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `thuadmin`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `role` enum('superadmin','manager') DEFAULT 'manager',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admins`
--

INSERT INTO `admins` (`id`, `username`, `password_hash`, `full_name`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$unp3CUnq9mYTVdBN4xQmdeA3wF7kS8cd40RM7whLQLfRkA6TnlGSa', 'Quản trị viên chính', 'superadmin', '2025-07-05 14:03:00', '2025-07-05 14:53:36'),
(2, 'manager', '$2y$10$yhTUzsrox7PtvVSTGIFmm.Q0bCBPS4FkfpYp8qCXhC1G5hafln1hy', 'Quản lý cửa hàng', 'manager', '2025-07-05 14:03:00', '2025-07-06 07:19:42');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `color` varchar(50) DEFAULT NULL,
  `size` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `shipping_address` text NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `shipping_address`, `payment_method`, `created_at`, `updated_at`) VALUES
(1, 1, 7550000.00, 'completed', 'Root', 'Bank Transfer', '2025-07-04 09:53:06', '2025-07-06 13:04:30'),
(2, 1, 9600000.00, 'cancelled', 'QS', 'COD', '2025-07-05 04:41:58', '2025-07-06 07:33:03'),
(3, 1, 2150000.00, 'completed', 'Root', 'COD', '2025-07-05 08:48:34', '2025-07-06 13:04:36'),
(4, 1, 950000.00, 'processing', 'Root', 'COD', '2025-07-05 08:49:14', '2025-07-06 13:04:43'),
(5, 1, 950000.00, 'pending', 'Root', 'Bank Transfer', '2025-07-05 08:49:59', '2025-07-05 08:49:59'),
(6, 1, 1200000.00, 'cancelled', 'Root', 'COD', '2025-07-05 08:56:15', '2025-07-06 07:33:05'),
(7, 1, 950000.00, 'pending', 'Root', 'Bank Transfer', '2025-07-05 08:56:29', '2025-07-05 08:56:29'),
(8, 1, 1200000.00, 'pending', 'Root', 'COD', '2025-07-05 08:57:44', '2025-07-05 08:57:44'),
(9, 1, 950000.00, 'pending', 'Root', 'COD', '2025-07-05 08:58:44', '2025-07-05 08:58:44'),
(10, 1, 1500000.00, 'cancelled', 'Root', 'COD', '2025-07-05 09:01:49', '2025-07-06 07:33:07'),
(11, 1, 1200000.00, 'pending', 'Root', 'Bank Transfer', '2025-07-05 09:01:57', '2025-07-05 09:01:57'),
(12, 1, 1200000.00, 'pending', 'Root', 'Bank Transfer', '2025-07-05 09:02:04', '2025-07-05 09:02:04'),
(13, 1, 1350000.00, 'pending', 'Root', 'COD', '2025-07-05 09:03:17', '2025-07-06 07:32:24'),
(14, 1, 3750000.00, 'shipped', 'Root', 'COD', '2025-07-05 14:29:40', '2025-07-06 13:04:22'),
(15, 1, 1200000.00, 'cancelled', 'Root', 'Bank Transfer', '2025-07-06 07:17:50', '2025-07-06 07:32:54'),
(16, 1, 1200000.00, 'pending', 'Root', 'Bank Transfer', '2025-07-06 07:18:20', '2025-07-06 07:32:51'),
(17, 6, 4234000.00, 'shipped', 'Phường 13, Tân Bình', 'Bank Transfer', '2025-07-06 13:06:40', '2025-07-06 13:07:19'),
(18, 6, 890000.00, 'pending', 'Phường 13, Tân Bình, Hồ Chí Minh', 'COD', '2025-07-06 16:03:06', '2025-07-06 16:03:06'),
(19, 7, 6179000.00, 'completed', 'Hà Nội', 'Bank Transfer', '2025-07-06 16:11:15', '2025-07-06 16:11:41'),
(20, 7, 6744000.00, 'pending', 'Hà Nội', 'Bank Transfer', '2025-07-06 17:28:09', '2025-07-06 17:28:09');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 3, 1200000.00),
(2, 1, 3, 2, 1500000.00),
(3, 1, 2, 1, 950000.00),
(4, 2, 1, 3, 1200000.00),
(5, 2, 3, 4, 1500000.00),
(6, 3, 1, 1, 1200000.00),
(7, 3, 2, 1, 950000.00),
(8, 4, 2, 1, 950000.00),
(9, 5, 2, 1, 950000.00),
(10, 6, 1, 1, 1200000.00),
(11, 7, 2, 1, 950000.00),
(12, 8, 1, 1, 1200000.00),
(13, 9, 2, 1, 950000.00),
(14, 10, 3, 1, 1500000.00),
(15, 11, 1, 1, 1200000.00),
(16, 12, 1, 1, 1200000.00),
(18, 14, 1, 1, 1200000.00),
(19, 14, 1, 1, 1200000.00),
(21, 15, 1, 1, 1200000.00),
(22, 16, 1, 1, 1200000.00),
(23, 17, 1, 1, 3239000.00),
(24, 17, 3, 1, 995000.00),
(25, 18, 10, 1, 890000.00),
(26, 19, 26, 1, 6179000.00),
(27, 20, 3, 1, 995000.00),
(28, 20, 7, 1, 2349000.00),
(29, 20, 14, 1, 2600000.00),
(30, 20, 21, 1, 800000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `color` varchar(50) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `material` varchar(100) DEFAULT NULL,
  `gender` enum('Nam','Nữ','Unisex') DEFAULT 'Unisex',
  `season` varchar(50) DEFAULT NULL,
  `style` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category`, `brand`, `created_at`, `updated_at`, `color`, `stock_quantity`, `material`, `gender`, `season`, `style`, `is_active`) VALUES
(1, 'Nike Dunk Hight', 'Ban đầu được tạo ra cho sàn gỗ cứng, Dunk sau đó đã xuất hiện trên đường phố—và, như người ta nói, phần còn lại là lịch sử. Hơn 35 năm sau khi ra mắt, hình bóng này vẫn mang phong cách táo bạo, thách thức và vẫn là diện mạo được các đội bóng thèm muốn cả trong và ngoài sân đấu. \r\n\r\nGiờ đây, OG bóng rổ đại học trở lại với khối màu lấy cảm hứng từ di sản. Sự kết hợp cổ điển giữa màu trắng và Game Royal mang đến cho kiểu trang điểm này cảm giác hoài cổ, với công nghệ giày dép hiện đại đưa sự thoải mái của thiết kế vào thế kỷ 21.', 3239000.00, 'nike1.png', 'Sneaker', 'Nike', '2025-06-26 05:42:10', '2025-07-06 07:49:16', 'Xanh Dương', 46, 'Vải lưới', 'Unisex', 'Hè', 'Thể thao', 1),
(2, 'Nike Air Max Nuaxis', 'Thoáng khí và thoải mái, Air Max Nuaxis đã sẵn sàng cho cuộc sống hàng ngày. Bạn có thể tin tưởng rằng nó sẽ trông đẹp và cũng cảm thấy thoải mái. Nó kết hợp một đơn vị Air trực quan với các yếu tố thiết kế lấy cảm hứng từ Air Max 270, khiến nó trở thành đôi giày hoàn hảo để buộc dây và đi.\r\n\r\nNhững lợi ích\r\nPhần lưới phía trên giúp bạn luôn mát mẻ khi cuộc sống bận rộn.\r\nMềm mại và thoải mái, đệm Max Air có độ nâng đỡ vừa phải.\r\nĐế ngoài bằng cao su mang lại cho bạn lực kéo bền bỉ.\r\nChi tiết sản phẩm\r\nMàu sắc hiển thị: Đen/Đen/Trắng/Trắng\r\nKiểu dáng: FD4329-001\r\nQuốc gia/Khu vực xuất xứ: Ấn Độ, Việt Nam\r\nĐơn vị Max Air\r\nVẻ đẹp ngoạn mục. Cửa sổ nhìn ra đế giày. Nhà thiết kế huyền thoại Tinker Hatfield đã lấy cảm hứng từ kiến ​​trúc Paris từ trong ra ngoài để tái lập công nghệ Air như một công nghệ hàng đầu về đệm. Mềm mại và thoải mái, bộ phận Max Air có độ nâng đỡ vừa phải.', 2649000.00, 'nike2.png', 'Chạy bộ', 'Nike', '2025-06-26 05:42:10', '2025-07-06 07:54:21', 'Đen', 33, '', 'Unisex', '', '', 1),
(3, 'Nike Blazer Vintage', 'PHONG CÁCH CỔ ĐIỂN.\r\n\r\nVào những năm 70, Nike là đôi giày mới trên thị trường. Thực tế là mới đến mức chúng tôi vẫn đang đột phá vào làng bóng rổ và thử nghiệm các mẫu giày trên chân của đội bóng địa phương. Tất nhiên, thiết kế đã được cải thiện qua nhiều năm, nhưng cái tên vẫn được giữ nguyên. Nike Blazer Mid \'77 Vintage—cổ điển ngay từ đầu.\r\n\r\nNhững lợi ích\r\nPhần trên bằng da và chất liệu tổng hợp vẫn giữ được vẻ cổ điển của bản gốc đồng thời tăng thêm sự thoải mái và hỗ trợ.\r\nPhần đế giữa được xử lý theo phong cách cổ điển mang đến vẻ ngoài cổ điển.\r\nKết cấu hấp khử trùng kết hợp đế ngoài với đế giữa để tạo nên vẻ ngoài hợp lý gợi nhớ đến thiết kế của thập niên 70.\r\nLớp bọt lộ ra trên lưỡi gà mang lại vẻ đẹp hoài cổ.\r\nĐế ngoài bằng cao su đặc, không để lại dấu vết vẫn có cùng họa tiết xương cá ngay từ đầu, tại sao phải thay đổi những gì hiệu quả? Độ bám đường và độ bền tuyệt vời giúp để lại dấu ấn mà bạn yêu thích.\r\nChi tiết sản phẩm\r\nMàu sắc hiển thị: Trắng/Đen\r\nKiểu dáng: BQ6806-100\r\nQuốc gia/Khu vực xuất xứ: Indonesia, Ấn Độ, Việt Nam', 995000.00, 'nike3.png', 'Sneaker', 'Nike', '2025-06-26 05:42:10', '2025-07-06 08:01:52', 'Đen/Trắng', 16, 'Vải lưới', 'Unisex', 'Thu-Đông', 'Sneaker', 1),
(4, 'Nike Air Force 1 \'07', 'Thoải mái, bền bỉ và vượt thời gian—nó là số một vì một lý do. Cấu trúc cổ điển của thập niên 80 được kết hợp với logo Swoosh phồng và lớp phủ kim loại để tạo nên phong cách theo dõi dù bạn đang ở trên sân hay đang di chuyển.\r\n\r\nNhững lợi ích\r\nPhần trên bằng da tổng hợp với phần mũi giày đục lỗ thoáng khí và thoải mái.\r\nĐược thiết kế ban đầu cho mục đích chơi bóng rổ, đệm Nike Air mang lại sự thoải mái nhẹ nhàng suốt cả ngày.\r\nĐế ngoài bằng cao su với các vòng tròn xoay truyền thống mang lại lực kéo và độ bền.\r\nChi tiết sản phẩm\r\n2 bộ dây giày\r\nCổ áo có đệm\r\nĐế giữa bằng bọt\r\nMàu sắc hiển thị: Trắng/Đen/Vàng kim loại/Trắng\r\nKiểu dáng: HF2014-100\r\nQuốc gia/Khu vực xuất xứ: Việt Nam\r\nKhông lực 1\r\nRa mắt vào năm 1982 như một đôi giày bóng rổ phải có, Air Force 1 đã trở nên nổi tiếng vào những năm 90. Vẻ ngoài sạch sẽ của đôi AF-1 trắng-trên-trắng cổ điển đã được chứng thực từ sân bóng rổ đến đường phố và hơn thế nữa. Tìm thấy nhịp điệu của mình trong văn hóa hip-hop, phát hành các bản phối lại và phối màu giới hạn, Air Force 1 đã trở thành một đôi giày thể thao mang tính biểu tượng trên toàn cầu. Và với hơn 2.000 lần lặp lại của mẫu giày chủ lực này, tác động của nó đối với thời trang, âm nhạc và văn hóa giày thể thao là không thể phủ nhận.', 2815000.00, 'nike4.png', 'Sneaker', 'Nike', '2025-06-26 05:49:11', '2025-07-06 08:04:50', '', 13, '', 'Unisex', '', '', 1),
(5, 'Nike Interact Run', 'Bạn có thể thấy tương lai không? Hãy nhanh chóng tiến về phía trước trong đôi Nike Interact Run tiên tiến. Nó được thiết lập với tất cả những điều tuyệt vời khi chạy mà bạn cần: phần trên Flyknit nhẹ, đế giữa bằng bọt mềm và sự thoải mái khi cần thiết. Quét mã QR trên lưỡi giày bằng điện thoại của bạn và xem phần giới thiệu trực tuyến của chúng tôi về những điều cần biết về Nike Interact Run. Thêm vào đó, phần EasyOn không dây có gót uốn cong để vừa vặn rảnh tay và Flyknit co giãn để phù hợp với mọi loại bàn chân.\r\n\r\n\r\nMàu sắc hiển thị: Đen/Đen/Trắng\r\nKiểu dáng: FV5590-001\r\nQuốc gia/Khu vực xuất xứ: Việt Nam', 1879000.00, 'nike5.png', 'Thể thao', 'Nike', '2025-06-26 05:49:11', '2025-07-06 08:13:56', 'Đen/Trắng', 19, '', 'Unisex', '', '', 1),
(6, 'Nike Air Force 1 Mid', 'Những đôi AF-1 này đều là về các chi tiết. Tùy chỉnh nhãn, dây giày và dubrae của bạn, và đừng quên để lại dấu ấn của bạn bằng văn bản cá nhân trên tab sau. Với 8 lựa chọn màu sắc và các tùy chọn cao su trong suốt và cao su tổng hợp bổ sung cho đế, thiết kế này chắc chắn sẽ trở thành duy nhất—giống như bạn vậy.\r\n\r\n\r\nKiểu dáng: HF0660-900', 4109000.00, 'nike6.png', 'Sneaker', 'Nike', '2025-06-26 05:49:11', '2025-07-06 08:15:08', '', 5, '', 'Unisex', '', '', 1),
(7, 'Nike Air Max INT', 'Bạn phải cảm nhận được cảm giác để trở thành cảm giác. Hãy đến với Air Max INTRLK. Đệm được thiết kế lại mang đến sự thoải mái với khả năng phản hồi tăng lên và độ nảy hoàn hảo. Vật liệu nhẹ, dễ tạo kiểu có thể chịu được sự hao mòn. Hoàn thiện hơn nữa, đế ngoài lấy cảm hứng từ Waffle mang đến sự hấp dẫn thực sự của Nike.\r\n\r\n\r\nMàu sắc hiển thị: Đen/Trắng\r\nKiểu dáng: DX3705-001\r\nQuốc gia/Khu vực xuất xứ: Indonesia', 2349000.00, 'nike7.png', 'Chạy bộ', 'Nike', '2025-06-26 05:49:11', '2025-07-06 08:18:50', '', 10, '', 'Unisex', '', '', 1),
(8, 'Nike Court Vision', 'Giày Thể Thao Nữ NIKE Nike Court Vision Low Next Nature DH3158-101\r\n\r\n100% chính hãng NIKE Việt Nam\r\n\r\nBao gồm: Sản phẩm mới nguyên TAG, hóa đơn bán hàng từ Shoestore', 1982000.00, 'nike8.png', 'Sneaker', 'Nike', '2025-06-26 05:49:11', '2025-07-06 08:21:34', '', 8, '', 'Unisex', '', '', 1),
(9, 'Nike Air Max Plus', 'Mua Giày Nike Air Max Plus ‘Black University Gold’ DM0032-013 chính hãng 100%. Giao hàng miễn phí trong 1 ngày khi thanh toán đầy đủ tổng giá trị đơn hàng. Cam kết đền tiền X5 nếu phát hiện Fake. Đổi trả miễn phí size. FREE vệ sinh giày trọn đời.', 5900000.00, 'nike9.png', 'Chạy bộ', 'Nike', '2025-06-26 05:49:11', '2025-07-06 08:25:50', '', 15, '', 'Unisex', '', '', 1),
(10, 'Nike Air Max 97', 'AM97 là đôi giày có thể thay đổi hình dạng vào thời điểm đó, và giờ đến lượt bạn làm điều tương tự. Tùy chỉnh mọi bộ phận của giày từ vật liệu trên cùng đến màu sắc của đế giữa và bộ phận Nike Air, cùng với dây giày chống trượt để đảm bảo vừa vặn. Sau đó, quyết định xem bạn muốn đế ngoài của mình là đế đặc, có màu hay trong suốt. Thậm chí còn có một đế trong được nâng cấp để tăng thêm độ đệm dưới chân. Cuối cùng, một đôi giày đa năng như chính bạn.\r\n\r\nHiển thị: Nhiều màu/Nhiều màu/Nhiều màu\r\nKiểu dáng: FN6743-900\r\nQuốc gia/Khu vực xuất xứ: Việt Nam', 890000.00, 'nike10.png', 'Chạy bộ', 'Nike', '2025-06-26 05:49:11', '2025-07-06 08:28:00', '', 13, '', 'Unisex', '', '', 1),
(11, 'Adidas Superstar', 'Đôi giày adidas classic với các điểm nhấn đính hạt.\r\nDòng giày adidas Superstar luôn thể hiện tinh thần thể thao với cá tính nổi loạn. Mang đến nét mới mẻ cho biểu tượng ấy, đôi giày trainer này nổi bật với 3 Sọc đính hạt. Mũi giày vỏ sò bằng cao su độc đáo mang đến phong cách đặc trưng, như đã có hơn 50 năm. Bất kể bạn đang xuống phố hay thư giãn, đôi giày cổ thấp này sẽ giúp bạn luôn thoải mái suốt ngày dài.', 2600000.00, 'adidas1.png', 'Sneaker', 'Adidas', '2025-06-26 05:49:11', '2025-07-06 08:32:22', '', 13, '', 'Unisex', '', '', 1),
(12, 'Adidas Samba', 'Đôi giày Samba đích thực dành cho thế hệ mới.\r\nNếu quay ngược thời gian trở về thập niên 1960, bạn sẽ thấy các cầu thủ bóng đá chuyên nghiệp mang những đôi giày adidas Samba giống hệt như đôi giày trẻ em này. Hoặc gần giống. Với thiết kế cải biên dành cho thế hệ mới, phiên bản này có lót giày mềm mại cho cảm giác thoải mái suốt ngày dài vui chơi. Thân giày bằng da, đế gum và 3 Sọc đậm chất classic.', 1400000.00, 'adidas2.png', 'Sneaker', 'Adidas', '2025-06-26 05:49:11', '2025-07-06 08:35:40', '', 8, '', 'Unisex', '', '', 1),
(14, 'Adidas Forum Low', 'Giày cổ thấp kiểu dáng classic với các chi tiết táo bạo.\r\nSự kết hợp giữa phong cách classic và hiện đại, đôi giày adidas Forum Low mang đến sự hòa quyện giữa các yếu tố đơn giản và táo bạo. Một món đồ thiết yếu hằng ngày, đôi giày này dành cho tất cả mọi người. Thân giày bằng da được tô điểm bằng các chi tiết da lộn và lót bên trong tạo sự thoải mái. Đế ngoài bằng cao su tạo độ bám để bạn có thể di chuyển một cách tự tin. Với thiết kế cổ thấp, những đôi giày này là một biểu hiện chân thực nhưng vẫn mang hơi thở đương đại của di sản thể thao adidas.', 2600000.00, 'adidas3.png', 'Sneaker', 'Adidas', '2025-07-06 07:37:12', '2025-07-06 08:37:14', '', 10, '', 'Unisex', '', '', 1),
(15, 'Adidas Run 80S', 'Giày adidas cổ điển có đế ngoài bằng cao su có độ bám tốt.\r\nBuộc dây giày theo phong cách cổ điển với sự thoải mái theo phong cách mới. Đôi giày lấy cảm hứng từ chạy bộ này có đệm đế giữa Cloudfoam sang trọng để bạn không bị mỏi khi đi bộ cả ngày dài. Lớp phủ da lộn và 3 sọc dễ nhận biết tạo nên dấu hiệu phong cách.', 3400000.00, 'adidas8.png', 'Thể thao', 'Adidas', '2025-07-06 08:44:25', '2025-07-06 08:45:59', 'Nâu/Xám', 15, '', 'Unisex', '', '', 1),
(16, 'Adidas Tokyo', 'Đôi giày dáng thấp thanh thoát lấy cảm hứng từ phong cách giày chạy bộ thập niên 70.\r\nLên đồ theo phong cách retro với đôi giày adidas Tokyo. Thân giày bằng da lộn mềm mại với dáng thấp, ôm chân vừa vintage lại vừa hiện đại. Bên dưới là đế ngoài bằng cao su bám tốt để bạn tự tin sải bước. Hãy mang đôi giày trainer này và di chuyển từ chỗ làm đến cuối tuần và mọi dịp casual khác.', 2400000.00, 'adidas11.avif', 'Thể thao', 'Adidas', '2025-07-06 08:48:48', '2025-07-06 08:53:21', '', 10, '', 'Unisex', '', '', 1),
(17, 'Adidas Adistar CS', 'NGHỆ THUẬT CHẠY BỘ TỐC ĐỘ CHẬM VÀ CỰ LY DÀI.\r\nLấy cảm hứng từ khái niệm chuyển động vĩnh cửu, giày ADISTAR CS hỗ trợ các runner chinh phục kỷ lục cá nhân cự ly dài và hơn thế nữa. Nhằm mang lại cảm giác thoải mái và nâng đỡ, đôi giày này có đường cong dài ôm dọc mũi giày, tạo chuyển động nhịp nhàng, mượt mà và đều đặn giúp bạn sải bước tiếp theo. Đế giữa REPETITOR và REPETITOR+ mật độ kép kết hợp giữa mút foam siêu nhẹ tạo lớp đệm đàn hồi và hợp chất cứng cáp bao bọc gót giày.', 2520000.00, 'adidas10.png', 'Thể thao', 'Adidas', '2025-07-06 08:51:31', '2025-07-06 08:56:32', '', 16, '', 'Unisex', '', '', 1),
(18, 'Adidas Samba OG', 'SAMBA ORIGINALS\r\nRa đời trên sân bóng, giày Samba là biểu tượng kinh điển của phong cách đường phố. Phiên bản này trung thành với di sản, thể hiện qua thân giày bằng da mềm, dáng thấp, nhã nhặn, các chi tiết phủ ngoài bằng da lộn và đế gum, biến đôi giày trở thành item không thể thiếu trong tủ đồ của tất cả mọi người - cả trong và ngoài sân cỏ.', 2700000.00, 'adidas7.png', 'Chạy bộ', 'Adidas', '2025-07-06 08:55:41', '2025-07-06 08:58:06', '', 9, '', 'Unisex', '', '', 1),
(19, 'Biti\'s BVM', 'BVM002477NAD là lựa chọn lý tưởng dành cho quý ông yêu thích phong cách cổ điển pha chút hiện đại, với tông màu nâu socola trầm ấm, dễ phối và sang trọng. Mẫu giày tây buộc dây này giúp hoàn thiện vẻ ngoài lịch thiệp cho các dịp đi làm, hội họp hay sự kiện trang trọng.\r\n\r\nĐược làm từ chất liệu da tổng hợp cao cấp, bề mặt bóng mịn dễ lau chùi và có độ bền vượt trội. Mũi giày bo tròn nhẹ, form giày gọn gàng giúp tôn dáng bàn chân mà vẫn tạo cảm giác thoải mái trong từng bước đi. Các đường may tỉ mỉ chạy dọc thân giày không chỉ tăng độ bền mà còn mang lại nét tinh tế cho thiết kế tổng thể.', 845000.00, 'btis1.jpg', 'Công sở', 'Bitis', '2025-07-06 09:02:13', '2025-07-06 09:03:32', '', 5, '', 'Unisex', '', '', 1),
(20, 'Biti\'s Mocasin', 'Giày Mọi Nam BMM002077DEN đến từ thương hiệu Biti’s là lựa chọn hoàn hảo dành cho quý ông yêu thích phong cách tối giản, tinh tế và tiện dụng. Sản phẩm nổi bật với màu đen sang trọng, dễ dàng phối hợp cùng nhiều trang phục từ công sở đến thường ngày.\r\n\r\nĐược chế tác từ da tổng hợp cao cấp với vân giả da tự nhiên, đôi giày không chỉ mang đến vẻ ngoài lịch lãm mà còn dễ dàng vệ sinh và giữ form tốt trong thời gian dài. Thiết kế slip-on không dây hỗ trợ thao tác mang vào – tháo ra nhanh chóng, tiết kiệm thời gian mà vẫn đảm bảo vừa vặn nhờ vào phần thun ôm gót chân linh hoạt.', 820000.00, 'btis2.jpg', 'Công sở', 'Bitis', '2025-07-06 09:05:06', '2025-07-06 09:05:38', '', 16, '', 'Unisex', '', '', 1),
(21, 'Biti\'s Mocasin Nâu', 'Giày Mọi Nam BMM002077NAD đến từ thương hiệu Biti’s là sự kết hợp hoàn hảo giữa phong cách cổ điển và hiện đại, tạo nên vẻ ngoài lịch lãm, sang trọng nhưng vẫn cực kỳ thoải mái khi sử dụng. Với tông nâu trầm nam tính, sản phẩm là lựa chọn lý tưởng cho những buổi gặp mặt, đi làm hoặc dạo phố.\r\n\r\nThiết kế giày mọi không dây giúp người dùng dễ dàng xỏ chân và tháo ra nhanh chóng. Chất liệu da tổng hợp cao cấp có độ bóng nhẹ, vân giả da cá tính, không chỉ mang lại vẻ ngoài sang trọng mà còn dễ dàng vệ sinh. Phần lót giày êm ái cùng đế cao su đàn hồi giúp giảm áp lực khi di chuyển, mang lại sự thoải mái tối đa ngay cả khi sử dụng trong thời gian dài.\r\n\r\nDành cho những quý ông yêu thích sự chỉn chu, tinh tế nhưng vẫn mong muốn cảm giác nhẹ nhàng và linh hoạt trong từng bước chân, mẫu giày BMM002077NAD chính là người bạn đồng hành lý tưởng trong mọi hoàn cảnh.', 800000.00, 'btis3.jpg', 'Công sở', 'Bitis', '2025-07-06 12:43:36', '2025-07-06 12:46:39', '', 12, '', 'Unisex', '', '', 1),
(22, 'Biti\'s BVM002077', 'Điểm nổi bật của mẫu giày nằm ở chất liệu da tổng hợp bóng mịn, giúp tăng tính thẩm mỹ và dễ dàng vệ sinh. Form giày ôm gọn bàn chân, kết hợp với đường may thủ công sắc sảo tại mũi giày và thân trên, tạo nên tổng thể chỉn chu, mạnh mẽ. Phần mũi giày bo tròn nhẹ giúp tạo cảm giác cân đối khi di chuyển và phù hợp với nhiều dáng chân khác nhau.\r\n\r\nLót giày mềm mại, thoáng khí tốt, hạn chế hầm nóng trong thời gian dài sử dụng. Đặc biệt, đế cao su tổng hợp chống trơn trượt, độ ma sát cao và bám sàn tốt, nâng cao sự an toàn khi di chuyển. Mặt đế được thiết kế tỉ mỉ với các rãnh chống trượt giúp tạo sự chắc chắn từng bước chân.\r\n\r\nSản phẩm là lựa chọn không thể thiếu dành cho các quý ông yêu thích sự chỉn chu, tinh tế và tiện dụng trong cùng một đôi giày. BVM002477DEN mang lại trải nghiệm thoải mái, tôn dáng và khẳng định phong cách chuyên nghiệp.', 950000.00, 'btis5.jpg', 'Công sở', 'Bitis', '2025-07-06 12:45:56', '2025-07-06 12:47:10', '', 6, '', 'Unisex', '', '', 1),
(23, 'Nike Air Max TL 2.5', 'Làm sống lại một đôi giày được ưa chuộng vào cuối những năm 90, Air Max TL 2.5 mang lại vẻ đẹp Air Max gợn sóng mang tính biểu tượng. Vải dệt thoáng khí kết hợp với da tổng hợp mịn màng để tạo nên lớp hoàn thiện sạch sẽ, trong khi đệm Max Air toàn chiều dài mang lại cảm giác nảy cho từng bước chân.\r\n\r\n\r\nMàu sắc hiển thị: Trắng/Đen/Xám khói/Nho dại\r\nKiểu dáng: FZ4110-105\r\nQuốc gia/Khu vực xuất xứ: Việt Nam', 5279000.00, 'nikeair1.avif', 'Thể thao', 'Nike', '2025-07-06 12:51:21', '2025-07-06 12:52:13', '', 13, '', 'Unisex', '', '', 1),
(24, 'Nike Initiator', 'Hãy đi nhiều dặm với sự hỗ trợ thoải mái của Nike Initiator. Nó kết hợp phần trên thoáng khí với đệm mềm mại giúp bạn tự tin sải bước.\r\n\r\n\r\nMàu sắc hiển thị: Trắng/Trắng/Đen/Bạc kim loại\r\nKiểu dáng: FQ6873-101\r\nQuốc gia/Khu vực xuất xứ: Indonesia, Việt Nam', 2499000.00, 'nikea1.avif', 'Thể thao', 'Nike', '2025-07-06 12:53:41', '2025-07-06 12:55:00', '', 5, '', 'Unisex', '', '', 1),
(25, 'Nike Pegasus ', 'Pegasus Premium tăng cường đệm phản ứng với bộ ba công nghệ chạy mạnh mẽ nhất của chúng tôi: bọt ZoomX, bộ phận Air Zoom được điêu khắc và bọt ReactX. Đây là đôi Pegasus phản ứng nhanh nhất từ ​​trước đến nay, mang lại khả năng hoàn trả năng lượng cao không giống bất kỳ đôi nào khác. Với phần trên nhẹ hơn không khí, nó giúp giảm trọng lượng và tăng khả năng thoáng khí để bạn có thể bay nhanh hơn.\r\n\r\n\r\nMàu sắc hiển thị: Xanh lá cây nhạt/Xốp bạc hà/Bạc kim loại/Đỏ thẫm sáng\r\nKiểu dáng: HQ2592-301\r\nQuốc gia/Khu vực xuất xứ: Việt Nam', 6179000.00, 'mikea2.avif', 'Thể thao', 'Nike', '2025-07-06 12:56:29', '2025-07-06 12:58:58', '', 10, '', 'Unisex', '', '', 1),
(26, 'Pegasus Premium', 'Pegasus Premium tăng cường đệm phản ứng với bộ ba công nghệ chạy mạnh mẽ nhất của chúng tôi: bọt ZoomX, bộ phận Air Zoom được điêu khắc và bọt ReactX. Đây là đôi Pegasus phản ứng nhanh nhất từ ​​trước đến nay, mang lại khả năng hoàn trả năng lượng cao không giống bất kỳ đôi nào khác. Với phần trên nhẹ hơn không khí, nó giúp giảm trọng lượng và tăng khả năng thoáng khí để bạn có thể bay nhanh hơn.\r\n\r\n\r\nMàu sắc hiển thị: Vàng đại học/Cam laser/Đen\r\nKiểu dáng: IH3256-700\r\nQuốc gia/Khu vực xuất xứ: Việt Nam', 6179000.00, 'nikea3.avif', 'Thể thao', 'Nike', '2025-07-06 12:57:59', '2025-07-06 12:59:29', '', 9, '', 'Unisex', '', '', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_variants`
--

CREATE TABLE `product_variants` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  `size` varchar(10) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `color`, `size`, `stock_quantity`, `created_at`, `updated_at`) VALUES
(14, 1, 'Xanh Dương', '39', 5, '2025-07-06 07:43:36', '2025-07-06 07:43:36'),
(15, 1, 'Đen', '39', 6, '2025-07-06 07:44:27', '2025-07-06 07:44:27'),
(16, 1, 'Trắng', '39', 4, '2025-07-06 07:44:53', '2025-07-06 07:44:53'),
(17, 1, 'Xanh Dương', '40', 4, '2025-07-06 07:45:07', '2025-07-06 07:45:07'),
(20, 1, 'Xanh Dương', '41', 5, '2025-07-06 07:46:27', '2025-07-06 07:46:27'),
(21, 1, 'Đen', '40', 5, '2025-07-06 07:47:27', '2025-07-06 07:47:27'),
(22, 1, 'Đen', '41', 6, '2025-07-06 07:47:49', '2025-07-06 07:47:49'),
(23, 1, 'Trắng', '40', 7, '2025-07-06 07:48:20', '2025-07-06 07:48:20'),
(24, 1, 'Trắng', '41', 4, '2025-07-06 07:48:52', '2025-07-06 07:48:52'),
(25, 2, 'Đen Logo Trắng', '40', 10, '2025-07-06 07:52:42', '2025-07-06 07:52:42'),
(26, 2, 'Trắng Logo Đen', '40', 11, '2025-07-06 07:53:19', '2025-07-06 07:53:19'),
(27, 2, 'Đen Logo Trắng', '41', 5, '2025-07-06 07:53:49', '2025-07-06 07:53:49'),
(28, 2, 'Trắng Logo Đen', '41', 7, '2025-07-06 07:54:06', '2025-07-06 07:54:06'),
(29, 3, 'Trắng Logo Đen', '37', 7, '2025-07-06 08:01:22', '2025-07-06 08:01:22'),
(30, 3, 'Trắng Logo Đen', '38', 9, '2025-07-06 08:01:41', '2025-07-06 08:01:41'),
(31, 4, 'Trắng', '35', 6, '2025-07-06 08:04:29', '2025-07-06 08:04:29'),
(32, 4, 'Trắng', '36', 7, '2025-07-06 08:04:41', '2025-07-06 08:04:41'),
(33, 5, 'Đen Logo Trắng', '43', 10, '2025-07-06 08:09:51', '2025-07-06 08:09:51'),
(34, 5, 'Đen Logo Trắng', '43', 9, '2025-07-06 08:10:05', '2025-07-06 08:10:05'),
(35, 6, 'Trắng', '40', 3, '2025-07-06 08:14:39', '2025-07-06 08:14:39'),
(36, 6, 'Trắng', '37', 2, '2025-07-06 08:14:59', '2025-07-06 08:14:59'),
(37, 7, 'Đen Logo Trắng', '39', 5, '2025-07-06 08:17:21', '2025-07-06 08:17:21'),
(38, 7, 'Đen Logo Trắng', '38', 5, '2025-07-06 08:18:40', '2025-07-06 08:18:40'),
(39, 8, 'Trắng Logo Đen', '41', 6, '2025-07-06 08:21:10', '2025-07-06 08:21:10'),
(40, 8, 'Trắng Logo Đen', '39', 2, '2025-07-06 08:21:26', '2025-07-06 08:21:26'),
(41, 9, 'Đen Nâu', '40', 12, '2025-07-06 08:25:20', '2025-07-06 08:25:20'),
(42, 9, 'Đen Xám', '40', 3, '2025-07-06 08:25:40', '2025-07-06 08:25:40'),
(43, 11, 'Trắng', '40', 10, '2025-07-06 08:28:12', '2025-07-06 08:28:12'),
(44, 10, 'Trắng', '40', 10, '2025-07-06 08:28:30', '2025-07-06 08:28:30'),
(45, 10, 'Trắng', '41', 3, '2025-07-06 08:28:40', '2025-07-06 08:28:40'),
(46, 11, 'Trắng Logo Đen', '40', 3, '2025-07-06 08:32:17', '2025-07-06 08:32:17'),
(47, 12, 'Trắng Logo Đen', '41', 4, '2025-07-06 08:35:12', '2025-07-06 08:35:12'),
(48, 12, 'Trắng Logo Đen', '40', 4, '2025-07-06 08:35:33', '2025-07-06 08:35:33'),
(49, 14, 'Trắng/Xanh Dương', '40', 5, '2025-07-06 08:37:47', '2025-07-06 08:37:47'),
(50, 14, 'Trắng/Hồng', '40', 5, '2025-07-06 08:38:09', '2025-07-06 08:38:09'),
(51, 15, 'Nâu/Xám', '40', 10, '2025-07-06 08:45:29', '2025-07-06 08:45:29'),
(52, 15, 'Nâu/Đen', '40', 5, '2025-07-06 08:45:50', '2025-07-06 08:45:50'),
(53, 16, 'Xám Logo Đen', '40', 5, '2025-07-06 08:49:14', '2025-07-06 08:49:14'),
(54, 16, 'Xám Logo Đen', '41', 5, '2025-07-06 08:49:31', '2025-07-06 08:49:31'),
(55, 17, 'Đen/Xám', '40', 6, '2025-07-06 08:56:10', '2025-07-06 08:56:10'),
(56, 17, 'Đen/Xám', '41', 10, '2025-07-06 08:56:25', '2025-07-06 08:56:25'),
(57, 18, 'Đen', '37', 4, '2025-07-06 08:57:48', '2025-07-06 08:57:48'),
(58, 18, 'Đen', '40', 5, '2025-07-06 08:58:01', '2025-07-06 08:58:01'),
(59, 19, 'Nâu Đậm', '41', 5, '2025-07-06 09:03:27', '2025-07-06 09:03:27'),
(60, 20, 'Đen', '43', 16, '2025-07-06 09:05:33', '2025-07-06 09:05:33'),
(61, 21, 'Nâu', '41', 6, '2025-07-06 12:46:21', '2025-07-06 12:46:21'),
(62, 21, 'Nâu', '42', 6, '2025-07-06 12:46:36', '2025-07-06 12:46:36'),
(63, 22, 'Đen', '40', 3, '2025-07-06 12:46:55', '2025-07-06 12:46:55'),
(64, 22, 'Đen', '42', 3, '2025-07-06 12:47:05', '2025-07-06 12:47:05'),
(65, 23, 'Trắng/Đen/Xám khói/Nho dại', '41', 13, '2025-07-06 12:52:08', '2025-07-06 12:52:08'),
(67, 24, 'Trắng/Đen/Bạc kim loại', '41', 5, '2025-07-06 12:54:54', '2025-07-06 12:54:54'),
(68, 25, 'Xanh lá cây nhạt/Xốp bạc hà/Bạc kim loại/Đỏ thẫm s', '42', 10, '2025-07-06 12:56:42', '2025-07-06 12:56:42'),
(69, 26, 'Vàng đại học/Cam laser/Đen', '40', 9, '2025-07-06 12:58:16', '2025-07-06 12:58:16');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `full_name`, `address`, `phone`, `created_at`, `updated_at`, `is_active`) VALUES
(1, 'Công Hoàng', '$2y$10$8dGzQhkoVbW6N4lJsAPUHuzQnaJ/LGJfCeEucQmWUKoktxJbFyYwC', 'conghoang@gmail.com', 'Công Hoàng', 'Quận 12', '0337116123', '2025-06-29 11:01:58', '2025-07-06 16:13:41', 1),
(2, 'hoang', '$2y$10$2.4Jyt5wHY0SeUFjJyZeROxzHP2mIJul45vZ1XSNTR6uUO.QmhZW6', 'hoang@gmail.com', 'Công Hoàng', '', '', '2025-07-04 09:06:46', '2025-07-04 09:06:46', 1),
(5, 'hoang2', '$2y$10$TYpJBHX86WgC9imKXZSLO.FVltHMO5ZejS5BwC1topo5qUHPTkSk2', 'hoang2@gmail.com', 'hoang2', '', '', '2025-07-05 15:23:54', '2025-07-05 15:23:54', 1),
(6, 'khai', '$2y$10$.YsCsXvDo2t2gurugnUT4eqANX2te0JezrOHtarz6XwlpSqKCgVy6', 'khai@gmail.com', 'Minh Khai', 'Phường 13, Tân Bình, Hồ Chí Minh', '0337123456', '2025-07-06 13:02:48', '2025-07-06 13:19:42', 1),
(7, 'thanhtam', '$2y$10$spDMRCZHy1/KPBbVMAU3YO3aoWuTtWW1TcnO/..0aGK6Is3hBhJ7y', 'thanhtam@gmail.com', 'Hoàng Thanh Tâm', 'Hà Nội', '0444123456', '2025-07-06 16:10:54', '2025-07-06 16:10:54', 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`),
  ADD KEY `brand` (`brand`);

--
-- Chỉ mục cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
