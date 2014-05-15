<?php

namespace MarkdownPublisher\Parser;

use Fabricius\Event\ContentParsedEvent;
use Fabricius\Events;
use Fabricius\Exception\InvalidArgumentException;
use Fabricius\Exception\RuntimeException;
use Fabricius\Metadata\ClassMetadata;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

use Fabricius\Parser\ParserInterface;

/**
 * Parser
 */
class Parser implements ParserInterface
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var string
     */
    private $excerptDelimiter;

    /**
     * Camelizes a string.
     *
     * @param string $id A string to camelize
     *
     * @return string The camelized string
     */
    public static function camelize($string)
    {
        return preg_replace_callback('/(^|_|\.)+(.)/', function ($match) { return ('.' === $match[1] ? '_' : '').strtoupper($match[2]); }, $string);
    }

    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function setExcerptDelimiter($excerptDelimiter)
    {
        $this->excerptDelimiter = $excerptDelimiter;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(ClassMetadata $metadata, $filename, $content)
    {
        @list($name, $extension) = explode('.', $filename);

        $contentItem = new $metadata->name;

        $metadata->propertyMetadata['slug']->setValue($contentItem, $name);
        $metadata->propertyMetadata['format']->setValue($contentItem, $extension);

        $body = $this->parseContent($contentItem, $metadata, $content, $filename);
        $this->parseBody($contentItem, $metadata, $body, $extension, $content, $filename);

        if ($this->eventDispatcher) {
            $event = new ContentParsedEvent($contentItem);
            $this->eventDispatcher->dispatch(Events::CONTENT_PARSED, $event);
        }

        return $contentItem;
    }

    /**
     * Parses the body of a content file.
     *
     * @param stdClass                $contentItem A stdClass instance.
     * @param \Metadata\ClassMetadata $metadata    A ClassMetadata instance.
     * @param string                  $body        The body of the content file.
     * @param string                  $extension   The extension of the content file.
     * @param string                  $content     The contents of the content file.
     * @param string                  $filename    The filename of the content file.
     */
    protected function parseBody($contentItem, ClassMetadata $metadata, $body, $extension, $content, $filename)
    {
        // Remove the header from the contents and all left whitespace at the begin and the end.
        $body = trim(str_replace($body, '', $content));

        if (false !== $excerptPosition = strpos($body, $this->excerptDelimiter)) {
            $excerpt = substr($body, 0, $excerptPosition);
            $metadata->propertyMetadata['excerpt']->setValue($contentItem, $excerpt);

            $body = str_replace($this->excerptDelimiter, '', $body);
        }

        $metadata->propertyMetadata['body']->setValue($contentItem, $body);
    }

    /**
     * Parses the content of a content file.
     *
     * @param stdClass                $contentItem A ContentItem instance.
     * @param \Metadata\ClassMetadata $metadata    A ClassMetadata instance.
     * @param string                  $content     The contents of the content file.
     * @param string                  $filename    The filename of the content file.
     *
     * @return string The body of the content file.
     */
    protected function parseContent($contentItem, ClassMetadata $metadata, $content, $filename)
    {
        // Check if a YAML header is present in the content
        if (!preg_match_all('/^-{3}(\s?\n?[\s\S]*)-{3}/', $content, $matches)) {
            throw new InvalidArgumentException(sprintf(
                '%s doesn\'t have a correct header',
                $filename
            ));
        }

        // Remove all whitespace at the beginning and the end of the header.
        $header = trim(array_shift($matches[1]));

        try {
            // Parses the YAML to an usable key-value array.
            if (!$parameters = Yaml::parse($header)) {
                throw new RuntimeException(sprintf(
                    '%s cannot have an empty header',
                    $filename
                ));
            }
        } catch (ParseException $e) {
            throw new RuntimeException(sprintf(
                '%s doesn\'t have a valid YAML header: %s',
                $filename,
                $e->getMessage()
            ));
        }

        foreach ($parameters as $parameter => $value) {
            if (!isset($metadata->propertyMetadata[$parameter])) {
                $parameter = lcfirst(self::camelize($parameter));

                if (!isset($metadata->propertyMetadata[$parameter])) {
                    throw new RuntimeException(sprintf('The parameter %s cannot be mapped to a property', $parameter));
                }
            }

            $metadata->propertyMetadata[$parameter]->setValue($contentItem, $value);
        }

        return $matches[0];
    }
}
