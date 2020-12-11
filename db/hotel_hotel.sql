-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 03-12-2020 a las 18:43:38
-- Versión del servidor: 5.7.32
-- Versión de PHP: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `hotel_hotel`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aboutus`
--

CREATE TABLE `aboutus` (
  `IDAboutus` int(11) NOT NULL,
  `AColLeft` text NOT NULL,
  `AColLeftEn` text NOT NULL,
  `AColRight` text NOT NULL,
  `AColRightEn` text NOT NULL,
  `AFirm` varchar(64) NOT NULL,
  `AFirmEn` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `aboutus`
--

INSERT INTO `aboutus` (`IDAboutus`, `AColLeft`, `AColLeftEn`, `AColRight`, `AColRightEn`, `AFirm`, `AFirmEn`) VALUES
(1, 'Hotel cÃ©ntricamente situado a 5km del aeropuerto de Mendoza, a 50m de la terminal de Ã³mnibus.\r\n\r\nPosee habitaciones cÃ³modas y muy bien amobladas, equipadas con detalles contemporÃ¡neos.\r\n\r\nAdemÃ¡s ofrecemos un excelente servicio y comodidad para su estadÃ­a en Mendoza, en un ambiente agradable, atendido por sus dueÃ±os.', 'Hotel centrally located 5km from Mendoza Airport, 50m from the bus terminal.\r\n\r\nIt has comfortable and well furnished and equipped with contemporary details.\r\n\r\nWe also offer excellent service and comfort for your stay in Mendoza, in a friendly, family run property.', 'TambiÃ©n encontrarÃ¡ los mejores precios con excelente atenciÃ³n y todo lo necesario para que su visita en nuestra provincia sea inolvidable. Esperando poder atenderlo como Usted merece, lo invitamos a conocer nuestras instalaciones y servicios.\r\n\r\nPara conocer nuestras habitaciones dirÃ­jase a la secciÃ³n \"GalerÃ­a de fotos\" y haga sus reservas en la secciÃ³n \"Tarifas y reservas\".', 'You\'ll also find the best prices with excellent service and all you need to make your visit a memorable one in our province. Hoping to serve you as you deserve, we invite you to visit our facilities and services.\r\n\r\nTo find our rooms go to \"Photo Gallery\" and make your reservations in the \"Rates and Reservations\".', 'Â¡Los esperamos!', 'See you there!');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contact`
--

