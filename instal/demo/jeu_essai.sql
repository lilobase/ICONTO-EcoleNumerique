
INSERT INTO dbuser
	( id_dbuser, login_dbuser, password_dbuser, enabled_dbuser, email_dbuser )
VALUES
	( '2', 'jean', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '3', 'lucie', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '4', 'marc', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '5', 'luc', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '6', 'irene', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '7', 'eric', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '8', 'jules', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '9', 'fanny', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '10', 'charlotte', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '11', 'adeline', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '12', 'pfranc', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '13', 'mmeyer', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '14', 'dkutz', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '15', 'fgilet', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '16', 'mbraton', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '17', 'alenaick', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '18', 'mlenaick', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '19', 'tlany', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '20', 'jkheira', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '21', 'ckheira', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '22', 'jmvadia', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '23', 'okonul', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '24', 'adanuta', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '25', 'mmaira', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '26', 'amihel', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '27', 'jvacken', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '28', 'rvurgul', 'e10adc3949ba59abbe56e057f20f883e', '1', '' );

INSERT INTO `kernel_bu_annee_scolaire` VALUES (2006, '2006-2007', '2006-09-04', '2007-08-31', 0);
INSERT INTO `kernel_bu_annee_scolaire` VALUES (2007, '2007-2008', '2007-09-04', '2008-06-30', 1);


INSERT INTO `kernel_bu_civilite` VALUES (1, 'Monsieur', 'M');
INSERT INTO `kernel_bu_civilite` VALUES (2, 'Madame', 'Mme');
INSERT INTO `kernel_bu_civilite` VALUES (3, 'Mademoiselle', 'Mlle');

INSERT INTO `kernel_bu_classe_niveau` VALUES (1, 'Toute petite section', 1, 'TPS');
INSERT INTO `kernel_bu_classe_niveau` VALUES (2, 'Petite section', 1, 'PS');
INSERT INTO `kernel_bu_classe_niveau` VALUES (3, 'Moyenne section', 1, 'MS');
INSERT INTO `kernel_bu_classe_niveau` VALUES (4, 'Grande section', 1, 'GS');
INSERT INTO `kernel_bu_classe_niveau` VALUES (5, 'Cours préparatoire', 2, 'CP');
INSERT INTO `kernel_bu_classe_niveau` VALUES (6, 'Cours élémentaire 1er année', 2, 'CE1');
INSERT INTO `kernel_bu_classe_niveau` VALUES (7, 'Cours élémentaire 2ème année', 3, 'CE2');
INSERT INTO `kernel_bu_classe_niveau` VALUES (8, 'Cours moyen 1er année', 3, 'CM1');
INSERT INTO `kernel_bu_classe_niveau` VALUES (9, 'Cours moyen 2ème année', 3, 'CM2');

INSERT INTO `kernel_bu_classe_type` VALUES (11, 'Ordinaire');
INSERT INTO `kernel_bu_classe_type` VALUES (12, 'CLAD');
INSERT INTO `kernel_bu_classe_type` VALUES (13, 'CLIS');
INSERT INTO `kernel_bu_classe_type` VALUES (31, 'CLIN');
INSERT INTO `kernel_bu_classe_type` VALUES (24, 'Groupe d''enseignement');
INSERT INTO `kernel_bu_classe_type` VALUES (32, 'Regroupement d''adaptation');
INSERT INTO `kernel_bu_classe_type` VALUES (33, 'Autre');

INSERT INTO `kernel_bu_cycle` VALUES (1, 'Maternelle');
INSERT INTO `kernel_bu_cycle` VALUES (2, 'Cycle 2');
INSERT INTO `kernel_bu_cycle` VALUES (3, 'Cycle 3');

INSERT INTO `kernel_bu_eleve_etat` VALUES (1, 'Admission');
INSERT INTO `kernel_bu_eleve_etat` VALUES (2, 'Admissibilité');
INSERT INTO `kernel_bu_eleve_etat` VALUES (3, 'Radiation');
INSERT INTO `kernel_bu_eleve_etat` VALUES (4, 'Non fréquentation');

