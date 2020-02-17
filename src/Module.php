<?php

namespace Module;

interface Module
{
    public function init(Di $di): void;
}
