<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Galette installation, database checks step
 *
 * PHP version 5
 *
 * Copyright © 2013 The Galette Team
 *
 * This file is part of Galette (http://galette.tuxfamily.org).
 *
 * Galette is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Galette is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Galette. If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Core
 * @package   Galette
 *
 * @author    Johan Cwiklinski <johan@x-tnd.be>
 * @copyright 2013 The Galette Team
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or (at your option) any later version
 * @version   SVN: $Id$
 * @link      http://galette.tuxfamily.org
 * @since     Available since 0.7.4dev - 2013-01-11
 */

use Galette\Core\Install as GaletteInstall;
use Galette\Core\Db as GaletteDb;

$db_connected = $install->testDbConnexion();
$conndb_ok = true;
$permsdb_ok = true;

if ( $db_connected === true ) {
    $zdb = new GaletteDb();

    /** FIXME: when tables already exists and DROP not allowed at this time
    the showed error is about CREATE, whenever CREATE is allowed */
    //We delete the table if exists, no error at this time
    $zdb->dropTestTable();

    $results = $zdb->grantCheck($install->getMode());

    $result = array();
    $error = false;

    $ares = array(
        'message'   => null,
        'debug'     => null,
        'res'       => false
    );

    //test returned values
    if ( $results['create'] instanceof Exception ) {
        $result = array(
            'message'   => _T("CREATE operation not allowed"),
            'debug'     => $results['create']->getMessage(),
            'res'       => false
        );
        /*$result .= '<li class="install-bad debuginfos">' .
            _T("CREATE operation not allowed") . '<span>' .
            $results['create']->getMessage() . '</span></li>';*/
        $error = true;
    } elseif ( $results['create'] != '' ) {
        $result[] = array(
            'message'   => _T("CREATE operation allowed"),
            'res'       => true
        );
        /*$result .= '<li class="install-ok">' .
            _T("CREATE operation allowed") . '</li>';*/
    }

    if ( $results['insert'] instanceof Exception ) {
        $result = array(
            'message'   => _T("INSERT operation not allowed"),
            'debug'     => $results['insert']->getMessage(),
            'res'       => false
        );
        /*$result .= '<li class="install-bad debuginfos">' .
            _T("INSERT operation not allowed") . '<span>' .
            $results['insert']->getMessage() . '</span></li>';*/
        $error = true;
    } elseif ( $results['insert'] != '' ) {
        $result[] = array(
            'message'   => _T("INSERT operation allowed"),
            'res'       => true
        );
        /*$result .= '<li class="install-ok">' .
            _T("INSERT operation allowed") . '</li>';*/
    }

    if ( $results['update'] instanceof Exception ) {
        $result = array(
            'message'   => _T("UPDATE operation not allowed"),
            'debug'     => $results['update']->getMessage(),
            'res'       => false
        );
        /*$result .= '<li class="install-bad debuginfos">' .
            _T("UPDATE operation not allowed") . '<span>' .
            $results['update']->getMessage() . '</span></li>';*/
        $error = true;
    } elseif ( $results['update'] != '' ) {
        $result[] = array(
            'message'   => _T("UPDATE operation allowed"),
            'res'       => true
        );
        /*$result .= '<li class="install-ok">' .
            _T("UPDATE operation allowed") . '</li>';*/
    }

    if ( $results['select'] instanceof Exception ) {
        $result = array(
            'message'   => _T("SELECT operation not allowed"),
            'debug'     => $results['select']->getMessage(),
            'res'       => false
        );
        /*$result .= '<li class="install-bad debuginfos">' .
            _T("SELECT operation not allowed") . '<span>' .
            $results['select']->getMessage() . '</span></li>';*/
        $error = true;
    } elseif ( $results['select'] != '' ) {
        $result[] = array(
            'message'   => _T("SELECT operation allowed"),
            'res'       => true
        );
        /*$result .= '<li class="install-ok">' .
            _T("SELECT operation allowed") . '</li>';*/
    }

    if ( $results['delete'] instanceof Exception ) {
        $result = array(
            'message'   => _T("DELETE operation not allowed"),
            'debug'     => $results['delete']->getMessage(),
            'res'       => false
        );
        /*$result .= '<li class="install-bad debuginfos">' .
            _T("DELETE operation not allowed") . '<span>' .
            $results['delete']->getMessage() . '</span></li>';*/
        $error = true;
    } elseif ( $results['delete'] != '' ) {
        $result[] = array(
            'message'   => _T("DELETE operation allowed"),
            'res'       => true
        );
        /*$result .= '<li class="install-ok">' .
            _T("DELETE operation allowed") . '</li>';*/
    }

    if ( $results['drop'] instanceof Exception ) {
        $result = array(
            'message'   => _T("DROP operation not allowed"),
            'debug'     => $results['drop']->getMessage(),
            'res'       => false
        );
        /*$result .= '<li class="install-bad debuginfos">' .
            _T("DROP operation not allowed") . '<span>' .
            $results['drop']->getMessage() . '</span></li>';*/
        $error = true;
    } elseif ( $results['drop'] != '' ) {
        $result[] = array(
            'message'   => _T("DROP operation allowed"),
            'res'       => true
        );
        /*$result .= '<li class="install-ok">' .
            _T("DROP operation allowed") . '</li>';*/
    }

    if ( $results['alter'] instanceof Exception ) {
        $result = array(
            'message'   => _T("ALTER operation not allowed"),
            'debug'     => $results['alter']->getMessage(),
            'res'       => false
        );
        /*$result .= '<li class="install-bad debuginfos">' .
            _T("ALTER Operation not allowed") . '<span>' .
            $results['alter']->getMessage() . '</span></li>';*/
        $error = true;
    } elseif ( $results['alter'] != '' ) {
        $result[] = array(
            'message'   => _T("ALTER operation allowed"),
            'res'       => true
        );
        /*$result .= '<li class="install-ok">' .
            _T("ALTER Operation allowed") . '</li>';*/
    }

    if ( $error ) {
        $permsdb_ok = false;
    }
}
?>
                <h2><?php echo $install->getStepTitle() ?></h2>
<?php
if ( $db_connected === true && $permsdb_ok === true ) {
    echo '<p id="infobox">' . _T("Connection to database successfull") .
        '<br/>' . _T("Permissions to database are OK.") . '</p>';
}
?>
                <h3><?php echo _T("Check of the database"); ?></h3>
<?php
if ( $db_connected !== true ) {
    $conndb_ok = false;
    echo '<div id="errorbox">';
    echo '<h1>' . _T("Unable to connect to the database") . '</h1>';
    echo '<p class="debuginfos">' . $db_connected->getMessage() . '<span>' .
        $db_connected->getTraceAsString() . '</span></p>';
    echo '</div>';
}

if ( !$conndb_ok ) {
    ?>
                <p><?php echo _T("Database can't be reached. Please go back to enter the connection parameters again."); ?></p>
    <?php
} else {
    ?>
                <p><?php echo _T("Database exists and connection parameters are OK."); ?></p>
                <h3><?php echo _T("Permissions on the base"); ?></h3>
    <?php
    if ( !$permsdb_ok ) {
        echo '<div id="errorbox">';
        echo '<h1>';
        if ( $install->isInstall() ) {
            echo _T("GALETTE hasn't got enough permissions on the database to continue the installation.");
        } else if ( $install->isUpgrade() ) {
            echo _T("GALETTE hasn't got enough permissions on the database to continue the update.");
        }
        echo '</h1>';
        echo '</div>';

    } else {
        ?>
            <ul class="leaders">
        <?php
        foreach ( $result as $r ) {
            ?>
                <li>
                    <span><?php echo $r['message'] ?></span>
                    <span><?php echo $install->getValidationImage($r['res']); ?></span>
                </li>
            <?php
        }
        ?>
            </ul>
        <?php
    }
}
?>
            <form action="installer.php" method="POST">
                <p id="btn_box">
                    <input id="next_btn" type="submit" value="<?php echo _T("Next step"); ?>"<?php if ( !$conndb_ok || !$permsdb_ok ) { echo ' disabled="disabled"';  } ?>/>
<?php
if ( $conndb_ok && $permsdb_ok ) {
    ?>

                    <input type="hidden" name="install_dbperms_ok" value="1"/>
    <?php
}
?>
                    <input type="submit" id="btnback" name="stepback_btn" value="<?php echo _T("Back"); ?>"/>
                </p>
            </form>
