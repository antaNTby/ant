SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


DELIMITER $$
DROP FUNCTION IF EXISTS `decode_entities`$$
CREATE DEFINER=`nixby`@`localhost` FUNCTION `decode_entities` (`str` LONGTEXT charset utf8) RETURNS LONGTEXT CHARSET utf8 DETERMINISTIC NO SQL BEGIN
  IF str IS NULL OR str NOT LIKE '%&%;%' THEN
    RETURN str;
  END IF;
  BEGIN
    DECLARE entities BLOB DEFAULT 'AElig,198,Aacute,193,Acirc,194,Agrave,192,Alpha,913,Aring,197,Atilde,195,Auml,196,Beta,914,Ccedil,199,Chi,935,Dagger,8225,Delta,916,ETH,208,Eacute,201,Ecirc,202,Egrave,200,Epsilon,917,Eta,919,Euml,203,Gamma,915,Iacute,205,Icirc,206,Igrave,204,Iota,921,Iuml,207,Kappa,922,Lambda,923,Mu,924,Ntilde,209,Nu,925,OElig,338,Oacute,211,Ocirc,212,Ograve,210,Omega,937,Omicron,927,Oslash,216,Otilde,213,Ouml,214,Phi,934,Pi,928,Prime,8243,Psi,936,Rho,929,Scaron,352,Sigma,931,THORN,222,Tau,932,Theta,920,Uacute,218,Ucirc,219,Ugrave,217,Upsilon,933,Uuml,220,Xi,926,Yacute,221,Yuml,376,Zeta,918,aacute,225,acirc,226,acute,180,aelig,230,agrave,224,alefsym,8501,alpha,945,amp,38,and,8743,ang,8736,apos,39,aring,229,asymp,8776,atilde,227,auml,228,bdquo,8222,beta,946,brvbar,166,bull,8226,cap,8745,ccedil,231,cedil,184,cent,162,chi,967,circ,710,clubs,9827,cong,8773,copy,169,crarr,8629,cup,8746,curren,164,dArr,8659,dagger,8224,darr,8595,deg,176,delta,948,diams,9830,divide,247,eacute,233,ecirc,234,egrave,232,empty,8709,emsp,8195,ensp,8194,epsilon,949,equiv,8801,eta,951,eth,240,euml,235,euro,8364,exist,8707,fnof,402,forall,8704,frac12,189,frac14,188,frac34,190,frasl,8260,gamma,947,ge,8805,gt,62,hArr,8660,harr,8596,hearts,9829,hellip,8230,iacute,237,icirc,238,iexcl,161,igrave,236,image,8465,infin,8734,int,8747,iota,953,iquest,191,isin,8712,iuml,239,kappa,954,lArr,8656,lambda,955,lang,9001,laquo,171,larr,8592,lceil,8968,ldquo,8220,le,8804,lfloor,8970,lowast,8727,loz,9674,lrm,8206,lsaquo,8249,lsquo,8216,lt,60,macr,175,mdash,8212,micro,181,middot,183,minus,8722,mu,956,nabla,8711,nbsp,160,ndash,8211,ne,8800,ni,8715,not,172,notin,8713,nsub,8836,ntilde,241,nu,957,oacute,243,ocirc,244,oelig,339,ograve,242,oline,8254,omega,969,omicron,959,oplus,8853,or,8744,ordf,170,ordm,186,oslash,248,otilde,245,otimes,8855,ouml,246,para,182,part,8706,permil,8240,perp,8869,phi,966,pi,960,piv,982,plusmn,177,pound,163,prime,8242,prod,8719,prop,8733,psi,968,quot,34,rArr,8658,radic,8730,rang,9002,raquo,187,rarr,8594,rceil,8969,rdquo,8221,real,8476,reg,174,rfloor,8971,rho,961,rlm,8207,rsaquo,8250,rsquo,8217,sbquo,8218,scaron,353,sdot,8901,sect,167,shy,173,sigma,963,sigmaf,962,sim,8764,spades,9824,sub,8834,sube,8838,sum,8721,sup1,185,sup2,178,sup3,179,sup,8835,supe,8839,szlig,223,tau,964,there4,8756,theta,952,thetasym,977,thinsp,8201,thorn,254,tilde,732,times,215,trade,8482,uArr,8657,uacute,250,uarr,8593,ucirc,251,ugrave,249,uml,168,upsih,978,upsilon,965,uuml,252,weierp,8472,xi,958,yacute,253,yen,165,yuml,255,zeta,950,zwj,8205,zwnj,8204';
    DECLARE len BIGINT UNSIGNED DEFAULT LENGTH(str);
    DECLARE ptr BIGINT UNSIGNED DEFAULT 0;
    DECLARE nxtamp BIGINT UNSIGNED DEFAULT NULL;
    DECLARE nxtsem BIGINT UNSIGNED DEFAULT NULL;
    DECLARE sbstr LONGTEXT DEFAULT NULL;
    DECLARE decval SMALLINT UNSIGNED DEFAULT NULL;
    DECLARE setpos SMALLINT UNSIGNED DEFAULT NULL;
    DECLARE uenc TINYTEXT DEFAULT NULL;
    walk:
    LOOP
      SET ptr = ptr + 1;
      IF ptr >= len THEN
        LEAVE walk;
      END IF;
      SET nxtamp = LOCATE('&',str,ptr);
      IF NOT nxtamp THEN
        LEAVE walk;
      END IF;
      SET nxtsem = LOCATE(';',str,ptr + 1);
      IF NOT nxtsem THEN
        LEAVE walk;
      END IF;
      IF nxtsem < nxtamp THEN
        ITERATE walk;
      END IF;
      SET sbstr = SUBSTRING(str FROM nxtamp +1 FOR nxtsem - nxtamp - 1);
      IF sbstr RLIKE '^#[0-9]+$' THEN
        SET decval = TRIM(LEADING '#' FROM sbstr);
      ELSEIF sbstr RLIKE '^#x[0-9a-f]+$' THEN
        SET decval = CONV(TRIM(LEADING '#x' FROM sbstr),16,10);
      ELSE
        SET setpos = FIND_IN_SET(sbstr,entities);
        IF setpos > 0 THEN
          SET decval = SUBSTRING_INDEX(SUBSTRING_INDEX(entities,',',setpos + 1),',',-1);
        ELSE
          ITERATE walk;
        END IF;
      END IF;
      IF (decval > 0) THEN
        SET uenc = CHAR(CASE
            WHEN decval <= 0x7F THEN decval
            WHEN decval <= 0x7FF THEN 0xC080 | ((decval >> 6) << 8) | (decval & 0x3F)
            WHEN decval <= 0xFFFF THEN 0xE08080 | (((decval >> 12) & 0x0F ) << 16)  | (((decval >> 6) & 0x3F ) << 8) | (decval & 0x3F)
            ELSE NULL END);
        IF uenc IS NOT NULL AND LENGTH(uenc) > 0 THEN
          SET str = INSERT(str, nxtamp, 1 + nxtsem - nxtamp, uenc);
        END IF;
      END IF;
    END LOOP;
    RETURN str;
  END;
