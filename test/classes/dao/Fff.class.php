<?php

class Fff {
    const VERSION_NO_PROPERTY = "version";
    const TIMESTAMP_PROPERTY = "updated";

    private $id;
    private $version;
    private $updated;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getVersion() {
        return $this->version;
    }

    public function setVersion($version) {
        $this->version = $version;
    }

    public function getUpdated() {
        return $this->updated;
    }

    public function setUpdated($updated) {
        $this->updated = $updated;
    }
}