<?php

namespace MarkdownPublisher\WordPress\Repository;

class Author extends Repository
{
    /**
     * @param string $username
     * @return int|null
     */
    public function getAuthorIDByUsername($username)
    {
        return $this->getProxy()->userNameExists($username);
    }
} 