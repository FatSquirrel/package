-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- 主機: 127.0.0.1:3306
-- 產生時間： 2019-08-15 02:29:11
-- 伺服器版本: 5.7.19
-- PHP 版本： 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `package`
--

-- --------------------------------------------------------

--
-- 資料表結構 `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `nickname` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idadmin_UNIQUE` (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='後台管理員帳號';

--
-- 資料表的匯出資料 `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `nickname`) VALUES
(3, 'admin', '111111', '管理猿');

-- --------------------------------------------------------

--
-- 資料表結構 `bill`
--

DROP TABLE IF EXISTS `bill`;
CREATE TABLE IF NOT EXISTS `bill` (
  `id` char(13) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Byyyymmddnnnn',
  `date` date NOT NULL COMMENT '帳單日期',
  `begindate` date NOT NULL,
  `enddate` date NOT NULL,
  `remark` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '備註',
  `fk_customer` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `tax` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='請款單';

-- --------------------------------------------------------

--
-- 資料表結構 `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `user_agent` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 資料表的匯出資料 `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('74908d862b6d5d7ff662337bfa3d1b8f', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36', 1565833414, 'a:3:{s:9:\"user_data\";s:0:\"\";s:7:\"islogin\";s:3:\"yes\";s:3:\"who\";s:9:\"蘇勇全\";}'),
('75e062c01aa7b63f529a45d7f90bd08e', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36', 1565835877, 'a:3:{s:9:\"user_data\";s:0:\"\";s:7:\"islogin\";s:3:\"yes\";s:3:\"who\";s:9:\"管理猿\";}');

-- --------------------------------------------------------

--
-- 資料表結構 `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `id` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `sname` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `companyno` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payremark` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `isdel` char(1) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '刪除旗標，''X''表示刪除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 資料表的匯出資料 `customer`
--

INSERT INTO `customer` (`id`, `name`, `sname`, `companyno`, `address`, `tel`, `fax`, `payremark`, `isdel`) VALUES
('67892500-fb81-4c5f-a78a-9137bcb2768b', 'test', 'tets', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- 資料表結構 `delivery`
--

DROP TABLE IF EXISTS `delivery`;
CREATE TABLE IF NOT EXISTS `delivery` (
  `id` char(13) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Dyyyymmddnnnn',
  `date` date NOT NULL COMMENT '出貨日期',
  `remark` varchar(200) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '備註',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='送貨單';

-- --------------------------------------------------------

--
-- 資料表結構 `order`
--

DROP TABLE IF EXISTS `order`;
CREATE TABLE IF NOT EXISTS `order` (
  `id` char(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '訂單id',
  `fk_product` char(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '訂購產品id',
  `fk_customer` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `qty` int(11) NOT NULL COMMENT '訂購數量',
  `etd` date NOT NULL COMMENT '預計交貨日',
  `prtpr` int(11) NOT NULL COMMENT '印刷版費請款(組)',
  `bladepr` int(11) NOT NULL COMMENT '刀模費請款(組)',
  `prtpr_price` int(11) DEFAULT '0' COMMENT '印刷版費請款(元)',
  `isprtprdeliv` char(1) COLLATE utf8_unicode_ci DEFAULT '',
  `bladepr_price` int(11) DEFAULT '0' COMMENT '刀模費請款(元)',
  `isbladeprdeliv` char(1) COLLATE utf8_unicode_ci DEFAULT '',
  `isdel` varchar(10) COLLATE utf8_unicode_ci DEFAULT ' ',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `orderdetail`
--

DROP TABLE IF EXISTS `orderdetail`;
CREATE TABLE IF NOT EXISTS `orderdetail` (
  `id` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `fk_order` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `fk_customer` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `fk_product` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `fk_productdetail` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `orderno` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '工單號碼：yymmddnn',
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '組件名稱',
  `tos` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '面紙訂購尺寸',
  `knum` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'K數',
  `back` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '對褙(紙張需求*2)',
  `tcs` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '面紙裁切尺寸',
  `cfreq` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '楞紙需求。用來套用在楞紙採購算式中，應該只會有0跟1，用來在算式最後的乘數',
  `cfs` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '楞紙尺寸',
  `qty` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '張/束',
  `t` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '面紙',
  `tvendor` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '面紙廠商',
  `prt` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '印刷',
  `prtvendor` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '印刷廠商',
  `sfc` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '表面',
  `sfcvendor` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '表面廠商',
  `heat` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '燙金',
  `heatvendor` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '燙金廠商',
  `cf` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '楞紙',
  `cfvendor` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '楞紙廠商',
  `pst` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '褙紙',
  `pstvendor` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '褙紙廠商',
  `ga` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '軋盒',
  `gavendor` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '軋盒廠商',
  `garemark` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '軋盒後說明',
  `glu` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '糊盒',
  `gluvendor` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '糊盒廠商',
  `price` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '售價(元)',
  `other` char(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '其它',
  `isorderdone` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `donedate` date DEFAULT '1970-01-01' COMMENT '訂單處理完成的日期',
  `po_t_no` char(13) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '面紙採購單號Tyyyymmddnnnn',
  `po_t_sn` smallint(6) NOT NULL DEFAULT '0' COMMENT '在面紙採購單的順序',
  `ispo_t_done` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `po_cf_no` char(13) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '楞紙採購單號Cyyyymmddnnnn',
  `po_cf_sn` smallint(6) NOT NULL DEFAULT '0' COMMENT '在楞紙採購單順序',
  `isneedpo_t` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `isneedpo_cf` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `ispo_cf_done` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ispodone` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `ppno` char(13) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Wyyyymmddnnnn',
  `isneedpp` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `isppdone` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `isneedother` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `isotherdone` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `qty2` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '製作數量',
  `toq` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '面紙應訂數量(4令1串)',
  `toq_n` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '面紙應訂數(數字型)',
  `toq2` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '面紙實訂數量(4令1串)',
  `tos2` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '面紙實訂尺寸',
  `cfqty` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '楞紙應訂數量',
  `cfqty2` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '楞紙實訂數量',
  `cfs2` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '楞紙實訂尺寸',
  `isdeliv` char(1) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '是否已出貨旗標',
  `isnotdeliv` char(1) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '此待出貨是否刪除',
  `delivno` char(13) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '出貨單號',
  `isbill` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `billno` char(13) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Byyyymmddnnnn',
  `remark` varchar(500) COLLATE utf8_unicode_ci NOT NULL COMMENT '備註 ',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `po_cf`
--

DROP TABLE IF EXISTS `po_cf`;
CREATE TABLE IF NOT EXISTS `po_cf` (
  `id` char(13) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Cyyyymmddnnnn',
  `podate` date NOT NULL COMMENT '採購單日期',
  `remark` varchar(200) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '備註',
  `df` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '刪除旗標(X)為已刪除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='楞紙採購單';

-- --------------------------------------------------------

--
-- 資料表結構 `po_t`
--

DROP TABLE IF EXISTS `po_t`;
CREATE TABLE IF NOT EXISTS `po_t` (
  `id` char(13) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Tyyyymmddnnnn',
  `podate` date NOT NULL COMMENT '採購單日期',
  `remark` varchar(200) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '備註',
  `df` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '刪除旗標(X)為已刪除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='面紙採購單';

-- --------------------------------------------------------

--
-- 資料表結構 `pp`
--

DROP TABLE IF EXISTS `pp`;
CREATE TABLE IF NOT EXISTS `pp` (
  `id` char(13) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Wyyyymmddnnnn',
  `ppdate` date NOT NULL COMMENT '工單日期',
  `remark` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '備註',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='工單';

-- --------------------------------------------------------

--
-- 資料表結構 `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `fk_customer` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `isdel` char(1) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '刪除旗標，''X''表示刪除中',
  `updatedate` date NOT NULL DEFAULT '1970-01-01' COMMENT '更新日期 ',
  `price` int(11) NOT NULL DEFAULT '0' COMMENT '價格',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 資料表的匯出資料 `product`
--

INSERT INTO `product` (`id`, `fk_customer`, `name`, `isdel`, `updatedate`, `price`) VALUES
('02ad137f-f86c-4739-8ca5-cb54e4db87f2', '67892500-fb81-4c5f-a78a-9137bcb2768b', 'test2', '', '1970-01-01', 0);

-- --------------------------------------------------------

--
-- 資料表結構 `productdetail`
--

DROP TABLE IF EXISTS `productdetail`;
CREATE TABLE IF NOT EXISTS `productdetail` (
  `id` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `fk_product` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '組件名稱',
  `tos` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '面紙訂購尺寸',
  `knum` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'K數',
  `back` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '對褙(紙張需求*2)',
  `tcs` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '面紙裁切尺寸',
  `cfreq` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '楞紙需求。用來套用在楞紙採購算式中，應該只會有0跟1，用來在算式最後的乘數',
  `cfs` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '楞紙尺寸',
  `qty` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '張/束',
  `t` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '面紙',
  `tvendor` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '面紙廠商',
  `prt` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '印刷',
  `prtvendor` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '印刷廠商',
  `sfc` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '表面',
  `sfcvendor` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '表面廠商',
  `heat` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '燙金',
  `heatvendor` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '燙金廠商',
  `cf` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '楞紙',
  `cfvendor` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '楞紙廠商',
  `pst` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '褙紙',
  `pstvendor` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '褙紙廠商',
  `ga` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '軋盒',
  `gavendor` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '軋盒廠商',
  `garemark` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '軋盒後說明',
  `glu` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '糊盒',
  `gluvendor` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '糊盒廠商',
  `price` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '(此欄已作廢)售價(元)',
  `other` char(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '其它',
  `otherremark` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(此欄已作廢)備註',
  `isdel` char(1) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 資料表的匯出資料 `productdetail`
--

INSERT INTO `productdetail` (`id`, `fk_product`, `name`, `tos`, `knum`, `back`, `tcs`, `cfreq`, `cfs`, `qty`, `t`, `tvendor`, `prt`, `prtvendor`, `sfc`, `sfcvendor`, `heat`, `heatvendor`, `cf`, `cfvendor`, `pst`, `pstvendor`, `ga`, `gavendor`, `garemark`, `glu`, `gluvendor`, `price`, `other`, `otherremark`, `isdel`) VALUES
('612c2312-e529-4c9c-af7a-6b108d44f837', '02ad137f-f86c-4739-8ca5-cb54e4db87f2', 'item1', '', '1', '1', '', '1', '', '125', '', '', '', '', '', '', '', '', '', '', '', '', '1', '', '', '', '', '0', '', '', '');

-- --------------------------------------------------------

--
-- 資料表結構 `setting`
--

DROP TABLE IF EXISTS `setting`;
CREATE TABLE IF NOT EXISTS `setting` (
  `lastposn` int(11) NOT NULL COMMENT '採購單流水號(每次歸零)',
  `lastppsn` int(11) NOT NULL COMMENT '工單單流水號(每次歸零)',
  `lastdelivsn` int(11) NOT NULL COMMENT '最後出貨單流水號',
  `lastbillsn` int(11) NOT NULL COMMENT '最後帳號單流水號',
  `lastordersn` int(11) NOT NULL COMMENT '最後工單流水號(2018年版)加在orderdetail中',
  `lastcheckdate` date NOT NULL COMMENT '最後檢查序號日期，若和檢查當下日期不同天，就歸零posn及ppsn'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 資料表的匯出資料 `setting`
--

INSERT INTO `setting` (`lastposn`, `lastppsn`, `lastdelivsn`, `lastbillsn`, `lastordersn`, `lastcheckdate`) VALUES
(0, 0, 0, 0, 2, '2019-08-15');

-- --------------------------------------------------------

--
-- 資料表結構 `vendor`
--

DROP TABLE IF EXISTS `vendor`;
CREATE TABLE IF NOT EXISTS `vendor` (
  `id` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `sname` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `companyno` varchar(12) COLLATE utf8_unicode_ci DEFAULT '',
  `address` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `tel` varchar(15) COLLATE utf8_unicode_ci DEFAULT '',
  `fax` varchar(15) COLLATE utf8_unicode_ci DEFAULT '',
  `payremark` varchar(300) COLLATE utf8_unicode_ci DEFAULT '',
  `isdel` char(1) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '刪除旗標，''X''表示刪除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 資料表的匯出資料 `vendor`
--

INSERT INTO `vendor` (`id`, `name`, `sname`, `companyno`, `address`, `tel`, `fax`, `payremark`, `isdel`) VALUES
('4239020c-c4bf-4071-ad55-6aff180a233e', 'test', 'test', '', '', '', '', '', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
