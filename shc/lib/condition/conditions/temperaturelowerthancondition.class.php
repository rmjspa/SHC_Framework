<?php

namespace SHC\Condition\Conditions;

//Imports
use SHC\Condition\AbstractCondition;
use SHC\Sensor\SensorPointEditor;
use SHC\Sensor\Sensors\BMP;
use SHC\Sensor\Sensors\DHT;
use SHC\Sensor\Sensors\DS18x20;

/**
 * Bedingung Temperatur kleiner als
 *
 * @author     Oliver Kleditzsch
 * @copyright  Copyright (c) 2014, Oliver Kleditzsch
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since      2.0.0-0
 * @version    2.0.0-0
 */
class TemperatureLowerThanCondition extends AbstractCondition {

    /**
     * gibt an ob die Bedingung erfuellt ist
     *
     * @return Boolean
     */
    public function isSatisfies() {

        //wenn deaktiviert immer True
        if (!$this->isEnabled()) {

            return true;
        }

        //noetige Parameter pruefen
        if (!isset($this->data['sensors']) || !isset($this->data['temperature'])) {

            throw new Exception('sensors und temperature müssen angegeben werden', 1580);
        }

        $sensors = explode(',', $this->data['sensors']);
        foreach ($sensors as $sensorId) {

            $sensor = SensorPointEditor::getInstance()->getSensorById($sensorId);
            if ($sensor instanceof DS18x20 || $sensor instanceof DHT || $sensor instanceof BMP) {

                $humidity = $sensor->getTemperature();
                if ($humidity <= (float) $this->data['temperature']) {

                    return true;
                }
            }
        }

        return false;
    }

}