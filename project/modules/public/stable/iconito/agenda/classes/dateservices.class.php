<?php
/**
 * Dans ce service, se trouvent toutes les opérations sur les dates
 * @package Iconito
 * @subpackage Agenda
 * @author Audrey Vassal
 * @copyright 2001-2005 CopixTeam
 * @link http://copix.org
 * @licence http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

require_once (COPIX_UTILS_PATH.'CopixDateTime.class.php');

class DateService
{
    /**
    * Ajoute un nombre de jours/mois/années à une date et retourne la nouvelle date obtenue.
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/07/24
    * @param string $ToDate La date que l'on va incrémenter. Format Fr.
    * @param integer $Day le nombre de jours à ajouter.
    * @param integer $Month le nombre de mois a ajouter.
    * @param integer $year le nombre d'années à ajouter.
    * @param string $SplitChar le caractere séparateur utilisé dans les dates (par defaut : /)
    * @return string La date modifiée. Format fr jj-mm-aaaa.
    */
    public function addToDate ($ToDate, $Day, $Month = 0, $Year = 0, $SplitChar = '/')
    {
        $TblToDate = explode ($SplitChar, $ToDate); //Tableau avec les valeurs actuelles.
        $NewValue = mktime (0, 0, 0, $TblToDate[1] + $Month, $TblToDate[0] + $Day, $TblToDate[2] + $Year);
        return date('d' . $SplitChar . 'm' . $SplitChar . 'Y', $NewValue); //Reconversion de la valeur en format date.
    }


    /*
    * Fonction qui donne le nombre de jours écoulés entre deux dates
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/08/16
    * @param date $pDate1 au format yyyymmdd
    * @param date $pDate1 au format yyyymmdd
    * @return integer $nbDay le nombre de jours écoulés
    */
    public function getNombreJoursEcoulesEntreDeuxDates($pDate1, $pDate2)
    {
        $date1 = $this->dateAndHoureBdToTimestamp($pDate1, null);
        $date2 = $this->dateAndHoureBdToTimestamp($pDate2, null);
        $nbSec = $date1 - $date2;//nb de sec entre les deux jours
        $nbDays = $nbSec/86400; //86400 est le nb de sec dans une journée
        return $nbDays;
    }


    /**
    * Soustrait un jour à une date
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/08/11
    * @param date $pDate date au format yyyymmdd
    * @return int la date moins 1 jour
    */
    public function retireUnJour ($pDate)
    {
        $date = mktime(0 ,0, 0, substr($pDate, 4, 2), substr($pDate, 6, 2), substr($pDate, 0, 4));
        $date = $date - 60*60*24;
        $date = date('Ymd', $date);
        return $date;
    }


    /*
    * Fonction qui donne la date du jour de la semaine qui suit une date donnée
    * Utilisée pour la reprise après une date($pDate), d'un évènement qui se répète toutes les semaines
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/08/11
    * @param date $pDate date de référence au format yyyymmdd
    * @param integer $pJour le jour de la semaine auquel débute l'évènement (0 pour dimanche, 6 pour samedi)
    * @return date $date la date recherchée au format yyyymmdd
    */
    public function getDayOfWeekAfterDate($pDate, $pJour)
    {
        $nbJourAjout = 7 - date('w', $this->dateAndHoureBdToTimestamp($pDate, null)) + $pJour;
        $date = $this->addToDate($this->dateBddToDateFr($pDate), $nbJourAjout, 0, 0);
        return $this->dateFrToDateBdd($date);
    }


    /*
    * Fonction qui donne le jour du mois qui suit une date donnée
    * Utilisée pour la reprise après une date($pDate), d'un évènement qui se répète tous les mois
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/08/16
    * @param date $pDate date de référence au format yyyymmdd
    * @param integer $pJour le jour du mois auquel débute l'évènement
    * @return date $date la date recherchée au format yyyymmdd
    */
    public function getDayOfMonthAfterDate($pDate, $pJour)
    {
        $nbJourEcart = (substr($pDate, 6, 2) - $pJour);

        if ($nbJourEcart < 0){
            $date = $this->addToDate($this->dateBddToDateFr($pDate), -($nbJourEcart), 0, 0);
            $date = $this->dateFrToDateBdd($date);
        } else{
            $date = $this->addToDate($this->dateBddToDateFr($pDate), 0, 1, 0);
            $date = $this->dateFrToDateBdd($date);
            $date = mktime(0 ,0, 0, substr($date, 4, 2), substr($date, 6, 2), substr($date, 0, 4));
            $date = $date - 60*60*24*$nbJourEcart;echo'<br />';
            $date = date('Ymd', $date);
        }
        return $date;
    }


    /*
    * Fonction qui le jour de l'année qui suit une date donnée
    * Utilisée pour la reprise après une date($pDate), d'un évènement qui se répète tous les ans
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/08/16
    * @param date $pDate date de référence au format yyyymmdd
    * @param integer $pJour le jour de l'année auquel débute l'évènement (format mmdd)
    * @return date $date la date recherchée au format yyyymmdd
    */
    public function getDayOfYearAfterDate($pDate, $pJour)
    {
        if(substr($pDate, 4, 4) < $pJour){//si l'évènement commence après $pDate, on reste dans l'année de $pDate
            $date = substr($pDate, 0, 4).$pJour;
        } else{
            $year = substr($pDate, 0, 4) + 1;
            $date = $year.$pJour;
        }
        return $date;
    }


    /**
    * Convertit une date (+ heure) en timestamp
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/07/27
    * @param integer $pDate La date a convertir (au format bdd : yyyymmdd)
    * @param string $pHour L'heure (au format : hh:mm)
    * @return string La date en timestamp
    */
    public function dateAndHoureBdToTimestamp ($pDate, $pHour)
    {
      //print_r("dateAndHoureBdToTimestamp ($pDate, $pHour)");
        if ($pHour) {
            $hour = substr ($pHour, 0, strpos($pHour, ':'));
            $minut = substr ($pHour, strpos($pHour, ':') + 1, 2);
        } else {
                    $hour = $minut = 0;
                }
        $day = substr($pDate, 6, 2);
        $month = substr($pDate, 4, 2);
        $year = substr($pDate, 0, 4);
        return mktime($hour, $minut, 0, $month, $day, $year);
    }


    /**
    * Fonction qui donne le nombre de jour écoulés entre deux dates+heure
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/07/28
    * @param date $pDateBegin au format jj/mm/aaaa
    * @param date $pDateEnd au format jj/mm/aaaa
    * @param date $pHeureDeb heure au format hh:mm
    * @param date $pHeureFin heure au format hh:mm
    * @return integer $nbDays nombre de jours qui se sont écoulés entre les deux dates
    */
    public function getNomberDaysBeetweenTwoDates($pDateBegin, $pDateEnd, $pHeureBegin, $pHeureEnd)
    {
        //Extraction des données
        //list($jour1, $mois1, $annee1) = explode('/', $pDateBegin);
        //list($jour2, $mois2, $annee2) = explode('/', $pDateEnd);
        $pDate = CopixDateTime::dateToTimestamp ($pDateBegin);
        $jour1 = substr($pDate, 6, 2);
    $mois1 = substr($pDate, 4, 2);
    $annee1 = substr($pDate, 0, 4);
        $pDate = CopixDateTime::dateToTimestamp ($pDateEnd);
        $jour2 = substr($pDate, 6, 2);
    $mois2 = substr($pDate, 4, 2);
    $annee2 = substr($pDate, 0, 4);

        list($heure1, $minutes1) = explode (':', $pHeureBegin);
        list($heure2, $minutes2) = explode (':', $pHeureEnd);
        //Calcul des timestamp
        $timestamp1 = mktime($heure1, $minutes1, 0, $mois1, $jour1, $annee1);
        $timestamp2 = mktime($heure2, $minutes2, 0, $mois2, $jour2, $annee2);
        $nbDays = ($timestamp2 - $timestamp1)/86400;

        return $nbDays;
        //echo abs($timestamp2 - $timestamp1)/(86400*7); //Affichage du nombre de semaine : 3.85
    }


    /**
    * Fonction qui convertit une date au format dd/mm/yyyy en format yyyymmdd
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/07/31
    * @param date $pDateToConvert au format jj/mm/aaaa
    * @return date au format yyyymmdd
    */
    public function dateFrToDateBdd($pDateToConvert)
    {
        return substr($pDateToConvert, 6, 4) . substr($pDateToConvert, 3, 2) . substr($pDateToConvert, 0, 2);
    }


    /**
    * Fonction qui convertit une date au format yyyymmdd en format dd/mm/yyyy
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/07/31
    * @param date $pDateToConvert au format yyyymmdd
    * @return date au format dd/mm/yyyy
    */
    public function dateBddToDateFr($pDateToConvert)
    {
        return substr($pDateToConvert, 6, 2) . '/' . substr($pDateToConvert, 4, 2) . '/' . substr($pDateToConvert, 0, 4);
    }


    /**
    * Fonction qui élimine l'élément qui sépare les heures et les minutes
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/07/31
    * @param heure $pHeureToConvert au format hh:mm
    * @return heure sans séparateur (hhmm)
    */
    public function heureWithSeparateurToheureWithoutSeparateur($pHeureToConvert)
    {
        return str_replace(':', '', $pHeureToConvert);
    }

    /**
    * Fonction qui élimine remet l'élément qui sépare les heures et les minutes
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/08/02
    * @param heure $pHeureToConvert au format hhmm
    * @return heure avec séparateur (hh:mm)
    */
    public function heureWithoutSeparateurToheureWithSeparateur($pHeureToConvert)
    {
        //cas où on a une heure au format hhmm
        if(strlen($pHeureToConvert) == 4){
            return substr($pHeureToConvert, 0, 2) . ':' .substr($pHeureToConvert, 2, 2);
        }
        //cas où on a une heure au format hmm
        else{
            return substr($pHeureToConvert, 0, 1) . ':' .substr($pHeureToConvert, 1, 2);
        }
    }


    /**
    * Fonction qui retourne le numéro de semaine d'une date donnée
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/07/31
    * @param date $date au format timestamp
    * @return int le numéro de la semaine correspondant à la date
    */
    public function dateToWeeknum($date)
    {
        // cette fonction calcule à partir d'une date le numéro de semaine associé
        // "la premiere semaine de l'année est celle dans laquelle se trouve le premier jeudi de l'année"
        $tmp_date = mktime(0,0,0,01,01,date("Y",$date));

        // initialisation de la date de départ du calcul
        // initialisation au premier lundi de l'année
        // indique au passage si ce lundi appartient à la semaine 1 ou 2 de l'année
        switch(date("w",$tmp_date)) {
            case 1:
                $tmp_date = mktime(0,0,0,01,01,date("Y",$date));
                $tmp_delta_week = 0;
            break;
            case 0:
                $tmp_date = mktime(0,0,0,01,02,date("Y",$date));
                $tmp_delta_week = 0;
            break;
            case 6:
                $tmp_date = mktime(0,0,0,01,03,date("Y",$date));
                $tmp_delta_week = 0;
            break;
            case 5:
                $tmp_date = mktime(0,0,0,01,04,date("Y",$date));
                $tmp_delta_week = 0;
            break;
            case 4:
                $tmp_date = mktime(0,0,0,01,05,date("Y",$date));
                $tmp_delta_week = 1;
            break;
            case 3:
                $tmp_date = mktime(0,0,0,01,06,date("Y",$date));
                $tmp_delta_week = 1;
            break;
            case 2:
                $tmp_date = mktime(0,0,0,01,07,date("Y",$date));
                $tmp_delta_week = 1;
            break;
        }

        if ($date >= $tmp_date) { 	// si la date recherchée est postérieure au premier lundi de l'année

            // nombre de jours écoulés depuis la date de début du calcul
            $tmp_nbjours = date("z",mktime(0,0,0,date("m",$date),date("d",$date),date("Y",$date)))-date("z",mktime(0,0,0,date("m",$tmp_date),date("d",$tmp_date),date("Y",$tmp_date)));

            // nombre de semaines écoulées
            $tmp_numsem = floor($tmp_nbjours/7)+$tmp_delta_week+1;
            if ($tmp_numsem < 10){
                $tmp_numsem = "0".$tmp_numsem;
            } // mise en forme du nombre de semaines

            if ($tmp_numsem == 53) { 	// si on a trouvé la semainhe n°53 : attention au piège : n'est-ce pas une semaine 1 anticipée ?
                //echo( date("d-m-Y",mktime(0,0,0,date("m",$date),date("d",$date)+delta_to_thursdaysameweek($date),date("Y",$date)))." || ".date("d-m-Y",mktime(0,0,0,01,01,date("Y",$date)+1)));
                if ( date("Y",mktime(0,0,0,date("m",$date),date("d",$date)+$this->delta_to_thursdaysameweek($date),date("Y",$date))) == date("Y",mktime(0,0,0,01,01,date("Y",$date)+1)) ) {
                    // si le jeudi de cette semaine tombe l'année prochaine alors on est en semaine 1
                    return "01";
                } else{
                    // si le jeudi de cette semaine tombe cette année alors on est en semaine 53
                    return "53";
                }
            } else{// si on est en semaine 1 à 52, ok.
                return $tmp_numsem;
            }
        } else{ 	// si la date recherchée est antérieure au premier lundi de l'année
            if ($tmp_delta_week == 1){
                // si on avait noté une semaine de décalage, les jours antérieurs au premier lundi sont tous en semaine 1
                return "01";
            } else{
                // si on n'avait pas noté de semaine de décalage, les jours antérieurs au premier lundi sont tous de la meme semaine que la dernière semaine de l'année d'avant.
                return $this->dateToWeeknum(mktime(0,0,0,12,31,date("Y",$date)-1));
            }
        }
    }



    public function delta_to_thursdaysameweek($date)
    {
        if (date("w",$date)==0) {
            return -3;
        } else {
            return 4-date("w",$date);
        }
    }

    /**
    * Fonction qui retourne la date du jour en fonction du numéro de la semaine et de l'année
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/07/31
    * @param int $numweek numéro de la semaine
    * @param int $year l'année
    * @param int $dayOfWeek le jour de la semaine demandé (0=>dimanche, 6=>samedi)
    * @return int $tmp_date la date du jour demandé
    */
    public function numweekToDate($numweek,$year,$dayOfWeek)
    {
        // cette fonction calcule à partir d'un numéro de semaine, d'une année et d'un jour de semaine la date associée.
        $tmp_date = mktime(0,0,0,01,01,$year);
        // si les paramètres sont mal formatés, la fonction renvoie false
        if (is_nan($numweek) || is_nan($year) || is_nan ($dayOfWeek) || $numweek > 53 || $numweek < 0 || $dayOfWeek < 0 || $dayOfWeek > 6 || $year <1970 || $year>2030) {
            return false;
            exit;
        }
        // initialisation de la date de départ du calcul
        // initialisation au premier lundi de l'année
        // indique au passage si ce lundi appartient à la semaine 1 ou 2 de l'année
        switch(date("w",$tmp_date)) {
            case 1:
                $tmp_date = mktime(0,0,0,01,01,$year);
                $tmp_delta_week = 0;
                break;
            case 0:
                $tmp_date = mktime(0,0,0,01,02,$year);
                $tmp_delta_week = 0;
                break;
            case 6:
                $tmp_date = mktime(0,0,0,01,03,$year);
                $tmp_delta_week = 0;
                break;
            case 5:
                $tmp_date = mktime(0,0,0,01,04,$year);
                $tmp_delta_week = 0;
                break;
            case 4:
                $tmp_date = mktime(0,0,0,01,05,$year);
                $tmp_delta_week = 1;
                break;
            case 3:
                $tmp_date = mktime(0,0,0,01,06,$year);
                $tmp_delta_week = 1;
                break;
            case 2:
                $tmp_date = mktime(0,0,0,01,07,$year);
                $tmp_delta_week = 1;
                break;
        }
        if ($dayOfWeek>=1 && $dayOfWeek<=6) {
            $tmp_delta_day = $dayOfWeek -1;
        } elseif ($dayOfWeek==0) {
            $tmp_delta_day = 6;
        }
        $tmp_date = mktime(0,0,0,date("m",$tmp_date),date("d",$tmp_date)+($numweek-1-$tmp_delta_week)*7 + $tmp_delta_day,date("Y",$tmp_date));
        //$tmp_date = date("d m Y",$tmp_date);
        // en cas de semaine 53, on vérifie que la semaine 53 existe en effet, sinon on renvoie false
        if ($numweek==53) {
            if ($this->numweekToDate(01,$year+1,$dayOfWeek)==$tmp_date) {
                //$tmp_date = false;
                $tmp_date = $this->numweekToDate(52,$year,$dayOfWeek);
            }
        }
        return $tmp_date;
    }


    /**
    * Fonction qui retourne le nom du mois en français à partir de son numéro
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/07/31
    * @param int $mois numéro du mois (de 1 à 12)
    * @return string le mois sous forme litérale
    */
    public function moisNumericToMoisLitteral($mois)
    {
        if ($mois == 1)
            return CopixI18N::get('agenda|agenda.message.jan');
        elseif ($mois == 2)
            return CopixI18N::get('agenda|agenda.message.fev');
        elseif ($mois == 3)
            return CopixI18N::get('agenda|agenda.message.mars');
        elseif ($mois == 4)
            return CopixI18N::get('agenda|agenda.message.avr');
        elseif ($mois == 5)
            return CopixI18N::get('agenda|agenda.message.mai');
        elseif ($mois == 6)
            return CopixI18N::get('agenda|agenda.message.juin');
        elseif ($mois == 7)
            return CopixI18N::get('agenda|agenda.message.juil');
        elseif ($mois == 8)
            return CopixI18N::get('agenda|agenda.message.aout');
        elseif ($mois == 9)
            return CopixI18N::get('agenda|agenda.message.sept');
        elseif ($mois == 10)
            return CopixI18N::get('agenda|agenda.message.oct');
        elseif ($mois == 11)
            return CopixI18N::get('agenda|agenda.message.nov');
        elseif ($mois == 12)
            return CopixI18N::get('agenda|agenda.message.dec');
    }


    /**
    * Fonction qui retourne le jour en français à partir de la date
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/08/18
    * @param date $pDate date du jour au format yyymmdd
    * @return string le jour sous forme litérale en français
    */
    public function dayNumericToDayLitteral($pDate)
    {
        $date = $this->dateAndHoureBdToTimestamp($pDate, null);
        $jour = date('w', $date);
        if ($jour == 0)
            return CopixI18N::get('agenda|agenda.message.dim');
        elseif ($jour == 1)
            return CopixI18N::get('agenda|agenda.message.lun');
        elseif ($jour == 2)
            return CopixI18N::get('agenda|agenda.message.mar');
        elseif ($jour == 3)
            return CopixI18N::get('agenda|agenda.message.mer');
        elseif ($jour == 4)
            return CopixI18N::get('agenda|agenda.message.jeu');
        elseif ($jour == 5)
            return CopixI18N::get('agenda|agenda.message.ven');
        elseif ($jour == 6)
            return CopixI18N::get('agenda|agenda.message.sam');
    }


    /**
    * Fonction qui convertit un nombre de minutes en heures au format (hh:mm)
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/08/03
    * @param int $pNbMinutes le nombre de minutes à convertir
    * @return string l'heure au format hh:mm
    */
    public function convertMinutesInHours($pNbMinutes)
    {
        $heures = floor( $pNbMinutes / 60 );
        $minutes = $pNbMinutes % 60;
        if(strlen($minutes) == 1){
            $minutes = '0' . $minutes;
        }

        return($heures . ':' . $minutes);
    }

    /**
    * Fonction qui convertit une heure au format (hh:mm) en minutes
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/08/03
    * @param hour $pHours l'heure à convertir
    * @return int le nombre de minutes
    */
    public function convertHoursInMinutes($pHours)
    {
        //Kernel::deb($pHours);
        $Tbl = explode (':', $pHours);
        $nb = $Tbl[0]*60;
        if (isset($Tbl[1]))
            $nb += $Tbl[1];
        return ($nb);
    }
}

