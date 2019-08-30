-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 30, 2019 at 03:15 AM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `billing`
--

-- --------------------------------------------------------

--
-- Table structure for table `ac_balance`
--

CREATE TABLE `ac_balance` (
  `id` int(11) NOT NULL,
  `bdate` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `stat` int(1) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `uid` int(10) DEFAULT NULL,
  `category` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ac_balance`
--

INSERT INTO `ac_balance` (`id`, `bdate`, `amount`, `stat`, `note`, `uid`, `category`) VALUES
(5, '2019-08-28', '126.00', 0, 'rent', 2, NULL),
(8, '2019-08-29', '250.00', 0, 'Rent', 0, NULL),
(9, '2019-08-29', '250.00', 1, '', 0, NULL),
(10, '2019-08-29', '500.00', 1, 'Note', 0, 'Other'),
(11, '2019-08-29', '250.00', 1, 'Note', 1, 'Rent'),
(12, '2019-08-29', '600.00', 1, 'Some Notes', 0, 'Petrol'),
(13, '2019-08-29', '210.00', 1, 'Some Notes here', 2, 'Petrol'),
(14, '2019-08-29', '210.00', 0, 'Some Notes here', 1, 'Petrol'),
(15, '2019-08-29', '5000.00', 0, 'fghff', 2, 'Salary');

-- --------------------------------------------------------

--
-- Table structure for table `ac_transactions`
--

CREATE TABLE `ac_transactions` (
  `id` int(11) NOT NULL,
  `tno` varchar(20) NOT NULL,
  `acid` int(5) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `stat` enum('Dr','Cr') NOT NULL,
  `tdate` datetime NOT NULL,
  `note` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ac_transactions`
--

INSERT INTO `ac_transactions` (`id`, `tno`, `acid`, `amount`, `stat`, `tdate`, `note`) VALUES
(1, '521', 1, '1000.00', 'Cr', '2018-05-21 04:52:20', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `bank_ac`
--

CREATE TABLE `bank_ac` (
  `id` int(5) NOT NULL,
  `acn` varchar(35) NOT NULL,
  `holder` varchar(100) NOT NULL,
  `bank` varchar(100) NOT NULL,
  `adate` datetime NOT NULL,
  `lastbal` decimal(10,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bank_ac`
--

INSERT INTO `bank_ac` (`id`, `acn`, `holder`, `bank`, `adate`, `lastbal`) VALUES
(1, '1234567890', 'John Doe', 'My Bank', '2016-04-01 00:00:00', '1209.00');

-- --------------------------------------------------------

--
-- Table structure for table `billing_terms`
--

CREATE TABLE `billing_terms` (
  `id` int(1) NOT NULL,
  `terms` text NOT NULL,
  `qterms` text NOT NULL,
  `rterms` text NOT NULL,
  `footer` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `billing_terms`
--

INSERT INTO `billing_terms` (`id`, `terms`, `qterms`, `rterms`, `footer`) VALUES
(1, 'Billing errors are subject of correction. Goods once sold will not be taken back.', 'Billing errors are subject of correction. Goods once sold will not be taken back.', 'Billing errors are subject of correction. Goods once sold will not be taken back.', 'Computer generated document and it does not require a signature.');

-- --------------------------------------------------------

--
-- Table structure for table `conf`
--

CREATE TABLE `conf` (
  `id` int(1) NOT NULL DEFAULT '1',
  `bank` int(1) NOT NULL,
  `acid` int(11) NOT NULL,
  `ext1` varchar(255) NOT NULL,
  `ext2` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `conf`
--

INSERT INTO `conf` (`id`, `bank`, `acid`, `ext1`, `ext2`) VALUES
(1, 1, 1, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `tid` int(8) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `taxid` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `damage`
--

CREATE TABLE `damage` (
  `id` int(11) NOT NULL,
  `tid` int(8) NOT NULL,
  `tsn_date` date NOT NULL,
  `tsn_due` date NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `shipping` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `discountr` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `notes` varchar(255) NOT NULL,
  `status` varchar(25) NOT NULL,
  `csd` int(5) NOT NULL DEFAULT '0',
  `eid` int(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `damage_customers`
--

CREATE TABLE `damage_customers` (
  `id` int(11) NOT NULL,
  `tid` int(8) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `taxid` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `damage_items`
--

CREATE TABLE `damage_items` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `product` text NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `trate` decimal(10,2) NOT NULL,
  `pid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(7) UNSIGNED NOT NULL,
  `username` varchar(15) NOT NULL,
  `first_name` varchar(15) NOT NULL,
  `last_name` varchar(15) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `password` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  `activated` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `confirmation` char(40) NOT NULL DEFAULT '',
  `reg_date` int(11) UNSIGNED NOT NULL,
  `last_login` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `group_id` int(2) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `username`, `first_name`, `last_name`, `phone`, `password`, `email`, `activated`, `confirmation`, `reg_date`, `last_login`, `group_id`) VALUES
(1, 'admin', 'John', 'Doe', '1234123458', 'b9a8b0080819164713c79456e0c74df3782eaa8d', 'owner@example.com', 1, '', 1457269114, 1567044160, 1),
(2, 'salesteam', 'Sales', 'Team', '12345678', '1e07e711384670efc9b34095a2b99243e3fbfcda', 'sales@example.com', 1, '', 1472325576, 1475690601, 3),
(4, 'manager', 'Mr.', 'Manager', '12345678', '553b6c4efd92122d9ece9c0a5ae90a451f114788', 'manager@example.com', 1, '', 1484584005, 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

CREATE TABLE `goals` (
  `id` int(1) NOT NULL,
  `income` int(11) NOT NULL,
  `expense` int(11) NOT NULL,
  `sales` int(11) NOT NULL,
  `invoices` int(11) NOT NULL,
  `rsales` int(11) NOT NULL,
  `rinvoices` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `goals`
--

INSERT INTO `goals` (`id`, `income`, `expense`, `sales`, `invoices`, `rsales`, `rinvoices`) VALUES
(1, 1, 1, 1, 8, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `tid` int(8) NOT NULL,
  `tsn_date` date NOT NULL,
  `tsn_due` date NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `shipping` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `discountr` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `tax2` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `status` enum('paid','due','canceled','partial','') NOT NULL,
  `csd` int(5) NOT NULL DEFAULT '0',
  `eid` int(4) NOT NULL,
  `pmethod` int(1) NOT NULL,
  `ramm` decimal(10,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `tid`, `tsn_date`, `tsn_due`, `subtotal`, `shipping`, `discount`, `discountr`, `tax`, `tax2`, `total`, `notes`, `status`, `csd`, `eid`, `pmethod`, `ramm`) VALUES
(12, 3, '2018-06-03', '2018-06-03', '2000.00', '0.00', '150.00', '150.00', '80.00', '70.00', '2000.00', '', 'due', 1, 1, 0, '0.00'),
(13, 4, '2018-06-03', '2018-06-03', '10000.00', '0.00', '500.00', '5.00', '400.00', '350.00', '10212.50', '', 'due', 1, 1, 0, '0.00'),
(14, 5, '2018-05-22', '2018-05-22', '40500.00', '1.00', '0.00', '0.00', '5220.00', '0.00', '45721.00', '', 'partial', 1, 1, 2, '0.00'),
(15, 10, '2018-06-07', '2018-06-07', '80000.00', '10.00', '0.00', '0.00', '10400.00', '0.00', '90410.00', '', 'partial', 1, 1, 2, '0.00'),
(16, 11, '2018-06-07', '2018-06-07', '40500.00', '1.00', '0.00', '0.00', '5220.00', '0.00', '45721.00', '', 'due', 1, 1, 2, '0.00'),
(17, 12, '2018-09-20', '2018-09-20', '2297124.91', '0.00', '0.00', '0.00', '413482.48', '0.00', '2710607.39', '', 'due', 1, 1, 0, '0.00'),
(11, 2, '2018-06-02', '2018-06-02', '40000.00', '0.00', '0.00', '0.00', '5200.00', '0.00', '45200.00', '', 'due', 1, 1, 2, '0.00'),
(9, 1, '2018-05-22', '2018-05-22', '40500.00', '1.00', '0.00', '0.00', '5220.00', '0.00', '45721.00', '', 'partial', 1, 1, 2, '32000.00'),
(18, 13, '2019-08-29', '2019-08-29', '21000.00', '0.00', '0.00', '0.00', '2780.00', '0.00', '23780.00', '', 'due', 1, 1, 0, '0.00'),
(19, 14, '2019-08-29', '2019-08-29', '40000.00', '0.00', '0.00', '0.00', '5200.00', '0.00', '45200.00', 'olkjkldf\r\nsdfkjldsfj\r\nlkjsdklfjds\r\n', 'due', 1, 1, 0, '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `product` text NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `trate` decimal(10,2) NOT NULL,
  `trate2` decimal(10,2) NOT NULL,
  `pid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `tid`, `product`, `qty`, `price`, `discount`, `subtotal`, `trate`, `trate2`, `pid`) VALUES
(86, 1, 'product 2-xx4', 5, '100.00', '4.00', '520.00', '4.00', '0.00', 3),
(69, 2, 'Onida 1t-44', 2, '20000.00', '13.00', '45200.00', '13.00', '0.00', 2),
(71, 3, 'ACC-ACC1', 2, '1000.00', '4.00', '2150.00', '4.00', '3.50', 0),
(84, 1, 'Onida 1t-44', 2, '20000.00', '13.00', '45200.00', '13.00', '0.00', 2),
(83, 1, 'product 2-xx3', 5, '100.00', '4.00', '520.00', '4.00', '0.00', 3),
(72, 4, 'ACC-ACC1', 1, '10000.00', '4.00', '10750.00', '4.00', '3.50', 0),
(85, 1, 'Onida 1t-44', 2, '20000.00', '13.00', '45200.00', '13.00', '0.00', 2),
(87, 5, 'product 2-xx4', 5, '100.00', '4.00', '520.00', '4.00', '0.00', 3),
(88, 5, 'Onida 1t-44', 2, '20000.00', '13.00', '45200.00', '13.00', '0.00', 2),
(89, 5, 'product 2-xx3', 5, '100.00', '4.00', '520.00', '4.00', '0.00', 3),
(90, 5, 'Onida 1t-44', 2, '20000.00', '13.00', '45200.00', '13.00', '0.00', 2),
(91, 10, 'Onida 1t-44', 2, '20000.00', '13.00', '45200.00', '13.00', '0.00', 2),
(92, 10, 'Onida 1t-44', 2, '20000.00', '13.00', '45200.00', '13.00', '0.00', 2),
(93, 11, 'product 2-xx4', 5, '100.00', '4.00', '520.00', '4.00', '0.00', 3),
(94, 11, 'Onida 1t-44', 2, '20000.00', '13.00', '45200.00', '13.00', '0.00', 2),
(95, 11, 'product 2-xx3', 5, '100.00', '4.00', '520.00', '4.00', '0.00', 3),
(96, 11, 'Onida 1t-44', 2, '20000.00', '13.00', '45200.00', '13.00', '0.00', 2),
(97, 12, '17C01E03-Q05V02A 290', 1, '106331.40', '18.00', '125471.05', '18.00', '0.00', 0),
(98, 12, '17C03E06-Q16V01A  440', 1, '161330.40', '18.00', '190369.87', '18.00', '0.00', 0),
(99, 12, '17C01E04-Q05V01A 394 ', 1, '144464.04', '18.00', '170467.57', '18.00', '0.00', 0),
(100, 12, '17C01M07-Q02V03A 204', 1, '74798.64', '18.00', '88262.40', '18.00', '0.00', 0),
(101, 12, '17C01M15-Q07V02A  343 ', 1, '125764.38', '18.00', '148401.97', '18.00', '0.00', 0),
(102, 12, '17C01M05-Q03V02A 285', 1, '104498.10', '18.00', '123307.76', '18.00', '0.00', 0),
(103, 12, '17C01M06-Q01V02A 319', 1, '116964.54', '18.00', '138018.16', '18.00', '0.00', 0),
(104, 12, '17C01E02-Q12V01A 359', 1, '131630.94', '18.00', '155324.51', '18.00', '0.00', 0),
(105, 12, '17C01E03-Q02V02A  490', 1, '179663.40', '18.00', '212002.81', '18.00', '0.00', 0),
(106, 12, '17C01E06-Q02V01A   330', 1, '120997.80', '18.00', '142777.40', '18.00', '0.00', 0),
(107, 12, '17C01E07-Q02V03A 442', 1, '162063.72', '18.00', '191235.19', '18.00', '0.00', 0),
(108, 12, '17C01E08-Q07V03A   483 ', 1, '177096.78', '18.00', '208974.20', '18.00', '0.00', 0),
(109, 12, '17C02E04-Q10V03A  272', 1, '99731.52', '18.00', '117683.19', '18.00', '0.00', 0),
(110, 12, '17C02E06-Q06V01A  310 ', 1, '113664.60', '18.00', '134124.23', '18.00', '0.00', 0),
(111, 12, '17C02E06-Q13V02A 351', 1, '128697.66', '18.00', '151863.24', '18.00', '0.00', 0),
(112, 12, '17C03E01-Q15V01A 437', 1, '160230.42', '18.00', '189071.90', '18.00', '0.00', 0),
(113, 12, '17C03E03-Q04V02A  516', 1, '189196.56', '18.00', '223251.94', '18.00', '0.00', 0),
(114, 13, 'Onida 1t-44', 1, '20000.00', '13.00', '22600.00', '13.00', '0.00', 2),
(115, 13, 'kjlj', 2, '500.00', '18.00', '1180.00', '18.00', '0.00', 0),
(116, 14, 'Onida 1t-44', 2, '20000.00', '13.00', '45200.00', '13.00', '0.00', 2);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `note` text NOT NULL,
  `date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `paic`
--

CREATE TABLE `paic` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `pdate` date NOT NULL,
  `poutcum` decimal(10,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `paic`
--

INSERT INTO `paic` (`id`, `tid`, `pdate`, `poutcum`) VALUES
(48, 2, '2018-06-02', '9200.00'),
(50, 3, '2018-06-03', '0.00'),
(51, 4, '2018-06-03', '0.00'),
(58, 1, '2018-05-22', '4720.00'),
(59, 5, '2018-05-22', '9440.00'),
(60, 10, '2018-06-07', '18400.00'),
(61, 11, '2018-06-07', '9440.00'),
(57, 1, '2018-05-22', '4720.00'),
(62, 12, '2018-09-20', '0.00'),
(63, 13, '2019-08-29', '4600.00'),
(64, 14, '2019-08-29', '9200.00');

-- --------------------------------------------------------

--
-- Table structure for table `panel`
--

CREATE TABLE `panel` (
  `id` int(1) NOT NULL,
  `cname` char(25) NOT NULL,
  `address` varchar(255) NOT NULL,
  `address2` varchar(200) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `cvatno` varchar(20) NOT NULL,
  `vatr` decimal(10,2) NOT NULL,
  `vatr2` decimal(10,2) NOT NULL,
  `vatst` int(1) NOT NULL,
  `vinc` int(1) NOT NULL,
  `crncy` varchar(4) NOT NULL,
  `fcrncy` int(1) NOT NULL,
  `pref` varchar(5) NOT NULL,
  `dfomat` int(1) NOT NULL,
  `zone` varchar(25) NOT NULL,
  `autosend` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `panel`
--

INSERT INTO `panel` (`id`, `cname`, `address`, `address2`, `phone`, `email`, `cvatno`, `vatr`, `vatr2`, `vatst`, `vinc`, `crncy`, `fcrncy`, `pref`, `dfomat`, `zone`, `autosend`) VALUES
(0, 'CoolLand', 'Omassery', 'California, Post Box 90017,US', '410-987-89-60', 'support@coolland.in', '123456789123546', '18.00', '0.00', 1, 0, 'INR', 0, 'SRN', 1, 'UTC', 0);

-- --------------------------------------------------------

--
-- Table structure for table `part_trans`
--

CREATE TABLE `part_trans` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `note` varchar(100) NOT NULL,
  `tdate` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `part_trans`
--

INSERT INTO `part_trans` (`id`, `tid`, `amount`, `note`, `tdate`) VALUES
(63, 1, '5000.00', '123456', '2018-06-03'),
(64, 1, '25000.00', 'party', '2018-06-04'),
(65, 1, '2000.00', 'hkjhk', '2019-08-29');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(2) NOT NULL,
  `popt` varchar(30) NOT NULL,
  `optvalue` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `popt`, `optvalue`) VALUES
(1, 'stripe_secret', 'sk_test_M0xtbrKBfwBW0AGDGF3EycG4l'),
(2, 'stripe_publishable', 'pk_test_SFDF8y6ikcKESgrFDGryt'),
(3, 'email', 'abc@gmail.com'),
(4, 'currency', 'usd'),
(5, 'enable_paypal', 'false'),
(6, 'paypal_email', 'yourpaypal@email.com'),
(7, 'paycurrency', 'USD');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `pid` int(11) NOT NULL,
  `pcat` int(3) NOT NULL DEFAULT '1',
  `product_name` varchar(50) NOT NULL,
  `product_code` varchar(255) NOT NULL,
  `product_price` decimal(10,2) DEFAULT NULL,
  `fproduct_price` decimal(10,2) NOT NULL,
  `qty` int(11) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `tax2` decimal(10,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`pid`, `pcat`, `product_name`, `product_code`, `product_price`, `fproduct_price`, `qty`, `tax`, `tax2`) VALUES
(3, 1, 'product 2', '442', '100.00', '1000.00', -10, '4.00', '0.00'),
(2, 2, 'Onida 1t', '44', '20000.00', '18000.00', 202, '13.00', '0.00'),
(4, 1, 'product 2', '442', '100.00', '1000.00', 10, '4.00', '3.00'),
(5, 1, 'sdf', 'erw', '1.00', '1.00', 0, '0.00', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `product_cat`
--

CREATE TABLE `product_cat` (
  `id` int(3) NOT NULL,
  `title` varchar(100) NOT NULL,
  `extra` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_cat`
--

INSERT INTO `product_cat` (`id`, `title`, `extra`) VALUES
(1, 'General', 'General'),
(2, 'A/C', 'Air.. ');

-- --------------------------------------------------------

--
-- Table structure for table `quote`
--

CREATE TABLE `quote` (
  `id` int(11) NOT NULL,
  `tid` int(8) NOT NULL,
  `tsn_date` date NOT NULL,
  `tsn_due` date NOT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `shipping` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `discountr` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `tax2` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `status` varchar(25) NOT NULL,
  `csd` int(5) NOT NULL DEFAULT '0',
  `eid` int(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quote`
--

INSERT INTO `quote` (`id`, `tid`, `tsn_date`, `tsn_due`, `subtotal`, `shipping`, `discount`, `discountr`, `tax`, `tax2`, `total`, `notes`, `status`, `csd`, `eid`) VALUES
(1, 1, '2018-03-20', '2018-03-20', '100.00', '0.00', '0.00', '0.00', '4.00', '3.50', '107.50', '', 'paid', 1, 1),
(2, 2, '2018-03-20', '2018-03-20', '100.00', '0.00', '0.00', '0.00', '4.00', '3.50', '107.50', '', 'paid', 1, 1),
(3, 3, '2018-03-20', '2018-03-20', '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '', 'paid', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `quote_customers`
--

CREATE TABLE `quote_customers` (
  `id` int(11) NOT NULL,
  `tid` int(8) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `taxid` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quote_items`
--

CREATE TABLE `quote_items` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `product` text NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `trate` decimal(10,2) NOT NULL,
  `trate2` decimal(10,2) NOT NULL,
  `pid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quote_items`
--

INSERT INTO `quote_items` (`id`, `tid`, `product`, `qty`, `price`, `discount`, `subtotal`, `trate`, `trate2`, `pid`) VALUES
(6, 2, 'Onida 1t-44', 1, '100.00', '4.00', '107.50', '4.00', '0.00', 0),
(11, 3, 'ssss', 2, '50.00', '0.00', '100.00', '0.00', '0.00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `id` int(11) NOT NULL,
  `tid` int(8) NOT NULL,
  `tsn_date` date NOT NULL,
  `tsn_due` date NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `shipping` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `discountr` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `tax2` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `status` varchar(25) NOT NULL,
  `csd` int(5) NOT NULL DEFAULT '0',
  `eid` int(4) NOT NULL,
  `ramm` decimal(10,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `receipts`
--

INSERT INTO `receipts` (`id`, `tid`, `tsn_date`, `tsn_due`, `subtotal`, `shipping`, `discount`, `discountr`, `tax`, `tax2`, `total`, `notes`, `status`, `csd`, `eid`, `ramm`) VALUES
(8, 3, '2018-08-23', '2018-08-23', '100.00', '0.00', '0.00', '0.00', '4.00', '3.50', '107.50', '', 'due', 0, 1, '0.00'),
(7, 2, '2018-06-02', '2018-06-02', '18000.00', '0.00', '0.00', '0.00', '2340.00', '720.00', '21060.00', '', 'partial', 1, 1, '20200.00'),
(6, 1, '2018-05-23', '2018-05-23', '18000.00', '0.00', '0.00', '0.00', '2340.00', '1800.00', '22140.00', '', 'partial', 1, 1, '5000.00');

-- --------------------------------------------------------

--
-- Table structure for table `receipts_items`
--

CREATE TABLE `receipts_items` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `product` text NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `subtotal` varchar(255) NOT NULL,
  `trate` decimal(10,2) NOT NULL,
  `trate2` decimal(10,2) NOT NULL,
  `pid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `receipts_items`
--

INSERT INTO `receipts_items` (`id`, `tid`, `product`, `qty`, `price`, `discount`, `subtotal`, `trate`, `trate2`, `pid`) VALUES
(23, 3, 'ACC-ACC1', 1, '100.00', '4.00', '107.50', '4.00', '3.50', 0),
(22, 2, 'Onida 1t-44', 1, '18000.00', '13.00', '21060.00', '13.00', '4.00', 2),
(21, 1, 'Onida 1t-44', 1, '18000.00', '13.00', '22140.00', '13.00', '10.00', 2);

-- --------------------------------------------------------

--
-- Table structure for table `receipt_trans`
--

CREATE TABLE `receipt_trans` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `note` varchar(100) NOT NULL,
  `tdate` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `receipt_trans`
--

INSERT INTO `receipt_trans` (`id`, `tid`, `amount`, `note`, `tdate`) VALUES
(52, 2, '20200.00', '', '2018-06-03'),
(55, 1, '5000.00', '123456', '2018-06-04');

-- --------------------------------------------------------

--
-- Table structure for table `rec_customers`
--

CREATE TABLE `rec_customers` (
  `id` int(11) NOT NULL,
  `tid` int(8) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `taxid` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rec_invoices`
--

CREATE TABLE `rec_invoices` (
  `id` int(11) NOT NULL,
  `tid` int(8) NOT NULL,
  `tsn_date` date NOT NULL,
  `tsn_due` date NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `shipping` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `discountr` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `tax2` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `notes` varchar(255) NOT NULL,
  `status` enum('paid','due','canceled','') NOT NULL,
  `csd` int(5) NOT NULL DEFAULT '0',
  `eid` int(4) NOT NULL,
  `pmethod` int(1) NOT NULL,
  `rperiod` int(1) NOT NULL,
  `rc_next` date NOT NULL,
  `rc_up` date NOT NULL,
  `active` int(11) NOT NULL,
  `ramm` decimal(10,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rec_invoices`
--

INSERT INTO `rec_invoices` (`id`, `tid`, `tsn_date`, `tsn_due`, `subtotal`, `shipping`, `discount`, `discountr`, `tax`, `tax2`, `total`, `notes`, `status`, `csd`, `eid`, `pmethod`, `rperiod`, `rc_next`, `rc_up`, `active`, `ramm`) VALUES
(1, 1, '2018-03-21', '2018-03-21', '200.00', '0.00', '0.00', '0.00', '8.00', '7.00', '215.00', '', 'paid', 1, 1, 0, 1, '2018-03-28', '0000-00-00', 0, '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `rec_items`
--

CREATE TABLE `rec_items` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `product` text NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `trate` decimal(10,2) NOT NULL,
  `trate2` decimal(10,2) NOT NULL,
  `pid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rec_items`
--

INSERT INTO `rec_items` (`id`, `tid`, `product`, `qty`, `price`, `discount`, `subtotal`, `trate`, `trate2`, `pid`) VALUES
(7, 1, '1', 1, '100.00', '4.00', '106.00', '4.00', '2.00', 0),
(6, 1, '123', 1, '100.00', '4.00', '109.00', '4.00', '5.00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `rec_part_trans`
--

CREATE TABLE `rec_part_trans` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `tdate` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rec_summary`
--

CREATE TABLE `rec_summary` (
  `id` int(4) NOT NULL,
  `mnth` enum('January','February','March','April','May','June','July','August','September','October','November','December') NOT NULL,
  `yer` int(4) NOT NULL,
  `paid` int(11) NOT NULL,
  `due` int(11) NOT NULL,
  `unpaid` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reg_customers`
--

CREATE TABLE `reg_customers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `taxid` varchar(50) NOT NULL,
  `rdate` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reg_customers`
--

INSERT INTO `reg_customers` (`id`, `name`, `address1`, `address2`, `phone`, `email`, `taxid`, `rdate`) VALUES
(1, 'Sainul ', 'Omassery', 'Chennai', '123456', '', '', '2018-03-17'),
(2, 'Customer 1', 'cs address 1', 'Address 2', '123456978', 'customer@abc.com', '1548', '2018-05-20');

-- --------------------------------------------------------

--
-- Table structure for table `reg_vendors`
--

CREATE TABLE `reg_vendors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `taxid` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reg_vendors`
--

INSERT INTO `reg_vendors` (`id`, `name`, `address1`, `address2`, `phone`, `email`, `taxid`) VALUES
(1, 'Vendor 1', 'Vendor address 1', 'Address 2', '123456978', 'verdor@abc.com', 'TX4578');

-- --------------------------------------------------------

--
-- Table structure for table `returnc`
--

CREATE TABLE `returnc` (
  `id` int(11) NOT NULL,
  `tid` int(8) NOT NULL,
  `tsn_date` date NOT NULL,
  `tsn_due` date NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `shipping` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `discountr` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `notes` varchar(255) NOT NULL,
  `status` varchar(25) NOT NULL,
  `csd` int(5) NOT NULL DEFAULT '0',
  `eid` int(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `returnc`
--

INSERT INTO `returnc` (`id`, `tid`, `tsn_date`, `tsn_due`, `subtotal`, `shipping`, `discount`, `discountr`, `tax`, `total`, `notes`, `status`, `csd`, `eid`) VALUES
(1, 2, '2018-03-17', '2018-03-17', '40000.00', '0.00', '0.00', '0.00', '1800.00', '41800.00', '', 'refunded', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `return_customers`
--

CREATE TABLE `return_customers` (
  `id` int(11) NOT NULL,
  `tid` int(8) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `taxid` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `return_items`
--

CREATE TABLE `return_items` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `product` text NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `trate` decimal(10,2) NOT NULL,
  `pid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `return_items`
--

INSERT INTO `return_items` (`id`, `tid`, `product`, `qty`, `price`, `discount`, `subtotal`, `trate`, `pid`) VALUES
(2, 2, 'Onida 1t-44', 2, '20000.00', '1800.00', '41800.00', '4.50', 2);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `pid` int(11) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `product_code` varchar(255) NOT NULL,
  `product_price` decimal(10,2) DEFAULT NULL,
  `tax` decimal(10,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `summary`
--

CREATE TABLE `summary` (
  `id` int(4) NOT NULL,
  `mnth` enum('January','February','March','April','May','June','July','August','September','October','November','December') NOT NULL,
  `yer` int(4) NOT NULL,
  `paid` int(11) NOT NULL,
  `due` int(11) NOT NULL,
  `unpaid` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `summary`
--

INSERT INTO `summary` (`id`, `mnth`, `yer`, `paid`, `due`, `unpaid`) VALUES
(4, 'June', 2017, 0, 0, 0),
(5, 'July', 2017, 0, 0, 0),
(6, 'August', 2017, 0, 0, 0),
(7, 'September', 2017, 0, 0, 0),
(8, 'October', 2017, 0, 0, 0),
(9, 'November', 2017, 0, 0, 0),
(10, 'December', 2017, 0, 0, 0),
(11, 'January', 2018, 0, 0, 0),
(12, 'February', 2018, 0, 0, 0),
(13, 'March', 2018, 0, 0, 0),
(14, 'April', 2018, 0, 0, 0),
(15, 'May', 2018, 45720, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sys_smtp`
--

CREATE TABLE `sys_smtp` (
  `Host` varchar(50) NOT NULL,
  `Port` int(11) NOT NULL,
  `Auth` enum('true','false') NOT NULL,
  `Username` varchar(30) NOT NULL,
  `password` varchar(50) NOT NULL,
  `Sender` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sys_smtp`
--

INSERT INTO `sys_smtp` (`Host`, `Port`, `Auth`, `Username`, `password`, `Sender`) VALUES
('localhost', 487, '', 'test', '1234', 'aa.vv@gg.cv');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int(11) NOT NULL,
  `tid` int(8) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `taxid` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `tid`, `name`, `address1`, `address2`, `phone`, `email`, `taxid`) VALUES
(1, 3, 'asd', 'asd', 'asd', 'qeqw', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ac_balance`
--
ALTER TABLE `ac_balance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stat` (`stat`),
  ADD KEY `bdate` (`bdate`);

--
-- Indexes for table `ac_transactions`
--
ALTER TABLE `ac_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tno` (`tno`),
  ADD KEY `id` (`id`),
  ADD KEY `acid` (`acid`);

--
-- Indexes for table `bank_ac`
--
ALTER TABLE `bank_ac`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `acn` (`acn`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `billing_terms`
--
ALTER TABLE `billing_terms`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `conf`
--
ALTER TABLE `conf`
  ADD UNIQUE KEY `id_2` (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tid` (`tid`);

--
-- Indexes for table `damage`
--
ALTER TABLE `damage`
  ADD PRIMARY KEY (`tid`),
  ADD UNIQUE KEY `invoice` (`tid`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `damage_customers`
--
ALTER TABLE `damage_customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice` (`tid`);

--
-- Indexes for table `damage_items`
--
ALTER TABLE `damage_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice` (`tid`) KEY_BLOCK_SIZE=1024 USING BTREE;

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice` (`tid`),
  ADD KEY `eid` (`eid`),
  ADD KEY `csd` (`csd`),
  ADD KEY `tsn_date` (`tsn_date`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tid` (`tid`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD UNIQUE KEY `id_2` (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `paic`
--
ALTER TABLE `paic`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `tid` (`tid`),
  ADD KEY `pdate` (`pdate`);

--
-- Indexes for table `panel`
--
ALTER TABLE `panel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `id_2` (`id`);

--
-- Indexes for table `part_trans`
--
ALTER TABLE `part_trans`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `tid` (`tid`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `pcat` (`pcat`),
  ADD KEY `product_name` (`product_name`),
  ADD KEY `product_code` (`product_code`);

--
-- Indexes for table `product_cat`
--
ALTER TABLE `product_cat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quote`
--
ALTER TABLE `quote`
  ADD PRIMARY KEY (`tid`),
  ADD UNIQUE KEY `invoice` (`tid`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `quote_customers`
--
ALTER TABLE `quote_customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice` (`tid`);

--
-- Indexes for table `quote_items`
--
ALTER TABLE `quote_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice` (`tid`) KEY_BLOCK_SIZE=1024 USING BTREE;

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice` (`tid`);

--
-- Indexes for table `receipts_items`
--
ALTER TABLE `receipts_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice` (`tid`) KEY_BLOCK_SIZE=1024 USING BTREE;

--
-- Indexes for table `receipt_trans`
--
ALTER TABLE `receipt_trans`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `tid` (`tid`);

--
-- Indexes for table `rec_customers`
--
ALTER TABLE `rec_customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tid` (`tid`);

--
-- Indexes for table `rec_invoices`
--
ALTER TABLE `rec_invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice` (`tid`),
  ADD KEY `eid` (`eid`),
  ADD KEY `csd` (`csd`);

--
-- Indexes for table `rec_items`
--
ALTER TABLE `rec_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice` (`tid`) KEY_BLOCK_SIZE=1024 USING BTREE;

--
-- Indexes for table `rec_part_trans`
--
ALTER TABLE `rec_part_trans`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `tid` (`tid`);

--
-- Indexes for table `rec_summary`
--
ALTER TABLE `rec_summary`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_key` (`mnth`,`yer`),
  ADD KEY `id` (`id`),
  ADD KEY `yer` (`yer`),
  ADD KEY `mnth` (`mnth`),
  ADD KEY `paid` (`paid`),
  ADD KEY `due` (`due`),
  ADD KEY `unpaid` (`unpaid`);

--
-- Indexes for table `reg_customers`
--
ALTER TABLE `reg_customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `phone` (`phone`);

--
-- Indexes for table `reg_vendors`
--
ALTER TABLE `reg_vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `returnc`
--
ALTER TABLE `returnc`
  ADD PRIMARY KEY (`tid`),
  ADD UNIQUE KEY `invoice` (`tid`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `return_customers`
--
ALTER TABLE `return_customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice` (`tid`);

--
-- Indexes for table `return_items`
--
ALTER TABLE `return_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice` (`tid`) KEY_BLOCK_SIZE=1024 USING BTREE;

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `product_name` (`product_name`),
  ADD KEY `product_code` (`product_code`);

--
-- Indexes for table `summary`
--
ALTER TABLE `summary`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_key` (`mnth`,`yer`),
  ADD KEY `id` (`id`),
  ADD KEY `yer` (`yer`),
  ADD KEY `mnth` (`mnth`),
  ADD KEY `paid` (`paid`),
  ADD KEY `due` (`due`),
  ADD KEY `unpaid` (`unpaid`);

--
-- Indexes for table `sys_smtp`
--
ALTER TABLE `sys_smtp`
  ADD PRIMARY KEY (`Host`),
  ADD UNIQUE KEY `Host` (`Host`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice` (`tid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ac_balance`
--
ALTER TABLE `ac_balance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `ac_transactions`
--
ALTER TABLE `ac_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `bank_ac`
--
ALTER TABLE `bank_ac`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `damage`
--
ALTER TABLE `damage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `damage_customers`
--
ALTER TABLE `damage_customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `damage_items`
--
ALTER TABLE `damage_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(7) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;
--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `paic`
--
ALTER TABLE `paic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
--
-- AUTO_INCREMENT for table `part_trans`
--
ALTER TABLE `part_trans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;
--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `product_cat`
--
ALTER TABLE `product_cat`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `quote`
--
ALTER TABLE `quote`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `quote_customers`
--
ALTER TABLE `quote_customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `quote_items`
--
ALTER TABLE `quote_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `receipts_items`
--
ALTER TABLE `receipts_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `receipt_trans`
--
ALTER TABLE `receipt_trans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;
--
-- AUTO_INCREMENT for table `rec_customers`
--
ALTER TABLE `rec_customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rec_invoices`
--
ALTER TABLE `rec_invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `rec_items`
--
ALTER TABLE `rec_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `rec_part_trans`
--
ALTER TABLE `rec_part_trans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rec_summary`
--
ALTER TABLE `rec_summary`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reg_customers`
--
ALTER TABLE `reg_customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `reg_vendors`
--
ALTER TABLE `reg_vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `returnc`
--
ALTER TABLE `returnc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `return_customers`
--
ALTER TABLE `return_customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `return_items`
--
ALTER TABLE `return_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `summary`
--
ALTER TABLE `summary`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
