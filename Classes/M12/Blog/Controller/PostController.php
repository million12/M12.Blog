<?php

namespace M12\Blog\Controller;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use TYPO3\Flow\Mvc\Routing\UriBuilder;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeTemplate;

/**
 * The posts controller for the M12.Blog package
 *
 * @Flow\Scope("singleton")
 */
class PostController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Service\NodeTypeManager
	 */
	protected $nodeTypeManager;

	/**
	 * Displays a list of most recent blog posts
	 */
	public function indexAction() {
		/** @var NodeInterface $blogDocumentNode */
		$blogDocumentNode = $this->request->getInternalArgument('__documentNode');
		if ($blogDocumentNode !== NULL) {
			$this->view->assign('postsNode', $blogDocumentNode);
			$this->view->assign('hasPostNodes', $blogDocumentNode->hasChildNodes('M12.Blog:Post'));
		} else {
			return 'Error: The Blog Post Plugin cannot determine the current document node. Please make sure to include this plugin only by inserting it into a page / document.';
		}
	}

}
