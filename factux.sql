-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Ven 17 Février 2012 à 17:52
-- Version du serveur: 5.0.51
-- Version de PHP: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `factux-work`
--

-- --------------------------------------------------------

--
-- Structure de la table `factux_article`
--

CREATE TABLE IF NOT EXISTS `factux_article` (
  `num` int(10) NOT NULL auto_increment,
  `article` varchar(40) NOT NULL default '0',
  `variete` varchar(40) NOT NULL,
  `taille` varchar(40) NOT NULL,
  `conditionnement` varchar(40) NOT NULL default '',
  `contenance` varchar(40) NOT NULL default '',
  `prix_ttc_part` float NOT NULL default '0',
  `prix_htva_part` float NOT NULL default '0',
  `taux_tva_part` float default '0',
  `prix_htva` float NOT NULL default '0',
  `taux_tva` float default '0',
  `commentaire` varchar(30) NOT NULL default '0',
  `uni` varchar(5) NOT NULL default '',
  `actif` varchar(5) NOT NULL default '',
  `stock` float(15,2) NOT NULL default '0.00',
  `stomin` float(15,2) NOT NULL default '0.00',
  `stomax` float(15,2) NOT NULL default '0.00',
  `cat` varchar(10) NOT NULL default '',
  `phyto` varchar(10) NOT NULL,
  `prix_achat` float NOT NULL default '0',
  `stock_disponible` float(15,2) NOT NULL default '0.00',
  `localisation` varchar(40) NOT NULL,
  `groupe_varietal` varchar(40) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY  (`num`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `factux_bon_comm`
--

CREATE TABLE IF NOT EXISTS `factux_bon_comm` (
  `num_bon` int(30) NOT NULL auto_increment,
  `client_num` varchar(10) collate latin1_general_ci NOT NULL default '',
  `date` date NOT NULL default '0000-00-00',
  `tot_htva` float(20,2) NOT NULL default '0.00',
  `tot_tva` float(20,2) NOT NULL default '0.00',
  `fact` varchar(4) collate latin1_general_ci NOT NULL default '0',
  `coment` varchar(200) collate latin1_general_ci NOT NULL default '',
  `isPro` int(10) NOT NULL default '1',
  PRIMARY KEY  (`num_bon`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `factux_categorie`
--

CREATE TABLE IF NOT EXISTS `factux_categorie` (
  `id_cat` int(11) NOT NULL auto_increment,
  `categorie` varchar(30) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`id_cat`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `factux_categorie_sav`
--

CREATE TABLE IF NOT EXISTS `factux_categorie_sav` (
  `id_cat` int(11) NOT NULL auto_increment,
  `categorie` varchar(30) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`id_cat`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `factux_client`
--

CREATE TABLE IF NOT EXISTS `factux_client` (
  `num_client` int(10) NOT NULL auto_increment,
  `nom` varchar(30) collate latin1_general_ci NOT NULL default '',
  `nom2` varchar(30) collate latin1_general_ci NOT NULL default '',
  `rue` varchar(30) collate latin1_general_ci NOT NULL default '',
  `ville` varchar(30) collate latin1_general_ci NOT NULL default '',
  `cp` varchar(5) collate latin1_general_ci NOT NULL default '',
  `num_tva` varchar(30) collate latin1_general_ci NOT NULL default '',
  `login` varchar(10) collate latin1_general_ci NOT NULL default '',
  `pass` varchar(40) collate latin1_general_ci NOT NULL default '',
  `mail` varchar(30) collate latin1_general_ci NOT NULL default '',
  `actif` varchar(5) collate latin1_general_ci NOT NULL default '',
  `permi` varchar(255) collate latin1_general_ci NOT NULL default '',
  `civ` varchar(15) collate latin1_general_ci NOT NULL default '',
  `tel` varchar(30) collate latin1_general_ci NOT NULL default '',
  `fax` varchar(30) collate latin1_general_ci NOT NULL default '',
  `type` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`num_client`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `factux_cont_bon`
--

CREATE TABLE IF NOT EXISTS `factux_cont_bon` (
  `num` int(30) NOT NULL auto_increment,
  `bon_num` varchar(30) collate latin1_general_ci NOT NULL default '',
  `num_lot` varchar(15) collate latin1_general_ci NOT NULL default '',
  `article_num` varchar(30) collate latin1_general_ci NOT NULL default '',
  `quanti` double NOT NULL default '0',
  `remise` double NOT NULL default '0',
  `tot_art_htva` float(20,2) NOT NULL default '0.00',
  `to_tva_art` float(20,2) NOT NULL default '0.00',
  `p_u_jour` float(20,2) NOT NULL default '0.00',
  `conditionnement` varchar(40) collate latin1_general_ci NOT NULL,
  `volume_pot` double NOT NULL,
  `p_u_jour_net` float(20,2) NOT NULL default '0.00',
  PRIMARY KEY  (`num`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `factux_cont_dev`
--

CREATE TABLE IF NOT EXISTS `factux_cont_dev` (
  `num` int(30) NOT NULL auto_increment,
  `dev_num` varchar(30) collate latin1_general_ci NOT NULL default '',
  `article_num` varchar(30) collate latin1_general_ci NOT NULL default '',
  `quanti` double NOT NULL default '0',
  `tot_art_htva` float(20,2) NOT NULL default '0.00',
  `to_tva_art` float(20,2) NOT NULL default '0.00',
  `p_u_jour_net` float NOT NULL default '0',
  `p_u_jour` float(20,2) NOT NULL default '0.00',
  `remise` double NOT NULL,
  `conditionnement` varchar(40) collate latin1_general_ci NOT NULL,
  `volume_pot` double NOT NULL,
  PRIMARY KEY  (`num`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `factux_cont_lot`
--

CREATE TABLE IF NOT EXISTS `factux_cont_lot` (
  `num` int(15) NOT NULL auto_increment,
  `num_lot` int(10) NOT NULL default '0',
  `ingr` varchar(20) collate latin1_general_ci NOT NULL default '',
  `fourn` varchar(15) collate latin1_general_ci default '',
  `fourn_lot` varchar(20) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`num`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `factux_depense`
--

CREATE TABLE IF NOT EXISTS `factux_depense` (
  `num` int(11) NOT NULL auto_increment,
  `date` date NOT NULL default '0000-00-00',
  `lib` varchar(50) collate latin1_general_ci NOT NULL default '',
  `fournisseur` varchar(30) collate latin1_general_ci NOT NULL default '',
  `prix` float(10,2) NOT NULL default '0.00',
  PRIMARY KEY  (`num`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `factux_devis`
--

CREATE TABLE IF NOT EXISTS `factux_devis` (
  `num_dev` int(30) NOT NULL auto_increment,
  `client_num` varchar(10) collate latin1_general_ci NOT NULL default '',
  `date` date NOT NULL default '0000-00-00',
  `tot_htva` float(20,2) NOT NULL default '0.00',
  `tot_tva` float(20,2) NOT NULL default '0.00',
  `resu` varchar(4) collate latin1_general_ci NOT NULL default '0',
  `coment` varchar(200) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`num_dev`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `factux_facture`
--

CREATE TABLE IF NOT EXISTS `factux_facture` (
  `num` int(11) NOT NULL auto_increment,
  `date_deb` date NOT NULL default '0000-00-00',
  `date_fin` date NOT NULL default '0000-00-00',
  `CLIENT` varchar(30) collate latin1_general_ci NOT NULL default '',
  `payement` varchar(15) collate latin1_general_ci NOT NULL default 'non',
  `date_fact` date NOT NULL default '0000-00-00',
  `total_fact_h` float(20,2) NOT NULL default '0.00',
  `total_fact_ttc` float(20,2) NOT NULL default '0.00',
  `r1` varchar(10) collate latin1_general_ci NOT NULL default 'non',
  `r2` varchar(10) collate latin1_general_ci NOT NULL default 'non',
  `r3` varchar(10) collate latin1_general_ci NOT NULL default 'non',
  `coment` varchar(200) collate latin1_general_ci NOT NULL default '',
  `acompte` float(10,2) NOT NULL default '0.00',
  `list_num` mediumtext collate latin1_general_ci NOT NULL,
  `date_depart` varchar(40) collate latin1_general_ci NOT NULL,
  `date_echeance` varchar(40) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`num`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `factux_lot`
--

CREATE TABLE IF NOT EXISTS `factux_lot` (
  `num` int(10) NOT NULL auto_increment,
  `prod` varchar(25) collate latin1_general_ci NOT NULL default '',
  `actif` char(3) collate latin1_general_ci NOT NULL default '0',
  `date` date default '0000-00-00',
  PRIMARY KEY  (`num`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `factux_payement`
--

CREATE TABLE IF NOT EXISTS `factux_payement` (
  `num` int(10) NOT NULL auto_increment,
  `num_fact` varchar(30) collate latin1_general_ci NOT NULL default '',
  `pay` varchar(4) collate latin1_general_ci NOT NULL default '',
  `date_pay` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`num`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `factux_user`
--

CREATE TABLE IF NOT EXISTS `factux_user` (
  `num` int(10) NOT NULL auto_increment,
  `login` varchar(10) collate latin1_general_ci NOT NULL default '',
  `nom` varchar(20) collate latin1_general_ci NOT NULL default '',
  `prenom` varchar(20) collate latin1_general_ci NOT NULL default '',
  `pwd` varchar(40) collate latin1_general_ci NOT NULL default '',
  `email` varchar(30) collate latin1_general_ci NOT NULL default '',
  `dev` char(1) collate latin1_general_ci NOT NULL default 'n',
  `com` char(1) collate latin1_general_ci NOT NULL default 'n',
  `fact` char(1) collate latin1_general_ci NOT NULL default 'n',
  `admin` char(1) collate latin1_general_ci NOT NULL default 'n',
  `dep` char(1) collate latin1_general_ci NOT NULL default 'n',
  `stat` char(1) collate latin1_general_ci NOT NULL default 'n',
  `art` char(1) collate latin1_general_ci NOT NULL default 'n',
  `cli` char(1) collate latin1_general_ci NOT NULL default 'n',
  PRIMARY KEY  (`num`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `factux_user`
--

/*!40000 ALTER TABLE `factux_user` DISABLE KEYS */;
INSERT INTO `factux_user` VALUES (1,'admin','admin','admin','21232f297a57a5a743894a0e4a801fc3','admin@mail.com','y','y','y','y','y','y','y','y');
/*!40000 ALTER TABLE `factux_user` ENABLE KEYS */;
