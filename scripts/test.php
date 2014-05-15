<?php

require __DIR__.'/../vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Cache\ArrayCache;
use Fabricius\Events;
use Fabricius\Formatter\Formatter;
use Fabricius\Formatter\Handler\MarkdownPhpHandler;
use Fabricius\LibraryBuilder;
use Fabricius\Loader\FileLoader;
use Fabricius\Validator\Validator;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\DefaultTranslator;
use Michelf\Markdown;

use Fabricius\Library;
use MarkdownPublisher\Parser\Parser;

AnnotationRegistry::registerLoader('class_exists');

// @todo Get rid of the need for this
$tempLibrary = LibraryBuilder::create()
    ->setCacheDir(__DIR__.'/cache')
    ->setExcerptDelimiter('<!-- more -->')
    ->build();

$parser = new Parser();
$parser->setEventDispatcher($tempLibrary->getEventDispatcher());
$parser->setExcerptDelimiter('<!-- more -->');

$library = new Library(
    $tempLibrary->getEventDispatcher(),
    $tempLibrary->getMetadataFactory(),
    $parser
);

$constraintValidatorFactory = new ConstraintValidatorFactory();
$translator = new DefaultTranslator();

$formatter = new Formatter($library->getMetadataFactory());

$markdownHandler = new MarkdownPhpHandler('Michelf\Markdown', 'defaultTransform');
$formatter->addFormatHandler($markdownHandler);

$library->getEventDispatcher()->addListener(Events::CONTENT_PARSED, array($formatter, 'onContentItemParsed'));

$finder = new Finder();
$provider = new FileLoader($finder, __DIR__ . '/../_content/test');

$cache = new ArrayCache();

$library->registerRepository('MarkdownPublisher\WordPress\Post', $provider, $cache);

/** @var Article $content */
$content = $library->getRepository('MarkdownPublisher\WordPress\Post')->query();

foreach ($content->toArray() as $contentItem) {
    // Prints 'Example'.
    $wpPost = $contentItem->toWpPost();
    echo $wpPost['post_title'] . " --- " . $wpPost['post_name'] . " --- " . $wpPost['post_parent'] . "\n";
    /*
    var_dump($wpPost);
    echo "\n";
    echo $wpPost['post_content'];
    echo "\n\n";
    echo $wpPost['post_excerpt'];
    */
}
