<?php

namespace Application\Models;

use System\Base\Model;
use PDO;

class Subject extends \System\Base\Model {
    public function getAllSubjects()
    {
        $query = $this->db->query('SELECT * FROM subject');

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGroupCountSubject()
    {
        $query = $this->db->query('SELECT subject_id, COUNT(*) AS count FROM review GROUP BY subject_id');

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}

