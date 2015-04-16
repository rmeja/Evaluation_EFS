SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `evalefs`
--

-- --------------------------------------------------------

--
-- Structure de la table `etapes`
--

CREATE TABLE IF NOT EXISTS `etapes` (
  `cod_etp` varchar(6) NOT NULL,
  `cod_vrs_vet` varchar(3) NOT NULL,
  `lic_etp` varchar(40) NOT NULL,
  `lib_etp` varchar(60) NOT NULL,
  `lib_web_vet` varchar(80) NOT NULL COMMENT 'libelle long à utiliser pour select resp parcours',
  PRIMARY KEY (`cod_etp`,`cod_vrs_vet`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='étapes à utiliser pour correspondance utilisateurs_etapes';

-- --------------------------------------------------------

--
-- Structure de la table `evaluation`
--

CREATE TABLE IF NOT EXISTS `evaluation` (
  `cod_etu` int(8) NOT NULL,
  `cod_etp` varchar(6) NOT NULL,
  `cod_vrs_vet` varchar(3) NOT NULL,
  `login_uti` varchar(11) DEFAULT NULL,
  `condition1` varchar(20) NOT NULL,
  `condition2` varchar(20) NOT NULL,
  `motif2` text,
  `condition3` varchar(20) DEFAULT NULL,
  `motif3` text,
  `directeur_choix` varchar(20) DEFAULT NULL,
  `datetime_modif` int(20) NOT NULL,
  UNIQUE KEY `cod_etu` (`cod_etu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `individu`
--

CREATE TABLE IF NOT EXISTS `individu` (
  `cod_etu` int(8) NOT NULL,
  `lib_nom_pat_ind` varchar(32) NOT NULL,
  `lib_pr1_ind` varchar(32) NOT NULL,
  `lib_nom_usu_ind` varchar(32) DEFAULT NULL,
  `dat_nai_ind` date NOT NULL,
  `cod_sex_etu` varchar(1) NOT NULL,
  `cod_dep_pay_nai` varchar(3) NOT NULL,
  `lib_dep_pay_nai` varchar(32) NOT NULL,
  `lib_vil_nai_etu` varchar(32) NOT NULL,
  `lib_ad1` varchar(40) NOT NULL,
  `lib_ad2` varchar(40) DEFAULT NULL,
  `lib_ad3` varchar(40) DEFAULT NULL,
  `cod_bdi` varchar(40) NOT NULL,
  `lib_com` varchar(10) NOT NULL,
  `cod_pay` varchar(3) NOT NULL,
  `lib_pay` varchar(40) NOT NULL,
  `login_etu` varchar(10) NOT NULL,
  `mail_etu` varchar(80) NOT NULL,
  UNIQUE KEY `cod_etu` (`cod_etu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `individu_etape`
--

CREATE TABLE IF NOT EXISTS `individu_etape` (
  `cod_etu` int(8) NOT NULL,
  `cod_anu` int(4) NOT NULL,
  `cod_dip` varchar(7) NOT NULL,
  `lic_dip` varchar(20) NOT NULL,
  `cod_vrs_vdi` varchar(3) NOT NULL,
  `cod_etp` varchar(6) NOT NULL,
  `lic_etp` varchar(20) NOT NULL,
  `lib_etp` varchar(60) NOT NULL,
  `cod_vrs_vet` varchar(3) NOT NULL,
  `lib_web_vet` varchar(80) NOT NULL,
  `tem_iae_prm` varchar(1) NOT NULL,
  `cod_cge` varchar(3) NOT NULL,
  `lic_cge` varchar(20) NOT NULL,
  `cod_cmp` varchar(3) NOT NULL,
  `lib_cmp` varchar(80) NOT NULL,
  PRIMARY KEY (`cod_etu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `login_uti` varchar(10) NOT NULL,
  `password_uti` varchar(120) DEFAULT NULL,
  `roles_uti` varchar(60) DEFAULT 'ROLE_ENSEIGNANT',
  `nom_uti` varchar(32) NOT NULL,
  `prenom_uti` varchar(32) NOT NULL,
  `profil_uti` varchar(8) NOT NULL,
  `composante` varchar(200) NOT NULL,
  `mail_uti` varchar(80) NOT NULL,
  PRIMARY KEY (`login_uti`),
  UNIQUE KEY `login_uti` (`login_uti`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs_etapes`
--

CREATE TABLE IF NOT EXISTS `utilisateurs_etapes` (
  `login` varchar(10) NOT NULL,
  `profil_etape` varchar(3) NOT NULL COMMENT 'profil pour l''étape',
  `cod_etp` varchar(6) NOT NULL,
  `LIC_ETP` varchar(80) NOT NULL,
  UNIQUE KEY `login_3` (`login`,`cod_etp`),
  KEY `login` (`login`),
  KEY `login_2` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Utilisateurs avec accès étapes ';

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `evaluation`
--
ALTER TABLE `evaluation`
  ADD CONSTRAINT `contrainte_cod_etu` FOREIGN KEY (`cod_etu`) REFERENCES `individu` (`cod_etu`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
