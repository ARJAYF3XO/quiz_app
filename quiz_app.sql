-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2026 at 11:26 AM
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
-- Database: `quiz_app`
--
CREATE DATABASE IF NOT EXISTS `quiz_app` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `quiz_app`;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option1` varchar(255) NOT NULL,
  `option2` varchar(255) NOT NULL,
  `option3` varchar(255) NOT NULL,
  `option4` varchar(255) NOT NULL,
  `correct_answer` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question`, `option1`, `option2`, `option3`, `option4`, `correct_answer`) VALUES
(10, 7, 'question name123124124', '12312', '3123', '123123', '123123123', 1),
(11, 8, 'At which OSI layer is the data stream broken up into segments that include source and destination port numbers?', 'A.Network', 'B.Session', 'C.Transport', 'Data Link', 4),
(12, 8, 'Which information is included in the header of UDP\r\nsegment?', 'A.Port Numbers', 'B.IP Address', 'C.Sequence Numbers', 'D.Mac Address', 1),
(13, 8, 'During the data encapsulation process which OSI layer adds a header\r\nthat contains MAC addressing information and a trailer used for error\r\nchecking.', 'A.Network', 'B.Session', 'C.Transport', 'D.Data Link', 1),
(14, 8, 'Which protocol allows you to securely upload files\r\nto another computer on the internet?', 'A.SFTP', 'B.HTTP', 'C.NTP', 'D.ICMP', 1),
(15, 8, 'A company develops application using cloud-based resources and tools.', 'Laas', 'Saas', 'Paas', 'n/a', 3),
(16, 8, 'These virtual machines are connected by a virtual network in the cloud', 'Laas', 'Saas', 'Paas', 'n/a', 1),
(17, 8, 'User access a web-based graphics design application in the cloud for a monthly fee', 'Laas', 'Saas', 'Paas', 'n/a', 2),
(18, 8, 'Provides the hardware and software needed for developing, running, and managing\r\napplications.', 'Laas', 'Saas', 'Paas', 'n/a', 3),
(19, 8, 'Provide pay-as-you-go access to resources provided on virtual machines and virtual\r\nstorage.', 'Laas', 'Saas', 'Paas', 'n/a', 1),
(20, 8, 'Provide on-demand access to applications delivered remotely over the internet.', 'Laas', 'Saas', 'Paas', 'n/a', 2),
(21, 9, 'Which protocol does an IPv6 host use to resolve the\r\nMAC address associated with a destination IPv6\r\naddress?', 'A. Address Resolution Protocol (ARP)', 'B. Cisco Discovery Protocol (CDP)', 'C. Neighbor Discovery Protocol (NDP)', 'D. Dynamic Host Configuration Protocol (DHCP)', 3),
(22, 9, 'Which protocol is used by IPV6 enabled host to\r\nperform automatic stateless address configuration?', 'A. DHCPV6', 'B. ICMPV6', 'C. TFTP', 'D. DNS', 3),
(23, 9, 'During the data encapsulation process, which OSI layer\r\nadds a header that contains MAC addressing information\r\nand a trailer used for error checking?', 'A. Network', 'B. Transport', 'C. Data Link', 'D. Session', 3),
(24, 9, 'A user initiates a trouble ticket stating that an external web page is\r\nnot loading. You determine that other resources both internal and\r\nexternal are still reachable.\r\nWhich command can you use to help locate where the issue is in\r\nthe network path to the external web page?', 'A. ping -t', 'B. tracert', 'C. ipconfig/all', 'D. nslookup', 2),
(25, 9, 'Which  statement is true about the IPv4\r\naddress of the default gateway configured on a host?', 'A. The IPv4 address of the default gateway must be the first host address in the subnet.', 'B. The same default gateway IPv4 address is configured on each host on the local network', 'C. The default gateway is the Loopback0 interface IPv4 address of the router connected to the same local network as the host.', 'D. Hosts learn the default gateway IPv4 address through router advertisement messages.', 0),
(26, 9, 'Which  statement is true about the IPv4\r\naddress of the default gateway configured on a host?', 'A. The IPv4 address of the default gateway must be the first host address in the subnet.', 'B. The default gateway is the IPv4 address of the router interface connected to the same local network as the host.', 'C. The default gateway is the Loopback0 interface IPv4 address of the router connected to the same local network as the host.', 'D. Hosts learn the default gateway IPv4 address through router advertisement messages.', 0),
(27, 9, 'Which two statements are true about the IPv4\r\naddress of the default gateway configured on a host?', 'A. The IPv4 address of the default gateway must be the first host address in the subnet.', 'B. The same default gateway IPv4 address is configured on each host on the local network.', 'C. The default gateway is the Loopback0 interface IPv4 address of the router connected to the same local network as the host.', 'D. Hosts learn the default gateway IPv4 address through router advertisement messages.', 2),
(28, 9, 'Which two statements are true about the IPv4\r\naddress of the default gateway configured on a host?', 'A. The IPv4 address of the default gateway must be the first host address in the subnet.', 'B. The default gateway is the IPv4 address of the router interface connected to the same local network as the host.', 'C. The default gateway is the Loopback0 interface IPv4 address of the router connected to the same local network as the host.', 'D. Hosts learn the default gateway IPv4 address through router advertisement messages.', 2),
(29, 9, 'An engineer configured a new VLAN named VLAN2 for the Data\r\nCenter team. When the team tries to ping addresses outside\r\nVLAN2 from a computer in VLAN2, they are unable to reach them.\r\nWhat should the engineer configure?', 'Additional VLAN', 'Default gateway', 'Default route', 'Static route', 2),
(30, 9, 'Which information is included in the header of a UDP\r\nsegment?', 'IP addresses', 'Sequence numbers', 'Port numbers', 'MAC addresses', 3),
(31, 9, 'What is the purpose of assigning an IP address to the\r\nmanagement VLAN interface on a Layer 2 switch?', 'To enable access to the CLI on the switch through Telnet or SSH', 'To enable the switch to provide DHCP services to other switches in the network', 'To enable the switch to act as a default gateway for the attached devices', 'To enable the switch to resolve URLs for the attached the devices', 1),
(32, 9, 'Which of the following is a characteristic of the\r\nSpanning Tree Protocol (STP)?', 'prevents loops in a network by blocking redundant links.', 'provides load balancing across multiple paths in a network.', 'prioritizes network traffic based on Quality of Service (QoS) settings.', 'allows for rapid convergence by eliminating the need for spanning tree calculations.', 1),
(33, 9, 'What protocol is used by OSPF to form neighbor\r\nrelationships and exchange routing information?', 'CP (Control Protocol)', 'P (Protocol)', 'MP (Multiprotocol)', 'Llo (Link-Local Operations)', 4),
(34, 9, 'What information is contained in the MAC address\r\ntable of a switch?', 'Dynamically learned Layer2 and Layer3 addresses of devices communicating on active ports on the switch', 'The MAC addresses of devices communicating on active ports and static MAC addresses configured by the administrator', 'All active ports on the switch and the host Layer3 addresses that were dynamically learned on each port.', 'MAC addresses to IP Address mappings learned through ARP requests or manually configured by the administrator', 2),
(35, 9, 'What is the purpose of a subnet mask?', 'determine the network portion of an IP address', 'determine the host portion of an IP address', 'determine the default gateway for a network', 'determine the DNS server for a network', 1),
(36, 9, 'A user at you company cannot connect to website on the internet.\r\nHowever, they can connect to network resources on the company\r\nLAN. You want to use the divide and conquer approach to\r\ntroubleshoot the issue. What should you do first?', 'Run the Telnet command from the user’s computer', 'Ping the default gateway from the user’s computer', 'Check the computer’s cable connections', 'Check the computer\'s network adapter', 2),
(37, 9, 'Your company has 20 Cisco switches throughout its\r\nbuilding. You need to view the configuration of each switch\r\nfrom the command line. Which protocol should you use?', 'FTP (File Transfer Protocol)', 'RDP (Remote Desktop Protocol)', 'SMTP (Simple Mail Transfer Protocol)', 'SNMP (Simple Network Management Protocol)', 4),
(38, 9, 'Which device protects the network by\r\npermitting or denying traffic based on IP\r\naddress, port number, or application?', 'Firewall', 'Access point', 'VPN gateway', 'Intrusion detection system', 1),
(39, 9, 'How does a firewall determine which traffic to block?', 'The firewall matches traffic based on the IP address in the ARP table', 'The firewall performs a one-to-many network address translation', 'The firewall matches the traffic based on source and destination IP address', 'The firewall performs a one-to-one network address translation.', 3),
(40, 9, 'Which best describes confidentiality with regards to network\r\nsecurity?', 'Ensures data is available for access by providing redundant systems.', 'Ensures data is not changed during transit between system.', 'Ensures data is kept secret using safeguards to prevent unauthorized access.', 'Ensures data is trusted and has not been tampered with or changed', 3),
(41, 10, 'A firewall can block traffic to specific ports on internal computers.', 'true', 'false', 'n/a', 'n/a', 1),
(42, 10, 'A firewall can direct all web traffic to a specific IP address.', 'true', 'false', 'n/a', 'n/a', 1),
(43, 10, 'A firewall can prevent specific apps from running on a computer.', 'true', 'false', 'n/a', 'n/a', 2),
(44, 10, 'Which best describes confidentiality with regards to network\r\nsecurity?', 'Ensures data is available for access by providing redundant systems.', 'Ensures data is not changed during transit between system.', 'Ensures data is kept secret using safeguards to prevent unauthorized access.', 'Ensures data is trusted and has not been tampered with or changed', 3),
(45, 10, 'Which component of the AAA service security model provides\r\nidentify verification?', 'Authentication', 'Accounting', 'Auditing', 'Authorization', 1),
(46, 10, 'When setting up a wireless network which security benefit is\r\nprovided by enabling WPA3?', 'Limits network access to only specified devices', 'Sends traffic through an encrypted tunnel', 'Secures authentication between client and access point', 'Makes it more difficult to discover wireless network', 3),
(47, 10, 'You generate a digital signature and attach it to a message', 'integrity', 'confidentiality', 'availability', 'n/a', 1),
(48, 10, 'You encrypt a sensitive email message', 'integrity', 'confidentiality', 'availability', 'n/a', 2),
(49, 10, 'You configure three redundant web servers at your company', 'integrity', 'confidentiality', 'availability', 'n/a', 3),
(50, 10, 'Specifying your name and password to log on to a service', 'knowledge', 'possession', 'inherence', 'n/a', 1),
(51, 10, 'Entering a one-time security code send to your device after logging in', 'knowledge', 'possession', 'inherence', 'n/a', 2),
(52, 10, 'Holding your phone to your face to be recognized', 'knowledge', 'possession', 'inherence', 'n/a', 3),
(53, 10, 'Uses a minimum of 40 bits for encryption', 'WEP', 'WEP2-Personal', 'WPA-Enterprise', 'n/a', 1),
(54, 10, 'Use a RADIU Server for authentication', 'WEP', 'WEP2-Personal', 'WPA-Enterprise', 'n/a', 3),
(55, 10, 'Use AES and a pre-shared key for authentication', 'WEP', 'WEP2-Personal', 'WPA-Enterprise', 'n/a', 2),
(56, 10, 'You want to prevent users from using the pushbutton method for accessing the.', 'Disable SSID broadcasting', 'set the security mode to WPA2-PSK', 'Disable WPS', 'n/a', 1),
(57, 10, 'You want devices to use a pre-shared key when\r\nconnecting to the network.', 'Disable SSID broadcasting', 'set the security mode to WPA2-PSK', 'Disable WPS', 'n/a', 2),
(58, 10, 'You want to prevent devices from discovering the\r\nname of the WIFI network', 'Disable SSID broadcasting', 'set the security mode to WPA2-PSK', 'Disable WPS', 'n/a', 3),
(59, 10, 'Which component of the AAA service security model provides\r\nidentity verification?', 'Authorization', 'Auditing', 'Authentication', 'Accounting', 3),
(60, 10, 'Which wireless security option uses a pre-shared key to\r\nauthenticate clients?', 'WPA2-Personal', '802.1x', '802.1q', 'WPA2-Enterprise', 1),
(61, 11, 'You need to connect a computer\'s network adapter to a switch\r\nusing a 1000BASE-T cable. Which connector should you use?', 'Coax', 'RJ-11', 'OS2 LC', 'RJ-45', 4),
(62, 11, 'Which type of connector should you use to terminate unshielded\r\ntwisted pair (UTP) cable?', 'ST (Straight Tip)', 'SC (Subscriber Connector)', 'RJ-45 (Registered Jack 45)', 'OS2 LC', 3),
(63, 11, 'You want to store files that will be accessible by every user on your\r\nnetwork. Which endpoint device do you need?', 'Access point', 'Server', 'Hub', 'Switch', 2),
(64, 11, 'Which standard contains the specifications for Wi-Fi networks?', 'GSM', 'LTE', 'IEEE 802.11', 'IEEE 802.3', 3),
(65, 11, 'Which device is an Internet of Things (IoT) device?', 'An internet-accessible thermostat', 'A video streaming server', 'A virtual private network concentrator', 'A Cloud-based file storage array', 1),
(66, 11, 'Which network technology is not impacted by electromagnetic and\r\nradio wave interference?', 'Wireless', 'Twisted Pair', 'Fiber', 'Copper', 3),
(67, 11, 'A cisco switch is not accessible from the network. You need to view\r\nits running configuration. Which out of band method can you use\r\nto access it?', 'SSH', 'SNMP', 'Console', 'Telnet', 3),
(68, 11, 'A local company requires two networks in two new buildings. The\r\naddresses used in these networks must be in the private network\r\nrange. Which two address ranges should the company use?\r\n(Choose 2.)', '172.16.0.0 to 172.31.255.255', '192.16.0.0 to 192.16.255.255', '11.0.0.0 to 11.255.255.255', '192.168.0.0 to 192.168.255.255', 1),
(69, 11, 'Which piecesof information should you include when\r\nyou initially create a support ticket?', 'A detailed description of the fault', 'Details about the computers connected to the network', 'The description of the top-down fault-finding procedure', 'The actions taken to resolve the fault', 2),
(70, 11, 'Which piecesof information should you include when\r\nyou initially create a support ticket?', 'A detailed description of the fault', 'A description of the conditions when the fault occurs', 'The description of the top-down fault-finding procedure', 'The actions taken to resolve the fault', 2),
(71, 11, 'A help desk technician receives the four trouble tickets listed\r\nbelow. Which ticket should receive the highest priority and be\r\naddressed first?', 'Ticket 1: A user requests relocation of a printer to a different network jack in the same office. The jack must be patched and made active.', 'Ticket 2: An online webinar is taking place in the conference room. The video conferencing equipment lost internet access.', 'Ticket 3: A user reports that response time for a cloud-based application is slower than usual', 'Ticket 4: Two users report that wireless access in the cafeteria has been down for the last hour.', 1),
(72, 11, 'An engineer configured a new VLAN named VLAN2 for the Data\r\nCenter team. When the team tries to ping addresses outside\r\nVLAN2 from a computer in VLAN2, they are unable to reach them.\r\nWhat should the engineer configure?', 'Additional VLAN', 'Default route', 'Default gateway', 'Static route', 3),
(73, 11, 'You are a senior network administrator tasked with diagnosing intermittent connectivity\r\nissues on the executive floor of a multinational corporation, which primarily uses iOS\r\ndevices. After initial checks, you suspect that the problem may be related to SSID settings\r\nand network configuration specifics not aligning correctly with the corporate security\r\nprotocols. Given the high-security requirements and the exclusive use of iOS devices on\r\nthis floor, which approach should you take to verify and rectify the network settings\r\ndirectly on the affected devices?', 'Network Reset', 'Manual Configuration', 'Use Fing', 'SSID Reconfiguration', 2),
(74, 11, 'You are a senior network administrator tasked with diagnosing intermittent connectivity\r\nissues on the executive floor of a multinational corporation, which primarily uses iOS\r\ndevices. After initial checks, you suspect that the problem may be related to SSID settings\r\nand network configuration specifics not aligning correctly with the corporate security\r\nprotocols. Given the high-security requirements and the exclusive use of iOS devices on\r\nthis floor, which approach should you take to verify and rectify the network settings\r\ndirectly on the affected devices?', 'Network Reset', 'Manual Configuration', 'Use Fing', 'SSID Reconfiguration', 2),
(75, 11, 'A support technician examines the front panel of a Cisco switch\r\nand sees 4 Ethernet cables connected in the first four ports. Port\r\n1,2 and 3 have a green LED. Port 4 has a blinking green light. What\r\nis the state of the Port 4?', 'Link is up and not stable', 'Link is up and there is no activity', 'Link is up with cable malfunctions', 'Link is up and active', 4),
(76, 11, 'A user reports a problem connecting to network resources. Other users\r\nconnected to the same switch are not experiencing the same problem. The\r\nuser\'s computer is patched to a switch port Gi0/15. The status indicator for\r\nthis port is blinking alternately green then amber. What does the light\r\npattern indicate about the status of port Gi0/15?', 'The port is administratively shutdown', 'The port is experiencing a high rate of errors.', 'The port is blocked by a firewall rule.', 'The port is not connected to a powered -on device.', 2),
(77, 11, 'A support technician examines the front panel of a Cisco switch\r\nand sees 4 Ethernet cables connected in the first four ports. Ports\r\n1, 2, and 3 have a green LED. Port 4 has a blinking green light.', 'Link is up with cable malfunctions.', 'Link is up and not stable.', 'Link is up and active.', 'Link is up and there is no activity', 3),
(78, 11, 'What is the purpose of assigning an IP address to the management\r\nVLAN interface on a Layer 2 switch?', 'To enable the switch to act as a default gateway for the attached devices', 'To enable the switch to resolve URLs for the attached the devices', 'To enable the switch to provide DHCP services to other switches in the network', 'To enable access to the CLI on the switch through Telnet or SSH', 4),
(79, 11, 'A network administrator can successfully ping the URL\r\nwww.cisco.com, but cannot ping a corporate server located at a\r\nremote branch in another city. You need to identify the specific\r\nrouter where packets are being dropped in the path to the remote\r\nbranch. Which utility should you use?', 'Traceroute', 'Netstat', 'telnet', 'ipconfig', 1),
(80, 11, 'In a network with multiple VLANs, a user is unable to communicate\r\nwith other users in the same VLAN but can communicate with\r\nusers in different VLANs. Which of the following could be the cause\r\nof this issue?', 'user\'s switchport is not configured as an access port', 'user\'s switchport is not assigned to the correct VLAN.', 'user\'s switchport is configured with the wrong duplex setting.', 'user\'s switchport is experiencing a spanning tree loop.', 2),
(81, 12, 'In a network with multiple VLANs, a user is unable to communicate\r\nwith other users in the same VLAN but can communicate with\r\nusers in different VLANs. Which of the following could be the cause\r\nof this issue?', 'user\'s switchport is not assigned to the correct VLAN', 'user\'s switchport is not configured as an access port.', 'user\'s switchport is configured with the wrong duplex setting.', 'user\'s switchport is experiencing a spanning tree loop.', 1),
(82, 12, 'A user initiates a trouble ticket stating that an external web page is\r\nnot loading. You determine that other resources both internal and\r\nexternal are still reachable. Which command can you use to help\r\nlocate where the issue is in the network path to the external web\r\npage?', 'ping -t', 'tracert', 'ipconfig/all', 'Nslookup', 2),
(83, 12, 'kaya mo na ba', 'yes', 'no', 'maybe', 'yesn\'t', 4);

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `quiz_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `quiz_name`, `created_at`) VALUES
(7, '35342352345', '2026-07-14 07:29:19'),
(8, 'networking boss 1', '2026-07-14 07:52:07'),
(9, 'networking boss 2', '2026-07-14 08:14:40'),
(10, 'networking boss 3', '2026-07-14 08:52:17'),
(11, 'networking boss 4', '2026-07-14 09:02:20'),
(12, 'networking boss 5', '2026-07-14 09:03:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
