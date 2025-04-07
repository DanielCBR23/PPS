<?php

namespace Api\Business\Queues\ValidateQueue;

interface Handler
{
    public function handle(): bool;
}