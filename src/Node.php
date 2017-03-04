<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  Node.php - Part of the php-xml2array project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\XML;

use OutOfBoundsException;
use InvalidArgumentException;

/**
 * Class Node.
 * Object representation of a Node.
 */
class Node {

    /** @var string */
    private $name;
    /** @var array */
    private $attributes;
    /** @var string */
    private $content;
    /** @var Node[]|array */
    private $children;

    /**
     * Get node name.
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Get node content, if any exist.
     * @return null|string
     */
    public function getContent(): ?string {
        return $this->content === "" ? null : $this->content;
    }

    /**
     * Element constructor.
     * @param string $name
     * @param string $content
     * @param array $attributes
     * @param array $children
     */
    public function __construct(string $name, string $content, array $attributes, array $children = []) {
        $this->name       = $name;
        $this->content    = $content;
        $this->attributes = $attributes;
        $this->children   = $children;
    }

    /**
     * Checks if the node has any attributes.
     * @return bool
     */
    public function hasAttributes(): bool {
        return !empty($this->attributes);
    }

    /**
     * Check if the node has any children.
     * @return bool
     */
    public function hasChildren(): bool {
        return !empty($this->children);
    }

    /**
     * Get all attributes in the node.
     * The returned array is an associative array with keys as the attribute name and values as attribute value.
     * @return array
     */
    public function getAttributes(): array {
        return $this->attributes;
    }

    /**
     * Get all children in the node.
     * @return array|Node[]
     */
    public function getChildren(): array {
        return $this->children;
    }

    /**
     * Get the child count.
     * @return int
     */
    public function childCount(): int {
        return count($this->children);
    }

    /**
     * Get the attribute count.
     * @return int
     */
    public function attributeCount(): int {
        return count($this->attributes);
    }

    /**
     * Check if the node has a given attribute.
     * @param string $name
     * @return bool
     */
    public function hasAttribute(string $name): bool {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * Get a attribute by name.
     * @param string $name
     * @return string
     * @throws InvalidArgumentException
     */
    public function getAttribute(string $name): string {
        if (!$this->hasAttribute($name)) {
            throw new InvalidArgumentException("Attribute with key \"$name\" does not exist.");
        }
        return $this->attributes[$name];
    }

    /**
     * Get a child by index.
     * @param int $index
     * @return Node|mixed
     * @throws OutOfBoundsException
     */
    public function getChild(int $index) {
        $childCount = $this->childCount();
        if (empty($this->children) || $index >= $childCount) {
            throw new OutOfBoundsException("Child with index $index does not exist. Index was out of bounds.");
        }
        return $this->children[$index];
    }

    /**
     * Convert the node and its sub-tree into a json string.
     * @return string
     */
    public function toJson(): string {
        return (string)$this;
    }

    /**
     * Convert the node and its sub-tree into an array (without element objects).
     * @return array
     */
    public function toArray(): array {
        return [
            'name'       => $this->name,
            'content'    => $this->content,
            'attributes' => $this->attributes,
            'children'   => array_map(function (Node $node) {
                return $node->toArray();
            }, $this->children)
        ];
    }

    function __toString() {
        return json_encode($this->toArray());
    }
}
