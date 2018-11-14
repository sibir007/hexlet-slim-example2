<?php
namespace App;
use App\ValidatorInterface;

class Validator implements ValidatorInterface
{
    public function validate(array $cours)
    {
        // BEGIN (write your solution here)
        $errors = [];
        //var_dump($cours);
        if (empty($cours['title']) && empty($cours['paid'])) {
            $errors['title'] = "Can't be blank";
            $errors['paid'] = "Can't be blank";
        }elseif (empty($cours['paid'])) {
            $errors['paid'] = "Can't be blank";
        }elseif (empty($cours['title'])) {
            $errors['title'] = "Can't be blank";
        }

        return $errors;
        // END
    }
}
