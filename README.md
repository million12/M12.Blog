TYPO3 Neos very simple Blog plugin
==================================

This is a node-based blog plugin for TYPO3 Neos websites. It is based on [robertlemke/RobertLemke.Plugin.Blog](https://github.com/robertlemke/RobertLemke.Plugin.Blog) work - thank you!

Features
--------
* **Blog Post** page type (M12.Plugin.Blog:Post)
* **Blog Post Overview** plugin to create blog post index (M12.Plugin.Blog:PostPlugin) with two modes:
    * index: renders list of blog posts, with paginating
    * latest: render latest blog posts

Quick start
-----------

* include plugin route definitions to your Configuration/Routes.yaml file:
    ```yaml
-
  name: 'M12.Plugin.Blog'
  uriPattern: '<M12PluginBlogSubroutes>'
  subRoutes:
    M12PluginBlogSubroutes:
      package: M12.Plugin.Blog
```

* install plugin using
    ```bash
composer require m12/neos-plugin-blog:*
    ```

* add new post by adding new 'Blog Post' page in your page tree
* add the plugin 'Blog Post Overview' to the position of your choice to render post list.
    * set _Posts source node_ to node containing blog posts. This step is only required if the plugin node is put somewhere else, not on the node containing blog posts.