INSERT INTO `kernel_bu_geo_departements` VALUES ('82', '01', 'Ain', 'AIN', '01053', 'Bourg-en-Bresse', 'BOURG-EN-BRESSE', 4, 43, 419, 471019, 515270);
INSERT INTO `kernel_bu_geo_departements` VALUES ('22', '02', 'Aisne', 'AISNE', '02408', 'Laon', 'LAON', 5, 42, 816, 537259, 535489);
INSERT INTO `kernel_bu_geo_departements` VALUES ('83', '03', 'Allier', 'ALLIER', '03190', 'Moulins', 'MOULINS', 3, 35, 320, 357710, 344721);
INSERT INTO `kernel_bu_geo_departements` VALUES ('93', '04', 'Alpes-de-Haute-Provence', 'ALPES-DE-HAUTE-PROVENCE', '04070', 'Digne-les-Bains', 'DIGNE-LES-BAINS', 4, 30, 200, 130883, 139561);
INSERT INTO `kernel_bu_geo_departements` VALUES ('93', '05', 'Hautes-Alpes', 'HAUTES-ALPES', '05061', 'Gap', 'GAP', 2, 30, 177, 113300, 121419);
INSERT INTO `kernel_bu_geo_departements` VALUES ('93', '06', 'Alpes-Maritimes', 'ALPES-MARITIMES', '06088', 'Nice', 'NICE', 2, 52, 163, 971829, 1011326);
INSERT INTO `kernel_bu_geo_departements` VALUES ('82', '07', 'Ardèche', 'ARDECHE', '07186', 'Privas', 'PRIVAS', 3, 33, 339, 277581, 286023);
INSERT INTO `kernel_bu_geo_departements` VALUES ('21', '08', 'Ardennes', 'ARDENNES', '08105', 'Charleville-Mézières', 'CHARLEVILLE-MEZIERES', 4, 37, 463, 296357, 290130);
INSERT INTO `kernel_bu_geo_departements` VALUES ('73', '09', 'Ariège', 'ARIEGE', '09122', 'Foix', 'FOIX', 3, 22, 332, 136455, 137205);
INSERT INTO `kernel_bu_geo_departements` VALUES ('21', '10', 'Aube', 'AUBE', '10387', 'Troyes', 'TROYES', 3, 33, 433, 289207, 292131);
INSERT INTO `kernel_bu_geo_departements` VALUES ('91', '11', 'Aude', 'AUDE', '11069', 'Carcassonne', 'CARCASSONNE', 3, 35, 438, 298712, 309770);
INSERT INTO `kernel_bu_geo_departements` VALUES ('73', '12', 'Aveyron', 'AVEYRON', '12202', 'Rodez', 'RODEZ', 3, 46, 304, 270141, 263808);
INSERT INTO `kernel_bu_geo_departements` VALUES ('93', '13', 'Bouches-du-Rhône', 'BOUCHES-DU-RHONE', '13055', 'Marseille', 'MARSEILLE', 4, 53, 119, 1759371, 1835719);
INSERT INTO `kernel_bu_geo_departements` VALUES ('25', '14', 'Calvados', 'CALVADOS', '14118', 'Caen', 'CAEN', 4, 49, 705, 618478, 648385);
INSERT INTO `kernel_bu_geo_departements` VALUES ('83', '15', 'Cantal', 'CANTAL', '15014', 'Aurillac', 'AURILLAC', 3, 27, 260, 158723, 150778);
INSERT INTO `kernel_bu_geo_departements` VALUES ('54', '16', 'Charente', 'CHARENTE', '16015', 'Angoulême', 'ANGOULEME', 3, 35, 404, 341993, 339628);
INSERT INTO `kernel_bu_geo_departements` VALUES ('54', '17', 'Charente-Maritime', 'CHARENTE-MARITIME', '17300', 'Rochelle (La)', 'ROCHELLE (LA)', 5, 51, 472, 527146, 557024);
INSERT INTO `kernel_bu_geo_departements` VALUES ('24', '18', 'Cher', 'CHER', '18033', 'Bourges', 'BOURGES', 3, 35, 290, 321559, 314428);
INSERT INTO `kernel_bu_geo_departements` VALUES ('74', '19', 'Corrèze', 'CORREZE', '19272', 'Tulle', 'TULLE', 3, 37, 286, 237908, 232576);
INSERT INTO `kernel_bu_geo_departements` VALUES ('26', '21', 'Côte-d''Or', 'COTE-D''OR', '21231', 'Dijon', 'DIJON', 3, 43, 707, 493866, 506755);
INSERT INTO `kernel_bu_geo_departements` VALUES ('53', '22', 'Côtes-d''Armor', 'COTES-D''ARMOR', '22278', 'Saint-Brieuc', 'SAINT-BRIEUC', 4, 52, 372, 538395, 542373);
INSERT INTO `kernel_bu_geo_departements` VALUES ('74', '23', 'Creuse', 'CREUSE', '23096', 'Guéret', 'GUERET', 2, 27, 260, 131349, 124470);
INSERT INTO `kernel_bu_geo_departements` VALUES ('72', '24', 'Dordogne', 'DORDOGNE', '24322', 'Périgueux', 'PERIGUEUX', 4, 50, 557, 386365, 388293);
INSERT INTO `kernel_bu_geo_departements` VALUES ('43', '25', 'Doubs', 'DOUBS', '25056', 'Besançon', 'BESANCON', 3, 35, 594, 484770, 499062);
INSERT INTO `kernel_bu_geo_departements` VALUES ('82', '26', 'Drôme', 'DROME', '26362', 'Valence', 'VALENCE', 3, 36, 369, 414072, 437778);
INSERT INTO `kernel_bu_geo_departements` VALUES ('23', '27', 'Eure', 'EURE', '27229', 'Évreux', 'EVREUX', 3, 43, 675, 513818, 541054);
INSERT INTO `kernel_bu_geo_departements` VALUES ('24', '28', 'Eure-et-Loire', 'EURE-ET-LOIRE', '28085', 'Chartres', 'CHARTRES', 4, 29, 403, 396073, 407665);
INSERT INTO `kernel_bu_geo_departements` VALUES ('53', '29', 'Finistère', 'FINISTERE', '29232', 'Quimper', 'QUIMPER', 4, 54, 283, 838687, 852418);
INSERT INTO `kernel_bu_geo_departements` VALUES ('94', '2A', 'Corse-du-Sud', 'CORSE-DU-SUD', '2A004', 'Ajaccio', 'AJACCIO', 2, 22, 124, 118808, 118593);
INSERT INTO `kernel_bu_geo_departements` VALUES ('94', '2B', 'Haute-Corse', 'HAUTE-CORSE', '2B033', 'Bastia', 'BASTIA', 3, 30, 236, 131563, 141603);
INSERT INTO `kernel_bu_geo_departements` VALUES ('91', '30', 'Gard', 'GARD', '30189', 'Nîmes', 'NIMES', 3, 46, 353, 585049, 623125);
INSERT INTO `kernel_bu_geo_departements` VALUES ('73', '31', 'Haute-Garonne', 'HAUTE-GARONNE', '31555', 'Toulouse', 'TOULOUSE', 3, 53, 588, 925962, 1046338);
INSERT INTO `kernel_bu_geo_departements` VALUES ('73', '32', 'Gers', 'GERS', '32013', 'Auch', 'AUCH', 3, 31, 463, 174587, 172335);
INSERT INTO `kernel_bu_geo_departements` VALUES ('72', '33', 'Gironde', 'GIRONDE', '33063', 'Bordeaux', 'BORDEAUX', 5, 63, 542, 1213499, 1287334);
INSERT INTO `kernel_bu_geo_departements` VALUES ('91', '34', 'Hérault', 'HERAULT', '34172', 'Montpellier', 'MONTPELLIER', 3, 49, 343, 794603, 896441);
INSERT INTO `kernel_bu_geo_departements` VALUES ('53', '35', 'Ille-et-Vilaine', 'ILLE-ET-VILAINE', '35238', 'Rennes', 'RENNES', 4, 53, 352, 798718, 867533);
INSERT INTO `kernel_bu_geo_departements` VALUES ('24', '36', 'Indre', 'INDRE', '36044', 'Châteauroux', 'CHATEAUROUX', 4, 26, 247, 237510, 231139);
INSERT INTO `kernel_bu_geo_departements` VALUES ('24', '37', 'Indre-et-Loire', 'INDRE-ET-LOIRE', '37261', 'Tours', 'TOURS', 3, 37, 277, 529345, 554003);
INSERT INTO `kernel_bu_geo_departements` VALUES ('82', '38', 'Isère', 'ISERE', '38185', 'Grenoble', 'GRENOBLE', 3, 58, 533, 1016228, 1094006);
INSERT INTO `kernel_bu_geo_departements` VALUES ('43', '39', 'Jura', 'JURA', '39300', 'Lons-le-Saunier', 'LONS-LE-SAUNIER', 3, 34, 545, 248759, 250857);
INSERT INTO `kernel_bu_geo_departements` VALUES ('72', '40', 'Landes', 'LANDES', '40192', 'Mont-de-Marsan', 'MONT-DE-MARSAN', 2, 30, 331, 311461, 327334);
INSERT INTO `kernel_bu_geo_departements` VALUES ('24', '41', 'Loir-et-Cher', 'LOIR-ET-CHER', '41018', 'Blois', 'BLOIS', 3, 30, 291, 305937, 314968);
INSERT INTO `kernel_bu_geo_departements` VALUES ('82', '42', 'Loire', 'LOIRE', '42218', 'Saint-Étienne', 'SAINT-ETIENNE', 3, 40, 327, 746288, 728524);
INSERT INTO `kernel_bu_geo_departements` VALUES ('83', '43', 'Haute-Loire', 'HAUTE-LOIRE', '43157', 'Puy-en-Velay (Le)', 'PUY-EN-VELAY (LE)', 3, 35, 260, 206568, 209113);
INSERT INTO `kernel_bu_geo_departements` VALUES ('52', '44', 'Loire-Atlantique', 'LOIRE-ATLANTIQUE', '44109', 'Nantes', 'NANTES', 4, 59, 221, 1052183, 1134266);
INSERT INTO `kernel_bu_geo_departements` VALUES ('24', '45', 'Loiret', 'LOIRET', '45234', 'Orléans', 'ORLEANS', 3, 41, 334, 580612, 618126);
INSERT INTO `kernel_bu_geo_departements` VALUES ('73', '46', 'Lot', 'LOT', '46042', 'Cahors', 'CAHORS', 3, 31, 340, 155816, 160197);
INSERT INTO `kernel_bu_geo_departements` VALUES ('72', '47', 'Lot-et-Garonne', 'LOT-ET-GARONNE', '47001', 'Agen', 'AGEN', 4, 40, 318, 305989, 305380);
INSERT INTO `kernel_bu_geo_departements` VALUES ('91', '48', 'Lozère', 'LOZERE', '48095', 'Mende', 'MENDE', 2, 25, 185, 72825, 73509);
INSERT INTO `kernel_bu_geo_departements` VALUES ('52', '49', 'Maine-et-Loire', 'MAINE-ET-LOIRE', '49007', 'Angers', 'ANGERS', 4, 41, 363, 705882, 732942);
INSERT INTO `kernel_bu_geo_departements` VALUES ('25', '50', 'Manche', 'MANCHE', '50502', 'Saint-Lô', 'SAINT-LO', 4, 52, 602, 479636, 481471);
INSERT INTO `kernel_bu_geo_departements` VALUES ('21', '51', 'Marne', 'MARNE', '51108', 'Châlons-en-Champagne', 'CHALONS-EN-CHAMPAGNE', 5, 44, 619, 558217, 565229);
INSERT INTO `kernel_bu_geo_departements` VALUES ('21', '52', 'Haute-Marne', 'HAUTE-MARNE', '52121', 'Chaumont', 'CHAUMONT', 3, 32, 432, 204067, 194873);
INSERT INTO `kernel_bu_geo_departements` VALUES ('52', '53', 'Mayenne', 'MAYENNE', '53130', 'Laval', 'LAVAL', 3, 32, 261, 278037, 285338);
INSERT INTO `kernel_bu_geo_departements` VALUES ('41', '54', 'Meurthe-et-Moselle', 'MEURTHE-ET-MOSELLE', '54395', 'Nancy', 'NANCY', 4, 44, 594, 711943, 713779);
INSERT INTO `kernel_bu_geo_departements` VALUES ('41', '55', 'Meuse', 'MEUSE', '55029', 'Bar-le-Duc', 'BAR-LE-DUC', 3, 31, 498, 196223, 192198);
INSERT INTO `kernel_bu_geo_departements` VALUES ('53', '56', 'Morbihan', 'MORBIHAN', '56260', 'Vannes', 'VANNES', 3, 42, 261, 619838, 643873);
INSERT INTO `kernel_bu_geo_departements` VALUES ('41', '57', 'Moselle', 'MOSELLE', '57463', 'Metz', 'METZ', 9, 51, 730, 1011302, 1023447);
INSERT INTO `kernel_bu_geo_departements` VALUES ('26', '58', 'Nièvre', 'NIEVRE', '58194', 'Nevers', 'NEVERS', 4, 32, 312, 233278, 225198);
INSERT INTO `kernel_bu_geo_departements` VALUES ('31', '59', 'Nord', 'NORD', '59350', 'Lille', 'LILLE', 6, 79, 652, 2531855, 2555020);
INSERT INTO `kernel_bu_geo_departements` VALUES ('22', '60', 'Oise', 'OISE', '60057', 'Beauvais', 'BEAUVAIS', 4, 41, 693, 725603, 766441);
INSERT INTO `kernel_bu_geo_departements` VALUES ('25', '61', 'Orne', 'ORNE', '61001', 'Alençon', 'ALENCON', 3, 40, 505, 293204, 292337);
INSERT INTO `kernel_bu_geo_departements` VALUES ('31', '62', 'Pas-de-Calais', 'PAS-DE-CALAIS', '62041', 'Arras', 'ARRAS', 7, 77, 894, 1433203, 1441568);
INSERT INTO `kernel_bu_geo_departements` VALUES ('83', '63', 'Puy-de-Dôme', 'PUY-DE-DOME', '63113', 'Clermont-Ferrand', 'CLERMONT-FERRAND', 5, 61, 470, 598213, 604266);
INSERT INTO `kernel_bu_geo_departements` VALUES ('72', '64', 'Pyrénées-Atlantiques', 'PYRENEES-ATLANTIQUES', '64445', 'Pau', 'PAU', 3, 52, 547, 578516, 600018);
INSERT INTO `kernel_bu_geo_departements` VALUES ('73', '65', 'Hautes-Pyrénées', 'HAUTES-PYRENEES', '65440', 'Tarbes', 'TARBES', 3, 34, 474, 224759, 222368);
INSERT INTO `kernel_bu_geo_departements` VALUES ('91', '66', 'Pyrénées-Orientales', 'PYRENEES-ORIENTALES', '66136', 'Perpignan', 'PERPIGNAN', 3, 31, 226, 363796, 392803);
INSERT INTO `kernel_bu_geo_departements` VALUES ('42', '67', 'Bas-Rhin', 'BAS-RHIN', '67482', 'Strasbourg', 'STRASBOURG', 7, 44, 526, 953053, 1026120);
INSERT INTO `kernel_bu_geo_departements` VALUES ('42', '68', 'Haut-Rhin', 'HAUT-RHIN', '68066', 'Colmar', 'COLMAR', 6, 31, 377, 671319, 708025);
INSERT INTO `kernel_bu_geo_departements` VALUES ('82', '69', 'Rhône', 'RHONE', '69123', 'Lyon', 'LYON', 2, 54, 293, 1508966, 1578869);
INSERT INTO `kernel_bu_geo_departements` VALUES ('43', '70', 'Haute-Saône', 'HAUTE-SAONE', '70550', 'Vesoul', 'VESOUL', 2, 32, 545, 229650, 229732);
INSERT INTO `kernel_bu_geo_departements` VALUES ('26', '71', 'Saône-et-Loire', 'SAONE-ET-LOIRE', '71270', 'Mâcon', 'MACON', 5, 57, 573, 559413, 544893);
INSERT INTO `kernel_bu_geo_departements` VALUES ('52', '72', 'Sarthe', 'SARTHE', '72181', 'Mans (Le)', 'MANS (LE)', 3, 40, 375, 513654, 529851);
INSERT INTO `kernel_bu_geo_departements` VALUES ('82', '73', 'Savoie', 'SAVOIE', '73065', 'Chambéry', 'CHAMBERY', 3, 37, 305, 348261, 373258);
INSERT INTO `kernel_bu_geo_departements` VALUES ('82', '74', 'Haute-Savoie', 'HAUTE-SAVOIE', '74010', 'Annecy', 'ANNECY', 4, 34, 294, 568286, 631679);
INSERT INTO `kernel_bu_geo_departements` VALUES ('11', '75', 'Paris', 'PARIS', '75056', 'Paris', 'PARIS', 1, 20, 1, 2152423, 2125246);
INSERT INTO `kernel_bu_geo_departements` VALUES ('23', '76', 'Seine-Maritime', 'SEINE-MARITIME', '76540', 'Rouen', 'ROUEN', 3, 69, 745, 1223429, 1239138);
INSERT INTO `kernel_bu_geo_departements` VALUES ('11', '77', 'Seine-et-Marne', 'SEINE-ET-MARNE', '77288', 'Melun', 'MELUN', 5, 43, 514, 1078166, 1193767);
INSERT INTO `kernel_bu_geo_departements` VALUES ('11', '78', 'Yvelines', 'YVELINES', '78646', 'Versailles', 'VERSAILLES', 4, 39, 262, 1307150, 1354304);
INSERT INTO `kernel_bu_geo_departements` VALUES ('54', '79', 'Deux-Sèvres', 'DEUX-SEVRES', '79191', 'Niort', 'NIORT', 3, 33, 307, 345965, 344392);
INSERT INTO `kernel_bu_geo_departements` VALUES ('22', '80', 'Somme', 'SOMME', '80021', 'Amiens', 'AMIENS', 4, 46, 783, 547825, 555551);
INSERT INTO `kernel_bu_geo_departements` VALUES ('73', '81', 'Tarn', 'TARN', '81004', 'Albi', 'ALBI', 2, 46, 324, 342723, 343402);
INSERT INTO `kernel_bu_geo_departements` VALUES ('73', '82', 'Tarn-et-Garonne', 'TARN-ET-GARONNE', '82121', 'Montauban', 'MONTAUBAN', 2, 30, 195, 200220, 206034);
INSERT INTO `kernel_bu_geo_departements` VALUES ('93', '83', 'Var', 'VAR', '83137', 'Toulon', 'TOULON', 3, 43, 153, 815449, 898441);
INSERT INTO `kernel_bu_geo_departements` VALUES ('93', '84', 'Vaucluse', 'VAUCLUSE', '84007', 'Avignon', 'AVIGNON', 3, 24, 151, 467075, 499685);
INSERT INTO `kernel_bu_geo_departements` VALUES ('52', '85', 'Vendée', 'VENDEE', '85191', 'Roche-sur-Yon (La)', 'ROCHE-SUR-YON (LA)', 3, 31, 282, 509356, 539664);
INSERT INTO `kernel_bu_geo_departements` VALUES ('54', '86', 'Vienne', 'VIENNE', '86194', 'Poitiers', 'POITIERS', 3, 38, 281, 380005, 399024);
INSERT INTO `kernel_bu_geo_departements` VALUES ('74', '87', 'Haute-Vienne', 'HAUTE-VIENNE', '87085', 'Limoges', 'LIMOGES', 3, 42, 201, 353593, 353893);
INSERT INTO `kernel_bu_geo_departements` VALUES ('41', '88', 'Vosges', 'VOSGES', '88160', 'Épinal', 'EPINAL', 3, 31, 515, 386258, 380952);
INSERT INTO `kernel_bu_geo_departements` VALUES ('26', '89', 'Yonne', 'YONNE', '89024', 'Auxerre', 'AUXERRE', 3, 42, 453, 323096, 333221);
INSERT INTO `kernel_bu_geo_departements` VALUES ('43', '90', 'Territoire de Belfort', 'TERRITOIRE DE BELFORT', '90010', 'Belfort', 'BELFORT', 1, 15, 102, 134097, 137408);
INSERT INTO `kernel_bu_geo_departements` VALUES ('11', '91', 'Essonne', 'ESSONNE', '91228', 'Évry', 'EVRY', 3, 42, 196, 1084824, 1134238);
INSERT INTO `kernel_bu_geo_departements` VALUES ('11', '92', 'Hauts-de-Seine', 'HAUTS-DE-SEINE', '92050', 'Nanterre', 'NANTERRE', 3, 45, 36, 1391658, 1428881);
INSERT INTO `kernel_bu_geo_departements` VALUES ('11', '93', 'Seine-Saint-Denis', 'SEINE-SAINT-DENIS', '93008', 'Bobigny', 'BOBIGNY', 3, 40, 40, 1381197, 1382861);
INSERT INTO `kernel_bu_geo_departements` VALUES ('11', '94', 'Val-de-Marne', 'VAL-DE-MARNE', '94028', 'Créteil', 'CRETEIL', 3, 49, 47, 1215538, 1227250);
INSERT INTO `kernel_bu_geo_departements` VALUES ('11', '95', 'Val-d''Oise', 'VAL-D''OISE', '95500', 'Pontoise', 'PONTOISE', 3, 39, 185, 1049598, 1105464);
INSERT INTO `kernel_bu_geo_departements` VALUES ('01', '971', 'Guadeloupe', 'GUADELOUPE', '97105', 'Basse-Terre', 'BASSE-TERRE', 3, 43, 34, 386987, 422496);
INSERT INTO `kernel_bu_geo_departements` VALUES ('02', '972', 'Martinique', 'MARTINIQUE', '97209', 'Fort-de-France', 'FORT-DE-FRANCE', 4, 45, 34, 359572, 381427);
INSERT INTO `kernel_bu_geo_departements` VALUES ('03', '973', 'Guyane', 'GUYANE', '97302', 'Cayenne', 'CAYENNE', 2, 19, 22, 114678, 157213);
INSERT INTO `kernel_bu_geo_departements` VALUES ('04', '974', 'Réunion', 'REUNION', '97411', 'Saint-Denis', 'SAINT-DENIS', 4, 49, 24, 597823, 706300);

INSERT INTO `kernel_bu_lien_parental` VALUES (1, 'Mère');
INSERT INTO `kernel_bu_lien_parental` VALUES (2, 'Père');
INSERT INTO `kernel_bu_lien_parental` VALUES (3, 'Frère');
INSERT INTO `kernel_bu_lien_parental` VALUES (4, 'Soeur');
INSERT INTO `kernel_bu_lien_parental` VALUES (5, 'Grand-père');
INSERT INTO `kernel_bu_lien_parental` VALUES (6, 'Grand-mère');
INSERT INTO `kernel_bu_lien_parental` VALUES (7, 'Oncle');
INSERT INTO `kernel_bu_lien_parental` VALUES (8, 'Tante');
INSERT INTO `kernel_bu_lien_parental` VALUES (9, 'Collatéraux');
INSERT INTO `kernel_bu_lien_parental` VALUES (10, 'Tuteur');
INSERT INTO `kernel_bu_lien_parental` VALUES (11, 'Inconnu');

