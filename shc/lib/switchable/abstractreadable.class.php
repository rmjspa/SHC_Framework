<?php

namespace SHC\Switchable;

//Imports
use RWF\User\Visitor;
use SHC\Room\Room;
use RWF\User\User;
use RWF\User\UserGroup;

/**
 * Basisklasse eines Lesbaren Elements
 * 
 * @author     Oliver Kleditzsch
 * @copyright  Copyright (c) 2014, Oliver Kleditzsch
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since      2.0.0-0
 * @version    2.0.0-0
 */
abstract class AbstractReadable implements Readable {
    
    /**
     * Status
     * 
     * @var Integer 
     */
    protected $state = self::STATE_OFF;
    
    /**
     * gibt an ob der Status veraendert wurde
     * 
     * @var Boolean 
     */
    protected $stateModified = false;
    
    /**
     * ID des Elements
     * 
     * @var Integer 
     */
    protected $id = 0;
    
    /**
     * Icon des Elements
     * 
     * @var String 
     */
    protected $icon = '';

    /**
     * Name des Elements
     * 
     * @var String 
     */
    protected $name = '';

    /**
     * Raum
     * 
     * @var \SHC\Room\Room 
     */
    protected $room = null;
    
    /**
     * Sortierungs ID
     * 
     * @var Integer 
     */
    protected $orderId = 0;

    /**
     * aktiviert/deaktiviert
     * 
     * @var Boolean 
     */
    protected $enabled = true;
    
    /**
     * sichtbarkeit des Schaltelementes
     * 
     * @var Integer 
     */
    protected $visibility = 1;

    /**
     * Berechtigte Benutzergruppen
     * 
     * @var Array 
     */
    protected $allowedUserGroups = array();
    
    /**
     * setzt den Status des Objekts
     * 
     * @param  Integer $state    Status
     * @param  Boolean $modified als veaendert Markieren
     * @return \SHC\Switchable\Readable
     */
    public function setState($state, $modified = true) {
        
        $this->state = $state;
        if($modified == true) {
            
            $this->stateModified = true;
        }
        return $this;
    }
    
    /**
     * gibt an ob der Status veraendert wurde
     * 
     * @return Boolean
     */
    public function isStateModified() {
        
        return $this->stateModified;
    }
    
    /**
     * setzt die ID des Elements
     * 
     * @param Integer $id
     * @return \SHC\Switchable\Readable
     */
    public function setId($id) {
        
        $this->id = $id;
        return $this;
    }
    
    /**
     * gibt die ID des Elements zurueck
     * 
     * @return Integer
     */
    public function getId() {
        
        return $this->id;
    }
    
    /**
     * setzt das Icon welches Angezeigt werden soll
     * 
     * @param  String $path Dateiname
     * @return \SHC\Switchable\Readable
     */
    public function setIcon($path) {

        $this->icon = $path;
        return $this;
    }

    /**
     * gibt den Dateinamen des Icons zurueck
     * 
     * @return String
     */
    public function getIcon() {

        return $this->icon;
    }

    /**
     * setzt den Namen des Elements
     * 
     * @param  String $name Name
     * @return \SHC\Switchable\Readable
     */
    public function setName($name) {

        $this->name = $name;
        return $this;
    }

    /**
     * gibt den Namen des Elements zurueck
     * 
     * @return String
     */
    public function getName() {

        return $this->name;
    }

    /**
     * setzt den Raum dem das Element zugeordnet ist
     * 
     * @param  \SHC\Room\Room $room
     * @return \SHC\Switchable\Readable
     */
    public function setRoom(Room $room) {

        $this->room = $room;
        return $this;
    }

    /**
     * gibt den Raum zurueck in dem das Element zugeordnet ist
     * 
     * @return \SHC\Room\Room
     */
    public function getRoom() {

        return $this->room;
    }
    
    /**
     * setzt die Sortierungs ID
     * 
     * @param  Integer $orderId Sortierungs ID
     * @return \SHC\Switchable\Readable
     */
    public function setOrderId($orderId) {
        
        $this->orderId = $orderId;
        return $this;
    }
    
    /**
     * gibt die Sortierungs ID zurueck
     * 
     * @return Integer
     */
    public function getOrderId() {
        
        return $this->orderId;
    }

    /**
     * Aktiviert/Deaktiviert das Element
     * 
     * @param  Boolean $enabled Aktiviert
     * @return \SHC\Switchable\Readable
     */
    public function enable($enabled) {

        if ($enabled == true) {

            $this->enabled = true;
        } else {

            $this->enabled = false;
        }
        return $this;
    }

    /**
     * gibt an ob das Element Aktiviert ist
     * 
     * @return Boolean 
     */
    public function isEnabled() {

        return $this->enabled;
    }
    
    /**
     * setzt die Sichtbarkeit dss Schaltelements
     * 
     * @param  Integer $visibility Sichtbarkeit
     * @return \SHC\Switchable\Readable
     */
    public function setVisibility($visibility) {
        
        $this->visibility = $visibility;
        return $this;
    }
    
    /**
     * gibt die Sichtbarkeit des Schaltelementes zurueck
     * 
     * @return Integer
     */
    public function isVisible() {
        
        return $this->visibility;
    }

    /**
     * fuegt eine Benutzergruppen hinzu der es erlaubt ist das Element zu schalten
     * 
     * @param  \RWF\User\UserGroup $userGroup
     * @return \SHC\Switchable\Readable
     */
    public function addAllowedUserGroup(UserGroup $userGroup) {

        $this->allowedUserGroups[] = $userGroup;
    }

    /**
     * entfernt eine Benutzergruppen der es erlaubt ist das Element zu schalten
     * 
     * @param  \RWF\User\UserGroup $userGroup
     * @return \SHC\Switchable\Readable
     */
    public function removeAllowedUserGroup(UserGroup $userGroup) {

        $this->allowedUserGroups = array_diff($this->allowedUserGroups, array($userGroup));
        return $this;
    }

    /**
     * entfernt alle Benutzergruppen
     * 
     * @return \SHC\Switchable\Readable
     */
    public function removeAllAllowedUserGroups() {

        $this->allowedUserGroups = array();
        return $this;
    }

    /**
     * gibt eine Liste mit allen erlaubten Benutzergruppen zurueck
     *
     * @return Array
     */
    public function listAllowedUserGroups() {

        return $this->allowedUserGroups;
    }

    /**
     * prueft ob ein Benutzer berechtigt ist das Element zu schalten
     *
     * @param \RWF\User\Visitor $user
     * @return Boolean
     */
    public function isUserEntitled(Visitor $user) {

        if (isset($this->allowedUserGroups[0]) && $this->allowedUserGroups[0] != '') {

            //Hauptgruppe pruefen
            if (in_array($user->getMainGroup(), $this->allowedUserGroups) || ($user instanceof User && $user->isOriginator())) {

                return true;
            }

            //Alle Benutzergruppen pruefen
            foreach ($user->listGroups() as $userGroup) {

                if ($userGroup instanceof UserGroup && in_array($userGroup, $this->allowedUserGroups)) {

                    return true;
                }
            }

            //keine berechtigte Gruppe gefunden
            return false;
        }
        return true;
    }
}
