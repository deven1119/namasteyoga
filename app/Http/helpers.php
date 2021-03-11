<?php

namespace App\Helpers;
use App\Tickets;
use App\Pressrelease;
use App\Users;
class CommonHelper
{
    static function countTickets()
    {
        $obj = new Tickets();
        $data = $obj->getNewTickets();
        return $data->new_ticket;
    }
    static function countPressrelease()
    {
        $obj = new Pressrelease();
        $data = $obj->getReviewPressrelease();
        return $data->pr;
    }

    static function getCommentAuthor($id)
    {
        $name = 'Administrator';
        if($id != '2'){
            $obj = new Users();
            $data = $obj->getUser(false,$id);
            $name = $data['name'];
        }
        return $name;
    }
}
