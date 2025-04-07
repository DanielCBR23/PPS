<?php

namespace Api\Repository\Mapper\User;

use Api\Repository\Mapper\Standard\Mapper;

class UserMap extends Mapper
{

    protected function setMapConfig(): void
    {
        $this->appendMap('id_user', 'idUser', '', false);
        $this->appendMap('name_user', 'nameUser', '', false);
        $this->appendMap('document_user', 'documentUser', '', false);
        $this->appendMap('email_user', 'emailUser', '', false);
        $this->appendMap('password_user', 'passwordUser', '', false);
        $this->appendMap('type_user', 'typeUser', '', false);
        $this->appendMap('created_at_user', 'createdAtUser', '', false);
        $this->appendMap('updated_at_user', 'updatedAtUser', '', false);
    }

    public static function getNameRepository(): string
    {
        return 'Users/Users';
    }

    public function hasShoopkeeper(): bool
    {
        return $this->typeUser === 'SHOPKEEPER';
    }
}
