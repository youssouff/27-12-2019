<?php
namespace App\Entity;

use App\Entity\Category;

class GoodiesSearch{

    /**
     * @var int|null
     */
    private $orderBy;

    /**
     */
    private $orderType;

    /**
     * @var int|null
     */
    private $maxPrice;

    /**
     * @var Category|null
     */
    private $category;

    public function getOrderBy(){
        return $this->orderBy;
    }

    public function setOrderBy(string $orderBy){
        $this->orderBy = $orderBy;
        return $this;
    }

    public function getOrderType(){
        return $this->orderType;
    }

    public function setOrderType(string $orderType){
        $this->orderType = $orderType;
        return $this;
    }

    public function getMaxPrice(){
        return $this->maxPrice;
    }

    public function setMaxPrice(int $maxPrice){
        $this->maxPrice = $maxPrice;
        return $this;
    }


    public function getCategory(){
        return $this->category;
    }

    public function setCategory(Category $category){
        $this->category = $category;
        return $this;
    }


}