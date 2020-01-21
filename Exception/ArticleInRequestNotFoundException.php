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

namespace Sulu\Bundle\ArticleBundle\Exception;

/**
 * Thrown when no article-page is found in request.
 */
class ArticleInRequestNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct('No article in request found');
    }
}
