<?php

namespace M12\Blog\Controller;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use TYPO3\Neos\Exception;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;


/**
 * The posts controller for the M12.Blog package
 *
 * @Flow\Scope("singleton")
 */
class PostController extends ActionController {

	/**
	 * Pre-indexAction:
	 */
	public function initializeIndexAction() {
		$node = $this->getPluginNode();
		$mode = $node->getProperty('mode');

		// When two plugins works on the same page
		// and paginator is working as well (overriding action name)
		// re-direct to [modeName]Action, so two plugins can work together.
		if ($mode && $mode !== 'index') {
			$this->forward($mode);
		}
	}

	/**
	 * Display index of blog posts
	 */
	public function indexAction() {
		$node = $this->getPluginNode();
		$postsSourceNode = $this->getPostsSourceNode($node);

		$this->view->assign('postsSourceNode', $postsSourceNode);
		$this->view->assign('hasPostNodes', $postsSourceNode->hasChildNodes('M12.Blog:Post'));
		$this->view->assign('itemsPerPage', $this->getPostsLimit($node));
	}

	/**
	 * Display latest blog posts
	 */
	public function latestAction() {
		$node = $this->getPluginNode();
		$postsSourceNode = $this->getPostsSourceNode($node);

		$this->view->assign('hasPostNodes', $postsSourceNode->hasChildNodes('M12.Blog:Post'));
		$this->view->assign('postNodes', $postsSourceNode->getChildNodes('M12.Blog:Post', $this->getPostsLimit($node)));
	}

	/**
	 * Get number of posts to display
	 *
	 * @param NodeInterface $node
	 * @return int
	 */
	protected function getPostsLimit(NodeInterface $node) {
		return ($limit = (int)$node->getProperty('limit')) ? $limit : 10;
	}

	/**
	 * Get plugin node
	 *
	 * @throws \TYPO3\Neos\Exception
	 * @return NodeInterface
	 */
	protected function getPluginNode() {
		$node = $this->request->getInternalArgument('__node');
		if (!$node) {
			throw new Exception('Could not determine plugin node.');
		}

		return $node;
	}

	/**
	 * Get node from which M12.Blog:Post are sourced (they have to be child nodes there)
	 *
	 * @param NodeInterface $node Plugin node
	 * @throws \TYPO3\Neos\Exception
	 * @return NodeInterface
	 */
	protected function getPostsSourceNode(NodeInterface $node) {
		$postsSourceNode = $node->getProperty('postsSourceNode');

		if (!$postsSourceNode) {
			$postsSourceNode = $this->request->getInternalArgument('__documentNode');
		}

		if (!$postsSourceNode) {
			throw new Exception('Error: The Blog Post Plugin cannot determine the posts source node. You might want to configure *Blog Posts Overview* plugin.');
		}

		return $postsSourceNode;
	}
}
