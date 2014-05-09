<?php

require __DIR__.'/../vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Cache\ArrayCache;
use Fabricius\Events;
use Fabricius\Formatter\Formatter;
use Fabricius\Formatter\Handler\MarkdownPhpHandler;
use Fabricius\Formatter\Handler\TextileHandler;
use Fabricius\LibraryBuilder;
use Fabricius\Loader\FileLoader;
use Fabricius\Validator\Validator;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\DefaultTranslator;
use Michelf\Markdown;

AnnotationRegistry::registerLoader('class_exists');

$library = LibraryBuilder::create()
    ->setCacheDir(__DIR__.'/cache')
    ->setExcerptDelimiter('<!-- more -->')
    ->build();

$constraintValidatorFactory = new ConstraintValidatorFactory();
$translator = new DefaultTranslator();

$validator = new Validator($library->getMetadataFactory(), $constraintValidatorFactory, $translator);

$formatter = new Formatter($library->getMetadataFactory());

$markdownHandler = new MarkdownPhpHandler('Michelf\Markdown', 'defaultTransform');
$formatter->addFormatHandler($markdownHandler);

$library->getEventDispatcher()->addListener(Events::CONTENT_PARSED, array($validator, 'onContentItemParsed'), -100);
$library->getEventDispatcher()->addListener(Events::CONTENT_PARSED, array($formatter, 'onContentItemParsed'));

$finder = new Finder();
$provider = new FileLoader($finder, '/home/kness/public_html/wp-markdown-publisher/_content/test');

$cache = new ArrayCache();

$library->registerRepository('MarkdownPublisher\WordPress\Post', $provider, $cache);

/** @var Article $content */
$content = $library->getRepository('MarkdownPublisher\WordPress\Post')->query();

foreach ($content->toArray() as $contentItem) {
    // Prints 'Example'.
    var_dump($contentItem);
}