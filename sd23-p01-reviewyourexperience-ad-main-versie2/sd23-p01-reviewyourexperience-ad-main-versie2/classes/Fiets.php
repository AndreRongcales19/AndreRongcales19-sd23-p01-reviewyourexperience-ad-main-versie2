<?php

class Fiets
{
    public $id;
    public $category;
    public $img;
    public $price;
    public function __construct()
    {
        settype($this->id, 'integer');
    }

    public function showImage(){
        echo "<img src='../img/racefietsen/" . $this->img . "' alt='img' class='img-thumbnail'>";
        echo "<p>". $this->img ."</p>";
    }
}
?>
