<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Main routes
 *
 * PHP version 5
 *
 * Copyright © 2014 The Galette Team
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
 * @category  Routes
 * @package   Galette
 *
 * @author    Johan Cwiklinski <johan@x-tnd.be>
 * @copyright 2014 The Galette Team
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or (at your option) any later version
 * @version   SVN: $Id$
 * @link      http://galette.tuxfamily.org
 * @since     0.8.2dev 2014-11-11
 */

use Galette\Core\Picture;
use Galette\Entity\Adherent;
use Galette\Controllers\GaletteController;
use Galette\Controllers\Crud;
use Galette\Controllers\AuthController;

//main route
//$app->get('/', GaletteController::class . ':slash')->setName('slash');
$app->get(
    '/',
    GaletteController::class . '::slash'
)->setName('slash');

//logo route
$app->get(
    '/logo',
    GaletteController::class . '::logo'
)->setName('logo');

//print logo route
$app->get(
    '/print-logo',
    GaletteController::class . '::printLogo'
)->setName('printLogo');

//photo route
$app->get(
    '/photo/{id:\d+}',
    Crud\MembersController::class . '::photo'
)->setName('photo');

//system informations
$app->get(
    '/system-informations',
    GaletteController::class . '::sysinfos'
)->setName('sysinfos')->add($authenticate);
