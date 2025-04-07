<?php

namespace Api\Repository\Mapper\Standard\Helpers;

use Api\Exceptions\FactoryException;

trait RenderMapperToArray
{

    public function renderObject()
    {
        $ret = [];
        $retArrayInfo = [];
        $fields = $this->getFieldsToReturnWebservice();
        if (count($fields) == 0) {
            throw FactoryException::create('Standard\Mapper\FieldsToReturnNotMapped', array($this));
        }
        foreach ($fields as $field) {
            $content = $this->$field;
            if ($this->isFieldAnObject($field) && $content != null) {
                if (!is_array($content)) {
                    if (!is_object($content)) {
                        throw FactoryException::create('Standard\Mapper\RenderObjectInStringException', array($field));
                    }
                    if (!$content->galaxPayId) {
                        continue;
                    }
                    $content = $content->renderObject();
                } else {
                    foreach ($content as $key => $eachContent) {
                        if (!is_object($content[$key])) {
                            throw FactoryException::create('Standard\Mapper\RenderObjectInStringException', array('array ' . $field));
                        }
                        $content[$key] = $eachContent->renderObject();
                    }
                }
            } else {
                $content = $this->getTreated($field);
            }
            if (is_array($content)) {
                $retArrayInfo[$field] = $content;
            } else {
                $ret[$field] = $content;
            }
        }
        foreach ($retArrayInfo as $field => $content) {
            $ret[$field] = $content;
        }
        return $ret;
    }

    public function renderObjectToDevelopment()
    {
        $ret = [];
        $fields = $this->getFieldsToReturnWebserviceDebug();
        if (count($fields) == 0) {
            throw FactoryException::create('Standard\Mapper\FieldsToReturnNotMapped', array($this));
        }
        foreach ($fields as $field) {
            $content = $this->$field;
            if ($this->isFieldAnObject($field) && $content != null) {
                if (!is_array($content)) {
                    if (!is_object($content)) {
                        throw FactoryException::create('Standard\Mapper\RenderObjectInStringException', array($field));
                    }
                    if (!$content->galaxPayId) {
                        continue;
                    }
                    $content = $content->renderObjectToDevelopment();
                } else {
                    foreach ($content as $key => $eachContent) {
                        if (!is_object($content[$key])) {
                            throw FactoryException::create('Standard\Mapper\RenderObjectInStringException', array('array ' . $field));
                        }
                        $content[$key] = $eachContent->renderObjectToDevelopment();
                    }
                }
            } else {
                $content = $this->getTreated($field);
            }
            $ret[$field] = $content;
        }
        return $ret;
    }
}