=== Hull.io ===
Contributors: rdardour
Tags: widget, social, engagement, facebook, twitter, google, login, media, plugin, comments, custom
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 0.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Hull plugins makes it easy to integrate your wordpress blog with Hull.io and get social login, engagement features across all your websites

== Description ==
* The Hull plugin allows you to benefit from the full power of http://hull.io without any complicated setup.
* Offer your users a cross-domain social login with Facebook, Twitter, Instagram, Github, LinkedIn, Foursquare, Google, Tumblr, Vkontakte with 1 line of code.
* Show personalized activity feeds, comments, ratings, likes, leaderboards, collections, user media uploads, list of friends and much more without any coding.
* Access the Facebook, Twitter or Instagram API without any backend code, and build amazing applications only in Javascript in your wordpress site!
* If you have multiple domains and your blog is only one of your properties, then you need hull because it's the only solution that recognizes users and offers these engagement features across all your web sites, whatever the technology they use.
* All the tracking and integration with Analytics, transactional email hosting, CDN is already done for you, you have nothing to code to have the best social infrastructure as a service available. If you're a developer you will get even more from Hull's flexibility.

What does it do? 
* All Posts are automatically referenced in your Hull App.
* Hull.js is automatically configured and initialized
* The Widgets and Templates defined in your Wordpress Theme are automatically loaded.

== Installation ==

The plugin in zip format can be downloaded [here](https://github.com/hull/hull-wordpress/archive/master.zip)

Then head over to the "Install Plugins" page on your wp-admin :

    open http://example.com/plugin-install.php?tab=upload

and upload the zip from there.

Alternatively, you can simply unzip it or clone the repo under `wp-content/plugins`.

The admin Panel is under Settings > Hull

== Frequently Asked Questions ==
== Upgrade Notice ==
== Changelog ==

= 1.0 =
* Initial Release

== Upgrade Notice ==

== Authentication with hull.io ==

The plugin hooks the authentication mechanics of wordpress so the users can login with hull to your blog.

Simply add the following HTML to the login page of your theme so users can also login with hull:

    <div data-hull-widget="identity@hull"></div>

== Creating and using Widgets in your Theme ==

You can create widgets in individual javascript files inside your Theme.

Theme structure:

    wp-content
    └── themes
        └── my_theme
            ├── home.php
            ├── index.php
            ├── Hull
            │   └── widgets
            │       └── my_widget
            │           ├── main.js
            │           └── my_template.hbs
            ├── page.php
            └── single.php

**wp-content/themes/my-theme/hull/my_widget/my_template/my_widget.hbs**

    Hello from my widget

and then, to use this widget inside your views :

    <?php hull_widget('my_widget') ?>
    => <div data-hull-widget='my_widget'></div>


== Widgets Helpers ==


**hull_widget($name, $options=array(), $tagName = "div", $placeholder="")**

* `$name`: The widget's name
* `$options`: `array(key => val)` translated to `data-hull-$key="$val"`
* `$tagName`: name of the wrapping tag
* `$placeholder`: Initial content placed inside your widget before first rendering


example

    <?php hull_widget('identity', array('provider' => 'facebook')) >

**hull_comments_widget($post_id, $options=array())**

* `$post_id`: the id of the Wordpress post you want to display the comments for.
* `$options`: same as `hull_widget`


example

    <?php hull_comments_widget($post->ID) ?>

**hull_reviews_widget($post_id, $options=array())**

* `$post_id`: the id of the Wordpress post you want to display the reviews for.
* `$options`: same as `hull_widget`


example

    <?php hull_reviews_widget($post->ID) ?>
