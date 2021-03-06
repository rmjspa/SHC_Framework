<?php

namespace SHC\Command\CLI;

//Imports
use RWF\Core\RWF;
use RWF\Request\Commands\CliCommand;
use RWF\Util\CliUtil;
use RWF\Util\String;
use SHC\Sheduler\Sheduler;

/**
 * Schaltserver
 * 
 * @author     Oliver Kleditzsch
 * @copyright  Copyright (c) 2014, Oliver Kleditzsch
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since      2.0.0-0
 * @version    2.0.0-0
 */
class ShedulerDeamonCli extends CliCommand {

    /**
     * kurzer Kommandozeilen Parameter
     * 
     * @var String 
     */
    protected $shortParam = '-sh';

    /**
     * voller Kommandozeilen Parameter
     * 
     * @var String 
     */
    protected $fullParam = '--sheduler';

    /**
     * Debug Modus aktiv
     * 
     * @var Boolean 
     */
    protected $debug = false;

    /**
     * gibt die Hilfe zu der Kommandozeilen Funktion auf die Kommandozeile aus
     */
    public function writeHelp() {

        //Sprache einbinden
        RWF::getLanguage()->loadModul('shedulerdaemon');

        $r = RWF::getResponse();
        $r->writeLnColored('-sh oder --sheduler startet den Scheduler Daemon', 'green_u');
        $r->writeLn('');
        $r->writeLn('Der Timer Deamon ist einer der wichtigsten Dienste das SHC, er verwaltet die Zeitsteuerung, sucht regelmäßig nach bekannten Geräten im Netzwerk und verarbeitet die Ereignisse.');
        $r->writeLn('Dieser Dienst muss in der SHC Hauptinstallation laufen (also auf dem RPi mit der SHC Weboberfläche), für alle zusätzlichen Dienste wird er nicht benötigt.');
        $r->writeLn('In den Standardeinstellungen ist dieser Dienst aktiviert.');
        $r->writeLn('');
    }

    /**
     * konfiguriert das CLI Kommando
     */
    protected function config() {

        $cli = new CliUtil();
        $response = $this->response;
        RWF::getLanguage()->loadModul('shedulerdaemon');

        //Dienst aktiv
        $n = 0;
        $valid = true;
        $valid_active = false;
        $active_not_change = false;
        while ($n < 5) {

            $sender = $cli->input(RWF::getLanguage()->get('shedulerDaemon.input.active', (RWF::getSetting('shc.shedulerDaemon.active') == true ? RWF::getLanguage()->get('global.yes') : RWF::getLanguage()->get('global.no'))));

            //Port nicht aendern
            if (String::length($sender) == 0) {

                $active_not_change = true;
                $valid = true;
                break;
            }

            if (!preg_match('#^('. RWF::getLanguage()->get('global.yes') .')|('. RWF::getLanguage()->get('global.yes.short') .')|('. RWF::getLanguage()->get('global.no') .')|('. RWF::getLanguage()->get('global.no.short') .')$#i', $sender)) {

                $response->writeLnColored(RWF::getLanguage()->get('shedulerDaemon.input.active.invalid'), 'red');
                $n++;
                $valid = false;
                continue;
            }

            if ($valid === true && preg_match('#^('. RWF::getLanguage()->get('global.yes') .')|('. RWF::getLanguage()->get('global.yes.short') .')$#i', $sender)) {

                $valid_active = true;
                break;
            } elseif ($valid === true && preg_match('#^('. RWF::getLanguage()->get('global.no') .')|('. RWF::getLanguage()->get('global.no.short') .')$#i', $sender)) {

                $valid_active = false;
                break;
            }
        }

        if ($valid === false) {

            $response->writeLnColored(RWF::getLanguage()->get('shedulerDaemon.input.active.invalid.repeated'), 'red');
            exit(1);
        }

        //Speichern
        if($active_not_change === false) {

            RWF::getSettings()->editSetting('shc.shedulerDaemon.active', $valid_active);
        }

        try {

            RWF::getSettings()->saveAndReload();
            $response->writeLnColored(RWF::getLanguage()->get('shedulerDaemon.input.save.success'), 'green');
        } catch(\Exception $e) {

            $response->writeLnColored(RWF::getLanguage()->get('shedulerDaemon.input.save.error'), 'red');
        }
    }

    /**
     * fuehrt das CLI Kommando aus
     */
    protected function executeCliCommand() {

        //pruefen on Server aktiviert
        if (!RWF::getSetting('shc.shedulerDaemon.active')) {

            throw new \Exception('Der Sheduler Dienst wurde deaktiviert', 1600);
        }

        //Sheduler Initialisieren
        $sheduler = new Sheduler();

        //Aufgaben zyklisch ausfuehren
        while (true) {
            
            $sheduler->executeTasks();

            //Ruhezeut bis zum naechsten Durchlauf
            sleep(1);
        }
    }

}
