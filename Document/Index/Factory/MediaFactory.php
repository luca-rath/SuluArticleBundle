<?php

declare(strict_types=1);

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\ArticleBundle\Document\Index\Factory;

use Sulu\Bundle\ArticleBundle\Document\MediaViewObject;
use Sulu\Bundle\MediaBundle\Media\Manager\MediaManagerInterface;

/**
 * Create a seo view object.
 */
class MediaFactory
{
    /**
     * @var MediaManagerInterface
     */
    private $mediaManager;

    /**
     * MediaCollectionFactory constructor.
     */
    public function __construct(MediaManagerInterface $mediaManager)
    {
        $this->mediaManager = $mediaManager;
    }

    public function create(int $id, string $locale): MediaViewObject
    {
        $mediaViewObject = new MediaViewObject();

        if (!$id) {
            return $mediaViewObject;
        }

        $media = $this->mediaManager->getById($id, $locale);

        $mediaViewObject->setData($media);

        return $mediaViewObject;
    }
}