CREATE TABLE `contact` (
  `IDContact` int(11) NOT NULL,
  `CPhone` varchar(255) NOT NULL,
  `CEmail` varchar(64) NOT NULL,
  `CGps` varchar(255) NOT NULL,
  `CAddress` varchar(255) NOT NULL,
  `CFooter` varchar(255) NOT NULL,
  `CFooterEn` varchar(255) NOT NULL,
  `CBank` text NOT NULL,
  `CBankEn` text NOT NULL,
  `CCard` text NOT NULL,
  `CCardEn` text NOT NULL,
  `CNote` text NOT NULL,
  `CNoteEn` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `contact`
--

INSERT INTO `contact` (`IDContact`, `CPhone`, `CEmail`, `CGps`, `CAddress`, `CFooter`, `CFooterEn`, `CBank`, `CBankEn`, `CCard`, `CCardEn`, `CNote`, `CNoteEn`) VALUES
(1, 'Tel.: (+54) 0261 - 4313121 Reservas 261-6554911 ', 'reservashotelelnevado@gmail.com', 'GPS: S 32Âº  53.741Â´   W 068Âº  49.696 Â´', 'Alberdi y GÃ¼emes 301, San JosÃ©, GuaymallÃ©n\r\nMendoza - Argentina - C.P. 5519', 'www.hotelelnevado.com.ar', 'www.hotelelnevado.com.ar', 'ENTIDAD BANCARIA: BANCO FRANCES\r\nNOMBRE: ROBERTO FERNANDO VIEGAS BORDEIRA\r\nCUIT: 20-24530843-5\r\nCAJA DE AHORRO EN PESOS: NÂº 285-321321/3\r\nCBU: 0170285140000032132131', 'BANK: BANCO FRANCES\r\nNAME: ROBERTO FERNANDO VIEGAS BORDEIRA\r\nCUIT: 20-24530843-5\r\nSAVINGS: NÂº 285-321321/3\r\nCBU: 0170285140000032132131', 'PUEDE ABONAR SU ESTADÃA EN EL HOTEL CON TARJETA DE CRÃ‰DITO (10% Recargo) O TARJETA DE DÃ‰BITO.\r\nOPERAMOS CON MASTER CARD, VISA, AMERICAN EXPRESS, NARANJA, CABAL, MAESTRO,  NEVADA.', 'You can PAY YOUR STAY IN THE HOTEL WITH CREDIT CARD (10% surcharge) OR DEBIT CARD.\r\nOPERATE WITH MASTER CARD, VISA, AMERICAN EXPRESS, ORANGE, CABAL, MAESTRO, NEVADA.\r\n', 'IMPORTANTE: Para realizar una reserva se debe depositar o transferir la totalidad de su estadÃ­a en el Hotel.', 'IMPORTANT: To make a reservation you must deposit or transfer the entire stay at the hotel.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `image`
--

CREATE TABLE `image` (
  `IDImage` int(11) NOT NULL,
  `IName` varchar(64) NOT NULL,
  `IDescription` varchar(255) NOT NULL,
  `IOrder` int(11) NOT NULL,
  `IDRoom` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `room`
--

CREATE TABLE `room` (
  `IDRoom` int(11) NOT NULL,
  `RName` varchar(32) NOT NULL,
  `RNameEn` varchar(64) NOT NULL,
  `RDescription` text NOT NULL,
  `RDescriptionEn` text NOT NULL,
  `RTarifer` float NOT NULL,
  `ROrder` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `room`
--

INSERT INTO `room` (`IDRoom`, `RName`, `RNameEn`, `RDescription`, `RDescriptionEn`, `RTarifer`, `ROrder`) VALUES
(1, 'Single (1 Persona)', 'Single', 'HabitaciÃ³n con Desayuno, BaÃ±o Privado, Tv Cable y Wi Fi..', 'Room with breakfast, private bathroom, cable TV and Wi Fi.', 1500, 5),
(2, 'Doble (2 Personas)', 'Double', 'HabitaciÃ³n con Desayuno, BaÃ±o Privado, Tv Cable y Wi Fi.', 'Room with breakfast, private bathroom, cable TV and Wi Fi.', 2200, 4),
(3, 'Triple (3 Personas)', 'Triple', 'HabitaciÃ³n con Desayuno, BaÃ±o Privado, Tv Cable y Wi Fi.', 'Room with breakfast, private bathroom, cable TV and Wi Fi.', 2900, 3),
(6, 'COCHERA', 'COCHERA', 'COCHERA', 'COCHERA', 100, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `slider`
--

CREATE TABLE `slider` (
  `IDSlider` int(11) NOT NULL,
  `SImage` varchar(64) NOT NULL,
  `SText` text NOT NULL,
  `STextEn` text NOT NULL,
  `SOrder` int(11) NOT NULL,
  `SStatus` int(1) NOT NULL,
  `SName` varchar(64) NOT NULL,
  `SNameEn` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `slider`
--

INSERT INTO `slider` (`IDSlider`, `SImage`, `SText`, `STextEn`, `SOrder`, `SStatus`, `SName`, `SNameEn`) VALUES
(6, '5cab62bc4c7b9.jpg', 'Bienvenidos a la Ciudad de Mendoza, Capital Internacional del Vino, elegida como una de las 28 Ciudades mÃ¡s maravillosas del mundo.\r\nUbicada al pie de las mÃ¡s altas montaÃ±as de Los Andes, Mendoza es la puerta obligada al OcÃ©ano PacÃ­fico. \r\n', 'Welcome to the City of Mendoza, Capital International Wine, voted one of the 28 most beautiful cities of the world.\r\nLocated at the foot of the highest mountains of the Andes, Mendoza is the gateway to the Pacific Ocean bound.', 2, 1, 'Bienvenidos!', 'Welcome!'),
(7, '5cab632ec55d4.jpg', 'HOTEL', 'HOTEL', 0, 1, 'HOTEL', 'HOTEL');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `IDUser` int(11) NOT NULL,
  `UName` text,
  `ULastname` text,
  `ULoginname` char(100) DEFAULT NULL,
  `UPassword` char(100) DEFAULT NULL,
  `UEmail` text,
  `UStatus` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`IDUser`, `UName`, `ULastname`, `ULoginname`, `UPassword`, `UEmail`, `UStatus`) VALUES
(59, 'Mauricio', 'Ampuero', 'mauri602', 'julian602', 'mdampuero@gmail.com', NULL),
(58, 'Roberto', 'Bordeira', 'tito', 'hotelelnevado', 'hotelelnevado@speedy.com.ar', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `aboutus`
--
ALTER TABLE `aboutus`
  ADD PRIMARY KEY (`IDAboutus`);

--
-- Indices de la tabla `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`IDContact`);

--
-- Indices de la tabla `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`IDImage`);

--
-- Indices de la tabla `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`IDRoom`);

--
-- Indices de la tabla `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`IDSlider`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`IDUser`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `aboutus`
--
ALTER TABLE `aboutus`
  MODIFY `IDAboutus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `contact`
--
ALTER TABLE `contact`
  MODIFY `IDContact` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `image`
--
ALTER TABLE `image`
  MODIFY `IDImage` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `room`
--
ALTER TABLE `room`
  MODIFY `IDRoom` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `slider`
--
ALTER TABLE `slider`
  MODIFY `IDSlider` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `IDUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