END$$

DROP FUNCTION IF EXISTS `is_numeric`$$
CREATE DEFINER=`nixby`@`localhost` FUNCTION `is_numeric` (`val` VARCHAR(1024)) RETURNS TINYINT(1) DETERMINISTIC return val regexp '^(-|\\+)?([0-9]+\\.[0-9]*|[0-9]*\\.[0-9]+|[0-9]+)$'$$

DROP FUNCTION IF EXISTS `nixru_encode_approxamount`$$
CREATE DEFINER=`nixby`@`localhost` FUNCTION `nixru_encode_approxamount` (`str` VARCHAR(255) CHARACTER SET 'utf8') RETURNS INT(11) DETERMINISTIC NO SQL BEGIN
  IF str IS NULL THEN
    RETURN 0;
  END IF;
  BEGIN
    DECLARE stringval VARCHAR(255);  
    DECLARE decval SMALLINT UNSIGNED DEFAULT NULL;  
    DECLARE result SMALLINT SIGNED DEFAULT NULL;  
    SET result = 0;
    IF is_numeric(str) THEN
      SET result = CONVERT(str,SIGNED );
      RETURN result;
    ELSE
        IF str LIKE 'много' THEN
            SET result = 9999;
        ELSE
            SET stringval = regex_replace('/\D/','',str);
            SET decval= CONVERT(stringval,SIGNED );
            SET result = -(decval);
        END IF;
    END IF;
    RETURN result;
  END;
END$$