INSERT INTO `kernel_bu_nationalite` VALUES (1, 'Française');
INSERT INTO `kernel_bu_nationalite` VALUES (2, 'Espagnole');
INSERT INTO `kernel_bu_nationalite` VALUES (3, 'Portugaise');
INSERT INTO `kernel_bu_nationalite` VALUES (4, 'Italienne');
INSERT INTO `kernel_bu_nationalite` VALUES (5, 'Autres nationalités de l''UE');
INSERT INTO `kernel_bu_nationalite` VALUES (6, 'Algérienne');
INSERT INTO `kernel_bu_nationalite` VALUES (7, 'Marocaine');
INSERT INTO `kernel_bu_nationalite` VALUES (8, 'Tunisienne');
INSERT INTO `kernel_bu_nationalite` VALUES (9, 'Autres nationalités d''Afrique');
INSERT INTO `kernel_bu_nationalite` VALUES (10, 'Turque');
INSERT INTO `kernel_bu_nationalite` VALUES (11, 'Cambodgienne, Laotienne, Vietnamienne');
INSERT INTO `kernel_bu_nationalite` VALUES (12, 'Autre');

INSERT INTO `kernel_bu_pays` VALUES (1, 'France');
INSERT INTO `kernel_bu_pays` VALUES (2, 'Espagne');
INSERT INTO `kernel_bu_pays` VALUES (3, 'Portugal');
INSERT INTO `kernel_bu_pays` VALUES (4, 'Italie');
INSERT INTO `kernel_bu_pays` VALUES (5, 'Allemagne');
INSERT INTO `kernel_bu_pays` VALUES (6, 'Algérie');
INSERT INTO `kernel_bu_pays` VALUES (7, 'Maroc');
INSERT INTO `kernel_bu_pays` VALUES (8, 'Tunisie');
INSERT INTO `kernel_bu_pays` VALUES (9, 'Sénégal');
INSERT INTO `kernel_bu_pays` VALUES (10, 'Turquie');
INSERT INTO `kernel_bu_pays` VALUES (11, 'Cambodge');
INSERT INTO `kernel_bu_pays` VALUES (12, 'Laos');
INSERT INTO `kernel_bu_pays` VALUES (13, 'Vietnam');

INSERT INTO `kernel_bu_pcs` VALUES (10, 'Agriculteurs exploitants');
INSERT INTO `kernel_bu_pcs` VALUES (21, 'Artisans');
INSERT INTO `kernel_bu_pcs` VALUES (22, 'Commerçants et assimilés');
INSERT INTO `kernel_bu_pcs` VALUES (23, 'Chef d''entreprise de dix salariés ou plus');
INSERT INTO `kernel_bu_pcs` VALUES (31, 'Professions libérales et assimilés');
INSERT INTO `kernel_bu_pcs` VALUES (33, 'Cadres de la fonction publique');
INSERT INTO `kernel_bu_pcs` VALUES (34, 'professeur et assimilé');
INSERT INTO `kernel_bu_pcs` VALUES (35, 'Professions de l''information, des arts et des spectacles');
INSERT INTO `kernel_bu_pcs` VALUES (37, 'Cadres administratifs et commerciaux d''entreprises');
INSERT INTO `kernel_bu_pcs` VALUES (38, 'Ingénieurs et cadres techniques d''entreprises');
INSERT INTO `kernel_bu_pcs` VALUES (42, 'Instituteurs et assimilés');
INSERT INTO `kernel_bu_pcs` VALUES (43, 'Professions intermédiaires de la santé et du travail social');
INSERT INTO `kernel_bu_pcs` VALUES (44, 'Clergé, religieux');
INSERT INTO `kernel_bu_pcs` VALUES (45, 'Professions intermédiaires administratives de la fonction publique');
INSERT INTO `kernel_bu_pcs` VALUES (46, 'Professions intermédiaires administratives et commerciales des entreprises');
INSERT INTO `kernel_bu_pcs` VALUES (47, 'Techniciens');
INSERT INTO `kernel_bu_pcs` VALUES (48, 'Contremaîtres');
INSERT INTO `kernel_bu_pcs` VALUES (52, 'Employés civils et agents de service de la fonction publique');
INSERT INTO `kernel_bu_pcs` VALUES (53, 'Policiers et militaires');
INSERT INTO `kernel_bu_pcs` VALUES (54, 'Employés administratifs d''entreprise');
INSERT INTO `kernel_bu_pcs` VALUES (55, 'Employés de commerce');
INSERT INTO `kernel_bu_pcs` VALUES (56, 'Personnels des services directs aux particuliers');
INSERT INTO `kernel_bu_pcs` VALUES (61, 'Ouvriers qualifiés');
INSERT INTO `kernel_bu_pcs` VALUES (66, 'Ouvriers non qualifiés');
INSERT INTO `kernel_bu_pcs` VALUES (69, 'Ouvriers agricoles');
INSERT INTO `kernel_bu_pcs` VALUES (71, 'Retraités agriculteurs exploitants');
INSERT INTO `kernel_bu_pcs` VALUES (72, 'Retraités artisans, commerçants, chefs d''entreprise');
INSERT INTO `kernel_bu_pcs` VALUES (73, 'Retraités cadres et professions intermédiaires');
INSERT INTO `kernel_bu_pcs` VALUES (76, 'Retraités employés et ouvriers');
INSERT INTO `kernel_bu_pcs` VALUES (81, 'Chômeurs n''ayant jamais travaillé');
INSERT INTO `kernel_bu_pcs` VALUES (82, 'Personnes diverses sans activité profesionnelle');
INSERT INTO `kernel_bu_pcs` VALUES (99, 'non renseignée (inconnue ou sans objet)');

INSERT INTO `kernel_bu_radiation` VALUES (1, 'Orientation en établissement spécialisé');
INSERT INTO `kernel_bu_radiation` VALUES (2, 'Passage de l''école maternelle à l''école élémentaire');
INSERT INTO `kernel_bu_radiation` VALUES (3, 'Autres passages induisant un changement d''école');
INSERT INTO `kernel_bu_radiation` VALUES (4, 'Passage en 6ième');

INSERT INTO `kernel_bu_situation_familiale` VALUES (1, 'Célibataire');
INSERT INTO `kernel_bu_situation_familiale` VALUES (2, 'Marié');
INSERT INTO `kernel_bu_situation_familiale` VALUES (3, 'Divorcé');
INSERT INTO `kernel_bu_situation_familiale` VALUES (4, 'Veuf');
INSERT INTO `kernel_bu_situation_familiale` VALUES (5, 'Concubinage');
INSERT INTO `kernel_bu_situation_familiale` VALUES (6, 'Séparé');
INSERT INTO `kernel_bu_situation_familiale` VALUES (7, 'Pacsé');

INSERT INTO `kernel_bu_ecole` VALUES (1, '', '', 'Elémentaire', 'Ecole du bois fleuri', '12', '', 'rue des fleurs', '', '67000', 'Granville', '01.02.99.33.44', 'http://', '', NULL, 0, 0, 1);
INSERT INTO `kernel_bu_ecole` VALUES (2, '', '', 'Maternelle', 'Maternelle Gutenberg', '4', '', 'boulevard des imprimeurs', '', '67100', 'Bourzac', '', 'http://', '', NULL, 0, 0, 1);
INSERT INTO `kernel_bu_ecole` VALUES (3, '', '', 'Primaire', 'Ecole Jules Ferry', '16', '', 'rue des écoliers', '', '67000', 'Granville', '', 'http://', '', NULL, 0, 0, 1);

