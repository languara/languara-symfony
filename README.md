Languara Plugin for Symfony 2.x
========================

<h3>Install</h3>

Add languara plugin information to your composer.json file:

<pre><code>
"require": {
  "languara/symfony": "1.0.*@dev"
}
</code></pre>

Use composer to install this package.

<pre><code>
$ composer update
</code></pre>

<h3>Register the pakcage</h3>

Add the package to the autoload array in app/AppKernel.php:

<pre><code>
public function registerBundles()
{
  $bundles = array(
    // .....
    new Languara\SymfonyBundle\LanguaraSymfonyBundle(),
  )
}
</pre></code>

<h3>Configure the Package</h3>

.....

<h3>Usage</h3>

Execute this command to see a list of available commands in your commandline:

<pre><code>
$ php app/console
</code></pre>

--------------------

Or you can check the commands and their usage here:

<pre><code>
$ php app/console languara:translate [options]
</code></pre>

to translate the texts you already have in your lang directory. You can also select the type of translation you want to perform, Machine or Human. It's set to machine by default.

<pre><code>
$ php app/console languara:pull
</code></pre>

to download your content from Languara to your app.

<pre><code>
$ php app/console languara:push
</code></pre>

to upload your content from your app to Languara.


<pre><code>
$ php app/console languara:register
</code></pre>

to register a new user on languara.com
