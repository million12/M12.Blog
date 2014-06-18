<?php

namespace M12\Plugin\Blog\Controller;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Routing\UriBuilder;
use TYPO3\Flow\Mvc\Controller\ActionController;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use M12\Plugin\Blog\Service\ContentService;
use RobertLemke\Rss\Channel;
use RobertLemke\Rss\Feed;
use RobertLemke\Rss\Item;


/**
 * The Rss controller for the M12.Plugin.Blog package
 *
 * @Flow\Scope("singleton")
 */
class RssController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\I18n\Service
	 */
	protected $i18nService;

	/**
	 * @Flow\Inject
	 * @var ContentService
	 */
	protected $contentService;


	/**
	 * @param NodeInterface $node
	 * @return string
	 */
	public function rssAction(NodeInterface $node) {
		$feedSettings = $this->settings['feed'];

		$uriBuilder = new UriBuilder();
		$uriBuilder->setRequest($this->request->getMainRequest());
		$uriBuilder->setCreateAbsoluteUri(TRUE);
		$uriBuilder->setFormat('xml');
		$feedUri = $uriBuilder->uriFor('rss', ['node'=>$node]);

		$channel = new Channel();
		$channel->setTitle($feedSettings['title']);
		$channel->setDescription($feedSettings['description']);
		$channel->setFeedUri($feedUri);
		$channel->setWebsiteUri($this->request->getHttpRequest()->getBaseUri());
		$channel->setLanguage((string)$this->i18nService->getConfiguration()->getCurrentLocale());

		/* @var $postNode NodeInterface */
		foreach ($node->getChildNodes('M12.Plugin.Blog:Post', $feedSettings['postsLimit']) as $postNode) {
			$uriBuilder->setFormat('html');
			$postUri = $uriBuilder->uriFor('show', array('node' => $postNode), 'Frontend\Node', 'TYPO3.Neos');

			$item = new Item();
			$item->setTitle($postNode->getProperty('title'));
			$item->setGuid($postNode->getIdentifier());

			// TODO: Remove this once all old node properties are migrated:
			$publicationDate = $postNode->getProperty('datePublished');
			if (is_string($publicationDate)) {
				$publicationDate = \DateTime::createFromFormat('Y-m-d', $publicationDate);
				$postNode->setProperty('datePublished', $publicationDate);
			}

			$item->setPublicationDate($postNode->getProperty('datePublished'));
			$item->setItemLink((string)$postUri);
			$item->setCommentsLink((string)$postUri . '#comments');

			$author = $postNode->getProperty('author');
			$item->setCreator($author ? $author : $feedSettings['defaultAuthor']);

			$description = $this->contentService->renderTeaser($postNode) . ' <a href="' . $postUri . '">Read more</a>';
			$item->setDescription($description);
			$channel->addItem($item);
		}

		// @TODO This won't work yet (plugin sub responses can't set headers yet) but keep that as a reminder:
		$headers = $this->response->getHeaders();
		$headers->setCacheControlDirective('s-max-age', 3600);
		$headers->set('Content-Type', 'application/rss+xml');
		$this->response->setHeaders($headers);

		$feed = new Feed();
		$feed->addChannel($channel);
		return $feed->render();
	}
}
