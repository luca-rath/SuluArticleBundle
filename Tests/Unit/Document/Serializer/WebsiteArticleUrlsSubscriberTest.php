<?php

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\ArticleBundle\Tests\Unit\Document\Serializer;

use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\Metadata\StaticPropertyMetadata;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sulu\Bundle\ArticleBundle\Document\ArticleDocument;
use Sulu\Bundle\ArticleBundle\Document\Serializer\WebsiteArticleUrlsSubscriber;
use Sulu\Bundle\RouteBundle\Entity\RouteRepository;
use Sulu\Bundle\RouteBundle\Entity\RouteRepositoryInterface;
use Sulu\Bundle\RouteBundle\Model\RouteInterface;
use Sulu\Component\Localization\Localization;
use Sulu\Component\Webspace\Analyzer\Attributes\RequestAttributes;
use Sulu\Component\Webspace\Webspace;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class WebsiteArticleUrlsSubscriberTest extends TestCase
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var RouteRepositoryInterface
     */
    private $routeRepository;

    /**
     * @var WebsiteArticleUrlsSubscriber
     */
    private $urlsSubscriber;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        $this->requestStack = $this->prophesize(RequestStack::class);
        $this->routeRepository = $this->prophesize(RouteRepository::class);

        $this->urlsSubscriber = new WebsiteArticleUrlsSubscriber(
            $this->requestStack->reveal(),
            $this->routeRepository->reveal()
        );

        $webspace = new Webspace();
        $webspace->addLocalization(new Localization('en'));
        $webspace->addLocalization(new Localization('de'));

        $request = $this->prophesize(Request::class);
        $request->get('_sulu')->willReturn(new RequestAttributes(['webspace' => $webspace]));
        $this->requestStack->getCurrentRequest()->willReturn($request->reveal());
    }

    public function testAddUrlsOnPostSerialize()
    {
        $article = $this->prophesize(ArticleDocument::class);
        $visitor = $this->prophesize(SerializationVisitorInterface::class);

        $context = $this->prophesize(SerializationContext::class);
        $context->hasAttribute('urls')->willReturn(true);

        $entityId = '123-123-123';
        $article->getUuid()->willReturn($entityId);

        $event = $this->prophesize(ObjectEvent::class);
        $event->getObject()->willReturn($article->reveal());
        $event->getVisitor()->willReturn($visitor->reveal());
        $event->getContext()->willReturn($context->reveal());

        $expected = ['de' => '/seite', 'en' => '/page'];

        $entityClass = get_class($article->reveal());
        foreach ($expected as $locale => $path) {
            $route = $this->prophesize(RouteInterface::class);
            $route->getPath()->willReturn($path);

            $this->routeRepository->findByEntity($entityClass, $entityId, $locale)->willReturn($route->reveal());
        }

        $visitor->visitProperty(Argument::that(function(StaticPropertyMetadata $metadata) {
            return 'urls' === $metadata->name;
        }), $expected)->shouldBeCalled();

        $this->urlsSubscriber->addUrlsOnPostSerialize($event->reveal());
    }
}