DROP FUNCTION IF EXISTS `regex_replace`$$
CREATE DEFINER=`nixby`@`localhost` FUNCTION `regex_replace` (`pattern` VARCHAR(1000), `replacement` VARCHAR(1000), `original` VARCHAR(1000)) RETURNS VARCHAR(1000) CHARSET utf8 DETERMINISTIC BEGIN 
 DECLARE temp VARCHAR(1000); 
 DECLARE ch VARCHAR(1); 
 DECLARE i INT;
 SET i = 1;
 SET temp = '';
 IF original REGEXP pattern THEN 
  loop_label: LOOP 
   IF i>CHAR_LENGTH(original) THEN
    LEAVE loop_label;  
   END IF;
   SET ch = SUBSTRING(original,i,1);
   IF NOT ch REGEXP pattern THEN
    SET temp = CONCAT(temp,ch);
   ELSE
    SET temp = CONCAT(temp,replacement);
   END IF;
   SET i=i+1;
  END LOOP;
 ELSE
  SET temp = original;
 END IF;
 RETURN temp;
END$$

DELIMITER ;

DROP TABLE IF EXISTS `nano_invoices`;
CREATE TABLE IF NOT EXISTS `nano_invoices` (
  `invoice_time` timestamp NOT NULL COMMENT 'дата выставления счета',
  `invoiceID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Первичный ключ',
  `orderID` int(11) DEFAULT NULL COMMENT 'ID заказа',
  `module_id` int(11) DEFAULT '100' COMMENT 'ID модуля формирования счета',
  `sellerID` int(11) DEFAULT NULL COMMENT 'ID Продавца',
  `buyerID` int(11) DEFAULT NULL COMMENT 'ID Покупателя',
  `currency_rate` float NOT NULL DEFAULT '1' COMMENT 'Курс без НДС и без наценок, по которому выставляем счет',
  `contractID` int(11) DEFAULT NULL COMMENT 'ID текстов договоров',
  `purposeID` varchar(255) DEFAULT '0' COMMENT 'Цели покупки - кодЫ',
  `fundingID` varchar(255) DEFAULT '0' COMMENT 'Источники финансировния - код',
  `DeliveryType` int(2) NOT NULL DEFAULT '0' COMMENT 'Тип доставки - код: 0 -самовывоз, 1-доставка',
  `PaymentType` int(2) NOT NULL DEFAULT '0' COMMENT 'Оплата: 0-полная предоплата, 1-оплата по факту поставки',
  `actuality_termin` varchar(40) DEFAULT '3' COMMENT 'срок действия счета',
  `delivery_termin` varchar(40) DEFAULT '10' COMMENT 'срок_поставки товара',
  `payment_termin` varchar(40) DEFAULT '1' COMMENT 'срок полной оплаты, равен actuality_termin, в случае 100% -ой предоплаты',
  `payment_prepay` float DEFAULT '100' COMMENT 'Процент предоплаты',
  `deliveryFrom` varchar(255) DEFAULT NULL COMMENT 'Адрес погрузки',
  `deliveryTo` varchar(255) DEFAULT NULL COMMENT 'Адрес разгрузки',
  `requisites` text COMMENT 'Реквизиты',
  `director_nominative` text COMMENT 'Именительный падеж Руководитель',
  `director_genitive` text COMMENT ' Руководитель Родительный падеж',
  `director_reason` text COMMENT 'Действует на основании',
  PRIMARY KEY (`invoiceID`),
  KEY `orderID` (`orderID`),
  KEY `buyerID` (`buyerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ant_categories`;
CREATE TABLE IF NOT EXISTS `ant_categories` (
  `categoryID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `products_count` int(11) DEFAULT NULL,
  `description` text,
  `picture` varchar(30) DEFAULT NULL,
  `products_count_admin` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT '0',
  `viewed_times` int(11) DEFAULT '0',
  `allow_products_comparison` int(11) DEFAULT '0',
  `allow_products_search` int(11) DEFAULT '1',
  `show_subcategories_products` int(11) DEFAULT '1',
  `meta_description` text,
  `meta_keywords` text,
  `title` text,
  `subcount` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '1' COMMENT 'Показывать категорию',
  PRIMARY KEY (`categoryID`),
  KEY `IDX_CATEGORIES1` (`parent`),
  KEY `SORT_ORDER` (`sort_order`),
  KEY `sort_order_name` (`sort_order`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ant_category_product`;
CREATE TABLE IF NOT EXISTS `ant_category_product` (
  `productID` int(11) NOT NULL,
  `categoryID` int(11) NOT NULL,
  PRIMARY KEY (`productID`,`categoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ant_companies`;
CREATE TABLE IF NOT EXISTS `ant_companies` (
  `companyID` int(11) NOT NULL AUTO_INCREMENT,
  `company_title` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_unp` varchar(32) DEFAULT NULL,
  `company_okpo` varchar(32) DEFAULT NULL,
  `company_adress` text,
  `company_bank` text,
  `company_contacts` text,
  `company_email` text,
  `company_director` text,
  `company_data` text,
  `company_admin` text,
  `read_only` int(1) DEFAULT '0',
  `creation_time` datetime DEFAULT NULL,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`companyID`),
  KEY `company_unp` (`company_unp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Таблица реквизитов Компаний';

DROP TABLE IF EXISTS `ant_currency_types`;
CREATE TABLE IF NOT EXISTS `ant_currency_types` (
  `CID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(60) DEFAULT NULL,
  `code` varchar(60) DEFAULT NULL,
  `currency_value` float DEFAULT NULL,
  `where2show` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT '0',
  `currency_iso_3` char(3) DEFAULT NULL,
  `roundval` int(11) DEFAULT '2',
  PRIMARY KEY (`CID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ant_customers`;
CREATE TABLE IF NOT EXISTS `ant_customers` (
  `customerID` int(11) NOT NULL AUTO_INCREMENT,
  `Login` varchar(32) DEFAULT NULL,
  `cust_password` varchar(255) NOT NULL,
  `Email` varchar(64) DEFAULT NULL,
  `first_name` varchar(32) DEFAULT NULL,
  `last_name` varchar(32) DEFAULT NULL,
  `subscribed4news` int(11) DEFAULT NULL,
  `custgroupID` int(11) DEFAULT NULL,
  `addressID` int(11) DEFAULT NULL,
  `reg_datetime` datetime DEFAULT NULL,
  `ActivationCode` varchar(64) NOT NULL DEFAULT '',
  `CID` int(11) DEFAULT NULL,
  `affiliateID` int(11) NOT NULL,
  `affiliateEmailOrders` int(11) NOT NULL DEFAULT '1',
  `affiliateEmailPayments` int(11) NOT NULL DEFAULT '1',
  `actions` text NOT NULL,
  `customer_aka` int(11) DEFAULT NULL,
  PRIMARY KEY (`customerID`),
  UNIQUE KEY `LoginUnique` (`Login`),
  KEY `AFFILIATEID` (`affiliateID`),
  KEY `login` (`Login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ant_invoices`;
CREATE TABLE IF NOT EXISTS `ant_invoices` (
  `invoiceID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID записи в таблице Контрактов',
  `orderID` int(11) DEFAULT NULL COMMENT 'ID заказа',
  `invoice_description` varchar(255) NOT NULL COMMENT 'Описание, комментарий',
  `invoice_time` datetime NOT NULL COMMENT 'дата выставления счета',
  `contract_time` datetime NOT NULL COMMENT 'дата заключения договора',
  `module_id` int(11) NOT NULL COMMENT 'ID модуля формирования счета',
  `sellerID` int(11) DEFAULT NULL COMMENT 'ID Продавца',
  `buyerID` int(11) DEFAULT NULL COMMENT 'ID Покупателя',
  `contractID` int(11) DEFAULT NULL COMMENT 'ID текстов договоров',
  `CID` int(11) DEFAULT NULL COMMENT 'ID Валюты',
  `currency_rate` float NOT NULL COMMENT 'Курс, по которому выставляем счет',
  `purposeID` varchar(255) DEFAULT '0' COMMENT 'Цели покупки - кодЫ',
  `fundingID` varchar(255) DEFAULT '0' COMMENT 'Источники финансировния - код',
  `DeliveryType` int(2) NOT NULL DEFAULT '0' COMMENT 'тип доставки - код: 0 -самовывоз, 1-доставка',
  `deliveryFrom` varchar(255) DEFAULT NULL COMMENT 'Адрес погрузки',
  `deliveryTo` varchar(255) DEFAULT NULL COMMENT 'Адрес разгрузки',
  `delivery_termin` varchar(40) DEFAULT '10' COMMENT 'срок_поставки',
  `PaymentType` int(2) NOT NULL DEFAULT '0' COMMENT 'Оплата: 0-полная предоплата, 1-оплата по факту поставки, 2 - кредит',
  `payment_prepay` float DEFAULT '100' COMMENT 'Процент предоплаты',
  `payment_transactions_count` int(2) DEFAULT '1' COMMENT 'число платежей на которые разбтивается оплата или преодплата',
  `payment_firstrpay_termin` varchar(40) DEFAULT '3' COMMENT 'срок поступления первого платежа',
  `payment_fullpay_termin` varchar(40) DEFAULT '1' COMMENT 'срок полной оплаты',
  `stampsBYTE` int(1) NOT NULL DEFAULT '0' COMMENT 'битовая маска выставления печатей 0000 0-war-contr-inv',
  `showToUser` int(1) NOT NULL DEFAULT '3' COMMENT '0 -не показывать, 1-показывать Покупателям',
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT 'Время последнего изменения',
  `invoice_subdiscount` float DEFAULT NULL COMMENT 'Индивидуальная скидка или наценка(для отриц) суммируется со скидкой в заказе',
  `invoice_hidden` int(1) DEFAULT NULL COMMENT 'Не показывать клиентам',
  PRIMARY KEY (`invoiceID`),
  KEY `module_id` (`module_id`),
  KEY `buyerID` (`buyerID`),
  KEY `sellerID` (`sellerID`),
  KEY `ant_invoices_ibfk_6` (`CID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Таблица Счетов к заказам -invoices ::russian.php';

DROP TABLE IF EXISTS `ant_online`;
CREATE TABLE IF NOT EXISTS `ant_online` (
  `uname` varchar(32) NOT NULL,
  `time` varchar(14) NOT NULL,
  PRIMARY KEY (`uname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ant_ordered_carts`;
CREATE TABLE IF NOT EXISTS `ant_ordered_carts` (
  `itemID` int(11) NOT NULL COMMENT 'ID пункта',
  `orderID` int(11) NOT NULL COMMENT 'ID заказа',
  `name` text COMMENT 'Наименование',
  `Price` double DEFAULT NULL COMMENT 'Price',
  `Quantity` int(11) DEFAULT NULL COMMENT 'Количество',
  `tax` double DEFAULT NULL COMMENT 'Налоги',
  `load_counter` int(11) DEFAULT '0' COMMENT 'Число загрузок',
  `itemUnit` varchar(16) DEFAULT 'шт' COMMENT 'Единица измерения',
  `order_aka` int(11) NOT NULL,
  `itemPriority` int(11) NOT NULL DEFAULT '0',
  `enabled` int(11) DEFAULT '1' COMMENT '0-не учитываются и не выводятся',
  PRIMARY KEY (`itemID`,`orderID`),
  KEY `orderID` (`orderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ant_orders`;
CREATE TABLE IF NOT EXISTS `ant_orders` (
  `orderID` int(11) NOT NULL AUTO_INCREMENT,
  `customerID` int(11) DEFAULT NULL,
  `order_time` datetime DEFAULT NULL,
  `customer_ip` varchar(15) DEFAULT NULL,
  `shipping_type` varchar(64) DEFAULT NULL,
  `payment_type` varchar(64) DEFAULT NULL,
  `customers_comment` text,
  `statusID` int(11) DEFAULT NULL,
  `shipping_cost` double DEFAULT NULL,
  `order_discount` double DEFAULT NULL,
  `order_amount` double DEFAULT NULL,
  `currency_code` varchar(12) DEFAULT NULL,
  `currency_value` float DEFAULT NULL,
  `customer_firstname` varchar(64) DEFAULT NULL,
  `customer_lastname` varchar(64) DEFAULT NULL,
  `customer_email` varchar(50) DEFAULT NULL,
  `shipping_firstname` varchar(64) DEFAULT NULL,
  `shipping_lastname` varchar(64) DEFAULT NULL,
  `shipping_country` varchar(64) DEFAULT NULL,
  `shipping_state` varchar(64) DEFAULT NULL,
  `shipping_city` varchar(64) DEFAULT NULL,
  `shipping_address` text,
  `billing_firstname` varchar(64) DEFAULT NULL,
  `billing_lastname` varchar(64) DEFAULT NULL,
  `billing_country` varchar(64) DEFAULT NULL,
  `billing_state` varchar(64) DEFAULT NULL,
  `billing_city` varchar(64) DEFAULT NULL,
  `billing_address` text,
  `cc_number` varchar(255) DEFAULT NULL,
  `cc_holdername` varchar(255) DEFAULT NULL,
  `cc_expires` char(255) DEFAULT NULL,
  `cc_cvv` varchar(255) DEFAULT NULL,
  `affiliateID` int(11) DEFAULT '0',
  `shippingServiceInfo` varchar(255) DEFAULT NULL,
  `custlink` varchar(36) DEFAULT NULL,
  `currency_round` int(11) DEFAULT '2',
  `shippmethod` int(11) DEFAULT NULL,
  `paymethod` int(11) DEFAULT NULL,
  `companyID` int(11) DEFAULT NULL,
  `contractID` int(11) DEFAULT NULL,
  `order_aka` varchar(255) DEFAULT NULL,
  `customer_aka` int(11) DEFAULT NULL,
  `admin_comment` text,
  PRIMARY KEY (`orderID`),
  KEY `contractID` (`contractID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='для старых счетов';

DROP TABLE IF EXISTS `ant_products`;
CREATE TABLE IF NOT EXISTS `ant_products` (
  `productID` int(11) NOT NULL AUTO_INCREMENT,
  `categoryID` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `customers_rating` float DEFAULT '0',
  `Price` double DEFAULT NULL,
  `in_stock` int(11) DEFAULT NULL,
  `customer_votes` int(11) DEFAULT '0',
  `items_sold` int(11) NOT NULL,
  `enabled` int(11) DEFAULT NULL,
  `brief_description` text,
  `list_price` double DEFAULT NULL,
  `product_code` varchar(25) DEFAULT NULL,
  `sort_order` int(11) DEFAULT '0',
  `default_picture` int(11) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `viewed_times` int(11) DEFAULT '0',
  `eproduct_filename` varchar(255) DEFAULT NULL,
  `eproduct_available_days` int(11) DEFAULT '5',
  `eproduct_download_times` int(11) DEFAULT '5',
  `weight` float DEFAULT '0',
  `meta_description` text,
  `meta_keywords` text,
  `free_shipping` int(11) DEFAULT '0',
  `min_order_amount` int(11) DEFAULT '1',
  `shipping_freight` double DEFAULT '0',
  `classID` int(11) DEFAULT NULL,
  `title` text,
  `vendorID` int(11) DEFAULT '0' COMMENT 'Поставщик',
  PRIMARY KEY (`productID`),
  UNIQUE KEY `vendorID_product_code` (`vendorID`,`product_code`),
  KEY `IDX_PRODUCTS1` (`categoryID`),
  KEY `date_added` (`date_added`),
  KEY `sort_order_name` (`sort_order`,`name`),
  KEY `price` (`Price`),
  KEY `enabled` (`enabled`),
  KEY `product_code` (`product_code`),
  KEY `productID` (`productID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ant_session`;
CREATE TABLE IF NOT EXISTS `ant_session` (
  `id` varchar(32) NOT NULL,
  `data` text NOT NULL,
  `expire` int(11) NOT NULL DEFAULT '0',
  `IP` varchar(15) NOT NULL,
  `Referer` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `URL` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ant_shopping_carts`;
CREATE TABLE IF NOT EXISTS `ant_shopping_carts` (
  `customerID` int(11) NOT NULL,
  `itemID` int(11) NOT NULL,
  `Quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`customerID`,`itemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ant_system`;
CREATE TABLE IF NOT EXISTS `ant_system` (
  `varName` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `ant_invoices`
  ADD CONSTRAINT `ant_invoices_ibfk_4` FOREIGN KEY (`buyerID`) REFERENCES `ant_companies` (`companyID`) ON DELETE SET NULL,
  ADD CONSTRAINT `ant_invoices_ibfk_5` FOREIGN KEY (`sellerID`) REFERENCES `ant_companies` (`companyID`) ON DELETE SET NULL,
  ADD CONSTRAINT `ant_invoices_ibfk_6` FOREIGN KEY (`CID`) REFERENCES `ant_currency_types` (`CID`) ON DELETE SET NULL;

ALTER TABLE `ant_orders`
  ADD CONSTRAINT `ant_orders_ibfk_1` FOREIGN KEY (`contractID`) REFERENCES `ant_contracts` (`contractID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
