<?php
/*=======================================================================
 Nuke-Evolution Basic: Enhanced PHP-Nuke Web Portal System
 =======================================================================*/

/************************************************************************
   Nuke-Evolution: Advanced Installer
   ============================================
   Copyright (c) 2005 by The Nuke-Evolution Team

   Filename      : lang-install.php
   Author        : JeFFb68CAM (www.Evo-Mods.com)
   Version       : 1.0.0
   Date          : 11.05.2005 (mm.dd.yyyy)

   Notes         : You may NOT use this installer for your own
                   needs or script. It is written specifically
                   for Nuke-Evolution.
************************************************************************/

$step_a[1] = "Stap 1: Taal kiezen ";
$step_a[2] = "Stap 2: Server Specificaties";
$step_a[3] = "Stap 3: File Check CHMOD";
$step_a[4] = "Stap 4: Database Connection";
$step_a[5] = "Stap 5: Installatie SQL";
$step_a[6] = "Stap 6: Setup Eerste Admin";
$step_a[7] = "Stap 7: Setup Site configs";
$step_a[8] = "Stap 8: Bevestig alle instellingen";
$step_a[9] = "Installatie Voltooien";

// New Language
$install_lang['go_back'] = "Ga terug naar de vorige pagina";
$install_lang['lang_stitle'] = "Selecteer een Taal";
$install_lang['lang_select'] = "Selecteer een taal die bij u past:";
$install_lang['chmod_check'] = "CHMOD bestand/map Check";
$install_lang['mysql_info'] = "MySQL Connection Info";
$install_lang['mysql_check'] = "MySQL verbinding te controleren";
$install_lang['server_check'] = "Check Server";
$install_lang['sql_install'] = "Het importeren van Nuke Evolution Database";
$install_lang['retry_sql'] = "Opnieuw SQL Installatie";
$install_lang['setup_admin'] = "Setup Uw eerste Admin-account";
$install_lang['admin_check'] = "Admin-account Validatie";
$install_lang['admin_nick'] = "Admin Nickname:";
$install_lang['admin_pass'] = "Admin Wachtwoord:";
$install_lang['admin_cpass'] = "Bevestig Wachtwoord:";
$install_lang['admin_email'] = "Admin e-mail:";
$install_lang['admin_web'] = "Admin Homepage:";
$install_lang['admin_success'] = "Uw god beheeraccount werd met succes samen met een gebruikersaccount aangemaakt.";
$install_lang['admin_fail'] = "Het wachtwoord is u opgegeven komen niet overeen.";
$install_lang['admin_nfail'] = "De bijnaam die u heeft opgegeven is niet geldig.";
$install_lang['admin_efail'] = "De e-mail die u heeft opgegeven is niet geldig.";
$install_lang['god_fail'] = "Niet invoegen admin gegevens in nuke auteurs tafel.";
$install_lang['nsnst_fail'] = "Niet invoegen admin bescherming van gegevens in nuke sentinel tafel.";
$install_lang['user_fail'] = "Niet invoegen gebruiker gegevens in nuke gebruikers tabel.";
$install_lang['setup_config'] = "Setup Site/Forum Configuratie";
$install_lang['server_title'] = "Server Specificaties";
$install_lang['os'] = "Besturingssysteem:";
$install_lang['po'] = "Proces Eigenaar:";
$install_lang['fo'] = "Eigenaar van bestand:";
$install_lang['rp'] = "Root Path:";
$install_lang['rtp'] = "Root permissies:";
$install_lang['interface'] = "Interface:";
$install_lang['openbasedir'] = "open_basedir:";
$install_lang['safemode'] = "safe_mode:";
$install_lang['safemodegid'] = "safe_mode_gid:";
$install_lang['safemodeexecdir'] = "safe_mode_exec_dir:";
$install_lang['safemodeincludedir'] = "safe_mode_include_dir:";
$install_lang['disablefunctions'] = "disable_functions:";
$install_lang['file_uploads'] = "file_uploads:";
$install_lang['upload_tmp_dir'] = "upload_tmp_dir:";
$install_lang['upload_max_filesize'] = "upload_max_filesize:";
$install_lang['upload_file'] = "Upload testbestand:";
$install_lang['next_step'] = "Next Step";
$install_lang['site_name'] = "Naam van de site:";
$install_lang['site_url'] = "URL van de site:";
$install_lang['site_slogan'] = "Site Slogan:";
$install_lang['start_date'] = "Site Startdatum:";
$install_lang['admin_email'] = "E-mail Administrator:";
$install_lang['setup_overview'] = "Bevestig uw site-instellingen";
$install_lang['return_setup'] = "Return To Setup";
$install_lang['finish_install'] = "De installatie is klaar";
$install_lang['access_files'] = '<strong>NOTE:</strong> Als je mist de. Htaccess of. Staccess bestand te hernoemen en / of evo.htaccess evo.staccess om de relatieve bestandsnamen ze moet zijn!';

