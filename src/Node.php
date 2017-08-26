<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  Node.php - Part of the php-xml2struct project.

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
     * Get children in the node.
     *
     * @param null|string $name Node name of the children to get, defaults to null (all).
     * @param bool $preserveKeys
     * @return array
     */
    public function getChildren(?string $name = null, $preserveKeys = true): array {
        if ($name === null) {
            return $this->children;
        }
        $children = array_filter($this->children, function(Node $child) use($name) {
            return $child->getName() === $name;
        });

        return $preserveKeys ? $children : array_values($children);
    }

    /**
     * Get the child count.
     * @param null|string $name Node name of the children to count, defaults to null (all).
     * @return int
     */
    public function childCount(?string $name = null): int {
        if ($name === null) {
            return count($this->children);
        }
        return count(array_filter($this->children, function(Node $child) use($name) {
            return $child->getName() === $name;
        }));
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
    public function getChild(int $index) : ?Node {
        $childCount = $this->childCount();
        if (empty($this->children) || $index >= $childCount) {
            throw new OutOfBoundsException("Child with index $index does not exist. Index was out of bounds.");
        }
        return $this->children[$index];
    }

    /**
     * Get first child with a given name.
     *
     * @param string $name
     * @return Node
     * @throws InvalidArgumentException
     */
    public function getChildByName(string $name) : ?Node {
        foreach ($this->children as $child) {
            if ($child->getName() === $name) {
                return $child;
            }
        }
        throw new InvalidArgumentException(
            sprintf('Child with name "%s" does not exist.', $name)
        );
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

    /**
     * {@inheritdoc}
     */
    function __toString() {
        return json_encode($this->toArray());
    }
}
