<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Category extends AbstractLazerEntity {

    public $_table = 'categories';
    public $_schema = [
        'id' => 'string', // unique cat key
        'name' => 'string',
    ];

    public $id;

    /**
     * @Assert\NotBlank()
     */
    public $name;

    public static function getAllKeyValue() {
        $categories = [];
        foreach (Category::getAll() as $item) {
            $categories[$item->id] = $item->name;
        }
        return $categories;
    }

}
