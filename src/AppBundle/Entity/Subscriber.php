<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Subscriber extends AbstractLazerEntity {

    public $_table = 'subscribers';
    public $_serialize = ['categories'];
    public $_schema = [
        'id' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'categories' => 'string',
        'date' => 'string',
    ];

    public $id;

    /**
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Email(
     *     checkMX = true
     * )
     */
    public $email;

    /**
     * @Assert\NotBlank()
     */
    public $categories;

    public $date;

    public function __construct($data = null) {
        $this->date = date('Y-m-d H:i:s');
        parent::__construct($data);
    }

    public function getCategoriesString() {
        $categories = Category::getAllKeyValue();
        $result = [];
        foreach ($this->categories as $i) {
            if (isset($categories[$i])) {
                $result[] = $categories[$i];
            }
        }
        return implode(', ', $result);
    }

}
