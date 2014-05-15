<?php

namespace MarkdownPublisher\WordPress\Repository;

use MarkdownPublisher\WordPress\Post as PostEntity;

class Post extends Repository
{
    /**
     * @param string $username
     * @param string $type "page"|"post"|etc.
     * @return int|null
     */
    public function getPostIDBySlug($slug, $type = "page")
    {
        $existingPosts = $this->getExistingPosts($slug, $type);

        if(count($existingPosts) > 1) {
            throw new \DomainException("Invalid slug constraints, multiple posts found");
        }

        if(count($existingPosts) == 1) {
            return $existingPosts[0]->ID;
        }

        return null;
    }

    public function getExistingPosts($slug, $type = "page")
    {
        // find an existing page
        $search = array(
            'name'      => $slug,
            'post_type' => $type
        );

        return $this->getProxy()->getPosts($search);
    }

    /**
     * @param PostEntity $post
     * @return \WP_Post|null
     * @throws \DomainException
     */
    public function getExistingPostByPost(PostEntity $post)
    {
        $existingPosts = $this->getExistingPosts($post->post_name, $post->post_type);

        if(count($existingPosts) > 1) {
            throw new \DomainException("Invalid post constraints, multiple posts found");
        }

        if(count($existingPosts) == 1) {
            return $existingPosts[0];
        }

        return null;
    }

    /**
     * @param PostEntity $post
     * @return int 0 if updated, 1 if inserted
     */
    public function insertOrUpdate(PostEntity $post)
    {

        if ($existingPost = $this->getExistingPostByPost($post)) {
            // update
            $post->ID = $existingPost->ID;
            $this->getProxy()->updatePost($post);
            return 0;
        } else {
            // insert
            $this->getProxy()->insertPost($post);
            return 1;
        }

    }
} 