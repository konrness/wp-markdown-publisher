<?php

namespace MarkdownPublisher;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\FileCacheReader;
use Fabricius\Library;
use Fabricius\Exception\InvalidArgumentException;
use Fabricius\Exception\RuntimeException;
use Fabricius\Metadata\Driver\AnnotationDriver;
use Fabricius\Metadata\MetadataFactory;
use MarkdownPublisher\Parser\Parser;
use Metadata\Cache\FileCache;
use Symfony\Component\EventDispatcher\EventDispatcher;

use Fabricius\Loader\LoaderInterface;

use Doctrine\Common\Cache\ArrayCache;
use Fabricius\Events;
use Fabricius\Formatter\Formatter;
use Fabricius\Formatter\Handler\MarkdownPhpHandler;

class LibraryBuilder
{
    private $annotationReader;

    private $cacheDir;

    private $debug = false;

    private $eventDispatcher;

    private $excerptDelimiter = '[READ MORE]';

    private $listenersConfigured = false;

    private $parser;


    public static function create()
    {
        return new static();
    }

    public function __construct()
    {
        $this->eventDispatcher = new EventDispatcher();
        $this->parser = new Parser();
    }

    public function addDefaultListeners()
    {
        $this->listenersConfigured = true;

        // @todo: Move listeners from bootstrap to this function.
        return $this;
    }

    public function configureListeners(\Closure $closure)
    {
        $this->listenersConfigured = true;
        $closure($this->eventDispatcher);

        return $this;
    }

    public function setCacheDir($dir)
    {
        if (!is_dir($dir)) {
            $this->createDir($dir);
        }
        if (!is_writable($dir)) {
            throw new InvalidArgumentException(sprintf('The cache directory "%s" is not writable.', $dir));
        }

        $this->cacheDir = $dir;

        return $this;
    }

    public function setExcerptDelimiter($excerptDelimiter)
    {
        $this->excerptDelimiter = $excerptDelimiter;

        return $this;
    }

    public function build()
    {
        $annotationReader = $this->annotationReader;
        if (null === $annotationReader) {
            $annotationReader = new AnnotationReader();

            if (null !== $this->cacheDir) {
                $this->createDir($this->cacheDir.'/annotations');
                $annotationReader = new FileCacheReader($annotationReader, $this->cacheDir.'/annotations', $this->debug);
            }
        }

        $metadataDriver = new AnnotationDriver($annotationReader);
        $metadataFactory = new MetadataFactory($metadataDriver, null, $this->debug);

        if (null !== $this->cacheDir) {
            $this->createDir($this->cacheDir.'/metadata');
            $metadataFactory->setCache(new FileCache($this->cacheDir.'/metadata'));
        }

        if (!$this->listenersConfigured) {
            $this->addDefaultListeners();
        }

        $this->parser->setEventDispatcher($this->eventDispatcher);
        $this->parser->setExcerptDelimiter($this->excerptDelimiter);

        $library = new Library(
            $this->eventDispatcher,
            $metadataFactory,
            $this->parser
        );

        // configure markdown handler
        $formatter = new Formatter($library->getMetadataFactory());
        $markdownHandler = new MarkdownPhpHandler('Michelf\Markdown', 'defaultTransform');
        $formatter->addFormatHandler($markdownHandler);

        // configure parser
        $library->getEventDispatcher()->addListener(Events::CONTENT_PARSED, array($formatter, 'onContentItemParsed'));

        return $library;
    }

    private function createDir($dir)
    {
        if (is_dir($dir)) {
            return;
        }

        if (false === @mkdir($dir, 0777, true)) {
            throw new RuntimeException(sprintf('Could not create directory "%s".', $dir));
        }
    }
} 