INSERT INTO `kernel_bu_ecole_classe` VALUES (1, 1, 'CP bleu', 2007, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (2, 1, 'CE1 bleu', 2007, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (3, 1, 'CE2 vert', 2007, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (4, 1, 'CM1 rouge', 2007, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (5, 1, 'CM2 rose', 2007, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (6, 2, 'Année 1', 2007, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (7, 2, 'Année 2', 2007, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (8, 2, 'Année 3', 2007, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (9, 3, 'CP', 2007, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (10, 3, 'CE1', 2007, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (11, 3, 'CE2', 2007, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (12, 3, 'CM1', 2007, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (13, 3, 'CM2', 2007, 1, 0);

INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (1, 5, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (2, 6, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (3, 7, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (4, 8, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (5, 7, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (6, 2, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (7, 3, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (8, 4, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (9, 5, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (10, 6, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (11, 7, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (12, 8, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (13, 9, 11);

INSERT INTO `kernel_bu_eleve` VALUES (1, '20051229-105033-', NULL, 'Lenaick', NULL, 'Jean', '', '', 'Monsieur', 1, 'France', 1, '67', 'Strasbourg', '1999-02-12', NULL, '5', '', 'rue St Michel', '', '67000', 'Granville', 0, 1, 0, NULL, NULL, 0, NULL, NULL, NOW());
INSERT INTO `kernel_bu_eleve` VALUES (2, '20051229-105446-', NULL, 'Lany', NULL, 'Lucie', '', '', 'Mademoiselle', 2, 'France', 1, '68', 'Mulhouse', '2000-10-10', NULL, '63', '', 'Avenue de la Marseillaise', '', '67000', 'Granville', 0, 1, 0, NULL, NULL, 0, NULL, NULL, NOW());
INSERT INTO `kernel_bu_eleve` VALUES (3, '20051229-105909-', NULL, 'Kheira', NULL, 'Marc', '', '', 'Monsieur', 1, 'France', 1, '14', 'Caen', '1999-09-04', NULL, '15', '', 'rue des Roses', '', '67000', 'Granville', 0, 1, 0, NULL, NULL, 0, NULL, NULL, NOW());
INSERT INTO `kernel_bu_eleve` VALUES (4, '20051229-110723-', NULL, 'Valia', NULL, 'Luc', '', '', 'Monsieur', 1, 'France', 1, '67', 'Strasbourg', '1999-11-19', NULL, '36', '', 'boulevard Mirabeau', '', '67000', 'Granville', 0, 1, 0, NULL, NULL, 0, NULL, NULL, NOW());
INSERT INTO `kernel_bu_eleve` VALUES (5, '20051229-111144-', NULL, 'Konul', NULL, 'Irène', '', '', 'Mademoiselle', 2, 'France', 1, '67', 'Strasbourg', '2000-03-30', NULL, '1', '', 'rue de la course', '', '67000', 'Granville', 0, 1, 0, NULL, NULL, 0, NULL, NULL, NOW());
INSERT INTO `kernel_bu_eleve` VALUES (6, '20051229-111953-', NULL, 'Danuta', NULL, 'Eric', '', '', 'Monsieur', 1, 'France', 1, '67', 'Schiltigheim', '1998-01-10', NULL, '3', '', 'rue des prés', '', '67000', 'Granville', 0, 1, 0, NULL, NULL, 0, NULL, NULL, NOW());
INSERT INTO `kernel_bu_eleve` VALUES (7, '20051229-112139-', NULL, 'Maira', NULL, 'Jules', '', '', 'Monsieur', 1, 'France', 1, '67', 'Schiltigheim', '1999-04-14', NULL, '20', '', 'avenue de Colmar', '', '67000', 'Granville', 0, 1, 0, NULL, NULL, 0, NULL, NULL, NOW());
INSERT INTO `kernel_bu_eleve` VALUES (8, '20051229-112429-', NULL, 'Mihel', NULL, 'Fanny', '', '', 'Mademoiselle', 2, 'France', 1, '68', 'Mulhouse', '1997-02-15', NULL, '39', '', 'impasse des châteaux-forts', '', '67000', 'Granville', 0, 1, 0, NULL, NULL, 0, NULL, NULL, NOW());
INSERT INTO `kernel_bu_eleve` VALUES (9, '20051229-112550-', NULL, 'Vacken', NULL, 'Charlotte', '', '', 'Mademoiselle', 2, 'France', 1, '75', 'Paris', '1997-07-27', NULL, '183', '', 'rue Curie', '', '67000', 'Granville', 0, 1, 0, NULL, NULL, 0, NULL, NULL, NOW());
INSERT INTO `kernel_bu_eleve` VALUES (10, '20051229-112718-', NULL, 'Vurgul', NULL, 'Adeline', '', '', 'Mademoiselle', 2, 'France', 1, '66', 'Perpignan', '1996-12-19', NULL, '15', '', 'fossé des tanneurs', '', '67000', 'Granville', 0, 1, 0, NULL, NULL, 0, NULL, NULL, NOW());

INSERT INTO `kernel_bu_eleve_admission` VALUES (1, 8, 1, 2007, 0, 1, '2007-08-29', '2007-09-01', 0, 0);
INSERT INTO `kernel_bu_eleve_admission` VALUES (2, 9, 1, 2007, 0, 1, '2007-08-29', '2007-09-01', 0, 0);
INSERT INTO `kernel_bu_eleve_admission` VALUES (3, 10, 1, 2007, 0, 1, '2007-08-29', '2007-09-01', 0, 0);
INSERT INTO `kernel_bu_eleve_admission` VALUES (4, 3, 1, 2007, 0, 1, '2007-08-29', '2007-09-01', 0, 0);
INSERT INTO `kernel_bu_eleve_admission` VALUES (5, 5, 1, 2007, 0, 1, '2007-08-29', '2007-09-01', 0, 0);
INSERT INTO `kernel_bu_eleve_admission` VALUES (6, 2, 1, 2007, 0, 1, '2007-08-29', '2007-09-01', 0, 0);
INSERT INTO `kernel_bu_eleve_admission` VALUES (7, 1, 1, 2007, 0, 1, '2007-08-29', '2007-09-01', 0, 0);
INSERT INTO `kernel_bu_eleve_admission` VALUES (8, 7, 1, 2007, 0, 1, '2007-08-29', '2007-09-01', 0, 0);
INSERT INTO `kernel_bu_eleve_admission` VALUES (9, 6, 1, 2007, 0, 1, '2007-08-29', '2007-09-01', 0, 0);

INSERT INTO `kernel_bu_eleve_affectation` VALUES (1, 8, 2007, 3, 7, '2007-09-01', 1, 0);
INSERT INTO `kernel_bu_eleve_affectation` VALUES (2, 9, 2007, 3, 7, '2007-09-01', 1, 0);
INSERT INTO `kernel_bu_eleve_affectation` VALUES (3, 10, 2007, 3, 7, '2007-09-01', 1, 0);
INSERT INTO `kernel_bu_eleve_affectation` VALUES (4, 3, 2007, 1, 5, '2007-09-01', 1, 0);
INSERT INTO `kernel_bu_eleve_affectation` VALUES (5, 5, 2007, 1, 5, '2007-09-01', 1, 0);
INSERT INTO `kernel_bu_eleve_affectation` VALUES (6, 2, 2007, 1, 5, '2007-09-01', 1, 0);
INSERT INTO `kernel_bu_eleve_affectation` VALUES (7, 1, 2007, 1, 5, '2007-09-01', 1, 0);
INSERT INTO `kernel_bu_eleve_affectation` VALUES (8, 7, 2007, 1, 5, '2007-09-01', 1, 0);
INSERT INTO `kernel_bu_eleve_affectation` VALUES (9, 6, 2007, 3, 7, '2007-09-01', 1, 0);

INSERT INTO `kernel_bu_eleve_inscription` VALUES (1, 1, 2007, '2007-08-01', '2007-08-01', '2007-09-01', '2007-09-01', 1, 0, 0, 0, 0, 0, '0000-00-00', 0, 0, '0000-00-00', '0000-00-00', 0, '', 0, NULL, 1);
INSERT INTO `kernel_bu_eleve_inscription` VALUES (2, 2, 2007, '2007-08-01', '2007-08-01', '2007-09-01', '2007-09-01', 1, 0, 0, 0, 0, 0, '0000-00-00', 0, 0, '0000-00-00', '0000-00-00', 0, '', 0, NULL, 1);
INSERT INTO `kernel_bu_eleve_inscription` VALUES (3, 3, 2007, '2007-08-01', '2007-08-01', '2007-09-01', '2007-09-01', 1, 0, 0, 0, 0, 0, '0000-00-00', 0, 0, '0000-00-00', '0000-00-00', 0, '', 0, NULL, 1);
INSERT INTO `kernel_bu_eleve_inscription` VALUES (4, 4, 2007, '2007-08-01', '2007-08-01', '2007-09-01', '2007-09-01', 1, 0, 0, 0, 0, 0, '0000-00-00', 0, 0, '0000-00-00', '0000-00-00', 0, '', 0, NULL, 1);
INSERT INTO `kernel_bu_eleve_inscription` VALUES (5, 5, 2007, '2007-08-01', '2007-08-01', '2007-09-01', '2007-09-01', 1, 0, 0, 0, 0, 0, '0000-00-00', 0, 0, '0000-00-00', '0000-00-00', 0, '', 0, NULL, 1);
INSERT INTO `kernel_bu_eleve_inscription` VALUES (6, 6, 2007, '2007-08-01', '2007-08-01', '2007-09-01', '2007-09-01', 1, 0, 0, 0, 0, 0, '0000-00-00', 0, 0, '0000-00-00', '0000-00-00', 0, '', 0, NULL, 1);
INSERT INTO `kernel_bu_eleve_inscription` VALUES (7, 7, 2007, '2007-08-01', '2007-08-01', '2007-09-01', '2007-09-01', 1, 0, 0, 0, 0, 0, '0000-00-00', 0, 0, '0000-00-00', '0000-00-00', 0, '', 0, NULL, 1);
INSERT INTO `kernel_bu_eleve_inscription` VALUES (8, 8, 2007, '2007-08-01', '2007-08-01', '2007-09-01', '2007-09-01', 1, 0, 0, 0, 0, 0, '0000-00-00', 0, 0, '0000-00-00', '0000-00-00', 0, '', 0, NULL, 1);
INSERT INTO `kernel_bu_eleve_inscription` VALUES (9, 9, 2007, '2007-08-01', '2007-08-01', '2007-09-01', '2007-09-01', 1, 0, 0, 0, 0, 0, '0000-00-00', 0, 0, '0000-00-00', '0000-00-00', 0, '', 0, NULL, 1);
INSERT INTO `kernel_bu_eleve_inscription` VALUES (10, 10, 2007, '2007-08-01', '2007-08-01', '2007-09-01', '2007-09-01', 1, 0, 0, 0, 0, 0, '0000-00-00', 0, 0, '0000-00-00', '0000-00-00', 0, '', 0, NULL, 1);

INSERT INTO `kernel_bu_eleve_periscolaire` VALUES (1, 1, 1, 2007, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `kernel_bu_eleve_periscolaire` VALUES (2, 2, 1, 2007, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `kernel_bu_eleve_periscolaire` VALUES (3, 3, 1, 2007, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `kernel_bu_eleve_periscolaire` VALUES (4, 4, 1, 2007, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `kernel_bu_eleve_periscolaire` VALUES (5, 5, 1, 2007, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `kernel_bu_eleve_periscolaire` VALUES (6, 6, 1, 2007, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `kernel_bu_eleve_periscolaire` VALUES (7, 7, 1, 2007, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `kernel_bu_eleve_periscolaire` VALUES (8, 8, 1, 2007, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `kernel_bu_eleve_periscolaire` VALUES (9, 9, 1, 2007, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `kernel_bu_eleve_periscolaire` VALUES (10, 10, 1, 2007, 0, 0, 0, 0, 0, 0, 0, 0);

INSERT INTO `kernel_bu_groupe_villes` VALUES (1, 'Agglomération de démo', NOW());

INSERT INTO `kernel_bu_personnel` VALUES (1, 'Franc', '', 'Pierre', 'Monsieur', 1, NULL, '', '', '', '', '', '', '', '', '', '', '', '', 1, 1, NULL, NULL);
INSERT INTO `kernel_bu_personnel` VALUES (2, 'Meyer', '', 'Martine', 'Madame', 2, NULL, '', '', '', '', '', '', '', '', '', '', '', '', 1, 1, NULL, NULL);
INSERT INTO `kernel_bu_personnel` VALUES (3, 'Kutz', '', 'Dorothée', 'Mademoiselle', 2, NULL, '', '', '', '', '', '', '', '', '', '', '', '', 1, 1, NULL, NULL);
INSERT INTO `kernel_bu_personnel` VALUES (4, 'Gilet', '', 'Franck', 'Monsieur', 1, NULL, '', '', '', '', '', '', '', '', '', '', '', '', 1, 1, NULL, NULL);
INSERT INTO `kernel_bu_personnel` VALUES (5, 'Braton', '', 'Marc', 'Monsieur', 1, NULL, '', '', '', '', '', '', '', '', '', '', '', '', 1, 1, NULL, NULL);

INSERT INTO `kernel_bu_personnel_entite` VALUES (1, 1, 'ECOLE', 2);
INSERT INTO `kernel_bu_personnel_entite` VALUES (1, 1, 'CLASSE', 1);
INSERT INTO `kernel_bu_personnel_entite` VALUES (2, 1, 'ECOLE', 1);
INSERT INTO `kernel_bu_personnel_entite` VALUES (2, 1, 'CLASSE', 1);
INSERT INTO `kernel_bu_personnel_entite` VALUES (3, 1, 'ECOLE', 1);
INSERT INTO `kernel_bu_personnel_entite` VALUES (3, 3, 'CLASSE', 1);
INSERT INTO `kernel_bu_personnel_entite` VALUES (4, 1, 'ECOLE', 1);
INSERT INTO `kernel_bu_personnel_entite` VALUES (4, 2, 'CLASSE', 1);
INSERT INTO `kernel_bu_personnel_entite` VALUES (4, 4, 'CLASSE', 1);
INSERT INTO `kernel_bu_personnel_entite` VALUES (4, 5, 'CLASSE', 1);
INSERT INTO `kernel_bu_personnel_entite` VALUES (5, 1, 'VILLE', 4);

INSERT INTO `kernel_bu_responsable` VALUES (1, 'Lenaick', '', 'Albert', 'Monsieur', 1, 0, '', 2, '', '', '', '', '5', '', 'rue St Michel', '', '67000', 'Granville', 0);
INSERT INTO `kernel_bu_responsable` VALUES (2, 'Lenaick', '', 'Martine', 'Madame', 2, 0, '', 2, '', '', '', '', '5', '', 'rue St Michel', '', '67000', 'Granville', 0);
INSERT INTO `kernel_bu_responsable` VALUES (3, 'Lany', '', 'Thierry', 'Monsieur', 1, 0, '', 3, '', '', '', '', '63', '', 'Avenue de la Marseillaise', '', '67000', 'Granville', 0);
INSERT INTO `kernel_bu_responsable` VALUES (4, 'Kheira', '', 'José', 'Monsieur', 1, 0, '', 2, '', '', '', '', '15', '', 'rue des Roses', '', '67000', 'Granville', 0);
INSERT INTO `kernel_bu_responsable` VALUES (5, 'Kheira', '', 'Corinne', 'Madame', 2, 0, '', 2, '', '', '', '', '15', '', 'rue des Roses', '', '67000', 'Granville', 0);
INSERT INTO `kernel_bu_responsable` VALUES (6, 'Valia', '', 'Jean-Marc', 'Monsieur', 1, 33, '', 3, '', '', '', '', '36', '', 'boulevard Mirabeau', '', '67000', 'Granville', 0);
INSERT INTO `kernel_bu_responsable` VALUES (7, 'Konul', '', 'Odile', 'Madame', 2, 0, '', 4, '', '', '', '', '1', '', 'rue de la course', '', '67000', 'Granville', 0);
INSERT INTO `kernel_bu_responsable` VALUES (8, 'Danuta', '', 'Albert', 'Monsieur', 1, 0, '', 6, '', '', '', '', '3', '', 'rue des prés', '', '67000', 'Granville', 0);
INSERT INTO `kernel_bu_responsable` VALUES (9, 'Maira', '', 'Michelle', 'Madame', 2, 0, '', 6, '', '', '', '', '20', '', 'avenue de Colmar', '', '67000', 'Granville', 0);
INSERT INTO `kernel_bu_responsable` VALUES (10, 'Mihel', '', 'André', 'Monsieur', 1, 0, '', 1, '', '', '', '', '39', '', 'impasse des châteaux-forts', '', '67000', 'Granville', 0);
INSERT INTO `kernel_bu_responsable` VALUES (11, 'Vacken', '', 'Jocelyne', 'Madame', 2, 0, '', 1, '', '', '', '', '183', '', 'rue Curie', '', '67000', 'Granville', 0);
INSERT INTO `kernel_bu_responsable` VALUES (12, 'Vurgul', '', 'Robert', 'Monsieur', 1, 34, '', 1, '', '', '', '', '15', '', 'fossé des tanneurs', '', '67000', 'Granville', 0);

INSERT INTO `kernel_bu_responsables` VALUES (1, 1, 'eleve', 1, 'responsable', 1, 2);
INSERT INTO `kernel_bu_responsables` VALUES (2, 1, 'eleve', 2, 'responsable', 1, 1);
INSERT INTO `kernel_bu_responsables` VALUES (3, 2, 'eleve', 3, 'responsable', 1, 2);
INSERT INTO `kernel_bu_responsables` VALUES (4, 3, 'eleve', 4, 'responsable', 1, 2);
INSERT INTO `kernel_bu_responsables` VALUES (5, 3, 'eleve', 5, 'responsable', 1, 1);
INSERT INTO `kernel_bu_responsables` VALUES (6, 4, 'eleve', 6, 'responsable', 1, 2);
INSERT INTO `kernel_bu_responsables` VALUES (7, 5, 'eleve', 7, 'responsable', 1, 1);
INSERT INTO `kernel_bu_responsables` VALUES (8, 6, 'eleve', 8, 'responsable', 1, 2);
INSERT INTO `kernel_bu_responsables` VALUES (9, 7, 'eleve', 9, 'responsable', 1, 1);
INSERT INTO `kernel_bu_responsables` VALUES (10, 8, 'eleve', 10, 'responsable', 1, 2);
INSERT INTO `kernel_bu_responsables` VALUES (11, 9, 'eleve', 11, 'responsable', 1, 1);
INSERT INTO `kernel_bu_responsables` VALUES (12, 10, 'eleve', 12, 'responsable', 1, 2);

INSERT INTO `kernel_bu_ville` VALUES (1, 'Granville', 'granville', 1, NOW());
INSERT INTO `kernel_bu_ville` VALUES (2, 'Bourzac', 'bourzac', 1, NOW());

INSERT INTO `kernel_link_bu2user` VALUES (2, 'USER_ELE', 1);
INSERT INTO `kernel_link_bu2user` VALUES (3, 'USER_ELE', 2);
INSERT INTO `kernel_link_bu2user` VALUES (4, 'USER_ELE', 3);
INSERT INTO `kernel_link_bu2user` VALUES (5, 'USER_ELE', 4);
INSERT INTO `kernel_link_bu2user` VALUES (6, 'USER_ELE', 5);
INSERT INTO `kernel_link_bu2user` VALUES (7, 'USER_ELE', 6);
INSERT INTO `kernel_link_bu2user` VALUES (8, 'USER_ELE', 7);
INSERT INTO `kernel_link_bu2user` VALUES (9, 'USER_ELE', 8);
INSERT INTO `kernel_link_bu2user` VALUES (10, 'USER_ELE', 9);
INSERT INTO `kernel_link_bu2user` VALUES (11, 'USER_ELE', 10);
INSERT INTO `kernel_link_bu2user` VALUES (12, 'USER_ENS', 1);
INSERT INTO `kernel_link_bu2user` VALUES (13, 'USER_ENS', 2);
INSERT INTO `kernel_link_bu2user` VALUES (14, 'USER_ENS', 3);
INSERT INTO `kernel_link_bu2user` VALUES (15, 'USER_ENS', 4);
INSERT INTO `kernel_link_bu2user` VALUES (16, 'USER_VIL', 5);
INSERT INTO `kernel_link_bu2user` VALUES (17, 'USER_RES', 1);
INSERT INTO `kernel_link_bu2user` VALUES (18, 'USER_RES', 2);
INSERT INTO `kernel_link_bu2user` VALUES (19, 'USER_RES', 3);
INSERT INTO `kernel_link_bu2user` VALUES (20, 'USER_RES', 4);
INSERT INTO `kernel_link_bu2user` VALUES (21, 'USER_RES', 5);
INSERT INTO `kernel_link_bu2user` VALUES (22, 'USER_RES', 6);
INSERT INTO `kernel_link_bu2user` VALUES (23, 'USER_RES', 7);
INSERT INTO `kernel_link_bu2user` VALUES (24, 'USER_RES', 8);
INSERT INTO `kernel_link_bu2user` VALUES (25, 'USER_RES', 9);
INSERT INTO `kernel_link_bu2user` VALUES (26, 'USER_RES', 10);
INSERT INTO `kernel_link_bu2user` VALUES (27, 'USER_RES', 11);
INSERT INTO `kernel_link_bu2user` VALUES (28, 'USER_RES', 12);

INSERT INTO `module_minimail_from` VALUES (1, 1, 'Bienvenue !', 'Bonjour, et bienvenue au **personnel** sur Iconito.\r\n\r\nCe message est envoyé en guise de test, pour vous permetter de découvrir la fonctionnalité "Minimail". Vous pouvez simplement y répondre en cliquant sur le bouton vert.\r\n\r\nPour en savoir plus sur Iconito, n''hésitez pas à consulter le site [[http://www.iconito.fr|www.iconito.fr]].', 'dokuwiki', NOW(), NULL, NULL, NULL, 0);
INSERT INTO `module_minimail_from` VALUES (2, 1, 'Bienvenue !', 'Bonjour, et bienvenue aux **parents** sur Iconito.\r\n\r\nCe message est envoyé en guise de test, pour vous permetter de découvrir la fonctionnalité "Minimail". Vous pouvez simplement y répondre en cliquant sur le bouton vert.\r\n\r\nPour en savoir plus sur Iconito, n''hésitez pas à consulter le site [[http://www.iconito.fr|www.iconito.fr]].', 'dokuwiki', NOW(), NULL, NULL, NULL, 0);
INSERT INTO `module_minimail_from` VALUES (3, 13, 'Bonjour les enfants !', 'Bonjour les enfants,\r\n\r\nJe suis Martine Meyer, votre institutrice de votre classe (CP bleu). Je vous envoie ce message pour vous souhaiter la bienvenue sur **Iconito**. Amusez-vous bien mais n\'oubliez pas non plus de faire vos devoirs !', 'dokuwiki', NOW(), NULL, NULL, NULL, 0);
INSERT INTO `module_minimail_from` VALUES (4, 1, 'Salut Martine', 'Salut Martine, comment tu vas ? Tu t\'en sors avec Iconito ?', 'dokuwiki', NOW(), NULL, NULL, NULL, 0);

INSERT INTO `module_minimail_to` VALUES (1, 1, 12, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (2, 1, 13, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (3, 1, 14, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (4, 1, 15, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (5, 1, 16, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (6, 2, 17, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (7, 2, 18, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (8, 2, 19, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (9, 3, 20, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (10, 2, 21, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (11, 2, 22, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (12, 2, 23, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (13, 2, 24, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (14, 2, 25, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (15, 2, 26, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (16, 2, 27, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (17, 2, 28, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (18, 3, 2, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (19, 3, 3, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (20, 3, 4, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (21, 3, 6, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (22, 3, 8, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (23, 4, 13, '0000-00-00 00:00:00', 0, 0, 0);

INSERT INTO `kernel_mod_enabled` (`node_type`, `node_id`, `module_type`, `module_id`) VALUES
('BU_VILLE', 1, 'MOD_TELEPROCEDURES', 1);
INSERT INTO `module_teleprocedure` (`id`, `titre`, `date_creation`) VALUES
(1, 'Granville', '2009-01-26 10:26:51');
INSERT INTO `module_teleprocedure_type` (`idtype`, `nom`, `is_online`, `teleprocedure`, `format`, `texte_defaut`, `responsables`, `lecteurs`, mail_from, mail_to, mail_cc, mail_message) VALUES
(1, 'Bris de vitre', 1, 1, 'fckeditor', '<p>Nombre de vitres cass&eacute;es :</p>\r\n<p>Dimensions des vitres :</p>', 'mbraton', '', 'nobody@iconito.fr', 'service@grandville.fr', NULL, 'Voilà une demande à traiter, merci.');
INSERT INTO `module_teleprocedure_type_droit` (`idtype`, `user_type`, `user_id`, `droit`) VALUES
(1, 'USER_VIL', 5, 30);
INSERT INTO `module_teleprocedure_intervention` (`idinter`, `iduser`, `dateinter`, `idetabliss`, `objet`, `idtype`, `idstatu`, `datederniere`, `detail`, `responsables`, `lecteurs`, mail_from, mail_to, mail_cc, mail_message) VALUES
(1, 12, '2009-01-26', 1, 'Vitres brisées au gymnase', 1, 1, '2009-01-26 12:15:19', '<p>Nombre de vitres cass&eacute;es : 1</p>\r\n<p>Dimensions des vitres : 100x200cm</p>\r\n<p>C''est au gymnase, un ballon&nbsp;a heurt&eacute; la fen&ecirc;tre</p>', 'mbraton', '', 'nobody@iconito.fr', 'service@grandville.fr', NULL, 'Voilà une demande à traiter, merci.');
INSERT INTO `module_teleprocedure_intervention_droit` (`idinter`, `user_type`, `user_id`, `droit`) VALUES
(1, 'USER_VIL', 5, 30);

INSERT INTO `module_carnet_topics` VALUES (1, 'Préparation fête de l\'école', 'La fête de l''école aura lieu **jeudi 15 décembre 2007 à 16h**. L''école est heureuse de fêter en même temps ses 30 ans. A cette occasion, le maire viendra saluer l''équipe éducative, les parents et les enfants.\r\n\r\n{{ demo/fete-ecole-1.jpg}}\r\n\r\nUn stand de restauration sera proposé, de même que des stands de jeux. Les parents qui souhaitent nous aider sont les bienvenus, merci de nous signaler ce qui vous intéresse:\r\n\r\n    * aider à prérarer les salles\r\n    * tenir le stand restauration\r\n    * tenir les stands de jeux (jeu de massacre, poisson vole, finis ton assiette, loups garous)\r\n    * aider à nettoyer et démonter\r\n    * préparer gâteaux ou rapporter boissons\r\n\r\nA bientôt', 'dokuwiki', 13, 1, DATE_ADD(NOW(), INTERVAL -10 MINUTE));
INSERT INTO `module_carnet_topics` VALUES (2, 'Comportement de Lucie', 'Bonjour,\r\n\r\nLucie a fait beaucoup de progrès au niveau de son attention en classe. Toutefois, elle est très dissipée lorsqu''elle est assise à côté d''Irène. Je les ai séparées pour quelques temps.', 'dokuwiki', 13, 1, DATE_ADD(NOW(), INTERVAL -15 MINUTE));
INSERT INTO `module_carnet_topics` VALUES (3, 'Théâtre de marionnettes', 'Le groupe Les Doigts Malins présentera son dernier spectacle de marionnettes le **22 mars à 16h à l''Ill-aux-herbes**.\r\n\r\nCoût du spectacle: 3 euros (la coopérative prend en charge le complément de 5 euros).', 'dokuwiki', 13, 1, DATE_ADD(NOW(), INTERVAL -20 MINUTE));

INSERT INTO `module_carnet_messages` VALUES (1, 1, 1, 'Moi je suis prêt à apporter des boissons, je travaille chez Coca.', 'dokuwiki', 17, DATE_ADD(NOW(), INTERVAL -1 MINUTE));
INSERT INTO `module_carnet_messages` VALUES (2, 2, 2, 'Merci de m''avoir alerté sur le comportement de Lucie. Nous allons lui parler entre quatre z''yeux et j''espère qu''elle ne posera plus de problèmes.\r\n\r\nCordialement, Thierry Lany (papa de Lucie)', 'dokuwiki', 19, DATE_ADD(NOW(), INTERVAL -2 MINUTE));

INSERT INTO `module_carnet_topics_to` VALUES (1, 1);
INSERT INTO `module_carnet_topics_to` VALUES (1, 2);
INSERT INTO `module_carnet_topics_to` VALUES (1, 3);
INSERT INTO `module_carnet_topics_to` VALUES (1, 5);
INSERT INTO `module_carnet_topics_to` VALUES (1, 7);
INSERT INTO `module_carnet_topics_to` VALUES (2, 2);
INSERT INTO `module_carnet_topics_to` VALUES (3, 1);
INSERT INTO `module_carnet_topics_to` VALUES (3, 2);
INSERT INTO `module_carnet_topics_to` VALUES (3, 3);
INSERT INTO `module_carnet_topics_to` VALUES (3, 5);
INSERT INTO `module_carnet_topics_to` VALUES (3, 7);

INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'1', 'prefs', 'avatar', 'admin.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'16', 'prefs', 'avatar', 'mbraton.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'2', 'prefs', 'avatar', 'jean.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'12', 'prefs', 'avatar', 'pfranc.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'13', 'prefs', 'avatar', 'mmeyer.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'18', 'prefs', 'avatar', 'mlenaick.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'17', 'prefs', 'avatar', 'alenaick.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'4', 'prefs', 'avatar', 'marc.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'8', 'prefs', 'avatar', 'jules.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'6', 'prefs', 'avatar', 'irene.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'3', 'prefs', 'avatar', 'lucie.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'15', 'prefs', 'avatar', 'fgilet.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'14', 'prefs', 'avatar', 'dkutz.png' );

INSERT INTO `module_groupe_groupe` VALUES (1, 'Les volcans', 'Groupe de travail sur les volcans : débats, photos, discussions...', 1, 13, DATE_ADD(NOW(), INTERVAL -55 MINUTE));
INSERT INTO `kernel_link_groupe2node` VALUES (1, 'BU_CLASSE', 1);
INSERT INTO `kernel_link_user2node` VALUES ('USER_ENS', 2, 'CLUB', 1, 70, NULL, NULL);
INSERT INTO `kernel_link_user2node` VALUES ('USER_ELE', 1, 'CLUB', 1, 35, NULL, NULL);
INSERT INTO `kernel_link_user2node` VALUES ('USER_ELE', 2, 'CLUB', 1, 35, NULL, NULL);
INSERT INTO `kernel_link_user2node` VALUES ('USER_ELE', 3, 'CLUB', 1, 35, NULL, NULL);
INSERT INTO `kernel_link_user2node` VALUES ('USER_ELE', 4, 'CLUB', 1, 35, NULL, NULL);
INSERT INTO `kernel_link_user2node` VALUES ('USER_ELE', 5, 'CLUB', 1, 35, NULL, NULL);
INSERT INTO `kernel_link_user2node` VALUES ('USER_ELE', 6, 'CLUB', 1, 35, NULL, NULL);
INSERT INTO `kernel_link_user2node` VALUES ('USER_ELE', 7, 'CLUB', 1, 35, NULL, NULL);

INSERT INTO `kernel_mod_enabled` VALUES ('CLUB', 1, 'MOD_ALBUM', 1);
INSERT INTO `module_album_albums` VALUES (1, '', 'aa', DATE_ADD(NOW(), INTERVAL -1 MINUTE), 'be8550b87c', 0);
INSERT INTO `module_album_photos` VALUES (2, 1, 0, 'volcan_007.jpg', 'Une fois solidifiées, les projections dessinent un paysage de science fiction', NOW(), 'jpg', '96c16abb19', NULL);
INSERT INTO `module_album_photos` VALUES (3, 1, 0, 'volcan_005.jpg', 'Rien ne peut resister à des coulées de lave',  NOW(), 'jpg', '578c089900', NULL);
INSERT INTO `module_album_photos` VALUES (4, 1, 0, 'volcan_002.jpg', '',  NOW(), 'jpg', '80cac360e5', NULL);
INSERT INTO `module_album_photos` VALUES (5, 1, 0, 'volcan_004.jpg', '',  NOW(), 'jpg', '2b7c5fe806', NULL);
INSERT INTO `module_album_photos` VALUES (6, 1, 0, 'volcan_006.jpg', '',  NOW(), 'jpg', '2d6d81911a', NULL);
INSERT INTO `module_album_photos` VALUES (7, 1, 0, 'Volcans-Volcanos-2954.jpg', '',  NOW(), 'jpg', 'c4055b423d', NULL);
INSERT INTO `module_album_photos` VALUES (8, 1, 0, 'Volcans-Volcanos-3625.jpg', '',  NOW(), 'jpg', '5dc99647a0', NULL);
INSERT INTO `module_album_photos` VALUES (9, 1, 0, '696px-Spaccato_vulcano_ita.png', '', NOW(), 'png', '02213ee64e', NULL);

INSERT INTO `kernel_mod_enabled` VALUES ('CLUB', 1, 'MOD_BLOG', 1);
INSERT INTO `module_blog` VALUES (1, 'Les volcans', 1, '1_semeru_panache.png', 'Les_volcans', 0, 1, 1, 'POST', 'dokuwiki');
INSERT INTO `module_blog_articlecategory` VALUES (1, 1, 1, 'Général', 'General20061023151649');
INSERT INTO `module_blog_articlecategory` VALUES (2, 1, 2, 'Photos', 'Photos20061026163749');
INSERT INTO `module_blog_article` VALUES (1, 1, 'Photos de volcans', 'Voici quelques photos de volcans en activité.\r\n\r\n[[static/album/1_be8550b87c/3_578c089900.jpg|{{ static/album/1_be8550b87c/3_578c089900_240.jpg |volcan_005.jpg}}]]\r\n\r\n[[static/album/1_be8550b87c/6_2d6d81911a.jpg|{{ static/album/1_be8550b87c/6_2d6d81911a_240.jpg |volcan_006.jpg}}]]\r\n\r\n[[static/album/1_be8550b87c/4_80cac360e5.jpg|{{ static/album/1_be8550b87c/4_80cac360e5_240.jpg |volcan_002.jpg}}]]\r\n\r\n[[static/album/1_be8550b87c/5_2b7c5fe806.jpg|{{ static/album/1_be8550b87c/5_2b7c5fe806_240.jpg |volcan_004.jpg}}]]', '\n<p>\nVoici quelques photos de volcans en activité.\n</p>\n\n<p>\n<a href="static/album/1_be8550b87c/3_578c089900.jpg" class="media" target="_blank" title="static/album/1_be8550b87c/3_578c089900.jpg"  rel="nofollow"><img src="static/album/1_be8550b87c/3_578c089900_240.jpg" class="mediacenter" title="volcan_005.jpg" alt="volcan_005.jpg" /></a>\n</p>\n\n<p>\n<a href="static/album/1_be8550b87c/6_2d6d81911a.jpg" class="media" target="_blank" title="static/album/1_be8550b87c/6_2d6d81911a.jpg"  rel="nofollow"><img src="static/album/1_be8550b87c/6_2d6d81911a_240.jpg" class="mediacenter" title="volcan_006.jpg" alt="volcan_006.jpg" /></a>\n</p>\n\n<p>\n<a href="static/album/1_be8550b87c/4_80cac360e5.jpg" class="media" target="_blank" title="static/album/1_be8550b87c/4_80cac360e5.jpg"  rel="nofollow"><img src="static/album/1_be8550b87c/4_80cac360e5_240.jpg" class="mediacenter" title="volcan_002.jpg" alt="volcan_002.jpg" /></a>\n</p>\n\n<p>\n<a href="static/album/1_be8550b87c/5_2b7c5fe806.jpg" class="media" target="_blank" title="static/album/1_be8550b87c/5_2b7c5fe806.jpg"  rel="nofollow"><img src="static/album/1_be8550b87c/5_2b7c5fe806_240.jpg" class="mediacenter" title="volcan_004.jpg" alt="volcan_004.jpg" /></a>\n\n</p>\n', '', '', 'dokuwiki', 13, DATE_FORMAT(NOW(),'%Y%m%d'), DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -10 MINUTE),'%H%i'), '1-Photos_de_volcans', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (1, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (1, 2);
INSERT INTO `module_blog_article` VALUES (2, 1, 'Un volcan, comment ça marche ?', '[[static/album/1_be8550b87c/9_02213ee64e.png|{{ static/album/1_be8550b87c/9_02213ee64e_240.png|696px-Spaccato_vulcano_ita.png}}]]\r\nUn volcan est un relief terrestre, sous-marin ou extra-terrestre formé à la suite de l''éjection et de l''empilement de matériaux issus du manteau (sous forme de laves, cendres, etc). L''accumulation peut atteindre des milliers de mètres d''épaisseur formant ainsi des montagnes ou des îles. La nature des matériaux, le type d''éruption, leur fréquence et l''orogenèse donnent aux volcans des formes variées mais prenant en général l''aspect d''une montagne conique couronnée par un cratère ou une caldeira.\r\n\r\nLe lieu principal de sortie des matériaux lors d''une éruption se situe dans la plupart des cas au sommet du volcan, là où débouche la cheminée volcanique, mais il arrive que des ouvertures latérales apparaissent sur les flancs ou aux pieds du volcan.\r\n\r\nDeux grands types de volcans existent sur terre :\r\n  * les « **volcans rouges** » aux éruptions effusives relativement calmes et émettant des laves fluides sous la forme de coulées. Ce sont les volcans de « point chaud » et les volcans sous-marins des dorsales océaniques.\r\n  * les « **volcans gris** » aux éruptions explosives et émettant des laves pâteuses et des cendres sous la forme de nuées ardentes (ou coulées pyroclastiques) et de panaches volcaniques. Ils sont principalement associés au phénomène de subduction (par exemple les volcans de la « ceinture de feu du Pacifique »).\r\n\r\nOn compte environ 1 500 volcans terrestres actifs dont une soixantaine en éruption par an. Les volcans sous-marins sont bien plus nombreux.\r\n\r\nLe volcanisme est l''ensemble des phénomènes associés aux volcans et à la présence de magma. La volcanologie (ou vulcanologie) est la science de l''étude, de l''observation et de la prévention des risques des volcans.\r\n\r\nLe mot « volcan » tire son origine de Vulcano, une des Îles Éoliennes nommée en l''honneur de Vulcain, le dieu romain du feu. Son équivalent dans le panthéon grec est Héphaïstos.\r\n\r\nPour en savoir plus, je vous invite à lire l''article complet sur Wikipédia : [[http://fr.wikipedia.org/wiki/Volcan]]', '\n<p>\n<a href="static/album/1_be8550b87c/9_02213ee64e.png" class="media" target="_blank" title="static/album/1_be8550b87c/9_02213ee64e.png"  rel="nofollow"><img src="static/album/1_be8550b87c/9_02213ee64e_240.png" class="mediaright" align="right" title="696px-Spaccato_vulcano_ita.png" alt="696px-Spaccato_vulcano_ita.png" /></a>\nUn volcan est un relief terrestre, sous-marin ou extra-terrestre formé à la suite de l&#039;éjection et de l&#039;empilement de matériaux issus du manteau (sous forme de laves, cendres, etc). L&#039;accumulation peut atteindre des milliers de mètres d&#039;épaisseur formant ainsi des montagnes ou des îles. La nature des matériaux, le type d&#039;éruption, leur fréquence et l&#039;orogenèse donnent aux volcans des formes variées mais prenant en général l&#039;aspect d&#039;une montagne conique couronnée par un cratère ou une caldeira.\n</p>\n\n<p>\nLe lieu principal de sortie des matériaux lors d&#039;une éruption se situe dans la plupart des cas au sommet du volcan, là où débouche la cheminée volcanique, mais il arrive que des ouvertures latérales apparaissent sur les flancs ou aux pieds du volcan.\n</p>\n\n<p>\nDeux grands types de volcans existent sur terre :\n</p>\n<ul>\n<li class="level1"><div class="li"> les « <strong>volcans rouges</strong> » aux éruptions effusives relativement calmes et émettant des laves fluides sous la forme de coulées. Ce sont les volcans de « point chaud » et les volcans sous-marins des dorsales océaniques.</div>\n</li>\n<li class="level1"><div class="li"> les « <strong>volcans gris</strong> » aux éruptions explosives et émettant des laves pâteuses et des cendres sous la forme de nuées ardentes (ou coulées pyroclastiques) et de panaches volcaniques. Ils sont principalement associés au phénomène de subduction (par exemple les volcans de la « ceinture de feu du Pacifique »).</div>\n</li>\n</ul>\n\n<p>\n\nOn compte environ 1 500 volcans terrestres actifs dont une soixantaine en éruption par an. Les volcans sous-marins sont bien plus nombreux.\n</p>\n\n<p>\nLe volcanisme est l&#039;ensemble des phénomènes associés aux volcans et à la présence de magma. La volcanologie (ou vulcanologie) est la science de l&#039;étude, de l&#039;observation et de la prévention des risques des volcans.\n</p>\n\n<p>\nLe mot « volcan » tire son origine de Vulcano, une des Îles Éoliennes nommée en l&#039;honneur de Vulcain, le dieu romain du feu. Son équivalent dans le panthéon grec est Héphaïstos.\n</p>\n\n<p>\nPour en savoir plus, je vous invite à lire l&#039;article complet sur Wikipédia : <a href="http://fr.wikipedia.org/wiki/Volcan" class="urlextern" target="_blank" title="http://fr.wikipedia.org/wiki/Volcan"  rel="nofollow">http://fr.wikipedia.org/wiki/Volcan</a>\n\n</p>\n', '', '', 'dokuwiki', 13, DATE_FORMAT(NOW(),'%Y%m%d'), DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -20 MINUTE),'%H%i'), '2-Un_volcan_comment_ca_marche', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (2, 1);
INSERT INTO `module_blog_articlecomment` VALUES (1, 1, 2, 'Jean Lenaick', '', '', '127.0.0.1', DATE_FORMAT(NOW(),'%Y%m%d'), DATE_FORMAT(NOW(),'%H%i'), 'J''adore la deuxième !', 1);
INSERT INTO `module_blog_link` VALUES (1, 1, 1, 'Volcan sur Wikipédia', 'http://fr.wikipedia.org/wiki/Volcan');
INSERT INTO `module_blog_link` VALUES (2, 1, 2, 'Vulcania', 'http://www.vulcania.fr');

INSERT INTO `kernel_mod_enabled` VALUES ('CLUB', 1, 'MOD_FORUM', 1);
INSERT INTO `module_forum_forums` VALUES (1, '', DATE_ADD(NOW(), INTERVAL -30 MINUTE));
INSERT INTO `module_forum_topics` VALUES (1, 'Qui a déjà vu un volcan ?', 1, DATE_ADD(NOW(), INTERVAL -25 MINUTE), 13, 2, 0, 1, 2, 2, DATE_ADD(NOW(), INTERVAL -5 MINUTE));
INSERT INTO `module_forum_messages` VALUES (1, 1, 1, 13, 'Les enfants, avez-vous déjà vu un volcan ?\r\nDites-moi si vous avez vu des images de volcan en activité à la télé, ou sur Internet. Ou peut-être avez-vous déjà vu un vrai volcan !\r\n\r\nQuelles sont vos impressions sur ce phénomène, cela vous fait-il peur ?', 'dokuwiki', DATE_ADD(NOW(), INTERVAL -25 MINUTE), 1, 0);
INSERT INTO `module_forum_messages` VALUES (2, 1, 1, 2, 'J''ai juste vu des images à la télé, et ça fait peur, ça a l''air très très chaud, ça brûle tout ! Et surtout, je crois qu''on ne peut pas l''arrêter', 'dokuwiki', DATE_ADD(NOW(), INTERVAL -5 MINUTE), 1, 0);
        
INSERT INTO `kernel_mod_enabled` VALUES ('CLUB', 1, 'MOD_LISTE', 1);
INSERT INTO `module_liste_listes` VALUES (1, '', DATE_ADD(NOW(), INTERVAL -1 MINUTE));
INSERT INTO `module_liste_messages` VALUES (1, 1, 'Nouvel article sur le blog', 'Je vous écris pour vous signaler que j''ai rédigé un nouvel article expliquant le fonctionnement des volcans.\r\nPour lire l''article, cliquez sur le lien suivant :\r\n[lire l''article|index.php?module=blog&desc=default&action=default&blog=Les_volcans]\r\n\r\nBonne lecture !', NOW(), 13);
INSERT INTO `module_minimail_from` VALUES (5, 13, 'Nouvel article sur le blog', 'Je vous écris pour vous signaler que j''ai rédigé un nouvel article expliquant le fonctionnement des volcans. Pour lire l''article, cliquez sur le lien suivant : [[index.php?module=blog&desc=default&action=default&blog=Les_volcans|lire l''article]].\r\n\r\nBonne lecture !\n\n----\nInformation : ce message a été envoyé par l''intermédiaire du module "Liste de diffusion" du groupe de travail [[index.php?module=groupe&desc=default&action=go&id=1|Les volcans]] dont vous faites partie.', 'dokuwiki', NOW(), NULL, NULL, NULL, 0);
INSERT INTO `module_minimail_to` VALUES (24, 5, 18, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (25, 5, 2, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (26, 5, 3, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (27, 5, 4, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (28, 5, 5, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (29, 5, 6, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (30, 5, 7, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (31, 5, 8, '0000-00-00 00:00:00', 0, 0, 0);

INSERT INTO `kernel_mod_enabled` VALUES ('BU_ECOLE', 1, 'MOD_BLOG', 2);
INSERT INTO `module_blog` VALUES (2, 'Bienvenue sur le site de l''école du Bois Fleuri', 1, '2_ecole.jpg', 'Ecole_du_bois_fleuri', 0, 1, 0, 'POST', 'dokuwiki');
INSERT INTO `module_blog_articlecategory` VALUES (3, 2, 1, 'Infos pratiques', 'Infos_pratiques');
INSERT INTO `module_blog_articlecategory` VALUES (4, 2, 2, 'Actualités de l''école', 'Actualites_de_l_ecole');
INSERT INTO `module_blog_article` VALUES (3, 2, 'Sécurité routière', 'En complément du travail sur le thème de la route assuré par Mme RIGOULET, M. RIBOT, intervenant Sécurité Routière interviendra auprès des classes de CE2, CM1 et CM2 le **vendredi prochain** en salle Roger Chonwa selon le planning ci-dessous :\r\n\r\n^ horaire ^ classe ^ enseignants accompagnateurs ^ \r\n| 07 h 50 – 08 h 45 | CE2 | Mme Dorothée KUTZ  |\r\n| 08 h 45 – 09 h 40 | CM1 | M. Franck GILET  |\r\n| 09 h 55 – 10 h 50 | CM2 | M. Roger Kyzu  |\r\n\r\n\r\n', '\n<p>\nEn complément du travail sur le thème de la route assuré par Mme RIGOULET, M. RIBOT, intervenant Sécurité Routière interviendra auprès des classes de CE2, CM1 et CM2 le <strong>vendredi prochain</strong> en salle Roger Chonwa selon le planning ci-dessous :\n\n</p>\n<table class="inline">\n	<tr class="row0">\n		<th class="col0"> horaire </th><th class="col1"> classe </th><th class="col2"> enseignants accompagnateurs </th>\n	</tr>\n	<tr class="row1">\n		<td class="col0"> 07 h 50 – 08 h 45 </td><td class="col1"> CE2 </td><td class="col2 leftalign"> Mme Dorothée KUTZ  </td>\n	</tr>\n	<tr class="row2">\n		<td class="col0"> 08 h 45 – 09 h 40 </td><td class="col1"> CM1 </td><td class="col2 leftalign"> M. Franck GILET  </td>\n	</tr>\n	<tr class="row3">\n		<td class="col0"> 09 h 55 – 10 h 50 </td><td class="col1"> CM2 </td><td class="col2 leftalign"> M. Roger Kyzu  </td>\n	</tr>\n</table>\n', '', '', 'dokuwiki', 12, DATE_FORMAT(NOW(),'%Y%m%d'), '0849', '3-Securite_routiere', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (3, 4);
INSERT INTO `module_blog_article` VALUES (4, 2, 'Les horaires de l''école', '^ Jours ^ Matin ^ Après-Midi ^\r\n| Lundi | 8h00 à 11h30 | 13h30 à 16h00 |\r\n| Mardi | 8h00 à 11h30 | 13h30 à 16h00 |\r\n| Jeudi | 8h00 à 11h30 | 13h30 à 16h00 |\r\n| Vendredi | 8h00 à 11h30 | 13h30 à 16h00 |', '<table class="inline">\n	<tr class="row0">\n		<th class="col0"> Jours </th><th class="col1"> Matin </th><th class="col2"> Après-Midi </th>\n	</tr>\n	<tr class="row1">\n		<td class="col0"> Lundi </td><td class="col1"> 8h00 à 11h30 </td><td class="col2"> 13h30 à 16h00 </td>\n	</tr>\n	<tr class="row2">\n		<td class="col0"> Mardi </td><td class="col1"> 8h00 à 11h30 </td><td class="col2"> 13h30 à 16h00 </td>\n	</tr>\n	<tr class="row3">\n		<td class="col0"> Jeudi </td><td class="col1"> 8h00 à 11h30 </td><td class="col2"> 13h30 à 16h00 </td>\n	</tr>\n	<tr class="row4">\n		<td class="col0"> Vendredi </td><td class="col1"> 8h00 à 11h30 </td><td class="col2"> 13h30 à 16h00 </td>\n	</tr>\n</table>\n', '', '', 'dokuwiki', 12, DATE_FORMAT(NOW(),'%Y%m%d'), '0820', '4-Les_horaires_de_l_ecole', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (4, 3);
INSERT INTO `module_blog_article` VALUES (5, 2, 'Coordonnées de l''école', 'Notre adresse:\r\n  * 35 avenue truc\r\n  * 67400 CHONZU\r\n  * Tél 01 54 87 98 78\r\n  * Fax 01 54 78 98 79\r\n  * e-mail boisfleuri@iconito.fr\r\n  * Directeur : Pierre Franc\r\n', '\n<p>\nNotre adresse:\n</p>\n<ul>\n<li class="level1"><div class="li"> 35 avenue truc</div>\n</li>\n<li class="level1"><div class="li"> 67400 CHONZU</div>\n</li>\n<li class="level1"><div class="li"> Tél 01 54 87 98 78</div>\n</li>\n<li class="level1"><div class="li"> Fax 01 54 78 98 79</div>\n</li>\n<li class="level1"><div class="li"> e-mail boisfleuri@iconito.fr</div>\n</li>\n<li class="level1"><div class="li"> Directeur : Pierre Franc</div>\n</li>\n</ul>\n', '', '', 'dokuwiki', 12, DATE_FORMAT(NOW(),'%Y%m%d'), '0748', '5-Coordonnees_de_l_ecole', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (5, 3);
INSERT INTO `module_blog_fluxrss` VALUES (1, 2, 'PrimeTICE', 1, 'http://www.educnet.education.fr/bd/urtic/rss/primtice_rss.xml');
INSERT INTO `module_blog_link` VALUES (3, 2, 1, 'Académie de Versailles', 'http://www.ac-versailles.fr');
INSERT INTO `module_blog_link` VALUES (4, 2, 2, 'Educnet : Portail des TIC pour l''éducation', 'http://www.educnet.education.fr');

INSERT INTO `kernel_mod_enabled` VALUES ('BU_ECOLE', 1, 'MOD_AGENDA', 1);
INSERT INTO `module_agenda_agenda` VALUES (1, 'Ecole du bois fleuri', 'Ecole du bois fleuri', 30);
INSERT INTO `module_agenda_event` VALUES (3, 1, 'Fête de l''école', '', 'Ecole du Bois Fleuri', DATE_FORMAT(NOW(),'%Y%m%d'), '08:00', DATE_FORMAT(NOW(),'%Y%m%d'), '12:00', 0, 0, 0, 0, 0, NULL);
INSERT INTO `module_agenda_event` VALUES (4, 1, 'Vacances de la Toussaint', '', '', '20071026', '', '20071105', '', 1, 0, 0, 0, 0, NULL);
        
INSERT INTO `kernel_mod_enabled` VALUES ('BU_CLASSE', 1, 'MOD_AGENDA', 2);
INSERT INTO `module_agenda_agenda` VALUES (2, 'CP bleu', 'CP bleu', 20);
INSERT INTO `module_agenda_event` VALUES (1, 2, 'Sport', '', 'Gymnase Hugo', DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 1 DAY),'%Y%m%d'), '14:00', DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 1 DAY),'%Y%m%d'), '16:00', 0, 0, 1, 0, 0, DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 10 WEEK),'%Y%m%d'));
INSERT INTO `module_agenda_event` VALUES (2, 2, 'Ferme Fritsch', 'Visite de la ferme Fritsch. Prévoyez bottes et cirés !', 'Huttelsheim', DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 7 DAY),'%Y%m%d'), '08:00', DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 7 DAY),'%Y%m%d'), '09:00', 1, 0, 0, 0, 0, NULL);
INSERT INTO `module_agenda_lecon` VALUES (1, 2, 'Finir les dessins', DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 1 DAY),'%Y%m%d'));
INSERT INTO `module_agenda_lecon` VALUES (2, 2, 'Apporter des jeux', DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 2 DAY),'%Y%m%d'));


INSERT INTO `module_groupe_groupe` VALUES (2, 'La Tunisie', 'Discussions autour de la Tunisie : ses plages, ses habitants, ses monuments...', 1, 16, DATE_ADD(NOW(), INTERVAL -1 DAY));
INSERT INTO `kernel_link_groupe2node` VALUES (2, 'BU_VILLE', 1);
INSERT INTO `kernel_link_user2node` VALUES ('USER_VIL', 5, 'CLUB', 2, 70, NULL, NULL);

INSERT INTO `kernel_mod_enabled` VALUES ('CLUB', 2, 'MOD_ALBUM', 2);
INSERT INTO `module_album_albums` VALUES (2, '', 'aa', DATE_ADD(NOW(), INTERVAL -5 MINUTE), 'cf057489c9', 0);
INSERT INTO `module_album_photos` VALUES (10, 2, 0, 'EPSN0007.jpg', '', NOW(), 'jpg', 'bf31503e53', NULL);
INSERT INTO `module_album_photos` VALUES (11, 2, 0, 'EPSN0124.jpg', '', NOW(), 'jpg', '52f0518564', NULL);
INSERT INTO `module_album_photos` VALUES (12, 2, 0, 'EPSN0134.jpg', '', NOW(), 'jpg', '0bd77e356a', NULL);
INSERT INTO `module_album_photos` VALUES (13, 2, 0, 'EPSN0146.jpg', '', NOW(), 'jpg', 'c696a3ce60', NULL);
INSERT INTO `module_album_photos` VALUES (14, 2, 0, 'EPSN0189.jpg', '', NOW(), 'jpg', '58a12ff6df', NULL);
INSERT INTO `module_album_photos` VALUES (15, 2, 0, 'EPSN0191.jpg', '', NOW(), 'jpg', '20c4f6aff7', NULL);
INSERT INTO `module_album_photos` VALUES (16, 2, 0, 'EPSN0202.jpg', '', NOW(), 'jpg', '5431069bb7', NULL);
INSERT INTO `module_album_photos` VALUES (17, 2, 0, 'EPSN0213.jpg', '', NOW(), 'jpg', '1c91b6499a', NULL);
INSERT INTO `module_album_photos` VALUES (18, 2, 0, 'EPSN0242.jpg', '', NOW(), 'jpg', 'd0a20b1ed4', NULL);

INSERT INTO `kernel_mod_enabled` VALUES ('CLUB', 2, 'MOD_BLOG', 3);
INSERT INTO `module_blog` VALUES (3, 'La Tunisie', 1, NULL, 'La_Tunisie', 0, 1, 0, 'POST', 'dokuwiki');
INSERT INTO `module_blog_articlecategory` VALUES (5, 3, 1, 'Général', 'GeneralTunisie');
INSERT INTO `module_blog_article` VALUES (6, 3, 'Mes photos', 'Mes photos, de retour de Tunisie :\r\n\r\n[[static/album/2_cf057489c9/10_bf31503e53.jpg|{{static/album/2_cf057489c9/10_bf31503e53_240.jpg|EPSN0007.jpg}}]] \r\n [[static/album/2_cf057489c9/11_52f0518564.jpg|{{static/album/2_cf057489c9/11_52f0518564_240.jpg|EPSN0124.jpg}}]] \r\n[[static/album/2_cf057489c9/12_0bd77e356a.jpg|{{static/album/2_cf057489c9/12_0bd77e356a_240.jpg|EPSN0134.jpg}}]]\r\n[[static/album/2_cf057489c9/13_c696a3ce60.jpg|{{static/album/2_cf057489c9/13_c696a3ce60_240.jpg|EPSN0146.jpg}}]]\r\n[[static/album/2_cf057489c9/14_58a12ff6df.jpg|{{static/album/2_cf057489c9/14_58a12ff6df_240.jpg|EPSN0189.jpg}}]]\r\n[[static/album/2_cf057489c9/15_20c4f6aff7.jpg|{{static/album/2_cf057489c9/15_20c4f6aff7_240.jpg|EPSN0191.jpg}}]]\r\n[[static/album/2_cf057489c9/16_5431069bb7.jpg|{{static/album/2_cf057489c9/16_5431069bb7_240.jpg|EPSN0202.jpg}}]]\r\n[[static/album/2_cf057489c9/17_1c91b6499a.jpg|{{static/album/2_cf057489c9/17_1c91b6499a_240.jpg|EPSN0213.jpg}}]]\r\n[[static/album/2_cf057489c9/18_d0a20b1ed4.jpg|{{static/album/2_cf057489c9/18_d0a20b1ed4_240.jpg|EPSN0242.jpg}}]]\r\n\r\n', '\n<p>\nMes photos, de retour de Tunisie :\n</p>\n\n<p>\n<a href="static/album/2_cf057489c9/10_bf31503e53.jpg" class="media" target="_blank" title="static/album/2_cf057489c9/10_bf31503e53.jpg"  rel="nofollow"><img src="static/album/2_cf057489c9/10_bf31503e53_240.jpg" class="media" title="EPSN0007.jpg" alt="EPSN0007.jpg" /></a> \n <a href="static/album/2_cf057489c9/11_52f0518564.jpg" class="media" target="_blank" title="static/album/2_cf057489c9/11_52f0518564.jpg"  rel="nofollow"><img src="static/album/2_cf057489c9/11_52f0518564_240.jpg" class="media" title="EPSN0124.jpg" alt="EPSN0124.jpg" /></a> \n<a href="static/album/2_cf057489c9/12_0bd77e356a.jpg" class="media" target="_blank" title="static/album/2_cf057489c9/12_0bd77e356a.jpg"  rel="nofollow"><img src="static/album/2_cf057489c9/12_0bd77e356a_240.jpg" class="media" title="EPSN0134.jpg" alt="EPSN0134.jpg" /></a>\n<a href="static/album/2_cf057489c9/13_c696a3ce60.jpg" class="media" target="_blank" title="static/album/2_cf057489c9/13_c696a3ce60.jpg"  rel="nofollow"><img src="static/album/2_cf057489c9/13_c696a3ce60_240.jpg" class="media" title="EPSN0146.jpg" alt="EPSN0146.jpg" /></a>\n<a href="static/album/2_cf057489c9/14_58a12ff6df.jpg" class="media" target="_blank" title="static/album/2_cf057489c9/14_58a12ff6df.jpg"  rel="nofollow"><img src="static/album/2_cf057489c9/14_58a12ff6df_240.jpg" class="media" title="EPSN0189.jpg" alt="EPSN0189.jpg" /></a>\n<a href="static/album/2_cf057489c9/15_20c4f6aff7.jpg" class="media" target="_blank" title="static/album/2_cf057489c9/15_20c4f6aff7.jpg"  rel="nofollow"><img src="static/album/2_cf057489c9/15_20c4f6aff7_240.jpg" class="media" title="EPSN0191.jpg" alt="EPSN0191.jpg" /></a>\n<a href="static/album/2_cf057489c9/16_5431069bb7.jpg" class="media" target="_blank" title="static/album/2_cf057489c9/16_5431069bb7.jpg"  rel="nofollow"><img src="static/album/2_cf057489c9/16_5431069bb7_240.jpg" class="media" title="EPSN0202.jpg" alt="EPSN0202.jpg" /></a>\n<a href="static/album/2_cf057489c9/17_1c91b6499a.jpg" class="media" target="_blank" title="static/album/2_cf057489c9/17_1c91b6499a.jpg"  rel="nofollow"><img src="static/album/2_cf057489c9/17_1c91b6499a_240.jpg" class="media" title="EPSN0213.jpg" alt="EPSN0213.jpg" /></a>\n<a href="static/album/2_cf057489c9/18_d0a20b1ed4.jpg" class="media" target="_blank" title="static/album/2_cf057489c9/18_d0a20b1ed4.jpg"  rel="nofollow"><img src="static/album/2_cf057489c9/18_d0a20b1ed4_240.jpg" class="media" title="EPSN0242.jpg" alt="EPSN0242.jpg" /></a>\n</p>\n', '', '', 'dokuwiki', 16, DATE_FORMAT(NOW(),'%Y%m%d'), '0800', '6-Mes_photos', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (6, 5);

INSERT INTO `module_groupe_groupe` VALUES (3, 'Discussion sur les chartes Internet', 'Discussions relatives à l''adaptation des chartes du ministère pour l''accès à Internet des élèves.', 0, 12, DATE_ADD(NOW(), INTERVAL -1 DAY));
INSERT INTO `kernel_link_groupe2node` VALUES (3, 'BU_ECOLE', 1);
INSERT INTO `kernel_link_user2node` VALUES ('USER_ENS', 1, 'CLUB', 3, 70, NULL, NULL);
INSERT INTO `kernel_link_user2node` VALUES ('USER_ENS', 2, 'CLUB', 3, 35, NULL, NULL);
INSERT INTO `kernel_link_user2node` VALUES ('USER_ENS', 3, 'CLUB', 3, 35, NULL, NULL);
INSERT INTO `kernel_link_user2node` VALUES ('USER_VIL', 5, 'CLUB', 3, 35, NULL, NULL);

INSERT INTO `kernel_mod_enabled` VALUES ('CLUB', 3, 'MOD_FORUM', 2);
INSERT INTO `module_forum_forums` VALUES (2, '', DATE_ADD(NOW(), INTERVAL -23 HOUR));
INSERT INTO `module_forum_topics` VALUES (2, 'Expérience d''adaptation d''une charte', 2, DATE_ADD(NOW(), INTERVAL -60 MINUTE), 12, 1, 1, 1, 3, 12, DATE_ADD(NOW(), INTERVAL -60 MINUTE));
INSERT INTO `module_forum_messages` VALUES (3, 2, 2, 12, 'Nous souhaitons adapter la charte du ministère, afin notamment d''en faire une version "enfants". Quelqu''un a-t-il déjà réalisé une telle opération ?\r\n\r\nQuels sont les arguments qu''il faut mettre en avant pour les enfants ? Le notion de propriété intellectuelle, même si elle n''est pas fondamentale pour la charte d''accès, me semble importante. Comment présenter cela aux enfants ? ', 'dokuwiki', DATE_ADD(NOW(), INTERVAL -60 MINUTE), 1, 0);
INSERT INTO `module_forum_topics` VALUES (3, 'Résumer une charte', 2, DATE_ADD(NOW(), INTERVAL -30 MINUTE), 12, 2, 3, 1, 5, 13, DATE_ADD(NOW(), INTERVAL -10 MINUTE));
INSERT INTO `module_forum_messages` VALUES (4, 3, 2, 12, 'Peut-on facilement résumer une charte d''accès à Internet en 4 ou 5 points ? Que mettre en avant ?\r\n\r\nNous cherchons notamment à afficher un résumé sur la porte de la salle informatique. ', 'dokuwiki', DATE_ADD(NOW(), INTERVAL -30 MINUTE), 1, 0);
INSERT INTO `module_forum_messages` VALUES (5, 3, 2, 13, 'Il me semble important de rappeler les règles concernant le piratage et donc le droit de la propriété intellectuelle. Nous savons bien qu''il ne sera pas facile d''arriver à un piratage 0, à aucun MP3 téléchargé, mais il me semble important de l''annoncer clairement et d''expliquer pourquoi il n''est pas légal de copier une oeuvre, quelle qu''elle soit.\r\n\r\n> il existe des oeuvres fournies avec une licence autorisant la copie, la diffusion, la modification, etc.\r\n> de même "mp3" est un standard de fichiers sonores très utile pédagogiquement : chants d''oiseaux, extraits musicaux pour étude, textes lus pour persannes handicapées, etc...\r\n\r\nLe notion de confidentialité du mot de passe me semble également cruciale. Nous avons déjà eu le cas de deux élèves très copines qui se sont échangés leurs mots de passe. Ensuite, elles se sont fâchées, et l''une a utilisé l''autre mot de passe pour envoyer un message sous un faux nom... Mais si ça prête à sourire, c''est presque un "cas d''école" à étudier en classe, en expliquant pourquoi le mot de passe est important et pourquoi il authentifie la personne qui l''utilise.', 'dokuwiki', DATE_ADD(NOW(), INTERVAL -10 MINUTE), 1, 0);

INSERT INTO `kernel_mod_enabled` VALUES ('CLUB', 3, 'MOD_LISTE', 2);
INSERT INTO `module_liste_listes` VALUES (2, '', DATE_ADD(NOW(), INTERVAL -2 MINUTE));

INSERT INTO `kernel_mod_enabled` VALUES ('CLUB', 3, 'MOD_MALLE', 1);
INSERT INTO `module_malle_malles` VALUES (1, '', DATE_ADD(NOW(), INTERVAL -2 MINUTE), '9a4ba0cdef');
INSERT INTO `module_malle_files` VALUES (1, 1, 0, 'charteproj.pdf', 'charteproj.pdf', 45935, 'application/pdf', '8a7b30cdea', DATE_ADD(NOW(), INTERVAL -1 MINUTE));

INSERT INTO `kernel_mod_enabled` VALUES ('BU_CLASSE', 1, 'MOD_ALBUM', 3);
INSERT INTO `module_album_albums` VALUES (3, '', 'aa', DATE_ADD(NOW(), INTERVAL -2 MINUTE), 'c996b6cf13', 0);
INSERT INTO `module_album_photos` VALUES (19, 3, 0, 'Un dessin plein de couleurs', '', DATE_ADD(NOW(), INTERVAL -1 MINUTE), 'jpg', 'f250349069', NULL);
INSERT INTO `module_album_photos` VALUES (20, 3, 0, 'Dessin sur le thème de la famille', '', DATE_ADD(NOW(), INTERVAL -1 MINUTE), 'jpg', '1a7b30cdec', NULL);

INSERT INTO `kernel_mod_enabled` VALUES ('BU_CLASSE', 1, 'MOD_BLOG', 4);
INSERT INTO `module_blog` VALUES (4, 'Journal de classe en ligne', 1, '4_cpbleu.jpg', 'CP_bleu', 0, 1, 1, 'POST', 'dokuwiki');
INSERT INTO `module_blog_articlecategory` VALUES (6, 4, 1, 'Actualités', 'Actualites');
INSERT INTO `module_blog_article` VALUES (7, 4, 'Journal des CP Bleu', 'L''école Varlin et le site [[http://www.gommeetgribouillages.fr/Presse/Presse.htm|Gomme et Gribouillages]] nous ont permis de reprendre des extraits du journal de la classe de CM1.\r\n\r\n', '\n<p>\nL&#039;école Varlin et le site <a href="http://www.gommeetgribouillages.fr/Presse/Presse.htm" class="urlextern" target="_blank" title="http://www.gommeetgribouillages.fr/Presse/Presse.htm"  rel="nofollow">Gomme et Gribouillages</a> nous ont permis de reprendre des extraits du journal de la classe de CM1.\n</p>\n', '', '', 'dokuwiki', 13, DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -7 DAY),'%Y%m%d'), '0803', '7-Journal_des_CP_Bleu', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (7, 6);
INSERT INTO `module_blog_article` VALUES (8, 4, 'L''équipe de rédaction', 'Et voici en photo l''équipe de rédaction de ce journal au grand complet.\r\n\r\n{{demo/redac.gif}}', '\n<p>\nEt voici en photo l&#039;équipe de rédaction de ce journal au grand complet.\n</p>\n\n<p>\n<a href="demo/redac.gif" class="media" target="_blank" title="demo/redac.gif"><img src="demo/redac.gif" class="media" alt="" /></a>\n\n</p>\n', '', '', 'dokuwiki', 13, DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -6 DAY),'%Y%m%d'), '1022', '8-L_equipe_de_redaction', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (8, 6);
INSERT INTO `module_blog_article` VALUES (9, 4, 'Une nouvelle maîtresse', 'Après les vacances de février, nous avons eu une nouvelle maîtresse qui s''appelle Aurélie. Elle remplace Mme Ployé qui est partie en stage à "l''école des maîtresses" pendant trois semaines.\r\n\r\nPendant ce temps, nous avons lu des journaux, fait ce journal, du sport, fabriqué des maracas, planté des graines de haricots.', '<p>\nAprès les vacances de février, nous avons eu une nouvelle maîtresse qui s''appelle Aurélie. Elle remplace Mme Ployé qui est partie en stage à &quot;l''école des maîtresses&quot; pendant trois semaines.\n</p>\n<p>\nPendant ce temps, nous avons lu des journaux, fait ce journal, du sport, fabriqué des maracas, planté des graines de haricots.\n</p>', '', '', 'dokuwiki', 13, DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 DAY),'%Y%m%d'), '1108', '9-Une_nouvelle_maitresse', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (9, 6);
INSERT INTO `module_blog_article` VALUES (10, 4, 'Pour avoir des dents en bonne santé', 'En début d''année, deux infirmières sont venues dans notre classe à Tremblay en France. Elles sont venues pour nous expliquer comment faire pour avoir de belles dents.\r\n\r\nElles nous ont d''abord montré un petit film: on y voyait un garçon qui avait des caries. Les infirmières nous ont appris qu''il faut se brosser les dents tous les jours et après chaque repas pour ne pas avoir de caries.\r\n\r\nNous avons aussi appris le nom des dents (voir dessins ci-dessous)\r\n\r\n{{demo/dents.gif}}', '\n<p>\nEn début d&#039;année, deux infirmières sont venues dans notre classe à Tremblay en France. Elles sont venues pour nous expliquer comment faire pour avoir de belles dents.\n</p>\n\n<p>\nElles nous ont d&#039;abord montré un petit film: on y voyait un garçon qui avait des caries. Les infirmières nous ont appris qu&#039;il faut se brosser les dents tous les jours et après chaque repas pour ne pas avoir de caries.\n</p>\n\n<p>\nNous avons aussi appris le nom des dents (voir dessins ci-dessous)\n</p>\n\n<p>\n<a href="demo/dents.gif" class="media" target="_blank" title="demo/dents.gif"><img src="demo/dents.gif" class="media" alt="" /></a>\n\n</p>\n', '', '', 'dokuwiki', 13, DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -4 DAY),'%Y%m%d'), '1408', '10-Pour_avoir_des_dents_en_bonne_sante', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (10, 6);
INSERT INTO `module_blog_article` VALUES
(11, 4, 'Concours de dessin', '[[static/album/3_c996b6cf13/20_1a7b30cdec.jpg|{{ static/album/3_c996b6cf13/20_1a7b30cdec_240.jpg|Dessin%20sur%20le%20th%E8me%20de%20la%20famille}}]]\r\nNotre concours de dessin sur le thème de la famille est terminé. Et la gagnante est...\r\nMais oui, c''est Aurélie ! Bravo !\r\nMais nous tenons à féliciter tous les participants, et vous proposons d''ores et déjà un nouveau concours, qui pourrait avoir pour thème les loups.\r\n\r\n\r\n', '\n<p>\n<a href="static/album/3_c996b6cf13/20_1a7b30cdec.jpg" class="media" target="_blank" title="static/album/3_c996b6cf13/20_1a7b30cdec.jpg"  rel="nofollow"><img src="static/album/3_c996b6cf13/20_1a7b30cdec_240.jpg" class="mediaright" align="right" title="Dessin%20sur%20le%20th%E8me%20de%20la%20famille" alt="Dessin%20sur%20le%20th%E8me%20de%20la%20famille" /></a>\nNotre concours de dessin sur le thème de la famille est terminé. Et la gagnante est&hellip;\nMais oui, c&#039;est Aurélie ! Bravo !\nMais nous tenons à féliciter tous les participants, et vous proposons d&#039;ores et déjà un nouveau concours, qui pourrait avoir pour thème les loups.\n</p>\n', 'Voici également un autre dessin qui nous a été donné par la maternelle mais qui est hors concours:\r\n\r\n{{static/album/3_c996b6cf13/19_f250349069_240.jpg|Un%20dessin%20plein%20de%20couleurs}}\r\n\r\n', '\n<p>\nVoici également un autre dessin qui nous a été donné par la maternelle mais qui est hors concours:\n</p>\n\n<p>\n<a href="static/album/3_c996b6cf13/19_f250349069_240.jpg" class="media" target="_blank" title="static/album/3_c996b6cf13/19_f250349069_240.jpg"><img src="static/album/3_c996b6cf13/19_f250349069_240.jpg" class="media" title="Un%20dessin%20plein%20de%20couleurs" alt="Un%20dessin%20plein%20de%20couleurs" /></a>\n</p>\n', 'dokuwiki', 13, '20090401', '1549', '11_concours_de_dessin', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (11, 6);
INSERT INTO `module_blog_articlecomment` VALUES (2, 11, 13, 'Arthur', '', '', '127.0.0.1', DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -1 DAY),'%Y%m%d'), '1631', 'Bravo Aurélie pour ce joli dessin !', 1);
INSERT INTO `module_blog_article` VALUES (12, 4, 'Vacances scolaires', 'Un grand sondage sera bientôt lancé pour savoir si les parents d''élèves souhaitent ou pas passer à la semaine de 4 jours. Rendez-vous ici prochainement !', '\n<p>\nUn grand sondage sera bientôt lancé pour savoir si les parents d&#039;élèves souhaitent ou pas passer à la semaine de 4 jours. Rendez-vous ici prochainement !\n\n</p>\n', '', '', 'dokuwiki', 13, DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -2 DAY),'%Y%m%d'), '0953', '12-Vacances_scolaires', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (12, 6);


