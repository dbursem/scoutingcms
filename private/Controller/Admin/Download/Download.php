<?php

namespace Controller\Admin\Download;

use Controller\Admin;
use Lib\Core\Translation;

/**
 * Class Download
 * @package Controller\Admin
 * @author Joost Mul <scoutingcms@jmul.net>
 */
final class Download extends Admin
{
    /**
     * @return array
     */
    public function getArray()
    {
        $download = $this->getDownloadRepository()->getById($this->getVariable('id', 0));
        if (!$download) {
            $download = new \Lib\Data\Download(
                null,
                Translation::getInstance()->translate('download.name'),
                \Lib\Data\Download::TYPE_REPORT,
                ''
            );
        } else {
            $this->ensurePermission('download.' . $download->getType() . '.view');
        }

        return [
            'download' => $download,
            'active' => 'download',
            'isNew' => $download->getId() == null,
        ];
    }
}