$install_lang['briefing'] = "Dit zal $nuke_name installeren op uw server";
$install_lang['couldnt_connect'] = "Kon niet verbinden met database<br />";
$install_lang['couldnt_select_db'] = "Kon geen database selecteren<br />";
$install_lang['continue'] = "Ga verder naar Stap";
$install_lang['connection_failed'] = "Connectie met de server is mislukt! Zorg ervoor dat uw instellingen correct zijn.";
$install_lang['connection_failed2'] = "Connectie met de database is mislukt! Zorg ervoor dat uw instellingen correct zijn. (Database name)";
$install_lang['chmod'] = "CHMOD Benodigde Bestanden";
$install_lang['config_maker'] = "Config.php Generator";
$install_lang['configure'] = "Stel Site in";
$install_lang['cant_open'] = "Kan het bestand niet openen";
$install_lang['cantwrite'] = "Kan niet naar het bestand schrijven";
$install_lang['chmod_failed'] = "Een of meer bestanden zijn mislukt, chmod ze handmatig A.U.B.";
$install_lang['config_success'] = "Config.php generatie gelukt!";
$install_lang['cookie_name'] = "Cookie Naam:";
$install_lang['cookie_path'] = "Cookie Pad:";
$install_lang['cookie_domain'] = "Cookie Domein:";
$install_lang['config_failed'] = "Config.php generatie mislukt!";
$install_lang['installer_heading'] = "Installatie :: Stap";
$install_lang['installer_heading2'] = "van";
$install_lang['dbhost'] = "DB Host:";
$install_lang['dbname'] = "DB Naam:";
$install_lang['dbuser'] = "DB Gebruiker:";
$install_lang['dbpass'] = "DB Wachtwoord:";
$install_lang['dbtype'] = "DB Type:";
$install_lang['dbhost_error'] = "Je moet een database host opgeven(standaard is \"localhost\")";
$install_lang['dbname_error'] = "Je moet een database naam opgeven.";
$install_lang['dbuser_error'] = "Je moet een database gebruiker opgeven.";
$install_lang['dbpass_error'] = "Je moet een database wachtwoord opgeven.";
$install_lang['dbtype_error'] = "Je moet een database type opgeven.";
$install_lang['data_success'] = "Data validatie en SQL Server Checks gelukt!";
$install_lang['die_message'] = "Generale Fout";
$install_lang['prefix'] = "Voorvoegsel:";
$install_lang['user_prefix'] = "Gebruiker Voorvoegsel:";
$install_lang['confirm_data'] = "Controleer Data";
$install_lang['server_check'] = "Server Check";
$install_lang['skip'] = "Sla configuratie over";
$install_lang['failed'] = "MISLUKT";
$install_lang['success'] = "SUCCES";
$install_lang['thefile'] = "Het bestand";
$install_lang['is_missing'] = "ontbreekt.<br />";
$install_lang['prefix_error'] = "Je moet een voorvoegsel opgeven.";
$install_lang['uprefix_error'] = "Je moet een gebruiker voorvoegsel opgeven.";
$install_lang['mysql_incorrect'] = "<font color=red>Uw MySQL versie is niet correct!</font><br />Uw server zegt dat u MySQL versie '.$sql_version.' heeft en 4.x is benodigd.";
$install_lang['dbtype_que'] = "U heeft iets anders gekozen dan MySQL als uw database type, weet u zeker dat u dit wilt gebruiken? Als u het niet zeker weet, ga dan terug en selecteer MySQL.";
$install_lang['session_lost'] = "Uw Sessie Gegevens zijn verloren gegaan, installeer opnieuw A.U.B.";
$install_lang['php_ver'] = "Uw PHP versie is incorrect!</font></strong><br />4.x.x is benodigd - Uw versie is";
$install_lang['checks_good'] = "Alle checks zijn succesvol afgerond. Ga door A.U.B.";
$install_lang['sql_error'] = "Er is een MySQL fout opgetreden. <strong>MySQL Fout Details:</strong></font><br />";
$install_lang['install_success'] = "Uw installatie van $nuke_name is succesvol afgerond.";
$install_lang['get_config_error'] = "Kon de configuratie gegevens niet verzenden<br />";
$install_lang['update_fail'] = "Het bijwerken van de configuratie gegevens zijn mislukt. Betreffend";
$install_lang['sitename'] = "Site Naam:";
$install_lang['sitedescr'] = "Site Descriptie:";
$install_lang['namechange'] = "Sta naam verandering toe:";
$install_lang['yes'] = "Ja:";
$install_lang['no'] = "Nee:";
$install_lang['email_sig'] = "Email Handtekening:";
$install_lang['site_email'] = "Site E-Mail:";
$install_lang['default_lang'] = "Standaard Taal:";
$install_lang['server_name'] = "Server Naam:";
$install_lang['server_port'] = "Server Poort:";
$install_lang['done'] = "Bedankt voor het kiezen van Nuke-Evolution.<br /><br /><a href=index.php>Ga naar uw startpagina</a>";
$install_lang['delete'] = "<font color=\"red\">Verwijder install.php en de install map A.U.B.</font>";
$install_lang['install_complete_header'] = " :: Installation Voltooid";
$install_lang['general_message'] = "Generaal Bericht";
$install_lang['data_error'] = "Kon data.txt niet openen";
$install_lang['safe_mode'] = "Uw php is momenteel in safe mode.<br /><br />Zo kan de installatie niet voltooid worden.<br /><br />Uw moet Nuke-Evolution handmatig installeren.<br /><br />Zie het installatie help bestand hoe een handmatige installatie uit te voeren.";

?>