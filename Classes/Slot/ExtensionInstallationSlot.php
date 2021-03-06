<?php
namespace IchHabRecht\Integrity\Slot;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Nicole Cordes <typo3@cordes.co>, CPS-IT GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use IchHabRecht\Integrity\ExtensionInformationRepositoryFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ExtensionInstallationSlot
{
    /**
     * @param $extensionKey
     */
    public function addExtensionInformation($extensionKey)
    {
        $packageManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Package\\PackageManager');
        $extensionInformationRepository = ExtensionInformationRepositoryFactory::create();
        if ($extensionKey === 'integrity') {
            $packages = $packageManager->getAvailablePackages();
        } else {
            $packages = array($packageManager->getPackage($extensionKey));
        }

        // There is no other way to find out, if an extension was downloaded from TER
        // If downloaded from TER we enforce to rewrite current checksums
        $extensionManagerVariables = GeneralUtility::_GP('tx_extensionmanager_tools_extensionmanagerextensionmanager');
        if (isset($extensionManagerVariables['action']) && $extensionManagerVariables['action'] === 'installFromTer') {
            $extensionInformationRepository->updateExtensionInformation($packages);
        } else {
            $extensionInformationRepository->addExtensionInformation($packages);
        }
    }
}
