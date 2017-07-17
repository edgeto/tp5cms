<?php
namespace app\home\controller;

class Index extends Base
{
    public function index()
    {
        return $this->fetch();
    }	

    public function contact()
    {
    	return $this->fetch();
    }

    public function test()
    {
    	return $this->fetch();
    }
}
