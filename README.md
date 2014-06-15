TYPO3 Neos very simple Blog plugin
==================================

This plugin provides a node-based plugin for TYPO3 Neos websites. It's based on [robertlemke/RobertLemke.Plugin.Blog](https://github.com/robertlemke/RobertLemke.Plugin.Blog) work - thank you!

Features
-----------
* **Blog Post** page type (M12.Blog:Post)
* **Blog Post Overview** plugin to create blog post index (M12.Blog:PostPlugin) with two modes:
	* index: renders list of blog posts, with paginating
	* latest: render latest blog posts

Quick start
-----------

* install plugin using `composer require m12/neos-blog:*`
* add new post by adding new 'Blog Post' page in your page tree
* add the plugin 'Blog Post Overview' to the position of your choice to render post list.
	* set _Posts source node_ to node containing blog posts. This step is only required if the plugin node is put somewhere else, not on the node containing blog posts.
