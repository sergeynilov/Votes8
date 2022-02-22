<?php namespace App\library {

    use WBoyz\LaravelEnum\BaseEnum;

    class CheckValueType extends BaseEnum
    {
        const cvtInteger = 1;
        const cvtFloat = 2;
        const cvtDate = 3;
        const cvDateTime = 4;
        const cvString = 5;
        const cvtBool = 6;
    }

}