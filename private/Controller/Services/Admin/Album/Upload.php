<?php

namespace Controller\Services\Admin\Album;

use Lib\Core\BaseController;
use Lib\Core\Imager;
use Lib\Data\Album;
use Lib\Data\AlbumCategory;
use Lib\Data\Picture;

/**
 * Class Upload
 * @package Controller\Services\Admin\Album
 */
final class Upload extends \Controller\Services\Admin
{
    /**
     * @return array
     * @throws \Exception
     */
    public function getArray()
    {
        $album = $this->getAlbumRepository()->getById($_GET['id']);
        if (!$album) {
            return [];
        }

        $this->ensurePermission('album.' . $this->getAlbumCategoryRepository()->getById($album->getCategory())->getName() . '.edit');

        $imager = new Imager();
        $category = $this->getAlbumCategoryRepository()->getById($album->getCategory());
        $targetDir = ROOT . "../public/upload/" . $category->getName() . "/" . md5($album->getId()) . '/';
        $targetFile = $targetDir . basename($_FILES["file"]["name"]);
        $imager->uploadImage($_FILES["file"]["tmp_name"], $targetFile);

        $picture = new Picture(
            null,
            $album->getId(),
            basename($_FILES["file"]["name"]),
            ''
        );
        $this->getPictureRepository()->save($picture);

        $isResized = $imager->resizeImage($targetFile, 1024, 720);
        if (!$isResized) {
            throw new \Exception("Could not resize image");
        }
        return [];
    }
}
