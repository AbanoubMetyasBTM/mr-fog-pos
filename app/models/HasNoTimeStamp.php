<?php


namespace App\models;


trait HasNoTimeStamp
{

    public function usesTimestamps()
    {
        return false;
    }

    public function setCreatedAt($value)
    {
        return null;
    }

    public function setUpdatedAt($value)
    {
        return null;
    }


}
